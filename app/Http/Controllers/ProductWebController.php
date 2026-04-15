<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * ProductWebController
 *
 * Handles browser-facing web routes for the product inventory UI.
 * Returns Blade views and redirects (not JSON). Delegates all business
 * logic to ProductService, which is shared with the API controller.
 */
class ProductWebController extends Controller
{
    /**
     * ProductWebController constructor.
     *
     * @param ProductService $service
     */
    public function __construct(private ProductService $service) {}

    /**
     * Display a listing of all products.
     *
     * GET /products
     *
     * @return View
     */
    public function index(): View
    {
        $products = $this->service->getAll();

        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new product.
     *
     * GET /products/create
     *
     * @return View
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created product submitted via the web form.
     *
     * POST /products
     *
     * On success, redirects to the product listing with a success flash message.
     * On unexpected error, redirects back with the submitted input and an error flash.
     * Validation failures are handled automatically by StoreProductRequest (redirect
     * back with $errors and old() input).
     *
     * @param  StoreProductRequest  $request
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create product. Please try again.');
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }
}
