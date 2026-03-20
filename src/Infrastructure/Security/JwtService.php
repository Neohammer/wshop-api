<?php

namespace App\Infrastructure\Security;

use App\Shared\Exception\UnauthorizedException;

class JwtService
{
    public function __construct(
        private string $secret,
        private int    $ttlSeconds = 3600
    )
    {
    }

    public function generateToken(int $userId, string $email): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'sub' => $userId,
            'email' => $email,
            'exp' => time() + $this->ttlSeconds,
        ];

        $headerEncoded = $this->base64UrlEncode(json_encode($header, JSON_THROW_ON_ERROR));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));

        $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $this->secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    public function validateToken(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new UnauthorizedException('Invalid token.');
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        $expectedSignature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            $this->secret,
            true
        );

        $actualSignature = $this->base64UrlDecode($signatureEncoded);

        if (!hash_equals($expectedSignature, $actualSignature)) {
            throw new UnauthorizedException('Invalid token signature.');
        }

        $payloadJson = $this->base64UrlDecode($payloadEncoded);
        $payload = json_decode($payloadJson, true);

        if (!is_array($payload)) {
            throw new UnauthorizedException('Invalid token payload.');
        }

        if (!isset($payload['exp']) || time() >= (int)$payload['exp']) {
            throw new UnauthorizedException('Token expired.');
        }

        return $payload;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;

        if ($remainder > 0) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }
}