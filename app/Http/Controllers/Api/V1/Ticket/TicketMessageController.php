<?php
namespace App\Http\Controllers\Api\V1\Ticket;

use App\Enums\PermissionCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\TicketMessageStoreRequest;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketMessageRepositoryInterface;
use App\Services\TicketMessageService;
use App\Support\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketMessageController extends Controller
{
    public function __construct(
        private readonly TicketMessageRepositoryInterface $ticketMessageRepository,
        private readonly TicketMessageService $ticketMessageService
    ) {
    }

    public function index(Request $request, Ticket $ticket): JsonResponse
    {
        $this->ensureCanAccessTicket($request->user(), $ticket);

        $perPage = max(1, min((int) $request->input('per_page', 20), 100));

        $messages = $this->ticketMessageRepository->paginateByTicket(
            ticket: $ticket,
            user: $request->user(),
            perPage: $perPage
        );

        return ApiResponse::success(
            message: 'Ticket messages retrieved successfully.',
            data: $messages
        );
    }

    public function store(TicketMessageStoreRequest $request, Ticket $ticket): JsonResponse
    {
        $this->ensureCanAccessTicket($request->user(), $ticket);

        $message = $this->ticketMessageService->create(
            actor: $request->user(),
            ticket: $ticket,
            data: $request->validated()
        );

        return ApiResponse::success(
            message: 'Ticket message created successfully.',
            data: $message,
            status: 201
        );
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
