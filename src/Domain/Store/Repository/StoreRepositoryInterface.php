<?php

namespace App\Domain\Store\Repository;

use App\Domain\Store\Entity\Store;

interface StoreRepositoryInterface
{
    public function findAll(array $filters = [], ?string $sort = null, string $order = 'ASC'): array;

    public function findById(int $id): ?Store;

    public function save(Store $store): Store;

    public function delete(int $id): void;
}