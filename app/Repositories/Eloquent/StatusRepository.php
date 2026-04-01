<?php
namespace App\Repositories\Eloquent;

use App\Models\Status;
use App\Repositories\Contracts\StatusRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StatusRepository implements StatusRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $sortBy = in_array(($filters['sort_by'] ?? 'sort_order'), ['name', 'sort_order', 'created_at'], true)
            ? $filters['sort_by']
            : 'sort_order';

        $sortDirection = in_array(($filters['sort_direction'] ?? 'asc'), ['asc', 'desc'], true)
            ? $filters['sort_direction']
            : 'asc';

        return Status::query()
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
            ->when(
                array_key_exists('is_final', $filters),
                fn($query) => $query->where('is_final', (bool) $filters['is_final'])
            )
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function allActive(): Collection
    {
        return Status::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function findByUuidOrFail(string $uuid): Status
    {
        return Status::query()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function findActiveByUuidOrFail(string $uuid): Status
    {
        return Status::query()
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function findByCodeOrFail(string $code): Status
    {
        return Status::query()
            ->where('code', $code)
            ->firstOrFail();
    }

    public function create(array $data): Status
    {
        return Status::query()->create($data);
    }

    public function update(Status $status, array $data): Status
    {
        $status->update($data);

        return $status->refresh();
    }

    public function delete(Status $status): void
    {
        $status->delete();
    }
}
