<?php

namespace Tests\Unit\Application\Service;

use App\Application\DTO\StoreInputDto;
use App\Application\Service\CreateStoreService;
use App\Application\Validator\StoreValidator;
use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateStoreServiceTest extends TestCase
{
    public function testExecuteCreatesStoreAndReturnsOutputDto(): void
    {
        $repository = $this->createMock(StoreRepositoryInterface::class);
        $validator = new StoreValidator();

        $repository->expects($this->once())
            ->method('save')
            ->willReturnCallback(function (Store $store) {
                return new Store(
                    42,
                    $store->getName(),
                    $store->getManagerName(),
                    $store->getPhone(),
                    $store->getStreet(),
                    $store->getPostalCode(),
                    $store->getCity(),
                    $store->getCreatedAt(),
                    $store->getUpdatedAt()
                );
            });

        $service = new CreateStoreService($repository, $validator);

        $input = new StoreInputDto(
            'Nantes Centre',
            'Laura Petit',
            '0405060708',
            '12 rue Crébillon',
            '44000',
            'Nantes'
        );

        $result = $service->execute($input);

        $this->assertSame(42, $result->id);
        $this->assertSame('Nantes Centre', $result->name);
        $this->assertSame('Laura Petit', $result->managerName);
    }
}