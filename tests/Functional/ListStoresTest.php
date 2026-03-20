<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;

class ListStoresTest extends TestCase
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

    public function testFilterStoresByCity(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->client->request(
            'GET',
            $this->baseUrl . '/stores?city=Paris',
            null,
            ['Authorization: Bearer ' . $token]
        );

        $this->assertSame(200, $response['status']);
        $this->assertCount(1, $response['body']['data']);
        $this->assertSame('Paris', $response['body']['data'][0]['city']);
    }

    public function testSortStoresByNameDesc(): void
    {
        $token = $this->loginAndGetToken();

        $response = $this->client->request(
            'GET',
            $this->baseUrl . '/stores?sort=name&order=DESC',
            null,
            ['Authorization: Bearer ' . $token]
        );

        $this->assertSame(200, $response['status']);
        $this->assertGreaterThanOrEqual(2, count($response['body']['data']));

        $first = $response['body']['data'][0]['name'];
        $second = $response['body']['data'][1]['name'];

        $this->assertGreaterThanOrEqual(0, strcmp($first, $second));
    }
}