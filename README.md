# API Documentation

## Authentication

### Register
- **Endpoint:** `POST /api/register`
- **Body (JSON):**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "admin/user"
}
```
- **Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "token": "<token>",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    ...
  }
}
```

### Login
- **Endpoint:** `POST /api/login`
- **Body (JSON):**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```
- **Response (200):**
```json
{
  "success": true,
  "message": "User logged in successfully",
  "token": "<token>",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    ...
  }
}
```

### Logout
- **Endpoint:** `POST /api/logout`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Response (200):**
```json
{
  "success": true,
  "message": "User logged out successfully"
}
```

---

## Products

> **Note:** All endpoints below require authentication (`Authorization: Bearer <token>`). Only users with role `admin` can create, update, or delete products. All authenticated users can view products.

### Get All Products
- **Endpoint:** `GET /api/products`
- **Response (200):**
```json
[
  {
    "id": 1,
    "user_id": 1,
    "product_name": "Baju Keren",
    "product_desc": "Baju Keren dari distro terkemuka",
    "product_price": "150000.00",
    "image": "images/1752242295.tBTu9KTTTk.png",
    "created_at": "2025-07-11T13:58:15.000000Z",
    "updated_at": "2025-07-11T13:58:15.000000Z"
  },
  ...
]
```

### Get Product by ID
- **Endpoint:** `GET /api/products/{id}`
- **Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user_id": 1,
    "product_name": "Baju Keren",
    "product_desc": "Baju Keren dari distro terkemuka",
    "product_price": "150000.00",
    "image": "images/1752242295.tBTu9KTTTk.png",
    "created_at": "2025-07-11T13:58:15.000000Z",
    "updated_at": "2025-07-11T13:58:15.000000Z"
  }
}
```
- **Response (404):**
```json
{
  "error": "Product not found"
}
```

### Create Product (Admin Only)
- **Endpoint:** `POST /api/products`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Body (form-data):**
  - `product_name`: string (required)
  - `product_desc`: string (required)
  - `product_price`: number (required)
  - `image`: file (optional, .jpg/.png, max 5MB)
- **Response (201):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 1,
    "product_name": "Baju Bandung",
    "product_desc": "Baju distro Bandung",
    "product_price": "200000.00",
    "image": "images/1752242295.tBTu9KTTTk.png",
    "created_at": "2025-07-11T14:03:55.000000Z",
    "updated_at": "2025-07-11T14:03:55.000000Z"
  },
  "message": "Product created successfully"
}
```
- **Response (422):**
```json
{
  "success": false,
  "error": "Validation failed",
  "messages": {
    "product_name": ["The product name field is required."],
    ...
  }
}
```

### Update Product (Admin Only)
- **Endpoint:** `PUT /api/products/{id}` or `PATCH /api/products/{id}`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Body (form-data):**
  - Any of: `product_name`, `product_desc`, `product_price`, `image` (file, optional)
- **Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "user_id": 1,
    "product_name": "Baju Bandung",
    "product_desc": "Baju distro Bandung update",
    "product_price": "250000.00",
    "image": "images/1752242295.tBTu9KTTTk.png",
    "created_at": "2025-07-11T14:03:55.000000Z",
    "updated_at": "2025-07-11T14:10:00.000000Z"
  },
  "message": "Product updated successfully"
}
```
- **Response (404):**
```json
{
  "error": "Product not found"
}
```

### Delete Product (Admin Only)
- **Endpoint:** `DELETE /api/products/{id}`
- **Headers:**
  - `Authorization: Bearer <token>`
- **Response (200):**
```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```
- **Response (204):**
```json
null
```
- **Response (404):**
```json
{
  "error": "Product not found"
}
```

---

## Error Responses
- **401 Unauthorized:**
  ```json
  { "message": "Unauthenticated." }
  ```
- **403 Forbidden:**
  ```json
  { "error": "Forbidden" }
  ```
- **422 Validation Error:**
  ```json
  {
    "success": false,
    "error": "Validation failed",
    "messages": { "field": ["error message"] }
  }
  ```
- **404 Not Found:**
  ```json
  { "error": "Product not found" }
  ```

---

## Notes
- All endpoints (except register & login) require `Authorization: Bearer <token>` header.
- Only users with `role: admin` can create, update, or delete products.
- Users with `role: user` can only view products.
- For file upload, use `multipart/form-data` with key `image`.
- Product images are accessible at `/storage/images/{filename}` after upload.
