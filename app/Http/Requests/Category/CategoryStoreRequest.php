<?php
namespace App\Http\Requests\Category;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class CategoryStoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::CATEGORY_CREATE->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:120', Rule::unique('categories', 'name')],
            'slug'        => ['nullable', 'string', 'max:140', Rule::unique('categories', 'slug')],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}
