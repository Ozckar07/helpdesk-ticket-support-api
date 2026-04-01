<?php
namespace App\Http\Requests\Priority;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class PriorityIndexRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::PRIORITY_VIEW_ANY->value,
            PermissionCode::PRIORITY_VIEW->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'search'         => ['nullable', 'string', 'max:150'],
            'is_active'      => ['nullable', 'boolean'],
            'sort_by'        => ['nullable', 'string', Rule::in(['name', 'level', 'created_at'])],
            'sort_direction' => $this->sortDirectionRules(),
            'per_page'       => $this->perPageRules(),
        ];
    }
}
