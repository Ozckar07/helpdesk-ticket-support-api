<?php
namespace App\Exceptions;

class NotFoundException extends ApiException
{
    public function __construct(
        string $message = 'The requested resource was not found.',
        array $meta = []
    ) {
        parent::__construct(
            title: 'Resource Not Found',
            message: $message,
            status: 404,
            errors: [],
            meta: $meta
        );
    }
}
