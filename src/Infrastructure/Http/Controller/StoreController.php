<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\DTO\StoreInputDto;
use App\Application\Service\CreateStoreService;
use App\Application\Service\DeleteStoreService;
use App\Application\Service\GetStoreService;
use App\Application\Service\ListStoresService;
use App\Application\Service\UpdateStoreService;
use App\Infrastructure\Http\JsonResponse;
use App\Infrastructure\Http\Request;

class StoreController
{
    public function __construct(
        private ListStoresService  $listStoresService,
        private GetStoreService    $getStoreService,
        private CreateStoreService $createStoreService,
        private UpdateStoreService $updateStoreService,
        private DeleteStoreService $deleteStoreService
    )
    {
    }

    public function list(Request $request): JsonResponse
    {
        $queryParams = $request->getQueryParams();

        $filters = array_intersect_key($queryParams, array_flip([
            'name',
            'manager_name',
            'postal_code',
            'city',
        ]));

        $sort = $queryParams['sort'] ?? null;
        $order = $queryParams['order'] ?? 'ASC';

        $stores = $this->listStoresService->execute($filters, $sort, $order);

        return JsonResponse::success(array_map(
            fn($storeDto) => $storeDto->toArray(),
            $stores
        ));
    }

    public function get(int $id): JsonResponse
    {
        $store = $this->getStoreService->execute($id);

        return JsonResponse::success($store->toArray());
    }

    public function create(Request $request): JsonResponse
    {
        $body = $request->getBody();

        $input = $this->buildStoreInputDto($body);

        $store = $this->createStoreService->execute($input);

        return JsonResponse::success($store->toArray(), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $body = $request->getBody();

        $input = $this->buildStoreInputDto($body);

        $store = $this->updateStoreService->execute($id, $input);

        return JsonResponse::success($store->toArray());
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $this->deleteStoreService->execute($id);

        return JsonResponse::success(null);
    }

    private function buildStoreInputDto(array $body): StoreInputDto
    {
        return new StoreInputDto(
            $body['name'] ?? '',
            $body['manager_name'] ?? '',
            $body['phone'] ?? '',
            $body['street'] ?? '',
            $body['postal_code'] ?? '',
            $body['city'] ?? ''
        );
    }
}