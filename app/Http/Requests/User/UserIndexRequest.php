<?php
namespace App\Http\Requests\User;

use App\Enums\PermissionCode;
use App\Enums\RoleCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class UserIndexRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::USER_VIEW_ANY->value,
            PermissionCode::USER_VIEW->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'search'         => ['nullable', 'string', 'max:150'],
            'role_code'      => [
                'nullable',
                'string',
                Rule::in(array_map(fn(RoleCode $case) => $case->value, RoleCode::cases())),
            ],
            'is_active'      => ['nullable', 'boolean'],
            'sort_by'        => ['nullable', 'string', Rule::in(['name', 'email', 'created_at'])],
            'sort_direction' => $this->sortDirectionRules(),
            'per_page'       => $this->perPageRules(),
        ];
    }
}
