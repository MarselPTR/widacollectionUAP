# Wida Collection - E-Commerce Thrift Store

Aplikasi web e-commerce untuk toko thrift (pakaian bekas berkualitas) dengan fitur lengkap termasuk katalog produk, keranjang belanja, checkout, dan dashboard admin.

---

## 📋 Daftar Isi

- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Struktur Database](#struktur-database)
- [Autentikasi JWT](#autentikasi-jwt)
- [API Routes](#api-routes)
- [Fitur Utama](#fitur-utama)
- [Cara Menjalankan](#cara-menjalankan)

---

## 🛠️ Teknologi yang Digunakan

| Komponen | Teknologi | Keterangan |
|----------|-----------|------------|
| **Backend** | Laravel 12 | Framework PHP untuk REST API |
| **Autentikasi** | JSON Web Token (JWT) | Menggunakan library `firebase/php-jwt` |
| **Frontend** | Laravel Blade + Tailwind CSS | Native Laravel templating dengan utility-first CSS |
| **Database** | MySQL | Relational database |
| **Server** | Laragon | Local development environment |

---

## 🗄️ Struktur Database

### Tabel Utama dengan UUID/Slug

| Tabel | Primary Key | Identifier Tambahan | Keterangan |
|-------|-------------|---------------------|------------|
| `users` | `id` | `uuid` | Data pengguna |
| `custom_products` | `id` | `uuid`, `slug`, `public_id` | Katalog produk |
| `orders` | `id` | `uuid`, `public_id` | Pesanan/transaksi |
| `addresses` | `id` | `uuid` | Alamat pengiriman |
| `wishlists` | `id` | `uuid` | Daftar keinginan |
| `cart_items` | `id` | `uuid` | Item keranjang |
| `reviews` | `id` | `uuid` | Ulasan produk |

### Relasi Antar Tabel

```
users (1) ──────< (N) orders
users (1) ──────< (N) cart_items
users (1) ──────< (N) addresses
users (1) ──────< (N) wishlists
users (1) ──────< (1) user_profiles

custom_products (1) ──────< (N) orders
custom_products (1) ──────< (N) cart_items
custom_products (1) ──────< (N) wishlists
custom_products (1) ──────< (N) reviews
```

---

## 🔐 Autentikasi JWT

### File Terkait

| File | Lokasi | Fungsi |
|------|--------|--------|
| `JwtService.php` | `app/Services/` | Service untuk issue, decode, dan revoke token |
| `JwtAuth.php` | `app/Http/Middleware/` | Middleware proteksi route |
| `AdminOnly.php` | `app/Http/Middleware/` | Middleware khusus admin |
| `AuthController.php` | `app/Http/Controllers/Api/` | Controller register, login, logout |

### Alur Autentikasi

1. **Register/Login** → User mengirim kredensial
2. **Issue Token** → `JwtService->issue()` membuat token dengan payload:
   - `sub`: User ID
   - `jti`: JWT ID (untuk revoke)
   - `email`: Email user
   - `is_admin`: Status admin
   - `exp`: Waktu kadaluarsa
3. **Set Cookie** → Token disimpan di cookie `wc_token` (HttpOnly)
4. **Request Terproteksi** → Middleware `JwtAuth` validasi token
5. **Logout** → Token dimasukkan ke tabel `revoked_tokens`

### Contoh JWT Payload

```json
{
  "sub": 1,
  "jti": "abc123-unique-id",
  "email": "user@example.com",
  "is_admin": false,
  "iat": 1703300000,
  "exp": 1703386400
}
```

---

## 🛣️ API Routes

### Autentikasi (Public)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `POST` | `/api/auth/register` | `AuthController@register` | Registrasi akun baru |
| `POST` | `/api/auth/login` | `AuthController@login` | Login dan dapatkan token |
| `POST` | `/api/auth/logout` | `AuthController@logout` | Logout dan revoke token |
| `GET` | `/api/auth/me` | `AuthController@me` | Data user yang login |

### Produk (Public)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/products` | `ProductController@index` | Daftar semua produk |
| `GET` | `/api/products/{identifier}` | `ProductController@show` | Detail produk (by slug/uuid) |

### Profil (Protected - Login Required)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/profile` | `ProfileController@show` | Data profil user |
| `PUT` | `/api/profile` | `ProfileController@update` | Update profil |

### Alamat (Protected)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/addresses` | `AddressController@index` | Daftar alamat |
| `POST` | `/api/addresses` | `AddressController@store` | Tambah alamat baru |
| `PUT` | `/api/addresses/{uuid}` | `AddressController@update` | Edit alamat |
| `DELETE` | `/api/addresses/{uuid}` | `AddressController@destroy` | Hapus alamat |
| `POST` | `/api/addresses/{uuid}/primary` | `AddressController@setPrimary` | Set alamat utama |

### Wishlist (Protected)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/wishlist` | `WishlistController@index` | Daftar wishlist |
| `POST` | `/api/wishlist` | `WishlistController@store` | Tambah ke wishlist |
| `DELETE` | `/api/wishlist/{uuid}` | `WishlistController@destroy` | Hapus dari wishlist |

### Keranjang (Protected)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/cart` | `CartController@show` | Isi keranjang |
| `POST` | `/api/cart/items` | `CartController@addItem` | Tambah item |
| `PATCH` | `/api/cart/items/{uuid}` | `CartController@updateItem` | Update quantity |
| `DELETE` | `/api/cart/items/{uuid}` | `CartController@removeItem` | Hapus item |
| `DELETE` | `/api/cart/clear` | `CartController@clear` | Kosongkan keranjang |

### Pesanan (Protected)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/orders` | `OrderController@index` | Riwayat pesanan user |
| `GET` | `/api/orders/{uuid}` | `OrderController@show` | Detail pesanan |
| `POST` | `/api/orders/checkout` | `OrderController@checkout` | Proses checkout |
| `PATCH` | `/api/orders/{uuid}/received` | `OrderController@markReceived` | Konfirmasi terima |

### Admin - Produk (Protected + Admin Only)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/admin/products` | `ProductController@adminIndex` | Daftar produk (admin) |
| `POST` | `/api/admin/products` | `ProductController@store` | **CREATE** produk baru |
| `PUT` | `/api/admin/products/{uuid}` | `ProductController@update` | **UPDATE** produk |
| `DELETE` | `/api/admin/products/{uuid}` | `ProductController@destroy` | **DELETE** produk |

### Admin - Pesanan (Protected + Admin Only)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `GET` | `/api/admin/orders` | `OrderController@adminIndex` | Semua pesanan |
| `PATCH` | `/api/admin/orders/{uuid}/ship` | `OrderController@adminMarkShipped` | Tandai dikirim |
| `PATCH` | `/api/admin/orders/{uuid}/deliver` | `OrderController@adminMarkDelivered` | Tandai diterima |

### Admin - Live Drop (Protected + Admin Only)

| Method | Endpoint | Controller | Fungsi |
|--------|----------|------------|--------|
| `PUT` | `/api/admin/live-drop` | `LiveDropController@update` | Update jadwal live drop |

---

## ⭐ Fitur Utama

### 1. Register & Login

**File:** `AuthController.php`

```php
// Register - Membuat akun baru
public function register(Request $request) {
    $validated = $request->validate([...]);
    $user = User::create([...]);
    $token = $this->jwtService->issue($user);
    return response()->json([...])->cookie('wc_token', $token);
}

// Login - Autentikasi user
public function login(Request $request) {
    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    $token = $this->jwtService->issue($user);
    return response()->json([...])->cookie('wc_token', $token);
}
```

### 2. CRUD Produk (Admin)

**File:** `ProductController.php`

| Operasi | Method | Keterangan |
|---------|--------|------------|
| **Create** | `store()` | Generate UUID, slug, public_id otomatis. Upload gambar ke storage. |
| **Read** | `index()`, `show()` | Ambil data dengan identifier (slug/uuid/public_id) |
| **Update** | `update()` | Update field yang dikirim, termasuk gambar baru |
| **Delete** | `destroy()` | Hapus produk dari database |

### 3. CRUD Pesanan dengan Relasi Produk

**File:** `OrderController.php`

#### Proses Checkout (dengan Pengurangan Stok Otomatis)

```php
public function checkout(Request $request) {
    // 1. Ambil cart items user
    $cartItems = DB::table('cart_items')->where('user_id', $userId)->get();
    
    // 2. Validasi stok setiap produk
    foreach ($cartItems as $item) {
        $product = DB::table('custom_products')->find($item->product_id);
        if ($product->stock < $item->quantity) {
            return response()->json(['message' => 'Stok tidak cukup'], 422);
        }
    }
    
    // 3. Buat order dan KURANGI STOK
    foreach ($cartItems as $item) {
        DB::table('orders')->insert([
            'uuid' => Str::uuid(),
            'user_id' => $userId,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'total' => $product->price * $item->quantity,
            'status' => 'pending',
        ]);
        
        // PENGURANGAN STOK OTOMATIS
        DB::table('custom_products')
            ->where('id', $item->product_id)
            ->decrement('stock', $item->quantity);
    }
    
    // 4. Kosongkan keranjang
    DB::table('cart_items')->where('user_id', $userId)->delete();
}
```

### 4. Proteksi Route dengan JWT

**File:** `routes/api.php`

```php
// Route PUBLIC (tanpa autentikasi)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);

// Route PROTECTED (wajib login)
Route::middleware([JwtAuth::class])->group(function () {
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/orders/checkout', [OrderController::class, 'checkout']);
});

// Route ADMIN (wajib login + admin)
Route::prefix('admin')->middleware([JwtAuth::class, AdminOnly::class])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{uuid}', [ProductController::class, 'update']);
    Route::delete('/products/{uuid}', [ProductController::class, 'destroy']);
});
```

---

## 🚀 Cara Menjalankan

### Prasyarat
- PHP 8.2+
- Composer
- MySQL
- Node.js (untuk compile Tailwind)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repository-url>
cd widacollection/backend

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di .env
# DB_DATABASE=widacollection
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi dan seeder
php artisan migrate
php artisan db:seed

# 7. Buat symbolic link untuk storage
php artisan storage:link

# 8. Jalankan server
php artisan serve
```

### Akses Aplikasi

| URL | Keterangan |
|-----|------------|
| `http://127.0.0.1:8000/body` | Halaman utama (katalog) |
| `http://127.0.0.1:8000/login` | Halaman login |
| `http://127.0.0.1:8000/register` | Halaman registrasi |
| `http://127.0.0.1:8000/profile` | Dashboard user |
| `http://127.0.0.1:8000/admin` | Dashboard admin |

---

## 📁 Struktur Folder Penting

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/     # Semua API controllers
│   │   │   ├── AuthController.php
│   │   │   ├── ProductController.php
│   │   │   ├── OrderController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       ├── JwtAuth.php      # Middleware JWT
│   │       └── AdminOnly.php    # Middleware admin
│   ├── Models/
│   │   └── User.php
│   └── Services/
│       └── JwtService.php       # JWT service
├── database/
│   ├── migrations/              # Struktur tabel
│   └── seeders/                 # Data awal
├── resources/
│   ├── css/
│   │   └── app.css              # Tailwind config
│   └── views/pages/             # Blade templates
├── routes/
│   ├── api.php                  # API routes
│   └── web.php                  # Web routes
└── public/
    ├── js/                      # JavaScript files
    └── output.css               # Compiled Tailwind
```

---

## ✅ Checklist Kepatuhan UAP

| Persyaratan | Status |
|-------------|--------|
| Backend menggunakan Laravel | ✅ |
| Autentikasi menggunakan JWT | ✅ |
| Frontend menggunakan Tailwind CSS | ✅ |
| Integrasi frontend dengan backend (Blade) | ✅ |
| Setiap tabel memiliki UUID/Slug | ✅ |
| Fitur Register | ✅ |
| Fitur Login | ✅ |
| Proteksi CRUD dengan JWT | ✅ |
| CRUD minimal 2 tabel berelasi | ✅ |
| Stok otomatis berkurang saat transaksi | ✅ |

---

## 👤 Author

**Wida Collection** - UAP Pemrograman Web 2024/2025
