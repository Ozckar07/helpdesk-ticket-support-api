<?php
namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function findByUuidOrFail(string $uuid): User;

    public function findActiveByUuidOrFail(string $uuid): User;

    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function syncRoles(User $user, array $roleCodes): User;

    public function getAssignableAgents(): Collection;
}
