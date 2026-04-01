<?php
namespace App\Http\Requests\Status;

use App\Enums\PermissionCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class StatusIndexRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::STATUS_VIEW_ANY->value,
            PermissionCode::STATUS_VIEW->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'search'         => ['nullable', 'string', 'max:150'],
            'is_active'      => ['nullable', 'boolean'],
            'is_final'       => ['nullable', 'boolean'],
            'sort_by'        => ['nullable', 'string', Rule::in(['name', 'sort_order', 'created_at'])],
            'sort_direction' => $this->sortDirectionRules(),
            'per_page'       => $this->perPageRules(),
        ];
    }
}
