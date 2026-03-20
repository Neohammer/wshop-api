<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Store\Entity\Store;
use App\Domain\Store\Repository\StoreRepositoryInterface;
use PDO;

class PdoStoreRepository implements StoreRepositoryInterface
{
    private const ALLOWED_FILTERS = [
        'name',
        'manager_name',
        'postal_code',
        'city',
    ];
    private const ALLOWED_SORTS = [
        'name',
        'manager_name',
        'postal_code',
        'city',
        'created_at',
    ];
    private const ALLOWED_DIRECTIONS = [
        'ASC',
        'DESC'
    ];

    public function __construct(private PDO $pdo)
    {
    }

    public function findAll(array $filters = [], ?string $sort = null, string $order = 'ASC'): array
    {
        $sql = "SELECT * FROM stores";
        $params = [];

        $filters = $this->sanitizeFilters($filters);
        if (count($filters) > 0) {
            $conditions = [];

            foreach ($filters as $field => $value) {
                $conditions[] = "$field = :$field";
                $params[$field] = $value;
            }

            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sort = $this->sanitizeSort($sort);

        if ($sort !== null) {
            $order = $this->sanitizeOrder($order);
            $sql .= " ORDER BY $sort $order";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll();

        return array_map([$this, 'mapRowToEntity'], $rows);
    }

    public function findById(int $id): ?Store
    {
        $stmt = $this->pdo->prepare("SELECT * FROM stores WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function save(Store $store): Store
    {
        if ($store->getId() === null) {
            $stmt = $this->pdo->prepare("
                INSERT INTO stores (name, manager_name, phone, street, postal_code, city, created_at, updated_at)
                VALUES (:name, :manager_name, :phone, :street, :postal_code, :city, :created_at, :updated_at)
            ");

            $stmt->execute([
                'name' => $store->getName(),
                'manager_name' => $store->getManagerName(),
                'phone' => $store->getPhone(),
                'street' => $store->getStreet(),
                'postal_code' => $store->getPostalCode(),
                'city' => $store->getCity(),
                'created_at' => $store->getCreatedAt(),
                'updated_at' => $store->getUpdatedAt(),
            ]);

            $id = (int)$this->pdo->lastInsertId();

            return new Store(
                $id,
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

        // UPDATE
        $stmt = $this->pdo->prepare("
            UPDATE stores SET
                name = :name,
                manager_name = :manager_name,
                phone = :phone,
                street = :street,
                postal_code = :postal_code,
                city = :city,
                updated_at = :updated_at
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $store->getId(),
            'name' => $store->getName(),
            'manager_name' => $store->getManagerName(),
            'phone' => $store->getPhone(),
            'street' => $store->getStreet(),
            'postal_code' => $store->getPostalCode(),
            'city' => $store->getCity(),
            'updated_at' => $store->getUpdatedAt(),
        ]);

        return $store;
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM stores WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    private function mapRowToEntity(array $row): Store
    {
        return new Store(
            (int)$row['id'],
            $row['name'],
            $row['manager_name'],
            $row['phone'],
            $row['street'],
            $row['postal_code'],
            $row['city'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    private function sanitizeFilters(array $filters): array
    {
        $sanitized = [];

        foreach ($filters as $field => $value) {
            if (!in_array($field, self::ALLOWED_FILTERS, true)) {
                continue;
            }

            if ($value === null || $value === '') {
                continue;
            }

            $sanitized[$field] = $value;
        }

        return $sanitized;
    }

    private function sanitizeSort(?string $sort): ?string
    {
        if ($sort === null) {
            return null;
        }

        return in_array($sort, self::ALLOWED_SORTS, true) ? $sort : null;
    }

    private function sanitizeOrder(string $order): string
    {
        $order = strtoupper($order);

        return in_array($order, self::ALLOWED_DIRECTIONS, true) ? $order : self::ALLOWED_DIRECTIONS[0];
    }
}