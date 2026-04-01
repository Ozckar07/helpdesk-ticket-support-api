<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Status\StatusIndexRequest;
use App\Http\Requests\Status\StatusStoreRequest;
use App\Http\Requests\Status\StatusUpdateRequest;
use App\Models\Status;
use App\Repositories\Contracts\StatusRepositoryInterface;
use App\Services\StatusService;
use App\Support\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function __construct(
        private readonly StatusRepositoryInterface $statusRepository,
        private readonly StatusService $statusService
    ) {
    }

    public function index(StatusIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 15);

        $statuses = $this->statusRepository->paginate(
            filters: $filters,
            perPage: $perPage
        );

        return ApiResponse::success(
            message: 'Statuses retrieved successfully.',
            data: $statuses
        );
    }

    public function show(Request $request, Status $status): JsonResponse
    {
        return ApiResponse::success(
            message: 'Status retrieved successfully.',
            data: $status
        );
    }

    public function store(StatusStoreRequest $request): JsonResponse
    {
        $status = $this->statusService->create($request->validated());

        return ApiResponse::success(
            message: 'Status created successfully.',
            data: $status,
            status: 201
        );
    }

    public function update(StatusUpdateRequest $request, Status $status): JsonResponse
    {
        $status = $this->statusService->update($status, $request->validated());

        return ApiResponse::success(
            message: 'Status updated successfully.',
            data: $status
        );
    }

    public function destroy(Request $request, Status $status): JsonResponse
    {
        $this->statusService->delete($status);

        return ApiResponse::success(
            message: 'Status deleted successfully.'
        );
    }
}
