<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Support\Str;

class ProductService
{
    /**
     * ProductService constructor.
     *
     * @param ProductRepository $repository
     */
    public function __construct(private ProductRepository $repository) {}

    /**
     * Return all products.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Find a product by ID. Returns null if not found.
     *
     * @param string $id  UUID v4 of the product to find.
     * @return array<string, mixed>|null  The product array, or null if not found.
     */
    public function findById(string $id): ?array
    {
        return $this->repository->findById($id);
    }

    /**
     * Create a new product. Assigns UUID v4, created_at, and updated_at.
     *
     * @param array<string, mixed> $data  Validated input containing at minimum: name, price, quantity.
     *                                    Optional fields: description, image_url.
     * @return array<string, mixed>       The created product array.
     */
    public function create(array $data): array
    {
        $now = now()->toIso8601String();

        $product = [
            'id'          => Str::uuid()->toString(),
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'quantity'    => $data['quantity'],
            'image_url'   => $data['image_url'] ?? null,
            'created_at'  => $now,
            'updated_at'  => $now,
        ];

        $products = $this->repository->findAll();
        $products[] = $product;
        $this->repository->save($products);

        return $product;
    }

    /**
     * Update an existing product. Returns null if not found.
     *
     * Only the keys present in $data are overwritten; id and created_at are
     * never modified. updated_at is always refreshed on a successful update.
     *
     * @param string               $id    UUID v4 of the product to update.
     * @param array<string, mixed> $data  Validated partial input.
     * @return array<string, mixed>|null  The updated product array, or null if not found.
     */
    public function update(string $id, array $data): ?array
    {
        $products = $this->repository->findAll();

        $index = null;
        foreach ($products as $i => $product) {
            if (isset($product['id']) && $product['id'] === $id) {
                $index = $i;
                break;
            }
        }

        if ($index === null) {
            return null;
        }

        // Merge only the provided keys, protecting id and created_at.
        $updatable = array_diff_key($data, array_flip(['id', 'created_at']));
        $products[$index] = array_merge($products[$index], $updatable);
        $products[$index]['updated_at'] = now()->toIso8601String();

        $this->repository->save($products);

        return $products[$index];
    }

    /**
     * Delete a product by ID.
     *
     * @param string $id  UUID v4 of the product to delete.
     * @return bool  True if the product was deleted, false if it was not found.
     */
    public function delete(string $id): bool
    {
        $products = $this->repository->findAll();
        $originalCount = count($products);

        $filtered = array_filter($products, fn($product) => $product['id'] !== $id);

        if (count($filtered) === $originalCount) {
            return false;
        }

        $this->repository->save(array_values($filtered));

        return true;
    }
}
