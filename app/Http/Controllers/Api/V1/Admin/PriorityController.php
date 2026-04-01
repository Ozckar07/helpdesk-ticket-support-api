<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Priority\PriorityIndexRequest;
use App\Http\Requests\Priority\PriorityStoreRequest;
use App\Http\Requests\Priority\PriorityUpdateRequest;
use App\Models\Priority;
use App\Repositories\Contracts\PriorityRepositoryInterface;
use App\Services\PriorityService;
use App\Support\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function __construct(
        private readonly PriorityRepositoryInterface $priorityRepository,
        private readonly PriorityService $priorityService
    ) {
    }

    public function index(PriorityIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 15);

        $priorities = $this->priorityRepository->paginate(
            filters: $filters,
            perPage: $perPage
        );

        return ApiResponse::success(
            message: 'Priorities retrieved successfully.',
            data: $priorities
        );
    }

    public function show(Request $request, Priority $priority): JsonResponse
    {
        return ApiResponse::success(
            message: 'Priority retrieved successfully.',
            data: $priority
        );
    }

    public function store(PriorityStoreRequest $request): JsonResponse
    {
        $priority = $this->priorityService->create($request->validated());

        return ApiResponse::success(
            message: 'Priority created successfully.',
            data: $priority,
            status: 201
        );
    }

    public function update(PriorityUpdateRequest $request, Priority $priority): JsonResponse
    {
        $priority = $this->priorityService->update($priority, $request->validated());

        return ApiResponse::success(
            message: 'Priority updated successfully.',
            data: $priority
        );
    }

    public function destroy(Request $request, Priority $priority): JsonResponse
    {
        $this->priorityService->delete($priority);

        return ApiResponse::success(
            message: 'Priority deleted successfully.'
        );
    }
}
