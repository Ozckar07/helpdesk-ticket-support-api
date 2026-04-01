<?php
namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

abstract class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors'  => $validator->errors(),
            'meta'    => (object) [],
        ], 422));
    }

    protected function perPageRules(int $max = 100): array
    {
        return ['nullable', 'integer', 'min:1', 'max:' . $max];
    }

    protected function sortDirectionRules(): array
    {
        return ['nullable', 'string', Rule::in(['asc', 'desc'])];
    }

    protected function uuidExistsRule(string $table, string $column = 'uuid'): Exists
    {
        return Rule::exists($table, $column)
            ->where(fn($query) => $query->whereNull("{$table}.deleted_at"));
    }

    protected function activeUuidExistsRule(string $table, string $column = 'uuid'): Exists
    {
        return Rule::exists($table, $column)
            ->where(fn($query) => $query
                    ->whereNull("{$table}.deleted_at")
                    ->where("{$table}.is_active", true));
    }

    protected function attachmentArrayRules(int $maxFiles = 5): array
    {
        return ['nullable', 'array', 'max:' . $maxFiles];
    }

    protected function attachmentFileRules(int $maxKb = 10240): array
    {
        return [
            'file',
            'max:' . $maxKb,
            'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,csv,txt,zip',
        ];
    }

    protected function canAnyPermission(array $permissionCodes): bool
    {
        $user = $this->user();

        if (! $user) {
            return false;
        }

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        foreach ($permissionCodes as $permissionCode) {
            if ($user->hasPermission($permissionCode)) {
                return true;
            }
        }

        return false;
    }
}
