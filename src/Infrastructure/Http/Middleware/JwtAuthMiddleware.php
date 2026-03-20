<?php

namespace App\Infrastructure\Http\Middleware;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Security\JwtService;
use App\Shared\Exception\UnauthorizedException;

class JwtAuthMiddleware
{
    public function __construct(private JwtService $jwtService)
    {
    }

    public function handle(Request $request): array
    {
        $authorization = $request->getHeader('Authorization');

        if ($authorization === null) {
            throw new UnauthorizedException('Missing Authorization header');
        }

        if (!preg_match('/^Bearer\s+(.+)$/i', $authorization, $matches)) {
            throw new UnauthorizedException('Invalid Authorization header');
        }

        $token = $matches[1];
        return $this->jwtService->validateToken($token);
    }
}