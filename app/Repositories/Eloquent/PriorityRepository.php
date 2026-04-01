<?php
namespace App\Repositories\Eloquent;

use App\Models\Priority;
use App\Repositories\Contracts\PriorityRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PriorityRepository implements PriorityRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $sortBy = in_array(($filters['sort_by'] ?? 'level'), ['name', 'level', 'created_at'], true)
            ? $filters['sort_by']
            : 'level';

        $sortDirection = in_array(($filters['sort_direction'] ?? 'asc'), ['asc', 'desc'], true)
            ? $filters['sort_direction']
            : 'asc';

        return Priority::query()
            ->when(
                ! empty($filters['search']),
                fn($query) => $query->where(function ($subQuery) use ($filters): void {
                    $search = trim((string) $filters['search']);

                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                })
            )
            ->when(
                array_key_exists('is_active', $filters),
                fn($query) => $query->where('is_active', (bool) $filters['is_active'])
            )
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function allActive(): Collection
    {
        return Priority::query()
            ->where('is_active', true)
            ->orderBy('level')
            ->get();
    }

    public function findByUuidOrFail(string $uuid): Priority
    {
        return Priority::query()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function findActiveByUuidOrFail(string $uuid): Priority
    {
        return Priority::query()
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function create(array $data): Priority
    {
        return Priority::query()->create($data);
    }

    public function update(Priority $priority, array $data): Priority
    {
        $priority->update($data);

        return $priority->refresh();
    }

    public function delete(Priority $priority): void
    {
        $priority->delete();
    }
}
