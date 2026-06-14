# Desain - POS Warung Sederhana

Sumber: `prd.md` versi 1.0.0, 14 Juni 2026.

## Ringkasan Produk

POS Warung Sederhana adalah aplikasi web kasir berbasis CodeIgniter 4 untuk usaha kecil seperti warung makan, toko kelontong, atau kedai kopi.

Tujuan utama:

- Mencatat transaksi penjualan.
- Mengelola produk dan kategori.
- Menampilkan laporan penjualan harian.
- Mengatur akses berdasarkan role admin dan kasir.

## Aktor

| Role | Deskripsi | Akses Utama |
|---|---|---|
| Admin | Pemilik atau pengelola usaha | Semua fitur |
| Kasir | Karyawan yang melayani transaksi | Dashboard terbatas, POS, riwayat transaksi miliknya |

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | CodeIgniter 4 |
| Database | SQLite via PDO/SQLite3 |
| View | CI4 native PHP view |
| CSS | Custom CSS light grayscale theme |
| JavaScript | Vanilla JS, Alpine.js opsional |
| Icon | Lucide Icons via CDN |
| Font | Geist atau Inter |
| PHP | 8.1+ |

## Arsitektur Aplikasi

Aplikasi memakai pola MVC CI4.

- Controller menangani request, validasi awal, redirect, dan pemanggilan model.
- Model menangani akses data dan query yang berulang.
- View hanya untuk tampilan, semua output dari data user wajib memakai `esc()`.
- Filter menangani auth dan otorisasi role.
- Helper menangani fungsi lintas halaman seperti format rupiah dan pembacaan setting.

## Struktur Folder Target

```text
app/
  Config/
    Routes.php
  Controllers/
    AuthController.php
    DashboardController.php
    ProductController.php
    CategoryController.php
    TransactionController.php
    UserController.php
    SettingController.php
  Database/
    Migrations/
    Seeds/
  Filters/
    AuthFilter.php
    RoleFilter.php
  Helpers/
    app_helper.php
  Models/
    UserModel.php
    ProductModel.php
    CategoryModel.php
    TransactionModel.php
    TransactionItemModel.php
    SettingModel.php
  Views/
    layouts/
      main.php
      auth.php
    partials/
      sidebar.php
      topbar.php
      alerts.php
    auth/
    dashboard/
    product/
    category/
    transaction/
    user/
    setting/
public/
  assets/
    css/app.css
    js/app.js
    img/products/
writable/
  db/warung.db
```

## Desain Database

### `users`

Menyimpan akun admin dan kasir.

| Kolom | Tipe | Catatan |
|---|---|---|
| id | integer | primary key |
| name | text | wajib |
| email | text | wajib, unique |
| password | text | bcrypt hash |
| role | text | `admin` atau `kasir` |
| is_active | integer | 1 aktif, 0 nonaktif |
| created_at | text | timestamp |
| updated_at | text | timestamp |

### `categories`

Menyimpan kategori produk.

| Kolom | Tipe | Catatan |
|---|---|---|
| id | integer | primary key |
| name | text | wajib, unique |
| created_at | text | timestamp |
| updated_at | text | timestamp |

### `products`

Menyimpan master produk.

| Kolom | Tipe | Catatan |
|---|---|---|
| id | integer | primary key |
| category_id | integer | nullable, FK ke categories |
| name | text | wajib |
| price | real | default 0 |
| stock | integer | default 0 |
| image | text | nama file gambar opsional |
| is_active | integer | 1 aktif, 0 nonaktif |
| created_at | text | timestamp |
| updated_at | text | timestamp |

### `transactions`

Menyimpan header transaksi.

| Kolom | Tipe | Catatan |
|---|---|---|
| id | integer | primary key |
| invoice_no | text | wajib, unique |
| user_id | integer | kasir yang melayani |
| total_amount | real | total belanja |
| payment_amount | real | uang dibayar |
| change_amount | real | kembalian |
| note | text | opsional |
| created_at | text | timestamp |

### `transaction_items`

Menyimpan item transaksi dengan snapshot nama dan harga produk.

