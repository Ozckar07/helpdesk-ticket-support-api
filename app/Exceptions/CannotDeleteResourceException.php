<?php
namespace App\Exceptions;

class CannotDeleteResourceException extends BusinessException
{
    public function __construct(
        string $resource = 'Resource',
        string $reason = 'It is currently in use.',
        array $errors = []
    ) {
        parent::__construct(
            message: "{$resource} cannot be deleted. {$reason}",
            errors: $errors,
            title: 'Delete Operation Not Allowed',
            status: 422
        );
    }
}
