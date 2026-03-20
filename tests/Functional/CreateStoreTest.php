<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;

class CreateStoreTest extends TestCase
{
    private HttpClient $client;
    private string $baseUrl = 'http://localhost:8000';

    protected function setUp(): void
    {
        $this->client = new HttpClient();
    }

    private function loginAndGetToken(): string
    {
        $login = $this->client->request('POST', $this->baseUrl . '/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        return $login['body']['data']['token'];
    }

    public function testCreateStoreReturns201(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->client->request(
            'POST',
            $this->baseUrl . '/stores',
            [
                'name' => 'Lille Centre',
                'manager_name' => 'Paul Durand',
                'phone' => '0506070809',
                'street' => '8 rue Faidherbe',
                'postal_code' => '59000',
                'city' => 'Lille',
            ],
            ['Authorization: Bearer ' . $token]
        );

        $this->assertSame(201, $response['status']);
        $this->assertSame('Lille Centre', $response['body']['data']['name']);
    }

    public function testCreateStoreReturns422WhenPayloadIsInvalid(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->client->request(
            'POST',
            $this->baseUrl . '/stores',
            [
                'name' => '',
                'manager_name' => '',
                'phone' => '',
                'street' => '',
                'postal_code' => '12',
                'city' => '',
            ],
            ['Authorization: Bearer ' . $token]
        );

        $this->assertSame(422, $response['status']);
        $this->assertSame('Validation failed', $response['body']['error']);
        $this->assertArrayHasKey('details', $response['body']);
    }
}