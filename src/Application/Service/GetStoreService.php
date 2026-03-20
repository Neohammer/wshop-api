<?php

namespace App\Application\Service;

use App\Application\DTO\StoreOutputDto;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use App\Shared\Exception\NotFoundException;

class GetStoreService
{
    public function __construct(private StoreRepositoryInterface $repository) {}

    public function execute(int $id): StoreOutputDto
    {
        $store = $this->repository->findById($id);

        if ($store === null) {
            throw new NotFoundException('Store not found');
        }

        return StoreOutputDto::fromEntity($store);
    }
}