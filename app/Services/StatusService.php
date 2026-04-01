<?php
namespace App\Services;

use App\Models\Status;
use App\Repositories\Contracts\StatusRepositoryInterface;
use Illuminate\Validation\ValidationException;

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
            throw ValidationException::withMessages([
                'status' => ['This status cannot be deleted because it is in use by tickets.'],
            ]);
        }

        $this->statusRepository->delete($status);
    }
}
