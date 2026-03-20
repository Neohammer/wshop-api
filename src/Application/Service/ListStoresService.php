<?php

namespace App\Application\Service;

use App\Application\DTO\StoreOutputDto;
use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;

class ListStoresService
{
    public function __construct(private StoreRepositoryInterface $repository)
    {
    }

    public function execute(array $filters = [], ?string $sort = null, string $order = 'ASC'): array
    {
        $stores = $this->repository->findAll($filters, $sort, $order);

        return array_map(
            fn(Store $store) => StoreOutputDto::fromEntity($store),
            $stores
        );
    }
}