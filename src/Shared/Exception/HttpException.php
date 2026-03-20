<?php

namespace App\Shared\Exception;

use Exception;

class HttpException extends Exception
{
    public function __construct(string $message, private int $statusCode = 500)
    {
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}