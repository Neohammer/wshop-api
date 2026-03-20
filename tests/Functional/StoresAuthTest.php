<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;

class StoresAuthTest extends TestCase
{
    private HttpClient $client;
    private string $baseUrl = 'http://localhost:8000';

    protected function setUp(): void
    {
        $this->client = new HttpClient();
    }

    public function testStoresRequiresAuthentication(): void
    {
        $response = $this->client->request('GET', $this->baseUrl . '/stores');

        $this->assertSame(401, $response['status']);
    }

    public function testStoresReturnsDataWithValidToken(): void
    {
        $login = $this->client->request('POST', $this->baseUrl . '/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $token = $login['body']['data']['token'];

        $response = $this->client->request(
            'GET',
            $this->baseUrl . '/stores',
            null,
            ['Authorization: Bearer ' . $token]
        );

        $this->assertSame(200, $response['status']);
        $this->assertArrayHasKey('data', $response['body']);
        $this->assertIsArray($response['body']['data']);
    }
}