<?php
namespace App\Services;

use App\Enums\TicketActivityType;
use App\Enums\TicketStatusCode;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\PriorityRepositoryInterface;
use App\Repositories\Contracts\StatusRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TicketService
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly PriorityRepositoryInterface $priorityRepository,
        private readonly StatusRepositoryInterface $statusRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly TicketActivityService $ticketActivityService,
        private readonly TicketAttachmentService $ticketAttachmentService
    ) {
    }

    public function create(User $actor, array $data): Ticket
    {
        return DB::transaction(function () use ($actor, $data): Ticket {
            $category   = $this->categoryRepository->findActiveByUuidOrFail($data['category_uuid']);
            $priority   = $this->priorityRepository->findActiveByUuidOrFail($data['priority_uuid']);
            $openStatus = $this->statusRepository->findByCodeOrFail(TicketStatusCode::OPEN->value);

            $ticket = $this->ticketRepository->create([
                'ticket_number'     => $this->ticketRepository->nextTicketNumber(),
                'subject'           => $data['subject'],
                'description'       => $data['description'],
                'customer_id'       => $actor->id,
                'assigned_agent_id' => null,
                'category_id'       => $category->id,
                'priority_id'       => $priority->id,
                'status_id'         => $openStatus->id,
            ]);

            $this->ticketActivityService->log(
                ticket: $ticket,
                user: $actor,
                type: TicketActivityType::CREATED->value,
                description: "Ticket {$ticket->ticket_number} was created."
            );

            if (! empty($data['attachments']) && is_array($data['attachments'])) {
                $attachments = $this->ticketAttachmentService->uploadMany(
                    ticket: $ticket,
                    user: $actor,
                    files: $data['attachments']
                );

                if (! empty($attachments)) {
                    $this->ticketActivityService->log(
                        ticket: $ticket,
                        user: $actor,
                        type: TicketActivityType::ATTACHMENT_ADDED->value,
                        description: count($attachments) . ' attachment(s) were added to the ticket.',
                        metadata: ['attachments_count' => count($attachments)]
                    );
                }
            }

            return $ticket->load([
                'customer',
                'assignedAgent',
                'category',
                'priority',
                'status',
            ]);
        });
    }

    public function update(User $actor, Ticket $ticket, array $data): Ticket
    {
        return DB::transaction(function () use ($actor, $ticket, $data): Ticket {
            $oldValue = [
                'subject'     => $ticket->subject,
                'description' => $ticket->description,
            ];

            $ticket = $this->ticketRepository->update($ticket, $data);

            $newValue = [
                'subject'     => $ticket->subject,
                'description' => $ticket->description,
            ];

            $this->ticketActivityService->log(
                ticket: $ticket,
                user: $actor,
                type: TicketActivityType::UPDATED->value,
                description: "Ticket {$ticket->ticket_number} was updated.",
                field: 'ticket',
                oldValue: $oldValue,
                newValue: $newValue
            );

            return $ticket->load([
                'customer',
                'assignedAgent',
                'category',
                'priority',
                'status',
            ]);
        });
    }

    public function assignAgent(User $actor, Ticket $ticket, string $assignedAgentUuid): Ticket
    {
        return DB::transaction(function () use ($actor, $ticket, $assignedAgentUuid): Ticket {
            $agent = $this->userRepository->findActiveByUuidOrFail($assignedAgentUuid);

            if (! $agent->isAdmin() && ! $agent->isAgent()) {
                throw ValidationException::withMessages([
                    'assigned_agent_uuid' => ['The selected user cannot be assigned as ticket agent.'],
                ]);
            }

            $oldAgent = $ticket->assignedAgent;

            $ticket = $this->ticketRepository->update($ticket, [
                'assigned_agent_id' => $agent->id,
            ]);

            $type = $oldAgent
                ? TicketActivityType::REASSIGNED->value
                : TicketActivityType::ASSIGNED->value;

            $description = $oldAgent
                ? "Ticket {$ticket->ticket_number} was reassigned from {$oldAgent->name} to {$agent->name}."
                : "Ticket {$ticket->ticket_number} was assigned to {$agent->name}.";

            $this->ticketActivityService->log(
                ticket: $ticket,
                user: $actor,
                type: $type,
                description: $description,
                field: 'assigned_agent_id',
                oldValue: $oldAgent?->uuid,
                newValue: $agent->uuid
            );

            return $ticket->load([
                'customer',
                'assignedAgent',
                'category',
                'priority',
                'status',
            ]);
        });
    }

    public function changePriority(User $actor, Ticket $ticket, string $priorityUuid, ?string $comment = null): Ticket
    {
        return DB::transaction(function () use ($actor, $ticket, $priorityUuid, $comment): Ticket {
            $newPriority = $this->priorityRepository->findActiveByUuidOrFail($priorityUuid);
            $oldPriority = $ticket->priority;

            if ($oldPriority && $oldPriority->id === $newPriority->id) {
                return $ticket->load(['customer', 'assignedAgent', 'category', 'priority', 'status']);
            }

            $ticket = $this->ticketRepository->update($ticket, [
                'priority_id' => $newPriority->id,
            ]);

            $this->ticketActivityService->log(
                ticket: $ticket,
                user: $actor,
                type: TicketActivityType::PRIORITY_CHANGED->value,
                description: "Ticket {$ticket->ticket_number} priority changed from {$oldPriority?->name} to {$newPriority->name}.",
                field: 'priority_id',
                oldValue: $oldPriority?->uuid,
                newValue: $newPriority->uuid,
                metadata: $comment ? ['comment' => $comment] : null
            );

            return $ticket->load([
                'customer',
                'assignedAgent',
                'category',
                'priority',
                'status',
            ]);
        });
    }

    public function changeCategory(User $actor, Ticket $ticket, string $categoryUuid, ?string $comment = null): Ticket
    {
        return DB::transaction(function () use ($actor, $ticket, $categoryUuid, $comment): Ticket {
            $newCategory = $this->categoryRepository->findActiveByUuidOrFail($categoryUuid);
            $oldCategory = $ticket->category;

            if ($oldCategory && $oldCategory->id === $newCategory->id) {
                return $ticket->load(['customer', 'assignedAgent', 'category', 'priority', 'status']);
            }

            $ticket = $this->ticketRepository->update($ticket, [
                'category_id' => $newCategory->id,
            ]);

            $this->ticketActivityService->log(
                ticket: $ticket,
                user: $actor,
                type: TicketActivityType::CATEGORY_CHANGED->value,
                description: "Ticket {$ticket->ticket_number} category changed from {$oldCategory?->name} to {$newCategory->name}.",
                field: 'category_id',
                oldValue: $oldCategory?->uuid,
                newValue: $newCategory->uuid,
                metadata: $comment ? ['comment' => $comment] : null
            );

            return $ticket->load([
                'customer',
                'assignedAgent',
                'category',
                'priority',
                'status',
            ]);
        });
    }

    public function changeStatus(User $actor, Ticket $ticket, string $statusUuid, ?string $comment = null): Ticket
    {
        return DB::transaction(function () use ($actor, $ticket, $statusUuid, $comment): Ticket {
            $ticket->loadMissing('status');

            $newStatus     = $this->statusRepository->findActiveByUuidOrFail($statusUuid);
            $currentStatus = $ticket->status;

            if (! $currentStatus) {
                throw ValidationException::withMessages([
                    'status' => ['The current ticket status is invalid.'],
                ]);
            }

            $currentStatusCode = TicketStatusCode::from($currentStatus->code);
            $targetStatusCode  = TicketStatusCode::from($newStatus->code);

            if (! $currentStatusCode->canTransitionTo($targetStatusCode)) {
                throw ValidationException::withMessages([
                    'status_uuid' => ['The selected ticket status transition is not allowed.'],
                ]);
            }

            $updateData = [
                'status_id' => $newStatus->id,
            ];

            $activityType = TicketActivityType::STATUS_CHANGED->value;
            $description  = "Ticket {$ticket->ticket_number} status changed from {$currentStatus->name} to {$newStatus->name}.";

            if ($targetStatusCode === TicketStatusCode::RESOLVED) {
                $updateData['resolved_at'] = now();
                $updateData['closed_at']   = null;
                $activityType              = TicketActivityType::RESOLVED->value;
                $description               = "Ticket {$ticket->ticket_number} was resolved.";
            }

            if ($targetStatusCode === TicketStatusCode::CLOSED) {
                $updateData['closed_at']   = now();
                $updateData['resolved_at'] = $ticket->resolved_at ?? now();
                $activityType              = TicketActivityType::CLOSED->value;
                $description               = "Ticket {$ticket->ticket_number} was closed.";
            }

            if (
                in_array($currentStatusCode, [TicketStatusCode::RESOLVED, TicketStatusCode::CLOSED], true)
                && in_array($targetStatusCode, [TicketStatusCode::OPEN, TicketStatusCode::IN_PROGRESS], true)
            ) {
                $updateData['resolved_at'] = null;
                $updateData['closed_at']   = null;
                $activityType              = TicketActivityType::REOPENED->value;
                $description               = "Ticket {$ticket->ticket_number} was reopened and moved to {$newStatus->name}.";
            }

            if (
                ! in_array($targetStatusCode, [TicketStatusCode::RESOLVED, TicketStatusCode::CLOSED], true)
                && $activityType === TicketActivityType::STATUS_CHANGED->value
            ) {
                $updateData['closed_at'] = null;
            }

            $ticket = $this->ticketRepository->update($ticket, $updateData);

            $this->ticketActivityService->log(
                ticket: $ticket,
                user: $actor,
                type: $activityType,
                description: $description,
                field: 'status_id',
                oldValue: $currentStatus->uuid,
                newValue: $newStatus->uuid,
                metadata: $comment ? ['comment' => $comment] : null
            );

            return $ticket->load([
                'customer',
                'assignedAgent',
                'category',
                'priority',
                'status',
            ]);
        });
    }
}
