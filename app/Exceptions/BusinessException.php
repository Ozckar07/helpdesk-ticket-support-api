<?php
namespace App\Exceptions;

class BusinessException extends ApiException
{
    public function __construct(
        string $message = 'A business rule prevented the operation.',
        array $errors = [],
        array $meta = [],
        string $title = 'Business Rule Violation',
        int $status = 422
    ) {
        parent::__construct(
            title: $title,
            message: $message,
            status: $status,
            errors: $errors,
            meta: $meta
        );
    }
}
