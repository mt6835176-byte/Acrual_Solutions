<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

/**
 * ProductController
 *
 * Handles all API endpoints for product CRUD operations.
 * Returns JSON responses only. Delegates all business logic to ProductService
 * and formats output via ProductResource.
 */
class ProductController extends Controller
{
    /**
     * ProductController constructor.
     *
     * @param ProductService $service
     */
    public function __construct(private ProductService $service) {}

    /**
     * Store a newly created product.
     *
     * POST /api/products
     *
     * @param  StoreProductRequest  $request  Validated request containing product data.
     * @return JsonResponse  201 on success, 500 on unexpected error.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->service->create($request->validated());

            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully',
                'data'    => new ProductResource($product),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'An internal server error occurred',
            ], 500);
        }
    }

    /**
     * Display the specified product.
     *
     * GET /api/products/{id}
     *
     * @param  string  $id  UUID of the product to retrieve.
     * @return JsonResponse  200 on success, 404 if not found, 500 on unexpected error.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $product = $this->service->findById($id);

            if ($product === null) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Product not found',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data'   => new ProductResource($product),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'An internal server error occurred',
            ], 500);
        }
    }

    /**
     * Update the specified product.
     *
     * PUT /api/products/{id}
     *
     * @param  UpdateProductRequest  $request  Validated request containing fields to update.
     * @param  string                $id       UUID of the product to update.
     * @return JsonResponse  200 on success, 404 if not found, 500 on unexpected error.
     */
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        try {
            $product = $this->service->update($id, $request->validated());

            if ($product === null) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Product not found',
                ], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Product updated successfully',
                'data'    => new ProductResource($product),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'An internal server error occurred',
            ], 500);
        }
    }

    /**
     * Remove the specified product.
     *
     * DELETE /api/products/{id}
     *
     * @param  string  $id  UUID of the product to delete.
     * @return JsonResponse  200 on success, 404 if not found, 500 on unexpected error.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->service->delete($id);

            if ($deleted === false) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Product not found',
                ], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'An internal server error occurred',
            ], 500);
        }
    }
}
