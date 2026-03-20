<?php

namespace Tests\Functional;

class HttpClient
{
    public function request(string $method, string $url, ?array $body = null, array $headers = []): array
    {
        $defaultHeaders = [
            'Content-Type: application/json',
        ];

        $allHeaders = array_merge($defaultHeaders, $headers);

        $context = stream_context_create([
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $allHeaders),
                'content' => $body !== null ? json_encode($body) : '',
                'ignore_errors' => true,
            ],
        ]);

        $responseBody = file_get_contents($url, false, $context);
        $responseHeaders = $http_response_header ?? [];

        preg_match('#HTTP/\S+\s+(\d{3})#', $responseHeaders[0] ?? '', $matches);
        $statusCode = isset($matches[1]) ? (int) $matches[1] : 0;

        return [
            'status' => $statusCode,
            'body' => $responseBody !== false ? json_decode($responseBody, true) : null,
            'raw_body' => $responseBody,
            'headers' => $responseHeaders,
        ];
    }
}