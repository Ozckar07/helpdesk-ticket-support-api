<?php
namespace App\Services;

use App\Enums\TicketActivityType;
use App\Enums\TicketMessageType;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Repositories\Contracts\TicketMessageRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TicketMessageService
{
    public function __construct(
        private readonly TicketMessageRepositoryInterface $ticketMessageRepository,
        private readonly TicketAttachmentService $ticketAttachmentService,
        private readonly TicketActivityService $ticketActivityService
    ) {
    }

    public function create(User $actor, Ticket $ticket, array $data): TicketMessage
    {
        return DB::transaction(function () use ($actor, $ticket, $data): TicketMessage {
            $isInternal = (bool) ($data['is_internal'] ?? false);
            $type       = $data['type'] ?? (
                $isInternal
                    ? TicketMessageType::INTERNAL_NOTE->value
                    : TicketMessageType::REPLY->value
            );

            $message = $this->ticketMessageRepository->create([
                'ticket_id'   => $ticket->id,
                'user_id'     => $actor->id,
                'message'     => $data['message'],
                'type'        => $type,
                'is_internal' => $isInternal,
            ]);

            if (! $isInternal && $ticket->first_response_at === null && ($actor->isAdmin() || $actor->isAgent())) {
                $ticket->update([
                    'first_response_at' => now(),
                ]);
            }

            if (! empty($data['attachments']) && is_array($data['attachments'])) {
                $attachments = $this->ticketAttachmentService->uploadMany(
                    ticket: $ticket,
                    user: $actor,
                    files: $data['attachments'],
                    ticketMessage: $message
                );

                if (! empty($attachments)) {
                    $this->ticketActivityService->log(
                        ticket: $ticket,
                        user: $actor,
                        type: TicketActivityType::ATTACHMENT_ADDED->value,
                        description: count($attachments) . ' attachment(s) were added to message on ticket ' . $ticket->ticket_number . '.',
                        metadata: [
                            'message_uuid'      => $message->uuid,
                            'attachments_count' => count($attachments),
                        ]
                    );
                }
            }

            $this->ticketActivityService->log(
                ticket: $ticket,
                user: $actor,
                type: $isInternal
                    ? TicketActivityType::INTERNAL_NOTE_ADDED->value
                    : TicketActivityType::MESSAGE_ADDED->value,
                description: $isInternal
                    ? "An internal note was added to ticket {$ticket->ticket_number}."
                    : "A new reply was added to ticket {$ticket->ticket_number}.",
                metadata: [
                    'message_uuid' => $message->uuid,
                    'message_type' => $type,
                ]
            );

            return $message->load(['user', 'attachments']);
        });
    }
}
