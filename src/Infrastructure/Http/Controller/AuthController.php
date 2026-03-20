<?php

namespace App\Infrastructure\Http\Controller;

use App\Application\DTO\LoginInputDto;
use App\Application\Service\AuthService;
use App\Infrastructure\Http\JsonResponse;
use App\Infrastructure\Http\Request;

class AuthController
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(Request $request): JsonResponse
    {
        $body = $request->getBody();

        $input = new LoginInputDto(
            $body['email'] ?? '',
            $body['password'] ?? ''
        );

        $result = $this->authService->login($input);

        return JsonResponse::success($result);
    }
}