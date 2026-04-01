<?php
namespace App\Http\Requests\User;

use App\Enums\PermissionCode;
use App\Enums\RoleCode;
use App\Http\Requests\ApiRequest;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return $this->canAnyPermission([
            PermissionCode::USER_UPDATE->value,
        ]);
    }

    public function rules(): array
    {
        /** @var User $user */
        $user = $this->route('user');

        return [
            'name'         => ['sometimes', 'required', 'string', 'max:120'],
            'email'        => [
                'sometimes',
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password'     => ['nullable', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'is_active'    => ['sometimes', 'boolean'],
            'role_codes'   => ['sometimes', 'array', 'min:1'],
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
