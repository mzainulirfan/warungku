# Rules - POS Warung Sederhana

Sumber: `prd.md` versi 1.0.0, 14 Juni 2026.

Dokumen ini berisi aturan implementasi yang wajib diikuti selama pengembangan.

## Urutan Pengerjaan

- Kerjakan fase secara berurutan: setup, auth, CRUD, POS, dashboard/polish.
- Jangan mengerjakan POS sebelum auth dan master data dasar selesai.
- Jangan menambah fitur di luar scope tanpa persetujuan.
- Setiap perubahan requirement harus didiskusikan sebelum implementasi.

## Scope Rules

Yang boleh dikerjakan:

- Produk dan kategori.
- Transaksi penjualan.
- Laporan harian sederhana.
- User management.
- Setting toko.
- Auth dan role.

Yang tidak dikerjakan di versi ini:

- Inventory lengkap.
- Multi-cabang.
- Printer struk hardware.
- QRIS, transfer, atau pembayaran non-tunai.
- Aplikasi mobile.

## CodeIgniter Rules

- Gunakan pola MVC CI4.
- Route didefinisikan di `app/Config/Routes.php`.
- Gunakan controller untuk alur request dan response.
- Gunakan model untuk akses database.
- Gunakan view untuk tampilan saja.
- Gunakan migration untuk struktur database.
- Gunakan seeder untuk data awal.
- Gunakan helper untuk fungsi lintas halaman seperti `rupiah()` dan `setting()`.

## Naming Rules

| Item | Aturan | Contoh |
|---|---|---|
| Controller | PascalCase + Controller | `ProductController` |
| Model | PascalCase + Model | `ProductModel` |
| View folder | snake_case | `transaction/` |
| View file | snake_case | `create.php` |
| Route | kebab-case atau singular sesuai PRD | `/product/create` |
| Table | snake_case plural | `transaction_items` |
| CSS class | kebab-case | `.btn-primary` |
| JS variable | camelCase | `cartItems` |
| PHP variable | camelCase | `$totalSales` |

## Database Rules

- Database default adalah SQLite.
- Semua tabel harus dibuat lewat migration.
- Timestamp memakai kolom `created_at` dan `updated_at` sesuai CI4.
- `users.email` harus unique.
- `categories.name` harus unique.
- `transactions.invoice_no` harus unique.
- `transaction_items.product_name` dan `transaction_items.price` wajib menyimpan snapshot saat transaksi.
- Produk yang sudah dipakai transaksi tidak boleh dihapus permanen tanpa pertimbangan histori.
- Kategori yang masih dipakai produk tidak boleh dihapus.

## Auth Rules

- Tidak ada register publik.
- Akun dibuat oleh admin.
- Login memakai email dan password.
- User dengan `is_active = 0` tidak boleh login.
- Setelah login sukses, panggil `session()->regenerate(true)`.
- Session login hanya menyimpan data minimal:
  - `user_id`
  - `user_name`
  - `user_role`
  - `logged_in`
- Logout harus menghancurkan session.

## Authorization Rules

- Semua halaman selain login wajib memakai filter auth.
- Route admin wajib memakai filter role admin.
- Admin punya akses penuh.
- Kasir hanya boleh akses dashboard terbatas, POS, dan riwayat transaksinya sendiri.
- Controller tetap wajib melakukan pengecekan tambahan untuk operasi sensitif.
- Admin tidak boleh menonaktifkan akunnya sendiri.

## Validation Rules

- Semua input dari form wajib divalidasi di controller.
- Jika validasi gagal, redirect kembali dengan input lama dan error.
- Harga tidak boleh negatif.
- Stok tidak boleh negatif.
- Qty transaksi minimal 1.
- Pembayaran harus lebih besar atau sama dengan total.
- Keranjang transaksi tidak boleh kosong.
- Role user hanya boleh `admin` atau `kasir`.
- Password user minimal 8 karakter.

## Security Rules

- CSRF protection wajib aktif.
- Semua form POST wajib memakai `csrf_field()`.
- AJAX wajib mengirim CSRF token.
- Password wajib disimpan dengan `password_hash()`.
- Verifikasi password wajib memakai `password_verify()`.
- Jangan pernah menyimpan password plain text.
- Jangan gunakan MD5 atau SHA1 untuk password.
- Semua output dinamis dari user wajib memakai `esc()`.
- Query database wajib memakai Query Builder atau parameter binding.
- Jangan membuat SQL dengan concatenation input user.
- Jangan menampilkan debug output di production.

## File Upload Rules

- Upload gambar produk bersifat opsional.
- Format yang diterima hanya jpg, png, dan webp.
- Ukuran maksimal 1MB.
- Jangan memakai nama asli file dari user.
- Gunakan nama acak dari CI4 seperti `getRandomName()`.
- Simpan file produk di `public/assets/img/products`.
- Simpan hanya nama file di database.

## UI Rules

- Tema utama light grayscale sesuai referensi `inpirasi.html`.
- Gunakan CSS custom di `public/assets/css/app.css`.
- Gunakan layout utama dengan sidebar dan topbar.
- Gunakan layout auth terpisah untuk login.
- Gunakan sidebar fixed putih, topbar sticky, background abu muda, dan card putih.
- Gunakan radius kecil sampai medium, default 8px dan card 12px.
- UI harus konsisten di semua halaman.
- Komponen minimal yang harus tersedia:
  - button,
  - card,
  - alert,
  - badge,
  - table,
  - form input.
- Sidebar harus menampilkan menu sesuai role.
- Flash message harus tampil untuk success dan error.
- Jangan memakai Tailwind CDN di aplikasi CI4; referensi `inpirasi.html` hanya dipakai sebagai inspirasi visual.

## Setup Lokal Rules

- `.env` tidak boleh dipush ke Git karena berisi konfigurasi lokal.
- Developer baru perlu copy `env` menjadi `.env`.
- Untuk Windows lokal proyek ini, SQLite memakai path database absolut di `.env`.
- File database `writable/db/warung.db` adalah artefak runtime dan tidak dipush.

## POS Rules

- POS hanya menampilkan produk aktif.
- Item keranjang harus berasal dari produk yang valid.
- Total dihitung dari subtotal seluruh item.
- Subtotal dihitung dari harga snapshot dikali qty.
- Kembalian dihitung dari pembayaran dikurangi total.
- Transaksi tidak boleh disimpan jika pembayaran kurang.
- `invoice_no` harus unik.
- Penyimpanan transaksi dan item harus dilakukan dalam database transaction.
- Jika penyimpanan item gagal, header transaksi tidak boleh tersimpan sendiri.

## Testing Rules

- Test login sukses dan gagal.
- Test user nonaktif.
- Test akses tanpa login.
- Test akses kasir ke route admin.
- Test semua CRUD admin.
- Test validasi form kosong.
- Test upload gambar valid dan invalid.
- Test transaksi normal.
- Test transaksi dengan keranjang kosong.
- Test transaksi dengan pembayaran kurang.
- Test dashboard admin dan kasir.
- Test CSRF pada semua form POST.
