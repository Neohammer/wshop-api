<?php

namespace App\Shared\Exception;

class NotFoundException extends HttpException
{
    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct($message, 404);
    }
}