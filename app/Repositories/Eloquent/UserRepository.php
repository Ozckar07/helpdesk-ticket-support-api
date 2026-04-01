<?php
namespace App\Repositories\Eloquent;

use App\Enums\RoleCode;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $requestedSortBy        = $filters['sort_by'] ?? 'name';
        $requestedSortDirection = $filters['sort_direction'] ?? 'asc';

        $sortBy = in_array($requestedSortBy, ['name', 'email', 'created_at'], true)
            ? $requestedSortBy
            : 'name';

        $sortDirection = in_array($requestedSortDirection, ['asc', 'desc'], true)
            ? $requestedSortDirection
            : 'asc';

        return User::query()
            ->with('roles')
            ->when(
                ! empty($filters['search']),
                fn($query) => $query->where(function ($subQuery) use ($filters): void {
                    $search = trim((string) $filters['search']);

                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
            )
            ->when(
                array_key_exists('is_active', $filters),
                fn($query) => $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN))
            )
            ->when(
                ! empty($filters['role_code']),
                fn($query) => $query->whereHas(
                    'roles',
                    fn($subQuery) => $subQuery->where('code', $filters['role_code'])
                )
            )
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findByUuidOrFail(string $uuid): User
    {
        return User::query()
            ->with('roles')
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function findActiveByUuidOrFail(string $uuid): User
    {
        return User::query()
            ->with('roles')
            ->where('uuid', $uuid)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user->refresh()->load('roles');
    }

    public function syncRoles(User $user, array $roleCodes): User
    {
        $roleIds = Role::query()
            ->whereIn('code', $roleCodes)
            ->pluck('id')
            ->all();

        $user->roles()->sync($roleIds);

        return $user->refresh()->load('roles');
    }

    public function getAssignableAgents(): Collection
    {
        return User::query()
            ->with('roles')
            ->where('is_active', true)
            ->whereHas('roles', function ($query): void {
                $query->whereIn('code', [
                    RoleCode::ADMIN->value,
                    RoleCode::AGENT->value,
                ]);
            })
            ->orderBy('name')
            ->get();
    }
}
