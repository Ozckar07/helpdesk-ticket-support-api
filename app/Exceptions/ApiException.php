<?php
namespace App\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    public function __construct(
        protected string $title = 'Request Error',
        string $message = 'The request could not be processed.',
        protected int $status = 400,
        protected array $errors = [],
        protected array $meta = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $previous);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function meta(): array
    {
        return $this->meta;
    }
}
