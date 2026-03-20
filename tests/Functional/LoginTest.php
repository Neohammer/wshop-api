<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private HttpClient $client;
    private string $baseUrl = 'http://localhost:8000';

    protected function setUp(): void
    {
        $this->client = new HttpClient();
    }

    public function testLoginReturnsTokenWithValidCredentials(): void
    {
        $response = $this->client->request('POST', $this->baseUrl . '/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->assertSame(200, $response['status']);
        $this->assertIsArray($response['body']);
        $this->assertArrayHasKey('data', $response['body']);
        $this->assertArrayHasKey('token', $response['body']['data']);
        $this->assertNotEmpty($response['body']['data']['token']);
    }

    public function testLoginFailsWithInvalidCredentials(): void
    {
        $response = $this->client->request('POST', $this->baseUrl . '/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertSame(401, $response['status']);
        $this->assertSame('Invalid credentials', $response['body']['error']);
    }
}