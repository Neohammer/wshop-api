<?php

namespace App\Infrastructure\Http;

class JsonResponse
{
    public function __construct(
        private mixed $data,
        private int $status = 200
    ) {}

    public static function success(mixed $data = null, int $status = 200): self
    {
        return new self(['data' => $data], $status);
    }

    public static function error(string $message, int $status, array $details = []): self
    {
        return new self([
            'error' => $message,
            'details' => $details
        ], $status);
    }

    public function send(): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json');

        echo json_encode(
            $this->data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}