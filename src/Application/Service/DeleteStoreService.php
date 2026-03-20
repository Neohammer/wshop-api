<?php

namespace App\Application\Service;

use App\Domain\Store\Repository\StoreRepositoryInterface;
use App\Shared\Exception\NotFoundException;

class DeleteStoreService
{
    public function __construct(private StoreRepositoryInterface $repository)
    {
    }

    public function execute(int $id): void
    {
        $store = $this->repository->findById($id);

        if ($store === null) {
            throw new NotFoundException('Store not found');
        }

        $this->repository->delete($id);
    }
}