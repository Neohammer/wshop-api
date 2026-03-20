<?php

namespace App\Domain\Store\Entity;

class Store
{
    public function __construct(
        private ?int   $id,
        private string $name,
        private string $managerName,
        private string $phone,
        private string $street,
        private string $postalCode,
        private string $city,
        private string $createdAt,
        private string $updatedAt
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getManagerName(): string
    {
        return $this->managerName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}