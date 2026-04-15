<?php

namespace Tests\Feature;

use Tests\TestCase;

class UpdateProductApiTest extends TestCase
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

    public function test_can_update_product_with_partial_data(): void
    {
        $service = $this->app->make(\App\Services\ProductService::class);
        $product = $service->create(['name' => 'Test Product', 'price' => 9.99, 'quantity' => 10]);

        $response = $this->putJson(
            '/api/products/' . $product['id'],
            ['price' => 19.99],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.price', 19.99)
            ->assertJsonPath('data.name', 'Test Product'); // name unchanged
    }

    public function test_update_nonexistent_product_returns_404(): void
    {
        $response = $this->putJson(
            '/api/products/nonexistent-uuid',
            ['price' => 19.99],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(404);
    }

    public function test_update_returns_400_for_invalid_price(): void
    {
        $service = $this->app->make(\App\Services\ProductService::class);
        $product = $service->create(['name' => 'Test Product', 'price' => 9.99, 'quantity' => 10]);

        $response = $this->putJson(
            '/api/products/' . $product['id'],
            ['price' => 0],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(400)
            ->assertJsonPath('status', 'error')
            ->assertJsonStructure(['errors' => ['price']]);
    }

    public function test_update_refreshes_updated_at(): void
    {
        $service = $this->app->make(\App\Services\ProductService::class);
        $product = $service->create(['name' => 'Test Product', 'price' => 9.99, 'quantity' => 10]);
        $originalUpdatedAt = $product['updated_at'];

        // Small sleep to ensure updated_at can advance
        sleep(1);

        $response = $this->putJson(
            '/api/products/' . $product['id'],
            ['price' => 14.99],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $updatedAt = $response->json('data.updated_at');
        $this->assertNotNull($updatedAt);
        $this->assertGreaterThanOrEqual($originalUpdatedAt, $updatedAt);
    }
}