| Kolom | Tipe | Catatan |
|---|---|---|
| id | integer | primary key |
| transaction_id | integer | FK ke transactions |
| product_id | integer | FK ke products |
| product_name | text | snapshot nama produk |
| price | real | snapshot harga saat transaksi |
| qty | integer | jumlah |
| subtotal | real | price x qty |

### `settings`

Menyimpan konfigurasi toko.

| Key | Contoh Value |
|---|---|
| store_name | Warung Sederhana |
| store_address | Jl. Contoh No. 1 |
| store_phone | 08123456789 |
| currency_symbol | Rp |

## Desain Halaman

### Login

- Layout fullscreen.
- Form di tengah.
- Field email dan password.
- Tidak ada register publik.

### Dashboard

Admin melihat:

- Total penjualan hari ini.
- Jumlah transaksi hari ini.
- Jumlah produk aktif.
- Jumlah kategori.
- Lima transaksi terakhir hari ini.

Kasir melihat:

- Total penjualan miliknya hari ini.
- Jumlah transaksi miliknya hari ini.
- Shortcut ke halaman POS.

### Produk

- Tabel produk.
- Filter kategori.
- Search nama produk.
- Pagination 10 data per halaman.
- Aksi tambah, edit, hapus, toggle aktif.
- Upload gambar produk ke `public/assets/img/products`.

### Kategori

- List kategori.
- Form tambah/edit inline atau modal.
- Hapus hanya jika kategori tidak sedang dipakai produk.

### POS

Layout 2 kolom:

- Kiri: daftar produk aktif, search, filter kategori.
- Kanan: keranjang, total, input pembayaran, kembalian, tombol simpan.

Transaksi disimpan lewat controller `TransactionController::store`.

### Riwayat Transaksi

- Daftar transaksi.
- Filter tanggal.
- Admin dapat melihat semua transaksi.
- Kasir hanya melihat transaksi miliknya.
- Detail transaksi menampilkan item dan ringkasan pembayaran.

### User Management

- Admin dapat tambah, edit, dan nonaktifkan user.
- Role hanya `admin` atau `kasir`.
- Admin tidak boleh menonaktifkan akunnya sendiri.

### Setting

- Form pengaturan nama toko, alamat, telepon, dan simbol mata uang.

## Desain UI

Tema visual:

- Light theme grayscale mengikuti referensi `inpirasi.html`.
- Sidebar putih fixed di kiri, topbar sticky, background halaman abu muda.
- Card putih dengan border tipis, radius `12px`, dan bayangan sangat halus.
- Navigation item memakai ikon inline SVG dan active state abu muda.
- Login memakai card putih terpusat dengan identitas toko.
- Radius kecil untuk kontrol form dan button, default `8px`.
- Komponen padat, jelas, dan mudah dipindai.

Komponen wajib:

- Button: primary, outline, danger, small.
- Card.
- Alert success/error.
- Badge aktif/nonaktif.
- Table.
- Form input.
- Layout sidebar + topbar.

Status implementasi desain saat ini:

- Layout utama sudah memakai sidebar fixed dan topbar sticky.
- Dashboard sudah memakai KPI cards dan tabel transaksi terakhir.
- Halaman login sudah mengikuti light card style.
- Halaman POS, transaksi, produk, kategori, user, dan setting masih memakai placeholder sampai fase masing-masing dikerjakan.

## Status Implementasi Saat Ini

Sudah tersedia:

- Konfigurasi SQLite lokal lewat `.env` development.
- Migration untuk tabel inti.
- Seeder awal untuk admin, setting default, dan kategori awal.
- Auth login/logout.
- Filter auth dan role.
- Dashboard awal.
- Placeholder controller untuk POS dan menu admin.

Belum tersedia:

- CRUD kategori, produk, user, dan setting.
- POS transaksi penuh.
- Riwayat dan detail transaksi.
- Dashboard final dengan data produksi lengkap.

## Desain Routing

Route publik:

- `GET /login`
- `POST /login`
- `GET /logout`

Route login wajib:

- `GET /dashboard`
- `GET /pos`
- `POST /pos/store`
- `GET /transaction`
- `GET /transaction/detail/(:num)`

Route admin:

- Produk CRUD.
- Kategori CRUD.
- User management.
- Setting.
