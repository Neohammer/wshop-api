<?php

namespace App\Application\Service;

use App\Application\DTO\StoreInputDto;
use App\Application\DTO\StoreOutputDto;
use App\Application\Validator\StoreValidator;
use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;

class CreateStoreService
{
    public function __construct(
        private StoreRepositoryInterface $repository,
        private StoreValidator           $validator
    )
    {
    }

    public function execute(StoreInputDto $input): StoreOutputDto
    {
        $this->validator->validate($input);

        $now = date(DATE_ATOM);

        $store = new Store(
            null,
            $input->name,
            $input->managerName,
            $input->phone,
            $input->street,
            $input->postalCode,
            $input->city,
            $now,
            $now
        );

        $savedStore = $this->repository->save($store);

        return StoreOutputDto::fromEntity($savedStore);
    }
}