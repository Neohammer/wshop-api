<?php

namespace App\Shared\Exception;

class UnauthorizedException extends HttpException
{
    public function __construct(string $message = 'Unauthorized')
    {
        parent::__construct($message, 401);
    }
}