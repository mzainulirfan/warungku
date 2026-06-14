# PRD — POS Warung Sederhana
**Product Requirements Document**
**Versi:** 1.0.0
**Tanggal:** 14 Juni 2026
**Status:** Draft
**Dibuat untuk:** Tim Developer (Junior-Friendly)

---

## Daftar Isi

1. [Gambaran Umum](#1-gambaran-umum)
2. [Tech Stack](#2-tech-stack)
3. [Struktur Folder Proyek](#3-struktur-folder-proyek)
4. [Database Schema](#4-database-schema)
5. [Fase Pengerjaan](#5-fase-pengerjaan)
6. [Halaman & Fitur Detail](#6-halaman--fitur-detail)
   - [Auth (Login/Logout)](#61-auth-loginlogout)
   - [Dashboard](#62-dashboard)
   - [Produk](#63-produk)
   - [Kategori](#64-kategori)
   - [Kasir (POS)](#65-kasir-pos--transaksi)
   - [User Management](#66-user-management)
   - [Setting](#67-setting)
7. [Otorisasi & Role](#7-otorisasi--role)
8. [Validasi](#8-validasi)
9. [Security](#9-security)
10. [UI/UX Guideline](#10-uiux-guideline)
11. [Konvensi Kode](#11-konvensi-kode)
12. [Checklist Per Fase](#12-checklist-per-fase)

---

## 1. Gambaran Umum

### Apa itu POS Warung Sederhana?

POS (Point of Sale) Warung Sederhana adalah aplikasi web kasir berbasis browser untuk usaha kecil seperti warung makan, toko kelontong, atau kedai kopi. Tujuannya sederhana:

> **Membantu pemilik warung mencatat transaksi penjualan, mengelola produk, dan melihat laporan harian dengan mudah.**

### Siapa penggunanya?

| Peran | Deskripsi |
|---|---|
| **Owner / Admin** | Pemilik usaha. Punya akses penuh ke semua fitur. |
| **Kasir** | Karyawan yang melayani transaksi. Hanya bisa akses halaman kasir & dashboard ringkas. |

### Batasan Proyek (Scope)

**Termasuk (In Scope):**
- Manajemen produk & kategori
- Transaksi penjualan (kasir)
- Laporan penjualan harian
- Manajemen user
- Setting aplikasi (nama toko, dll.)
- Auth + otorisasi berbasis role

**Tidak Termasuk (Out of Scope):**
- Manajemen stok / inventory lengkap (direncanakan di versi berikutnya)
- Multi-cabang
- Integrasi printer struk hardware
- Pembayaran non-tunai (QRIS, transfer) — bisa ditambah nanti
- Aplikasi mobile

---

## 2. Tech Stack

| Layer | Teknologi | Keterangan |
|---|---|---|
| **Backend / Framework** | CodeIgniter 4 (CI4) | PHP framework, MVC pattern |
| **Database** | SQLite (via PDO) | Ringan, tidak perlu install MySQL, cocok untuk pengembangan awal |
| **Template Engine** | CI4 View (PHP native) | Tidak pakai Blade atau Twig |
| **CSS** | Custom CSS | Tema hitam putih/grayscale terinspirasi shadcn/ui |
| **JavaScript** | Vanilla JS + Alpine.js (opsional) | Untuk interaksi ringan tanpa framework berat |
| **Icon** | Lucide Icons (via CDN) | SVG icon pack gratis |
| **Font** | Geist / Inter (via Google Fonts) | Konsisten dengan estetika shadcn |
| **Versi PHP** | PHP 8.1+ | Minimum untuk CI4 |
| **Web Server** | Built-in PHP server / Apache / Nginx | Bebas, untuk dev pakai `php spark serve` |

### Cara Install (untuk junior)

```bash
# 1. Buat proyek CI4 baru
composer create-project codeigniter4/appstarter pos-warung

# 2. Masuk ke folder proyek
cd pos-warung

# 3. Copy file env
cp env .env

# 4. Edit .env — ganti database ke SQLite
# CI_ENVIRONMENT = development
# database.default.DBDriver = SQLite3
# database.default.database = writable/db/warung.db

# 5. Buat folder database
mkdir -p writable/db

# 6. Jalankan server
php spark serve
```

---

## 3. Struktur Folder Proyek

```
pos-warung/
├── app/
│   ├── Config/
│   │   ├── Auth.php              ← Konfigurasi session auth
│   │   └── Routes.php            ← Semua route didefinisikan di sini
│   ├── Controllers/
│   │   ├── BaseController.php    ← Controller induk (sudah ada di CI4)
│   │   ├── AuthController.php    ← Login, logout
│   │   ├── DashboardController.php
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── TransactionController.php
│   │   ├── UserController.php
│   │   └── SettingController.php
│   ├── Models/
│   │   ├── UserModel.php
│   │   ├── ProductModel.php
│   │   ├── CategoryModel.php
│   │   ├── TransactionModel.php
│   │   └── TransactionItemModel.php
│   ├── Views/
│   │   ├── layouts/
│   │   │   ├── main.php          ← Layout utama (sidebar + topbar)
│   │   │   └── auth.php          ← Layout halaman login
│   │   ├── partials/
│   │   │   ├── sidebar.php
│   │   │   ├── topbar.php
│   │   │   └── alerts.php        ← Flash message (success/error)
│   │   ├── auth/
│   │   │   └── login.php
│   │   ├── dashboard/
│   │   │   └── index.php
│   │   ├── product/
│   │   │   ├── index.php         ← Daftar produk
│   │   │   ├── create.php        ← Form tambah produk
│   │   │   └── edit.php          ← Form edit produk
│   │   ├── category/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   ├── transaction/
│   │   │   ├── pos.php           ← Halaman kasir utama
│   │   │   └── history.php       ← Riwayat transaksi
│   │   ├── user/
│   │   │   ├── index.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   └── setting/
│   │       └── index.php
│   ├── Filters/
│   │   ├── AuthFilter.php        ← Cek apakah sudah login
│   │   └── RoleFilter.php        ← Cek role (admin/kasir)
│   └── Helpers/
│       └── app_helper.php        ← Fungsi bantu (format rupiah, dll.)
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── app.css           ← File CSS utama (dark theme)
│   │   ├── js/
│   │   │   └── app.js            ← JS utama
│   │   └── img/
│   │       └── logo.png
├── writable/
│   └── db/
│       └── warung.db             ← File database SQLite
└── .env
```

---

## 4. Database Schema

### Tabel: `users`

```sql
CREATE TABLE users (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    name       TEXT NOT NULL,
    email      TEXT NOT NULL UNIQUE,
    password   TEXT NOT NULL,          -- bcrypt hash
    role       TEXT NOT NULL DEFAULT 'kasir',  -- 'admin' | 'kasir'
    is_active  INTEGER NOT NULL DEFAULT 1,     -- 1=aktif, 0=nonaktif
    created_at TEXT,
    updated_at TEXT
);
```

**Penjelasan kolom:**
- `role` hanya boleh bernilai `admin` atau `kasir`
- `password` wajib di-hash dengan `password_hash()` — **jangan simpan plain text**
- `is_active` untuk menonaktifkan user tanpa menghapus datanya

---

### Tabel: `categories`

```sql
CREATE TABLE categories (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    name       TEXT NOT NULL UNIQUE,
    created_at TEXT,
    updated_at TEXT
);
```

---

### Tabel: `products`

```sql
CREATE TABLE products (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER,
    name        TEXT NOT NULL,
    price       REAL NOT NULL DEFAULT 0,
    stock       INTEGER NOT NULL DEFAULT 0,
    image       TEXT,                   -- nama file gambar (opsional)
    is_active   INTEGER NOT NULL DEFAULT 1,
    created_at  TEXT,
    updated_at  TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);
```

---

### Tabel: `transactions`

```sql
CREATE TABLE transactions (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    invoice_no    TEXT NOT NULL UNIQUE,   -- contoh: INV-20260614-001
    user_id       INTEGER NOT NULL,       -- kasir yang melayani
    total_amount  REAL NOT NULL DEFAULT 0,
    payment_amount REAL NOT NULL DEFAULT 0,
    change_amount  REAL NOT NULL DEFAULT 0,
    note          TEXT,
    created_at    TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

### Tabel: `transaction_items`

```sql
CREATE TABLE transaction_items (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER NOT NULL,
    product_id     INTEGER NOT NULL,
    product_name   TEXT NOT NULL,    -- snapshot nama saat transaksi
    price          REAL NOT NULL,    -- snapshot harga saat transaksi
    qty            INTEGER NOT NULL DEFAULT 1,
    subtotal       REAL NOT NULL DEFAULT 0,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

> **Catatan untuk junior:** Kolom `product_name` dan `price` di tabel `transaction_items` sengaja menyimpan snapshot data produk saat transaksi terjadi. Ini penting agar riwayat transaksi tidak berubah jika harga produk diupdate di kemudian hari.

---

### Tabel: `settings`

```sql
CREATE TABLE settings (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    key        TEXT NOT NULL UNIQUE,
    value      TEXT,
    created_at TEXT,
    updated_at TEXT
);

-- Data awal (seed)
INSERT INTO settings (key, value) VALUES
    ('store_name', 'Warung Sederhana'),
    ('store_address', 'Jl. Contoh No. 1'),
    ('store_phone', '08123456789'),
    ('currency_symbol', 'Rp');
```

---

### Migration File CI4

Buat migration untuk setiap tabel. Contoh untuk tabel `users`:

```bash
php spark make:migration CreateUsersTable
```

```php
// app/Database/Migrations/2026-06-14-000001_CreateUsersTable.php
public function up()
{
    $this->forge->addField([
        'id'         => ['type' => 'INTEGER', 'auto_increment' => true],
        'name'       => ['type' => 'TEXT', 'null' => false],
        'email'      => ['type' => 'TEXT', 'null' => false],
        'password'   => ['type' => 'TEXT', 'null' => false],
        'role'       => ['type' => 'TEXT', 'default' => 'kasir'],
        'is_active'  => ['type' => 'INTEGER', 'default' => 1],
        'created_at' => ['type' => 'TEXT', 'null' => true],
        'updated_at' => ['type' => 'TEXT', 'null' => true],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey('email');
    $this->forge->createTable('users');
}

public function down()
{
    $this->forge->dropTable('users');
}
```

Jalankan semua migration:
```bash
php spark migrate
```

---

## 5. Fase Pengerjaan

Proyek dikerjakan dalam **5 fase** secara berurutan. Selesaikan satu fase sebelum lanjut ke fase berikutnya.

```
Fase 1 → Fase 2 → Fase 3 → Fase 4 → Fase 5
Setup    Auth    CRUD     POS      Polish
```

| Fase | Nama | Estimasi | Deskripsi |
|---|---|---|---|
| **1** | Setup & Fondasi | 1–2 hari | Install CI4, konfigurasi SQLite, buat layout dark theme, helper |
| **2** | Auth & Keamanan | 1–2 hari | Login, logout, filter auth, filter role, seed admin pertama |
| **3** | CRUD Master Data | 2–3 hari | Produk, Kategori, User Management, Setting |
| **4** | Kasir (POS) | 2–3 hari | Halaman transaksi, keranjang, simpan transaksi, riwayat |
| **5** | Dashboard & Polish | 1–2 hari | Dashboard dengan statistik, validasi lengkap, UI polish |

---

## 6. Halaman & Fitur Detail

---

### 6.1 Auth (Login/Logout)

**Route:**
```php
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');
```

**Tampilan Login:**
- Halaman fullscreen dengan form di tengah
- Field: Email, Password
- Tombol "Masuk"
- Tidak ada link register (akun dibuat oleh admin)

**Logika `AuthController::processLogin()`:**
```
1. Validasi input (email format, password tidak kosong)
2. Cari user berdasarkan email di database
3. Cek is_active == 1 (user tidak dinonaktifkan)
4. Verifikasi password dengan password_verify()
5. Jika gagal: kembalikan ke form + pesan error
6. Jika berhasil:
   a. Regenerate session ID (keamanan)
   b. Simpan data user ke session:
      - session()->set('user_id', $user->id)
      - session()->set('user_name', $user->name)
      - session()->set('user_role', $user->role)
      - session()->set('logged_in', true)
   c. Redirect ke /dashboard
```

**`AuthController::logout()`:**
```
1. Hapus semua session: session()->destroy()
2. Redirect ke /login
3. Tampilkan flash message "Berhasil logout"
```

---

### 6.2 Dashboard

**Route:** `GET /dashboard`
**Akses:** Admin & Kasir

**Konten untuk Admin:**
```
┌─────────────────────────────────────────────────┐
│  Selamat datang, [Nama User] — [Tanggal Hari Ini] │
├──────────┬──────────┬──────────┬────────────────┤
│ Penjualan│ Transaksi│ Produk   │ Kategori       │
│ Hari Ini │ Hari Ini │ Aktif    │                │
│ Rp 250rb │ 12       │ 48       │ 6              │
└──────────┴──────────┴──────────┴────────────────┘
│  Tabel: 5 Transaksi Terakhir Hari Ini           │
└─────────────────────────────────────────────────┘
```

**Konten untuk Kasir:**
- Hanya tampil jumlah transaksi & total penjualan miliknya hari ini
- Tombol shortcut ke halaman Kasir

**Query yang dibutuhkan:**
```php
// Total penjualan hari ini
$today = date('Y-m-d');
$totalSales = $db->query("
    SELECT SUM(total_amount) as total 
    FROM transactions 
    WHERE DATE(created_at) = ?
", [$today])->getRow();

// Jumlah transaksi hari ini
$totalTrx = $db->query("
    SELECT COUNT(*) as count 
    FROM transactions 
    WHERE DATE(created_at) = ?
", [$today])->getRow();
```

---

### 6.3 Produk

**Route:**
```php
$routes->get('/product', 'ProductController::index');
$routes->get('/product/create', 'ProductController::create');
$routes->post('/product/store', 'ProductController::store');
$routes->get('/product/edit/(:num)', 'ProductController::edit/$1');
$routes->post('/product/update/(:num)', 'ProductController::update/$1');
$routes->post('/product/delete/(:num)', 'ProductController::delete/$1');
$routes->post('/product/toggle/(:num)', 'ProductController::toggle/$1');
```

**Akses:** Admin only

**Halaman Index Produk (`/product`):**
- Tabel daftar produk dengan kolom: No, Gambar, Nama, Kategori, Harga, Stok, Status, Aksi
- Filter: dropdown kategori + input search nama
- Pagination: 10 produk per halaman
- Tombol "Tambah Produk"
- Badge status: Aktif (hijau) / Nonaktif (abu)
- Aksi per baris: Edit, Hapus, Toggle Aktif

**Form Tambah / Edit Produk:**

| Field | Tipe Input | Validasi | Keterangan |
|---|---|---|---|
| Nama Produk | text | required, min 2 char, max 100 char | |
| Kategori | select | required | Dropdown dari tabel categories |
| Harga | number | required, min 0, numeric | Format Rupiah |
| Stok | number | required, min 0, integer | |
| Gambar | file | opsional, max 1MB, jpg/png/webp | Upload gambar produk |
| Status | toggle/select | required | Aktif / Nonaktif |

**Logika Upload Gambar:**
```php
// Di ProductController::store()
$img = $this->request->getFile('image');
if ($img && $img->isValid() && !$img->hasMoved()) {
    $newName = $img->getRandomName();
    $img->move(ROOTPATH . 'public/assets/img/products', $newName);
    $imageName = $newName;
} else {
    $imageName = null;
}
```

**Logika Hapus:**
- Hapus tidak langsung dihapus dari DB jika sudah pernah ada di transaksi
- Tampilkan konfirmasi dialog sebelum hapus
- Jika produk pernah ada di `transaction_items`, gunakan soft delete (set `is_active = 0`) atau tampilkan error

---

### 6.4 Kategori

**Route:**
```php
$routes->get('/category', 'CategoryController::index');
$routes->post('/category/store', 'CategoryController::store');
$routes->post('/category/update/(:num)', 'CategoryController::update/$1');
$routes->post('/category/delete/(:num)', 'CategoryController::delete/$1');
```

**Akses:** Admin only

**Tampilan:**
- Halaman sederhana, list kategori di kiri, form tambah/edit di kanan
- Tidak perlu halaman terpisah untuk create/edit kategori (bisa inline atau modal)

**Field:**

| Field | Tipe | Validasi |
|---|---|---|
| Nama Kategori | text | required, min 2 char, max 50 char, unique |

**Logika Hapus:**
- Cek apakah kategori masih dipakai oleh produk
- Jika masih ada produk, tampilkan error: "Kategori tidak dapat dihapus karena masih digunakan oleh X produk"
- Jika tidak ada produk, boleh dihapus

---

### 6.5 Kasir (POS / Transaksi)

**Route:**
```php
$routes->get('/pos', 'TransactionController::pos');
$routes->post('/pos/store', 'TransactionController::store');
$routes->get('/transaction', 'TransactionController::history');
$routes->get('/transaction/detail/(:num)', 'TransactionController::detail/$1');
```

**Akses:** Admin & Kasir

**Layout Halaman Kasir:**
```
┌────────────────────┬───────────────────────────┐
│  PRODUK            │  KERANJANG BELANJA         │
│                    │  ─────────────────────     │
│  [Search produk]   │  Nasi Goreng      1 × 15rb │
│  [Filter kategori] │  Es Teh           2 × 5rb  │
│                    │  ─────────────────────     │
│  [Kopi  ] [Nasi ]  │  Total:         Rp 25.000  │
│  [Es Teh] [Roti ]  │                            │
│  [Ayam  ] [Soto ]  │  Bayar: [_____________]    │
│                    │  Kembalian: Rp 0           │
│                    │                            │
│                    │  [Bayar] [Reset]           │
└────────────────────┴───────────────────────────┘
```

**Logika Keranjang (JavaScript / Session):**
```
1. User klik produk → masuk ke keranjang
2. Klik lagi produk yang sama → qty +1
3. Bisa ubah qty langsung di keranjang
4. Bisa hapus item dari keranjang
5. Total otomatis dihitung
6. Input "Bayar" → hitung kembalian otomatis
```

**Logika Simpan Transaksi (`POST /pos/store`):**
```
1. Validasi: keranjang tidak kosong
2. Validasi: jumlah bayar >= total
3. Generate invoice_no: "INV-" + date('Ymd') + "-" + urutan
4. Simpan ke tabel transactions
5. Loop item keranjang → simpan ke transaction_items
6. Kurangi stok produk (jika fitur stok aktif)
7. Return response JSON: {success: true, invoice_no: "..."}
8. Reset keranjang
9. Tampilkan struk ringkas (modal)
```

**Riwayat Transaksi (`/transaction`):**
- Tabel: No Invoice, Kasir, Total, Waktu, Aksi (Detail)
- Filter: tanggal (default hari ini)
- Total penjualan periode yang dipilih

---

### 6.6 User Management

**Route:**
```php
$routes->get('/user', 'UserController::index');
$routes->get('/user/create', 'UserController::create');
$routes->post('/user/store', 'UserController::store');
$routes->get('/user/edit/(:num)', 'UserController::edit/$1');
$routes->post('/user/update/(:num)', 'UserController::update/$1');
$routes->post('/user/toggle/(:num)', 'UserController::toggle/$1');
```

**Akses:** Admin only

**Field Form Tambah User:**

| Field | Tipe | Validasi |
|---|---|---|
| Nama | text | required, min 2 char, max 100 char |
| Email | email | required, valid email, unique |
| Password | password | required saat create, min 8 char |
| Konfirmasi Password | password | required, harus sama dengan password |
| Role | select | required, nilai: admin / kasir |
| Status | select | Aktif / Nonaktif |

**Catatan:**
- Admin tidak bisa menghapus akunnya sendiri
- Admin tidak bisa menonaktifkan akunnya sendiri
- Edit user: password boleh kosong (tidak diubah jika kosong)

---

### 6.7 Setting

**Route:**
```php
$routes->get('/setting', 'SettingController::index');
$routes->post('/setting/update', 'SettingController::update');
```

**Akses:** Admin only

**Field Setting:**

| Key | Label | Tipe | Validasi |
|---|---|---|---|
| `store_name` | Nama Toko | text | required, max 100 |
| `store_address` | Alamat Toko | textarea | opsional, max 255 |
| `store_phone` | Nomor Telepon | text | opsional, max 20 |
| `currency_symbol` | Simbol Mata Uang | text | required, max 10 |

**Cara kerja Setting:**
```php
// Ambil semua setting
$settings = $db->table('settings')->get()->getResultArray();
$config = array_column($settings, 'value', 'key');

// Update setting
foreach ($data as $key => $value) {
    $db->table('settings')
       ->where('key', $key)
       ->update(['value' => $value]);
}
```

---

## 7. Otorisasi & Role

### Filter Auth (`app/Filters/AuthFilter.php`)

Filter ini memastikan hanya user yang sudah login yang bisa akses halaman.

```php
<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah sudah login
        if (!session()->get('logged_in')) {
            // Simpan URL tujuan, redirect ke login
            session()->set('redirect_url', current_url());
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu implementasi
    }
}
```

### Filter Role (`app/Filters/RoleFilter.php`)

Filter ini memastikan hanya role tertentu yang bisa akses route.

```php
<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userRole = session()->get('user_role');

        // $arguments berisi role yang diizinkan, misal: ['admin']
        if ($arguments && !in_array($userRole, $arguments)) {
            return redirect()->to('/dashboard')
                             ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu implementasi
    }
}
```

### Daftarkan Filter di `app/Config/Filters.php`

```php
public array $aliases = [
    'auth' => \App\Filters\AuthFilter::class,
    'role' => \App\Filters\RoleFilter::class,
];
```

### Terapkan Filter di `app/Config/Routes.php`

```php
// Semua route di bawah ini wajib login
$routes->group('', ['filter' => 'auth'], function($routes) {

    $routes->get('/dashboard', 'DashboardController::index');
    $routes->get('/pos', 'TransactionController::pos');
    $routes->post('/pos/store', 'TransactionController::store');

    // Hanya admin yang bisa akses route ini
    $routes->group('', ['filter' => 'role:admin'], function($routes) {
        $routes->get('/product', 'ProductController::index');
        $routes->get('/product/create', 'ProductController::create');
        // ... dst
        $routes->get('/category', 'CategoryController::index');
        $routes->get('/user', 'UserController::index');
        $routes->get('/setting', 'SettingController::index');
    });
});
```

### Tabel Akses Halaman

| Halaman | Admin | Kasir |
|---|---|---|
| Login | ✅ | ✅ |
| Dashboard | ✅ | ✅ (versi terbatas) |
| Kasir / POS | ✅ | ✅ |
| Riwayat Transaksi | ✅ | ✅ (hanya miliknya) |
| Produk (CRUD) | ✅ | ❌ |
| Kategori (CRUD) | ✅ | ❌ |
| User Management | ✅ | ❌ |
| Setting | ✅ | ❌ |

---

## 8. Validasi

### Aturan Umum Validasi CI4

CI4 punya library validasi bawaan. Gunakan dengan cara ini:

```php
// Di dalam controller
$rules = [
    'name'  => 'required|min_length[2]|max_length[100]',
    'email' => 'required|valid_email|is_unique[users.email]',
    'price' => 'required|numeric|greater_than_equal_to[0]',
];

if (!$this->validate($rules)) {
    // Kembalikan ke form dengan error
    return redirect()->back()
                     ->withInput()
                     ->with('errors', $this->validator->getErrors());
}
```

### Tampilkan Error di View

```php
<!-- Di file view (misal: create.php) -->
<?php if (session()->has('errors')): ?>
    <div class="alert alert-error">
        <ul>
        <?php foreach (session('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<!-- Atau per field -->
<div class="form-group">
    <label>Nama Produk</label>
    <input type="text" name="name" value="<?= old('name') ?>">
    <?php if (isset($errors['name'])): ?>
        <span class="error-text"><?= $errors['name'] ?></span>
    <?php endif ?>
</div>
```

### Referensi Validasi Per Halaman

| Form | Field | Rule |
|---|---|---|
| Login | email | `required\|valid_email` |
| Login | password | `required` |
| Produk | name | `required\|min_length[2]\|max_length[100]` |
| Produk | category_id | `required\|is_not_unique[categories.id]` |
| Produk | price | `required\|numeric\|greater_than_equal_to[0]` |
| Produk | stock | `required\|integer\|greater_than_equal_to[0]` |
| Produk | image | `permit_empty\|uploaded[image]\|max_size[image,1024]\|is_image[image]` |
| Kategori | name | `required\|min_length[2]\|max_length[50]\|is_unique[categories.name]` |
| User | name | `required\|min_length[2]\|max_length[100]` |
| User | email | `required\|valid_email\|is_unique[users.email]` |
| User | password | `required\|min_length[8]` |
| User | password_confirm | `required\|matches[password]` |
| User | role | `required\|in_list[admin,kasir]` |
| Transaksi | items | custom: array tidak boleh kosong |
| Transaksi | payment | custom: >= total |

---

## 9. Security

### 9.1 CSRF Protection

CI4 sudah include CSRF protection bawaan. Aktifkan di `.env`:

```ini
security.tokenName    = csrf_token
security.headerName   = X-CSRF-TOKEN
security.cookieName   = csrf_cookie
security.expires      = 7200
security.regenerate   = true
security.redirect     = true
security.samesite     = Lax
```

Sertakan token di setiap form:
```html
<form method="POST" action="/product/store">
    <?= csrf_field() ?>
    <!-- field lainnya -->
</form>
```

Untuk request AJAX:
```javascript
// Ambil token dari meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('/pos/store', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
    },
    body: JSON.stringify(cartData),
});
```

Tambahkan meta tag di layout:
```html
<meta name="csrf-token" content="<?= csrf_hash() ?>">
```

### 9.2 Password Hashing

**Wajib** gunakan `password_hash()` dengan algoritma bcrypt:

```php
// Saat simpan password baru
$hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

// Saat verifikasi login
if (password_verify($inputPassword, $hashedPassword)) {
    // password cocok
}
```

**Jangan pernah:**
- Simpan password plain text
- Gunakan MD5 atau SHA1 untuk password

### 9.3 Escape Output (XSS Prevention)

Selalu gunakan `esc()` saat menampilkan data dari user di view:

```php
<!-- BENAR -->
<td><?= esc($product->name) ?></td>

<!-- SALAH - rentan XSS -->
<td><?= $product->name ?></td>
```

### 9.4 SQL Injection Prevention

Gunakan Query Builder CI4 atau parameter binding — **jangan** string concatenation:

```php
// BENAR - menggunakan Query Builder
$product = $this->productModel->find($id);

// BENAR - menggunakan parameter binding
$result = $db->query("SELECT * FROM users WHERE email = ?", [$email]);

// SALAH - rentan SQL Injection
$result = $db->query("SELECT * FROM users WHERE email = '" . $email . "'");
```

### 9.5 Session Security

```php
// Regenerate session ID setelah login (cegah session fixation)
session()->regenerate(true);

// Simpan data minimal di session
session()->set([
    'user_id'   => $user->id,
    'user_name' => $user->name,
    'user_role' => $user->role,
    'logged_in' => true,
]);
```

### 9.6 Authorization Check di Controller

Selain filter, lakukan double-check di controller untuk operasi sensitif:

```php
// Di UserController::update()
public function update($id)
{
    // Admin tidak boleh nonaktifkan dirinya sendiri
    if ($id == session()->get('user_id') && $this->request->getPost('is_active') == 0) {
        return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
    }
    // ... lanjut proses update
}
```

### 9.7 File Upload Security

```php
$img = $this->request->getFile('image');

// Cek valid & belum dipindah
if ($img->isValid() && !$img->hasMoved()) {
    // Validasi tipe file yang diizinkan
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($img->getMimeType(), $allowedTypes)) {
        return redirect()->back()->with('error', 'Format file tidak didukung.');
    }
    // Validasi ukuran (max 1MB)
    if ($img->getSizeByUnit('mb') > 1) {
        return redirect()->back()->with('error', 'Ukuran file maksimal 1MB.');
    }
    // Beri nama acak (jangan pakai nama asli file dari user)
    $newName = $img->getRandomName();
    $img->move(ROOTPATH . 'public/assets/img/products', $newName);
}
```

---

## 10. UI/UX Guideline

### Color Palette (Dark Theme — shadcn inspired)

```css
/* app/assets/css/app.css */

:root {
    /* Background */
    --bg-base:       #09090b;  /* Zinc-950 — background utama */
    --bg-card:       #18181b;  /* Zinc-900 — card, sidebar */
    --bg-input:      #27272a;  /* Zinc-800 — input, table row hover */
    --bg-hover:      #3f3f46;  /* Zinc-700 — hover state */

    /* Border */
    --border:        #27272a;  /* Zinc-800 */
    --border-focus:  #71717a;  /* Zinc-500 */

    /* Text */
    --text-primary:  #fafafa;  /* Zinc-50 */
    --text-muted:    #a1a1aa;  /* Zinc-400 */
    --text-subtle:   #52525b;  /* Zinc-600 */

    /* Accent / Primary */
    --primary:       #ffffff;
    --primary-text:  #09090b;

    /* Status Colors */
    --success:       #22c55e;  /* Green-500 */
    --success-bg:    #14532d;
    --error:         #ef4444;  /* Red-500 */
    --error-bg:      #7f1d1d;
    --warning:       #f59e0b;  /* Amber-500 */
    --warning-bg:    #451a03;
    --info:          #3b82f6;  /* Blue-500 */

    /* Sizing */
    --radius:        6px;
    --radius-lg:     10px;
    --sidebar-width: 240px;
}
```

### Komponen UI yang Perlu Dibuat

**1. Button:**
```html
<!-- Primary -->
<button class="btn btn-primary">Simpan</button>

<!-- Secondary / Outline -->
<button class="btn btn-outline">Batal</button>

<!-- Danger -->
<button class="btn btn-danger">Hapus</button>

<!-- Size kecil -->
<button class="btn btn-primary btn-sm">Edit</button>
```

**2. Card:**
```html
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Judul Card</h3>
    </div>
    <div class="card-body">
        Konten di sini
    </div>
</div>
```

**3. Alert / Flash Message:**
```html
<div class="alert alert-success">Produk berhasil disimpan.</div>
<div class="alert alert-error">Terjadi kesalahan.</div>
```

**4. Badge:**
```html
<span class="badge badge-success">Aktif</span>
<span class="badge badge-muted">Nonaktif</span>
```

**5. Tabel:**
```html
<div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Nasi Goreng</td>
                <td>
                    <a href="#" class="btn btn-sm btn-outline">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

**6. Form Input:**
```html
<div class="form-group">
    <label class="form-label" for="name">Nama Produk</label>
    <input class="form-input" type="text" id="name" name="name" placeholder="Masukkan nama produk">
    <span class="form-error">Nama produk wajib diisi.</span>
</div>
```

### Layout Utama

```html
<!-- app/Views/layouts/main.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= $title ?? 'POS Warung' ?> — <?= setting('store_name') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <?= view('partials/sidebar') ?>

        <!-- Main content -->
        <div class="main-content">
            <?= view('partials/topbar') ?>
            <div class="page-content">
                <?= view('partials/alerts') ?>
                <?= $content ?>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
```

### Typography

```css
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    color: var(--text-primary);
    background-color: var(--bg-base);
}

h1 { font-size: 1.5rem; font-weight: 600; }
h2 { font-size: 1.25rem; font-weight: 600; }
h3 { font-size: 1rem; font-weight: 600; }
```

---

## 11. Konvensi Kode

### Penamaan

| Hal | Konvensi | Contoh |
|---|---|---|
| Controller | PascalCase + "Controller" | `ProductController` |
| Model | PascalCase + "Model" | `ProductModel` |
| View folder | snake_case | `product/`, `transaction/` |
| View file | snake_case | `create.php`, `edit.php` |
| Route | kebab-case | `/product/create` |
| Database table | snake_case, plural | `products`, `transaction_items` |
| CSS class | kebab-case | `.btn-primary`, `.form-group` |
| JS variable | camelCase | `cartItems`, `totalAmount` |
| PHP variable | camelCase | `$productList`, `$totalSales` |

### Model CI4 — Standar Penggunaan

```php
<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'object';

    protected $allowedFields = [
        'category_id', 'name', 'price', 'stock', 'image', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Relasi sederhana: ambil produk beserta nama kategorinya
    public function getProductsWithCategory()
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id', 'left')
                    ->findAll();
    }
}
```

### Helper: Format Rupiah

```php
// app/Helpers/app_helper.php

if (!function_exists('rupiah')) {
    function rupiah($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('setting')) {
    function setting($key)
    {
        $db = \Config\Database::connect();
        $row = $db->table('settings')->where('key', $key)->get()->getRow();
        return $row ? $row->value : null;
    }
}
```

Daftarkan di `app/Config/Autoload.php`:
```php
public $helpers = ['url', 'form', 'app'];
```

---

## 12. Checklist Per Fase

Gunakan checklist ini untuk tracking progress. Tandai dengan `[x]` jika selesai.

### ✅ Fase 1 — Setup & Fondasi

```
[ ] Install CodeIgniter 4 via Composer
[ ] Konfigurasi .env (SQLite, base_url, dll.)
[ ] Buat folder writable/db/
[ ] Buat semua migration & jalankan migrate
[ ] Buat seeder untuk user admin pertama & data dummy setting
[ ] Buat file app.css dengan CSS variables dark theme
[ ] Buat layout/main.php dan layout/auth.php
[ ] Buat partials: sidebar.php, topbar.php, alerts.php
[ ] Buat app_helper.php (fungsi rupiah, setting)
[ ] Test: php spark serve berjalan tanpa error
```

### ✅ Fase 2 — Auth & Keamanan

```
[ ] Buat AuthController (login, processLogin, logout)
[ ] Buat AuthFilter
[ ] Buat RoleFilter
[ ] Daftarkan filter di Config/Filters.php
[ ] Buat view: auth/login.php
[ ] Terapkan filter ke routes
[ ] Test: login berhasil → redirect dashboard
[ ] Test: login gagal → pesan error muncul
[ ] Test: akses /dashboard tanpa login → redirect ke /login
[ ] Test: kasir tidak bisa akses /product → redirect + pesan error
[ ] Aktifkan CSRF protection
```

### ✅ Fase 3 — CRUD Master Data

```
[ ] CategoryController: index, store, update, delete
[ ] Category views: index.php (dengan form inline)
[ ] ProductController: index, create, store, edit, update, delete, toggle
[ ] Product views: index.php, create.php, edit.php
[ ] Implementasi upload gambar produk
[ ] UserController: index, create, store, edit, update, toggle
[ ] User views: index.php, create.php, edit.php
[ ] SettingController: index, update
[ ] Setting view: index.php
[ ] Semua form punya validasi
[ ] Semua output di-escape dengan esc()
[ ] Test seluruh CRUD sebagai admin
```

### ✅ Fase 4 — Kasir (POS)

```
[ ] TransactionController: pos, store, history, detail
[ ] POS view: pos.php dengan layout 2 kolom
[ ] Implementasi keranjang di JavaScript (vanilla JS)
[ ] Search & filter produk di halaman POS
[ ] Hitung total & kembalian otomatis
[ ] Kirim transaksi via AJAX + CSRF token
[ ] Generate invoice_no unik
[ ] Simpan transaksi + items ke database
[ ] Tampilkan struk ringkas setelah transaksi berhasil
[ ] History view: transaction/history.php
[ ] Detail view: transaction/detail.php
[ ] Filter riwayat transaksi by tanggal
```

### ✅ Fase 5 — Dashboard & Polish

```
[ ] Dashboard: query statistik (total hari ini, jumlah transaksi, dll.)
[ ] Dashboard: tabel 5 transaksi terakhir
[ ] Tampilan berbeda dashboard admin vs kasir
[ ] Konsistensi UI semua halaman
[ ] Semua flash message tampil dengan benar
[ ] Semua halaman punya page title yang benar
[ ] Test CSRF di semua form POST
[ ] Test semua validasi (coba submit form kosong)
[ ] Test role access (login sebagai kasir, coba akses URL admin)
[ ] Review: tidak ada debug output / error di production mode
[ ] Set CI_ENVIRONMENT = production di .env
```

---

## Lampiran: Seed Data

Buat file seeder untuk data awal:

```bash
php spark make:seeder InitialSeeder
```

```php
// app/Database/Seeds/InitialSeeder.php

public function run()
{
    // User admin pertama
    $this->db->table('users')->insert([
        'name'       => 'Administrator',
        'email'      => 'admin@warung.com',
        'password'   => password_hash('admin1234', PASSWORD_BCRYPT),
        'role'       => 'admin',
        'is_active'  => 1,
        'created_at' => date('Y-m-d H:i:s'),
    ]);

    // Setting default
    $settings = [
        ['key' => 'store_name',      'value' => 'Warung Sederhana'],
        ['key' => 'store_address',   'value' => 'Jl. Contoh No. 1'],
        ['key' => 'store_phone',     'value' => '08123456789'],
        ['key' => 'currency_symbol', 'value' => 'Rp'],
    ];
    $this->db->table('settings')->insertBatch($settings);

    // Kategori awal
    $categories = [
        ['name' => 'Makanan',  'created_at' => date('Y-m-d H:i:s')],
        ['name' => 'Minuman',  'created_at' => date('Y-m-d H:i:s')],
        ['name' => 'Snack',    'created_at' => date('Y-m-d H:i:s')],
    ];
    $this->db->table('categories')->insertBatch($categories);
}
```

Jalankan:
```bash
php spark db:seed InitialSeeder
```

Login pertama: `admin@warung.com` / `admin1234`

> ⚠️ **Penting:** Segera ganti password default setelah pertama kali login!

---

*Dokumen ini akan diperbarui seiring perkembangan proyek. Setiap perubahan requirement wajib didiskusikan terlebih dahulu sebelum implementasi.*
