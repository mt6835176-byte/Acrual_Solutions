<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProductWebTest extends TestCase
{
    private string $testStoragePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testStoragePath = storage_path('products_test_' . uniqid() . '.json');
        $this->app->bind(\App\Repositories\ProductRepository::class, function () {
            $repo = new \App\Repositories\ProductRepository();
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

    public function test_products_index_page_returns_200(): void
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
    }

    public function test_products_create_page_returns_200(): void
    {
        $response = $this->get('/products/create');

        $response->assertStatus(200);
    }

    public function test_can_create_product_via_web_form(): void
    {
        $payload = [
            'name'     => 'Test Widget',
            'price'    => 19.99,
            'quantity' => 5,
        ];

        $response = $this->post('/products', $payload);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');
    }

    public function test_web_form_shows_validation_errors_for_invalid_data(): void
    {
        $payload = [
            'price'    => 9.99,
            'quantity' => 10,
            // 'name' intentionally omitted
        ];

        $response = $this->post('/products', $payload);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name']);
    }

    public function test_web_form_preserves_old_input_on_validation_failure(): void
    {
        $payload = [
            'price'    => 9.99,
            'quantity' => 10,
            // 'name' intentionally omitted to trigger validation failure
        ];

        $response = $this->post('/products', $payload);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name']);
        $response->assertSessionHasInput('price', 9.99);
        $response->assertSessionHasInput('quantity', 10);
    }
}
