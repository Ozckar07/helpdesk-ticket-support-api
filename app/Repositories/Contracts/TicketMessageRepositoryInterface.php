<?php
namespace App\Repositories\Contracts;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TicketMessageRepositoryInterface
{
    public function create(array $data): TicketMessage;

    public function paginateByTicket(Ticket $ticket, User $user, int $perPage = 20): LengthAwarePaginator;
}
