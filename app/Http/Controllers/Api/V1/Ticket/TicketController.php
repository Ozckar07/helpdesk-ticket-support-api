<?php
namespace App\Http\Controllers\Api\V1\Ticket;

use App\Enums\PermissionCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TicketAssignRequest;
use App\Http\Requests\Ticket\TicketChangeCategoryRequest;
use App\Http\Requests\Ticket\TicketChangePriorityRequest;
use App\Http\Requests\Ticket\TicketChangeStatusRequest;
use App\Http\Requests\Ticket\TicketIndexRequest;
use App\Http\Requests\Ticket\TicketStoreRequest;
use App\Http\Requests\Ticket\TicketUpdateRequest;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Services\TicketService;
use App\Support\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly TicketService $ticketService
    ) {
    }

    public function index(TicketIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 15);

        $tickets = $this->ticketRepository->paginateForUser(
            user: $request->user(),
            filters: $filters,
            perPage: $perPage
        );

        return ApiResponse::success(
            message: 'Tickets retrieved successfully.',
            data: $tickets
        );
    }

    public function show(Request $request, Ticket $ticket): JsonResponse
    {
        $this->ensureCanAccessTicket($request->user(), $ticket);

        $ticket->load($this->ticketRelations());

        return ApiResponse::success(
            message: 'Ticket retrieved successfully.',
            data: $ticket
        );
    }

    public function store(TicketStoreRequest $request): JsonResponse
    {
        $ticket = $this->ticketService->create(
            actor: $request->user(),
            data: $request->validated()
        );

        return ApiResponse::success(
            message: 'Ticket created successfully.',
            data: $ticket,
            status: 201
        );
    }

    public function update(TicketUpdateRequest $request, Ticket $ticket): JsonResponse
    {
        $this->ensureCanAccessTicket($request->user(), $ticket);

        $ticket = $this->ticketService->update(
            actor: $request->user(),
            ticket: $ticket,
            data: $request->validated()
        );

        return ApiResponse::success(
            message: 'Ticket updated successfully.',
            data: $ticket
        );
    }

    public function assign(TicketAssignRequest $request, Ticket $ticket): JsonResponse
    {
        $ticket = $this->ticketService->assignAgent(
            actor: $request->user(),
            ticket: $ticket,
            assignedAgentUuid: $request->validated()['assigned_agent_uuid']
        );

        return ApiResponse::success(
            message: 'Ticket assigned successfully.',
            data: $ticket
        );
    }

    public function changeStatus(TicketChangeStatusRequest $request, Ticket $ticket): JsonResponse
    {
        $ticket = $this->ticketService->changeStatus(
            actor: $request->user(),
            ticket: $ticket,
            statusUuid: $request->validated()['status_uuid'],
            comment: $request->validated()['comment'] ?? null
        );

        return ApiResponse::success(
            message: 'Ticket status changed successfully.',
            data: $ticket
        );
    }

    public function changePriority(TicketChangePriorityRequest $request, Ticket $ticket): JsonResponse
    {
        $ticket = $this->ticketService->changePriority(
            actor: $request->user(),
            ticket: $ticket,
            priorityUuid: $request->validated()['priority_uuid'],
            comment: $request->validated()['comment'] ?? null
        );

        return ApiResponse::success(
            message: 'Ticket priority changed successfully.',
            data: $ticket
        );
    }

    public function changeCategory(TicketChangeCategoryRequest $request, Ticket $ticket): JsonResponse
    {
        $ticket = $this->ticketService->changeCategory(
            actor: $request->user(),
            ticket: $ticket,
            categoryUuid: $request->validated()['category_uuid'],
            comment: $request->validated()['comment'] ?? null
        );

        return ApiResponse::success(
            message: 'Ticket category changed successfully.',
            data: $ticket
        );
    }

    private function ticketRelations(): array
    {
        return [
            'customer.roles',
            'assignedAgent.roles',
            'category',
            'priority',
            'status',
            'messages.user',
            'messages.attachments',
            'attachments.uploadedBy',
            'activities.user',
        ];
    }

    private function ensureCanAccessTicket($user, Ticket $ticket): void
    {
        if (
            $user->isAdmin()
            || $user->hasPermission(PermissionCode::TICKET_VIEW_ANY->value)
            || $ticket->customer_id === $user->id
            || $ticket->assigned_agent_id === $user->id
        ) {
            return;
        }

        abort(403, 'You are not allowed to access this ticket.');
    }
}
