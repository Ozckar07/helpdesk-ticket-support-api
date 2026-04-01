<?php
namespace App\Http\Requests\User;

use App\Enums\PermissionCode;
use App\Enums\RoleCode;
use App\Http\Requests\ApiRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::USER_CREATE->value,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['required', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')],
            'password'     => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'is_active'    => ['sometimes', 'boolean'],
            'role_codes'   => ['required', 'array', 'min:1'],
            'role_codes.*' => [
                'required',
                'string',
                Rule::in(array_map(fn(RoleCode $case) => $case->value, RoleCode::cases())),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->email)) {
            $this->merge([
                'email' => mb_strtolower(trim($this->email)),
            ]);
        }
    }
}
