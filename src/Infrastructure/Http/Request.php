<?php

namespace App\Infrastructure\Http;

use App\Shared\Exception\HttpException;

class Request
{
    public function __construct(
        private string $method,
        private string $path,
        private array  $queryParams,
        private array  $headers,
        private array  $body
    )
    {
    }

    public static function fromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $rawUri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($rawUri, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        $path = preg_replace('#/+#', '/', $path) ?? '/';
        $path = rtrim($path, '/');
        $path = $path === '' ? '/' : $path;

        $queryParams = $_GET;
        $headers = function_exists('getallheaders') ? getallheaders() : [];

        $rawBody = file_get_contents('php://input');
        $body = [];

        if ($rawBody !== false && $rawBody !== '') {
            $decoded = json_decode($rawBody, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new HttpException('Invalid JSON', 400);
            }

            $body = is_array($decoded) ? $decoded : [];
        }

        return new self($method, $path, $queryParams, $headers, $body);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getHeader(string $name): ?string
    {
        foreach ($this->headers as $headerName => $value) {
            if (strcasecmp($headerName, $name) === 0) {
                return $value;
            }
        }

        return null;
    }
}