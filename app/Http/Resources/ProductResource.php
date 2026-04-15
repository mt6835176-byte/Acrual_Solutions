<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ProductResource
 *
 * Transforms a plain PHP array representing a product into a JSON-serializable
 * structure for API responses. Because this resource wraps a plain array (not
 * an Eloquent model), all field access uses $this->resource['key'] syntax
 * rather than the magic-property shorthand $this->key.
 *
 * Optional fields (description, image_url) default to null when absent from
 * the underlying array.
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->resource['id'],
            'name'        => $this->resource['name'],
            'description' => $this->resource['description'] ?? null,
            'price'       => (float) $this->resource['price'],
            'quantity'    => (int) $this->resource['quantity'],
            'image_url'   => $this->resource['image_url'] ?? null,
            'created_at'  => $this->resource['created_at'],
            'updated_at'  => $this->resource['updated_at'],
        ];
    }
}
