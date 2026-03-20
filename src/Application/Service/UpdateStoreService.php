<?php

namespace App\Application\Service;

use App\Application\DTO\StoreInputDto;
use App\Application\DTO\StoreOutputDto;
use App\Application\Validator\StoreValidator;
use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use App\Shared\Exception\NotFoundException;

class UpdateStoreService
{
    public function __construct(
        private StoreRepositoryInterface $repository,
        private StoreValidator           $validator
    )
    {
    }

    public function execute(int $id, StoreInputDto $input): StoreOutputDto
    {
        $this->validator->validate($input);

        $existingStore = $this->repository->findById($id);

        if ($existingStore === null) {
            throw new NotFoundException('Store not found');
        }

        $updatedStore = new Store(
            $existingStore->getId(),
            $input->name,
            $input->managerName,
            $input->phone,
            $input->street,
            $input->postalCode,
            $input->city,
            $existingStore->getCreatedAt(),
            date(DATE_ATOM)
        );

        $savedStore = $this->repository->save($updatedStore);

        return StoreOutputDto::fromEntity($savedStore);
    }
}