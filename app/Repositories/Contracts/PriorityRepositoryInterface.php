<?php
namespace App\Repositories\Contracts;

use App\Models\Priority;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PriorityRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function allActive(): Collection;

    public function findByUuidOrFail(string $uuid): Priority;

    public function findActiveByUuidOrFail(string $uuid): Priority;

    public function create(array $data): Priority;

    public function update(Priority $priority, array $data): Priority;

    public function delete(Priority $priority): void;
}
