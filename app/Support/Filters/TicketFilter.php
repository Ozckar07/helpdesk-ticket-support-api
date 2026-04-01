<?php
namespace App\Support\Filters;

use Illuminate\Database\Eloquent\Builder;

class TicketFilter
{
    public function apply(Builder $query, array $filters = []): Builder
    {
        $query
            ->when(
                ! empty($filters['search']),
                fn(Builder $q) => $q->where(function (Builder $subQuery) use ($filters): void {
                    $search = trim((string) $filters['search']);

                    $subQuery
                        ->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
            )
            ->when(
                ! empty($filters['status_uuid']),
                fn(Builder $q) => $q->whereHas('status', fn(Builder $subQuery) => $subQuery->where('uuid', $filters['status_uuid']))
            )
            ->when(
                ! empty($filters['priority_uuid']),
                fn(Builder $q) => $q->whereHas('priority', fn(Builder $subQuery) => $subQuery->where('uuid', $filters['priority_uuid']))
            )
            ->when(
                ! empty($filters['category_uuid']),
                fn(Builder $q) => $q->whereHas('category', fn(Builder $subQuery) => $subQuery->where('uuid', $filters['category_uuid']))
            )
            ->when(
                ! empty($filters['assigned_agent_uuid']),
                fn(Builder $q) => $q->whereHas('assignedAgent', fn(Builder $subQuery) => $subQuery->where('uuid', $filters['assigned_agent_uuid']))
            )
            ->when(
                ! empty($filters['customer_uuid']),
                fn(Builder $q) => $q->whereHas('customer', fn(Builder $subQuery) => $subQuery->where('uuid', $filters['customer_uuid']))
            )
            ->when(
                ! empty($filters['date_from']),
                fn(Builder $q) => $q->whereDate('created_at', '>=', $filters['date_from'])
            )
            ->when(
                ! empty($filters['date_to']),
                fn(Builder $q) => $q->whereDate('created_at', '<=', $filters['date_to'])
            )
            ->when(
                ($filters['only_assigned'] ?? false) === true,
                fn(Builder $q) => $q->whereNotNull('assigned_agent_id')
            )
            ->when(
                ($filters['only_unassigned'] ?? false) === true,
                fn(Builder $q) => $q->whereNull('assigned_agent_id')
            );

        return $query;
    }
}
