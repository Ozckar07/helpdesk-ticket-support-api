<?php
namespace App\Services;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function create(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    public function update(Category $category, array $data): Category
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function delete(Category $category): void
    {
        if ($category->tickets()->exists()) {
            throw ValidationException::withMessages([
                'category' => ['This category cannot be deleted because it is in use by tickets.'],
            ]);
        }

        $this->categoryRepository->delete($category);
    }
}
