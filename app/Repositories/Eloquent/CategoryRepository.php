<?php
namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $sortBy = in_array(($filters['sort_by'] ?? 'name'), ['name', 'created_at'], true)
            ? $filters['sort_by']
            : 'name';

        $sortDirection = in_array(($filters['sort_direction'] ?? 'asc'), ['asc', 'desc'], true)
            ? $filters['sort_direction']
            : 'asc';

        return Category::query()
            ->when(
                ! empty($filters['search']),
                fn($query) => $query->where(function ($subQuery) use ($filters): void {
                    $search = trim((string) $filters['search']);

                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
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
        return Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function findByUuidOrFail(string $uuid): Category
    {
        return Category::query()
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function findActiveByUuidOrFail(string $uuid): Category
    {
        return Category::query()
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function create(array $data): Category
    {
        return Category::query()->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->refresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
