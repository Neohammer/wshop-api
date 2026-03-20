<?php

namespace App\Application\Service;

use App\Application\DTO\LoginInputDto;
use App\Infrastructure\Persistence\PdoUserRepository;
use App\Infrastructure\Security\JwtService;
use App\Shared\Exception\UnauthorizedException;

class AuthService
{
    public function __construct(
        private PdoUserRepository $userRepository,
        private JwtService        $jwtService,
    )
    {
    }

    public function login(LoginInputDto $input): array
    {
        $user = $this->userRepository->findByEmail($input->email);

        if ($user === null) {
            throw new UnauthorizedException('Invalid credentials');
        }

        if (!password_verify($input->password, $user['password_hash'])) {
            throw new UnauthorizedException('Invalid credentials');
        }

        $token = $this->jwtService->generateToken((int)$user['id'], $user['email']);

        return [
            'token' => $token,
        ];
    }
}