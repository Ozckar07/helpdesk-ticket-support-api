<?php
namespace App\Repositories\Contracts;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TicketRepositoryInterface
{
    public function paginateForUser(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function findByUuidOrFail(string $uuid, array $with = []): Ticket;

    public function create(array $data): Ticket;

    public function update(Ticket $ticket, array $data): Ticket;

    public function nextTicketNumber(): string;
}
