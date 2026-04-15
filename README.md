# E-Commerce Product Inventory System

A production-structured Laravel REST API and web UI for managing a product inventory. Built to demonstrate clean architecture, proper separation of concerns, and real-world Laravel best practices — suitable for portfolio and job interview evaluation.

---

## Project Overview

This system provides:

- **REST API** — Full CRUD endpoints for products (`POST`, `GET`, `PUT`, `DELETE`)
- **Web UI** — Bootstrap-styled Blade pages for browsing and creating products
- **JSON file storage** — No database required; all data persisted to `storage/products.json`
- **Clean architecture** — Controller → Service → Repository layers with Form Request validation and API Resources

### Architecture

```
HTTP Request
    │
    ├── API Routes (routes/api.php)
    │       └── ProductController          ← JSON responses
    │
    └── Web Routes (routes/web.php)
            └── ProductWebController       ← Blade views / redirects
                        │
                        └── ProductService (shared business logic)
                                    │
                                    └── ProductRepository (file I/O)
                                                │
                                                └── storage/products.json
```

### Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ProductController.php       # API controller (JSON)
│   │   └── ProductWebController.php    # Web controller (Blade)
│   ├── Requests/
│   │   ├── StoreProductRequest.php     # Validation for create
│   │   └── UpdateProductRequest.php    # Validation for update
│   └── Resources/
│       └── ProductResource.php         # API response formatter
├── Services/
│   └── ProductService.php              # Business logic
└── Repositories/
    └── ProductRepository.php           # JSON file I/O with locking

routes/
├── api.php                             # /api/products routes
└── web.php                             # /products web routes

resources/views/
├── layouts/app.blade.php               # Shared Bootstrap layout
└── products/
    ├── index.blade.php                 # Product listing page
    └── create.blade.php                # Create product form

storage/
└── products.json                       # JSON data store (auto-created)

postman/
└── ProductInventoryAPI.postman_collection.json
```

---

## Setup

### Requirements

- PHP 8.3+
- Composer
- Node.js (optional, only needed if you modify frontend assets)

### Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment file
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Start the development server
php artisan serve
```

The application will be available at **http://localhost:8000**.

> **No database configuration required.** All product data is stored in `storage/products.json`, which is created automatically on the first write.

---

## Web UI

| URL | Description |
|-----|-------------|
| `http://localhost:8000/products` | Product listing — browse all products in a card grid |
| `http://localhost:8000/products/create` | Create product form — add a new product via browser |

---

## REST API

All API endpoints are prefixed with `/api` and return `Content-Type: application/json`.

### Response Envelope

**Success:**
```json
{
    "status": "success",
    "message": "...",
    "data": { }
}
```

**Error:**
```json
{
    "status": "error",
    "message": "..."
}
```

---

### POST /api/products — Create Product

Creates a new product and returns it with HTTP 201.

**Request:**
```http
POST /api/products
Content-Type: application/json
Accept: application/json

{
    "name": "Wireless Keyboard",
    "description": "Compact Bluetooth keyboard with backlight",
    "price": 49.99,
    "quantity": 150,
    "image_url": "https://example.com/images/keyboard.jpg"
}
```

| Field | Type | Required | Rules |
|-------|------|----------|-------|
| `name` | string | Yes | max 255 characters |
| `price` | number | Yes | greater than 0 |
| `quantity` | integer | Yes | >= 0 |
| `description` | string | No | — |
| `image_url` | string | No | valid URL, max 2048 chars |

**Response — 201 Created:**
```json
{
    "status": "success",
    "message": "Product created successfully",
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "name": "Wireless Keyboard",
        "description": "Compact Bluetooth keyboard with backlight",
        "price": 49.99,
        "quantity": 150,
        "image_url": "https://example.com/images/keyboard.jpg",
        "created_at": "2025-01-15T10:30:00+00:00",
        "updated_at": "2025-01-15T10:30:00+00:00"
    }
}
```

**Response — 422 Validation Error:**
```json
{
    "message": "The product name is required.",
    "errors": {
        "name": ["The product name is required."],
        "price": ["The product price must be greater than 0."]
    }
}
```

---

### GET /api/products/{id} — Get Product

Retrieves a single product by its UUID.

**Request:**
```http
GET /api/products/550e8400-e29b-41d4-a716-446655440000
Accept: application/json
```

**Response — 200 OK:**
```json
{
    "status": "success",
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "name": "Wireless Keyboard",
        "description": "Compact Bluetooth keyboard with backlight",
        "price": 49.99,
        "quantity": 150,
        "image_url": "https://example.com/images/keyboard.jpg",
        "created_at": "2025-01-15T10:30:00+00:00",
        "updated_at": "2025-01-15T10:30:00+00:00"
    }
}
```

**Response — 404 Not Found:**
```json
{
    "status": "error",
    "message": "Product not found"
}
```

---

### PUT /api/products/{id} — Update Product

Partially updates a product. Only the fields you include are changed.

**Request:**
```http
PUT /api/products/550e8400-e29b-41d4-a716-446655440000
Content-Type: application/json
Accept: application/json

{
    "price": 39.99,
    "quantity": 200
}
```

**Response — 200 OK:**
```json
{
    "status": "success",
    "message": "Product updated successfully",
    "data": {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "name": "Wireless Keyboard",
        "description": "Compact Bluetooth keyboard with backlight",
        "price": 39.99,
        "quantity": 200,
        "image_url": "https://example.com/images/keyboard.jpg",
        "created_at": "2025-01-15T10:30:00+00:00",
        "updated_at": "2025-01-15T11:00:00+00:00"
    }
}
```

---

### DELETE /api/products/{id} — Delete Product

Permanently removes a product from the inventory.

**Request:**
```http
DELETE /api/products/550e8400-e29b-41d4-a716-446655440000
Accept: application/json
```

**Response — 200 OK:**
```json
{
    "status": "success",
    "message": "Product deleted successfully"
}
```

**Response — 404 Not Found:**
```json
{
    "status": "error",
    "message": "Product not found"
}
```

---

## Data Storage

All product data is stored in `storage/products.json` as a JSON array:

```json
[
    {
        "id": "550e8400-e29b-41d4-a716-446655440000",
        "name": "Wireless Keyboard",
        "description": "Compact Bluetooth keyboard with backlight",
        "price": 49.99,
        "quantity": 150,
        "image_url": "https://example.com/images/keyboard.jpg",
        "created_at": "2025-01-15T10:30:00+00:00",
        "updated_at": "2025-01-15T10:30:00+00:00"
    }
]
```

- The file is created automatically on the first product creation
- File locking (`flock`) is used to prevent data corruption under concurrent requests
- No database, migrations, or `.env` database configuration is required

---

## Testing

Run the full test suite:

```bash
php artisan test
```

Run only API tests:

```bash
php artisan test --filter=ProductApi
```

Run only web tests:

```bash
php artisan test --filter=ProductWebTest
```

The test suite includes:
- **Feature tests** — Full HTTP stack tests for all 4 API endpoints and the web UI
- **Unit tests** — Isolated tests for individual components

All tests use a temporary `products.json` file and clean up after themselves, so they never affect your development data.

---

## Postman Collection

Import `postman/ProductInventoryAPI.postman_collection.json` into Postman to get pre-built requests for all 4 API endpoints with example bodies and expected responses.

Set the `base_url` collection variable to your server address (default: `http://localhost:8000`).

---

## HTTP Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success (GET, PUT, DELETE) |
| 201 | Created (POST) |
| 404 | Product not found |
| 422 | Validation error |
| 500 | Internal server error |
