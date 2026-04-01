<?php
namespace App\Repositories\Eloquent;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Repositories\Contracts\TicketMessageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketMessageRepository implements TicketMessageRepositoryInterface
{
    public function create(array $data): TicketMessage
    {
        return TicketMessage::query()->create($data);
    }

    public function paginateByTicket(Ticket $ticket, User $user, int $perPage = 20): LengthAwarePaginator
    {
        $query = TicketMessage::query()
            ->with(['user', 'attachments'])
            ->where('ticket_id', $ticket->id)
            ->orderBy('created_at');

        if (! $user->isAdmin() && ! $user->isAgent()) {
            $query->where('is_internal', false);
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
