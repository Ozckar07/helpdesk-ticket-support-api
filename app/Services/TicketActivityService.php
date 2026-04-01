<?php
namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Contracts\TicketActivityRepositoryInterface;

class TicketActivityService
{
    public function __construct(
        private readonly TicketActivityRepositoryInterface $ticketActivityRepository
    ) {
    }

    public function log(
        Ticket $ticket,
        ?User $user,
        string $type,
        string $description,
        ?string $field = null,
        mixed $oldValue = null,
        mixed $newValue = null,
        ?array $metadata = null
    ): void {
        $this->ticketActivityRepository->create([
            'ticket_id'   => $ticket->id,
            'user_id'     => $user?->id,
            'type'        => $type,
            'field'       => $field,
            'old_value'   => $oldValue,
            'new_value'   => $newValue,
            'description' => $description,
            'metadata'    => $metadata,
        ]);
    }
}
