<?php

namespace App\Application\DTO;

class LoginInputDto
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    )
    {
    }
}