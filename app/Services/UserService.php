<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $roleCodes = $data['role_codes'] ?? [];
            unset($data['role_codes']);

            $user = $this->userRepository->create($data);

            if (! empty($roleCodes)) {
                $user = $this->userRepository->syncRoles($user, $roleCodes);
            }

            return $user->load('roles');
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $roleCodes = $data['role_codes'] ?? null;
            unset($data['role_codes']);

            $user = $this->userRepository->update($user, $data);

            if (is_array($roleCodes)) {
                $user = $this->userRepository->syncRoles($user, $roleCodes);
            }

            return $user->load('roles');
        });
    }
}
