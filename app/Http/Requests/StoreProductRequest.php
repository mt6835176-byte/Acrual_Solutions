<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for validating product creation input.
 *
 * Validates all fields required to create a new product record,
 * returning human-readable error messages on failure.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'gt:0'],
            'quantity'    => ['required', 'integer', 'min:0'],
            'image_url'   => ['nullable', 'url', 'max:2048'],
        ];
    }

    /**
     * Get custom human-readable error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'The product name is required.',
            'name.string'        => 'The product name must be a text value.',
            'name.max'           => 'The product name may not be longer than 255 characters.',

            'description.string' => 'The description must be a text value.',

            'price.required'     => 'The product price is required.',
            'price.numeric'      => 'The product price must be a number.',
            'price.gt'           => 'The product price must be greater than 0.',

            'quantity.required'  => 'The product quantity is required.',
            'quantity.integer'   => 'The product quantity must be a whole number.',
            'quantity.min'       => 'The product quantity must be at least 0.',

            'image_url.url'      => 'The image URL must be a valid URL (e.g. https://example.com/image.jpg).',
            'image_url.max'      => 'The image URL may not be longer than 2048 characters.',
        ];
    }
}
