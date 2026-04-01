<?php
namespace App\Repositories\Contracts;

use App\Models\Status;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface StatusRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function allActive(): Collection;

    public function findByUuidOrFail(string $uuid): Status;

    public function findActiveByUuidOrFail(string $uuid): Status;

    public function findByCodeOrFail(string $code): Status;

    public function create(array $data): Status;

    public function update(Status $status, array $data): Status;

    public function delete(Status $status): void;
}
