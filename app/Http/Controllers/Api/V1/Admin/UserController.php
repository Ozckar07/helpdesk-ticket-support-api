<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UserService;
use App\Support\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserService $userService
    ) {
    }

    public function index(UserIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 15);

        $users = $this->userRepository->paginate(
            filters: $filters,
            perPage: $perPage
        );

        return ApiResponse::success(
            message: 'Users retrieved successfully.',
            data: $users
        );
    }

    public function show(Request $request, User $user): JsonResponse
    {
        return ApiResponse::success(
            message: 'User retrieved successfully.',
            data: $user->load('roles')
        );
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return ApiResponse::success(
            message: 'User created successfully.',
            data: $user,
            status: 201
        );
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $user = $this->userService->update($user, $request->validated());

        return ApiResponse::success(
            message: 'User updated successfully.',
            data: $user
        );
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $user->delete();

        return ApiResponse::success(
            message: 'User deleted successfully.'
        );
    }

    public function assignableAgents(Request $request): JsonResponse
    {
        $users = $this->userRepository->getAssignableAgents();

        return ApiResponse::success(
            message: 'Assignable agents retrieved successfully.',
            data: $users
        );
    }
}
