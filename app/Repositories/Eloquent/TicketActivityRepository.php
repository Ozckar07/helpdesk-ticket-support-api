<?php
namespace App\Repositories\Eloquent;

use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Repositories\Contracts\TicketActivityRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketActivityRepository implements TicketActivityRepositoryInterface
{
    public function create(array $data): TicketActivity
    {
        return TicketActivity::query()->create($data);
    }

    public function paginateByTicket(Ticket $ticket, int $perPage = 20): LengthAwarePaginator
    {
        return TicketActivity::query()
            ->with('user')
            ->where('ticket_id', $ticket->id)
            ->latest('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }
}
