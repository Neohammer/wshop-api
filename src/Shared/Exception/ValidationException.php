<?php

namespace App\Shared\Exception;

class ValidationException extends HttpException
{
    public function __construct(
        string        $message = 'Validation failed',
        private array $errors = []
    )
    {
        parent::__construct($message, 422);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}