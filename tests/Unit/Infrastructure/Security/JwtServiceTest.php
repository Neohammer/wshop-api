<?php

namespace Tests\Unit\Infrastructure\Security;

use App\Infrastructure\Security\JwtService;
use App\Shared\Exception\UnauthorizedException;
use PHPUnit\Framework\TestCase;

class JwtServiceTest extends TestCase
{
    public function testGenerateAndValidateToken(): void
    {
        $service = new JwtService('test-secret', 3600);

        $token = $service->generateToken(1, 'admin@example.com');
        $payload = $service->validateToken($token);

        $this->assertSame(1, $payload['sub']);
        $this->assertSame('admin@example.com', $payload['email']);
        $this->assertArrayHasKey('exp', $payload);
    }

    public function testValidateThrowsExceptionForInvalidToken(): void
    {
        $service = new JwtService('test-secret', 3600);

        $this->expectException(UnauthorizedException::class);

        $service->validateToken('invalid.token.value');
    }
}