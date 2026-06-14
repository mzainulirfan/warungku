# Warungku - POS Warung Sederhana

Warungku adalah aplikasi POS sederhana berbasis CodeIgniter 4 untuk warung makan, toko kecil, atau kedai. Fokus awal aplikasi ini adalah login berbasis role, fondasi database SQLite, dashboard ringkas, dan struktur fitur untuk kasir, produk, kategori, user, serta setting.

## Status Saat Ini

Sudah tersedia:

- CodeIgniter 4 app starter.
- SQLite database schema lewat migration.
- Seeder awal untuk admin, setting toko, dan kategori.
- Login/logout admin.
- Auth filter dan role filter.
- Dashboard awal dengan light dashboard UI.
- Placeholder halaman POS, transaksi, produk, kategori, user, dan setting.
- Dokumentasi PRD dan breakdown di folder `docs/`.

Belum tersedia:

- CRUD penuh produk, kategori, user, dan setting.
- POS transaksi penuh.
- Riwayat dan detail transaksi.
- Laporan/dashboard final.

## Tech Stack

- PHP 8.2+
- CodeIgniter 4.7+
- SQLite3
- CI4 native views
- Custom CSS light grayscale theme
- Vanilla JavaScript

## Setup Lokal

Install dependency:

```bash
composer install
```

Copy file environment:

```bash
cp env .env
```

Untuk Windows PowerShell:

```powershell
Copy-Item env .env
```

Edit `.env`:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'

database.default.database = D:\dev\php\ci4\warungku\writable\db\warung.db
database.default.DBDriver = SQLite3
database.default.DBPrefix =
database.default.foreignKeys = true
database.default.busyTimeout = 1000
```

Sesuaikan path `database.default.database` dengan lokasi project lokal masing-masing. File `.env` tidak dipush ke Git.

Siapkan database:

```bash
mkdir -p writable/db
php spark migrate
php spark db:seed InitialSeeder
```

Untuk Windows PowerShell:

```powershell
New-Item -ItemType Directory -Force writable\db
php spark migrate
php spark db:seed InitialSeeder
```

Jalankan server:

```bash
php spark serve --host 127.0.0.1 --port 8080
```

Buka:

```text
http://localhost:8080/login
```

## Login Awal

```text
Email: admin@warung.com
Password: admin1234
```

Ganti password default setelah fitur user management tersedia.

## Struktur Dokumentasi

- `prd.md`: Product Requirements Document utama.
- `docs/desain.md`: desain aplikasi, database, UI, dan routing.
- `docs/requirements.md`: functional dan non-functional requirements.
- `docs/tasks.md`: checklist implementasi per fase.
- `docs/rules.md`: aturan implementasi, security, UI, dan setup lokal.
- `inpirasi.html`: referensi visual light dashboard.

## Perintah Verifikasi

Lint PHP:

```bash
Get-ChildItem -Recurse -Filter *.php app | ForEach-Object { php -l $_.FullName }
```

Lihat route:

```bash
php spark routes
```

Reset database lokal dari awal:

```bash
php spark migrate:refresh
php spark db:seed InitialSeeder
```

## Catatan Git

File berikut tidak dipush:

- `.env`
- `vendor/`
- `writable/db/warung.db`
- runtime cache/log/session/debugbar di `writable/`

Database dibuat ulang melalui migration dan seeder.
