<?php

namespace App\Application\DTO;

class StoreInputDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $managerName,
        public readonly string $phone,
        public readonly string $street,
        public readonly string $postalCode,
        public readonly string $city
    )
    {
    }
}