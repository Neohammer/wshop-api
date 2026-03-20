<?php

namespace App\Application\DTO;

use App\Domain\Store\Entity\Store;

class StoreOutputDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $managerName,
        public readonly string $phone,
        public readonly string $street,
        public readonly string $postalCode,
        public readonly string $city,
        public readonly string $createdAt,
        public readonly string $updatedAt
    ) {}

    public static function fromEntity(Store $store): self
    {
        return new self(
            $store->getId(),
            $store->getName(),
            $store->getManagerName(),
            $store->getPhone(),
            $store->getStreet(),
            $store->getPostalCode(),
            $store->getCity(),
            $store->getCreatedAt(),
            $store->getUpdatedAt()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'manager_name' => $this->managerName,
            'phone' => $this->phone,
            'street' => $this->street,
            'postal_code' => $this->postalCode,
            'city' => $this->city,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}