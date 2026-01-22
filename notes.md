# REST API Learning Notes

## What is REST API?

**REST** (Representational State Transfer) is an architectural style for building web services that use HTTP methods to perform operations on resources.

---

## HTTP Methods (CRUD Operations)

| Method | Operation | Purpose | Example |
|--------|-----------|---------|---------|
| GET | Read | Retrieve data | Get all products |
| POST | Create | Add new data | Create a product |
| PUT/PATCH | Update | Modify existing data | Update product price |
| DELETE | Delete | Remove data | Delete a product |

---

## Project Structure

```
app/
├── Http/
│   └── Controllers/
│       └── ProductController.php    # Handles HTTP requests
└── Models/
    └── Product.php                  # Represents database table

routes/
└── api.php                          # Defines API endpoints

database/
└── migrations/
    └── xxxx_create_products_table.php
```

---

## Key Components

### 1. Routes (api.php)
Defines the API endpoints and maps them to controller methods.

```php
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);      // List all
    Route::post('/', [ProductController::class, 'store']);     // Create
    Route::get('/{id}', [ProductController::class, 'show']);   // Get one
    Route::put('/{id}', [ProductController::class, 'update']); // Update
    Route::delete('/{id}', [ProductController::class, 'destroy']); // Delete
});
```

### 2. Model (Product.php)
Represents the database table and handles data operations.

```php
class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'quantity'];
}
```

**Purpose:**
- Communicates with database
- `$fillable` = columns allowed for mass assignment (security)
- Provides methods: `all()`, `find()`, `create()`, `update()`, `delete()`

### 3. Controller (ProductController.php)
Handles business logic and HTTP responses.

**Methods:**
- `index()` - Get all products
- `store()` - Create new product
- `show($id)` - Get single product
- `update($id)` - Update product
- `destroy($id)` - Delete product

---

## API Endpoints

Base URL: `http://localhost:8000/api`

### GET /products
**Purpose:** Get all products
```
Request: GET /api/products
Response: 200 OK
[
    {
        "id": 1,
        "name": "Laptop",
        "description": "Gaming laptop",
        "price": "999.99",
        "quantity": 10
    }
]
```

### POST /products
**Purpose:** Create new product
```
Request: POST /api/products
Body (JSON):
{
    "name": "Laptop",
    "description": "Gaming laptop",
    "price": 999.99,
    "quantity": 10
}

Response: 201 Created
{
    "id": 1,
    "name": "Laptop",
    "description": "Gaming laptop",
    "price": "999.99",
    "quantity": 10,
    "created_at": "2026-01-22T13:00:00.000000Z",
    "updated_at": "2026-01-22T13:00:00.000000Z"
}
```

### GET /products/{id}
**Purpose:** Get single product
```
Request: GET /api/products/1
Response: 200 OK
{
    "id": 1,
    "name": "Laptop",
    "price": "999.99"
}

Response (if not found): 404 Not Found
{
    "message": "Product not found"
}
```

### PUT /products/{id}
**Purpose:** Update product
```
Request: PUT /api/products/1
Body (JSON):
{
    "price": 899.99,
    "quantity": 8
}

Response: 200 OK
{
    "id": 1,
    "name": "Laptop",
    "price": "899.99",
    "quantity": 8
}
```

### DELETE /products/{id}
**Purpose:** Delete product
```
Request: DELETE /api/products/1
Response: 200 OK
{
    "message": "Product deleted successfully"
}
```

---

## HTTP Status Codes

| Code | Meaning | When Used |
|------|---------|-----------|
| 200 | OK | Successful GET, PUT, DELETE |
| 201 | Created | Successful POST |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Server error |

---

## Validation Rules

```php
$request->validate([
    'name' => 'required|string|max:255',      // Required, string, max 255 chars
    'description' => 'nullable|string',        // Optional
    'price' => 'required|numeric|min:0',       // Required, number, positive
    'quantity' => 'required|integer|min:0'     // Required, integer, positive
]);
```

**Common Rules:**
- `required` - Field must be present
- `nullable` - Field is optional
- `string` - Must be text
- `numeric` - Must be number
- `integer` - Must be whole number
- `min:0` - Minimum value
- `max:255` - Maximum value
- `sometimes` - Validate only if present (for updates)

---

## Testing with Thunder Client / Postman

### Setup
1. Install Thunder Client extension in VS Code
2. Create new request
3. Set method (GET, POST, PUT, DELETE)
4. Enter URL: `http://localhost:8000/api/products`

### Headers (for POST/PUT)
```
Content-Type: application/json
Accept: application/json
```

### Body (for POST/PUT)
Select "JSON" and enter data:
```json
{
    "name": "Product Name",
    "description": "Description here",
    "price": 99.99,
    "quantity": 5
}
```

---

## Common Commands

```bash
# Create model with migration
php artisan make:model Product -m

# Create controller
php artisan make:controller ProductController --api

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Start development server
php artisan serve

# View routes
php artisan route:list
```

---

## Model vs Controller

### Model (Product.php)
**Role:** Data Layer
- Represents database table
- Handles database operations
- No HTTP logic

```php
Product::all();           // SELECT * FROM products
Product::find(1);         // SELECT * FROM products WHERE id = 1
Product::create($data);   // INSERT INTO products
```

### Controller (ProductController.php)
**Role:** Logic Layer
- Receives HTTP requests
- Validates input
- Uses Model to access data
- Returns JSON responses

```php
public function store(Request $request) {
    $validated = $request->validate([...]);   // Validate
    $product = Product::create($validated);    // Use Model
    return response()->json($product, 201);    // Return response
}
```

---

## REST API Principles

1. **Stateless** - Each request is independent
2. **Resource-based** - URLs represent resources (`/products`, not `/getProducts`)
3. **HTTP Methods** - Use proper verbs (GET, POST, PUT, DELETE)
4. **JSON Format** - Data exchanged in JSON
5. **Status Codes** - Proper HTTP codes for responses

---

## Next Steps (Considerations)

- [ ] Add authentication (Sanctum)
- [ ] Implement pagination
- [ ] Add search/filtering
- [ ] Create relationships (categories, orders)
- [ ] Add API documentation (Swagger)
- [ ] Write unit tests
- [ ] Deploy to production

---

## Useful Resources

- [Laravel Documentation](https://laravel.com/docs)
- [REST API Tutorial](https://restfulapi.net)
- [HTTP Status Codes](https://httpstatuses.com)
- [Postman Learning Center](https://learning.postman.com)

