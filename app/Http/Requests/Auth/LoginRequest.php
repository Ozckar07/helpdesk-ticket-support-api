<?php
namespace App\Http\Requests\Auth;

use App\Http\Requests\ApiRequest;

class LoginRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'      => ['required', 'email:rfc', 'max:255'],
            'password'   => ['required', 'string', 'max:255'],
            'token_name' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email'      => is_string($this->email) ? mb_strtolower(trim($this->email)) : $this->email,
            'token_name' => $this->token_name ?: 'api-token',
        ]);
    }
}
