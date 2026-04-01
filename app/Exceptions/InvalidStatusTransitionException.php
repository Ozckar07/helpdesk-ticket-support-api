<?php
namespace App\Exceptions;

class InvalidStatusTransitionException extends BusinessException
{
    public function __construct(
        string $from,
        string $to,
        array $errors = []
    ) {
        parent::__construct(
            message: "The ticket status cannot transition from '{$from}' to '{$to}'.",
            errors: $errors,
            title: 'Invalid Status Transition',
            status: 422
        );
    }
}
