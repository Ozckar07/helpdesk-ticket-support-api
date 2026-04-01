<?php
namespace App\Services;

use App\Models\Priority;
use App\Repositories\Contracts\PriorityRepositoryInterface;
use Illuminate\Validation\ValidationException;

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
            throw ValidationException::withMessages([
                'priority' => ['This priority cannot be deleted because it is in use by tickets.'],
            ]);
        }

        $this->priorityRepository->delete($priority);
    }
}
