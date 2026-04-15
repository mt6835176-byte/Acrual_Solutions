<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateProductApiTest extends TestCase
{
    private string $testStoragePath;

    protected function setUp(): void
    {
        parent::setUp();
        // Point ProductRepository to a temp file for this test
        $this->testStoragePath = storage_path('products_test_' . uniqid() . '.json');
        // Bind a custom ProductRepository that uses the temp path
        $this->app->bind(\App\Repositories\ProductRepository::class, function () {
            $repo = new \App\Repositories\ProductRepository();
            // Use reflection to set the private storagePath
            $reflection = new \ReflectionClass($repo);
            $prop = $reflection->getProperty('storagePath');
            $prop->setAccessible(true);
            $prop->setValue($repo, $this->testStoragePath);
            return $repo;
        });
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testStoragePath)) {
            unlink($this->testStoragePath);
        }
        parent::tearDown();
    }

    public function test_can_create_product_with_valid_data(): void
    {
        $payload = [
            'name'     => 'Test Widget',
            'price'    => 19.99,
            'quantity' => 5,
        ];

        $response = $this->postJson('/api/products', $payload, ['Accept' => 'application/json']);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'price',
                    'quantity',
                ],
            ])
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name', 'Test Widget')
            ->assertJsonPath('data.price', 19.99)
            ->assertJsonPath('data.quantity', 5);
    }

    public function test_create_product_returns_400_when_name_missing(): void
    {
        $payload = [
            'price'    => 9.99,
            'quantity' => 10,
        ];

        $response = $this->postJson('/api/products', $payload, ['Accept' => 'application/json']);

        $response->assertStatus(400)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['errors' => ['name']]);
    }

    public function test_create_product_returns_400_when_price_is_zero(): void
    {
        $payload = [
            'name'     => 'Zero Price Product',
            'price'    => 0,
            'quantity' => 10,
        ];

        $response = $this->postJson('/api/products', $payload, ['Accept' => 'application/json']);

        $response->assertStatus(400)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['errors' => ['price']]);
    }

    public function test_create_product_returns_400_when_quantity_is_negative(): void
    {
        $payload = [
            'name'     => 'Negative Quantity Product',
            'price'    => 9.99,
            'quantity' => -1,
        ];

        $response = $this->postJson('/api/products', $payload, ['Accept' => 'application/json']);

        $response->assertStatus(400)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['errors' => ['quantity']]);
    }

    public function test_create_product_with_optional_fields(): void
    {
        $payload = [
            'name'        => 'Full Product',
            'price'       => 29.99,
            'quantity'    => 100,
            'description' => 'A detailed description',
            'image_url'   => 'https://example.com/image.jpg',
        ];

        $response = $this->postJson('/api/products', $payload, ['Accept' => 'application/json']);

        $response->assertStatus(201)
            ->assertJsonPath('data.description', 'A detailed description')
            ->assertJsonPath('data.image_url', 'https://example.com/image.jpg');
    }
}
