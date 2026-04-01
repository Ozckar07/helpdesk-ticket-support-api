<?php
namespace App\Http\Requests\Category;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryUpdateRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::CATEGORY_UPDATE->value,
        ]);
    }

    public function rules(): array
    {
        /** @var Category $category */
        $category = $this->route('category');

        return [
            'name'        => [
                'sometimes',
                'required',
                'string',
                'max:120',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
            'slug'        => [
                'nullable',
                'string',
                'max:140',
                Rule::unique('categories', 'slug')->ignore($category->id),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}
