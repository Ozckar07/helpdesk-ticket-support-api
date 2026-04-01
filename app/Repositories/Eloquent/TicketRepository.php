<?php
namespace App\Repositories\Eloquent;

use App\Enums\PermissionCode;
use App\Models\Ticket;
use App\Models\User;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Support\Filters\TicketFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketRepository implements TicketRepositoryInterface
{
    public function __construct(
        private readonly TicketFilter $ticketFilter
    ) {
    }

    public function paginateForUser(User $user, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $sortBy = in_array(
            ($filters['sort_by'] ?? 'created_at'),
            ['created_at', 'updated_at', 'resolved_at', 'closed_at', 'ticket_number', 'subject'],
            true
        ) ? $filters['sort_by'] : 'created_at';

        $sortDirection = in_array(($filters['sort_direction'] ?? 'desc'), ['asc', 'desc'], true)
            ? $filters['sort_direction']
            : 'desc';

        $query = Ticket::query()
            ->with([
                'customer',
                'assignedAgent',
                'category',
                'priority',
                'status',
                'latestMessage.user',
            ]);

        if (! $user->isAdmin() && ! $user->hasPermission(PermissionCode::TICKET_VIEW_ANY->value)) {
            $query->where('customer_id', $user->id);
        }

        if (($filters['only_mine'] ?? false) === true) {
            $query->where(function ($subQuery) use ($user): void {
                $subQuery
                    ->where('customer_id', $user->id)
                    ->orWhere('assigned_agent_id', $user->id);
            });
        }

        $this->ticketFilter->apply($query, $filters);

        return $query
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findByUuidOrFail(string $uuid, array $with = []): Ticket
    {
        return Ticket::query()
            ->with($with)
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function create(array $data): Ticket
    {
        return Ticket::query()->create($data);
    }

    public function update(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);

        return $ticket->refresh();
    }

    public function nextTicketNumber(): string
    {
        $year = now()->format('Y');

        $lastTicket = Ticket::query()
            ->where('ticket_number', 'like', "HD-{$year}-%")
            ->lockForUpdate()
            ->orderByDesc('ticket_number')
            ->first();

        $nextSequence = 1;

        if ($lastTicket) {
            $parts        = explode('-', $lastTicket->ticket_number);
            $lastSequence = (int) end($parts);
            $nextSequence = $lastSequence + 1;
        }

        return sprintf('HD-%s-%06d', $year, $nextSequence);
    }
}
