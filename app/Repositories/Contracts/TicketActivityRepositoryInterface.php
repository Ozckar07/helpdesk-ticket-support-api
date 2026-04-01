<?php
namespace App\Repositories\Contracts;

use App\Models\Ticket;
use App\Models\TicketActivity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TicketActivityRepositoryInterface
{
    public function create(array $data): TicketActivity;

    public function paginateByTicket(Ticket $ticket, int $perPage = 20): LengthAwarePaginator;
}
