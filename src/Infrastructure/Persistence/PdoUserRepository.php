<?php

namespace App\Infrastructure\Persistence;

use PDO;

class PdoUserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
}