<?php
namespace App\Services;

use App\Exceptions\CannotDeleteResourceException;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

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
            throw new CannotDeleteResourceException(
                resource: 'Category',
                reason: 'It is currently associated with one or more tickets.'
            );
        }

        $this->categoryRepository->delete($category);
    }
}
