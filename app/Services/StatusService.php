<?php

namespace App\Services;

use App\Exceptions\CannotDeleteResourceException;
use App\Models\Status;
use App\Repositories\Contracts\StatusRepositoryInterface;

class StatusService
{
    public function __construct(
        private readonly StatusRepositoryInterface $statusRepository
    ) {
    }

    public function create(array $data): Status
    {
        return $this->statusRepository->create($data);
    }

    public function update(Status $status, array $data): Status
    {
        return $this->statusRepository->update($status, $data);
    }

    public function delete(Status $status): void
    {
        if ($status->tickets()->exists()) {
            throw new CannotDeleteResourceException(
                resource: 'Status',
                reason: 'It is currently associated with one or more tickets.'
            );
        }

        $this->statusRepository->delete($status);
    }
}
