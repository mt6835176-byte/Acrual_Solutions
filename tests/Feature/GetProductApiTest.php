<?php

namespace Tests\Feature;

use Tests\TestCase;

class GetProductApiTest extends TestCase
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

    public function test_can_get_existing_product(): void
    {
        $service = $this->app->make(\App\Services\ProductService::class);
        $product = $service->create(['name' => 'Test Product', 'price' => 9.99, 'quantity' => 10]);

        $response = $this->getJson('/api/products/' . $product['id'], ['Accept' => 'application/json']);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.id', $product['id'])
            ->assertJsonPath('data.name', 'Test Product')
            ->assertJsonPath('data.price', 9.99)
            ->assertJsonPath('data.quantity', 10);
    }

    public function test_get_nonexistent_product_returns_404(): void
    {
        $response = $this->getJson('/api/products/nonexistent-uuid', ['Accept' => 'application/json']);

        $response->assertStatus(404)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Product not found');
    }
}
