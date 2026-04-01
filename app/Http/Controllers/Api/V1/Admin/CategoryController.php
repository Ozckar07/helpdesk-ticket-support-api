<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryIndexRequest;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\CategoryService;
use App\Support\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly CategoryService $categoryService
    ) {
    }

    public function index(CategoryIndexRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = (int) ($filters['per_page'] ?? 15);

        $categories = $this->categoryRepository->paginate(
            filters: $filters,
            perPage: $perPage
        );

        return ApiResponse::success(
            message: 'Categories retrieved successfully.',
            data: $categories
        );
    }

    public function show(Request $request, Category $category): JsonResponse
    {
        return ApiResponse::success(
            message: 'Category retrieved successfully.',
            data: $category
        );
    }

    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $category = $this->categoryService->create($request->validated());

        return ApiResponse::success(
            message: 'Category created successfully.',
            data: $category,
            status: 201
        );
    }

    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        $category = $this->categoryService->update($category, $request->validated());

        return ApiResponse::success(
            message: 'Category updated successfully.',
            data: $category
        );
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        $this->categoryService->delete($category);

        return ApiResponse::success(
            message: 'Category deleted successfully.'
        );
    }
}
