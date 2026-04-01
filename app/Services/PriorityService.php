<?php
namespace App\Services;

use App\Exceptions\CannotDeleteResourceException;
use App\Models\Priority;
use App\Repositories\Contracts\PriorityRepositoryInterface;

class PriorityService
{
    public function __construct(
        private readonly PriorityRepositoryInterface $priorityRepository
    ) {
    }

    public function create(array $data): Priority
    {
        return $this->priorityRepository->create($data);
    }

    public function update(Priority $priority, array $data): Priority
    {
        return $this->priorityRepository->update($priority, $data);
    }

    public function delete(Priority $priority): void
    {
        if ($priority->tickets()->exists()) {
            throw new CannotDeleteResourceException(
                resource: 'Priority',
                reason: 'It is currently associated with one or more tickets.'
            );
        }

        $this->priorityRepository->delete($priority);
    }
}
