# Requirements - POS Warung Sederhana

Sumber: `prd.md` versi 1.0.0, 14 Juni 2026.

## Scope

### In Scope

- Auth login dan logout.
- Otorisasi berbasis role admin dan kasir.
- Dashboard ringkas.
- Manajemen produk.
- Manajemen kategori.
- Halaman kasir atau POS.
- Penyimpanan transaksi dan item transaksi.
- Riwayat transaksi.
- Manajemen user.
- Setting toko.
- Validasi form.
- Security dasar aplikasi web.

### Out of Scope

- Inventory lengkap.
- Multi-cabang.
- Integrasi printer struk hardware.
- Pembayaran non-tunai seperti QRIS atau transfer.
- Aplikasi mobile.

## Functional Requirements

### Auth

- User dapat login menggunakan email dan password.
- Sistem menolak login jika email tidak ditemukan.
- Sistem menolak login jika password salah.
- Sistem menolak login jika `is_active = 0`.
- Sistem melakukan session regenerate setelah login sukses.
- Sistem menyimpan `user_id`, `user_name`, `user_role`, dan `logged_in` di session.
- User dapat logout dan session harus dihancurkan.
- Halaman selain login harus membutuhkan session login.

### Dashboard

- Admin dapat melihat total penjualan hari ini.
- Admin dapat melihat jumlah transaksi hari ini.
- Admin dapat melihat jumlah produk aktif.
- Admin dapat melihat jumlah kategori.
- Admin dapat melihat lima transaksi terakhir hari ini.
- Kasir dapat melihat total penjualan miliknya hari ini.
- Kasir dapat melihat jumlah transaksi miliknya hari ini.
- Kasir mendapat shortcut ke halaman POS.

### Produk

- Admin dapat melihat daftar produk.
- Admin dapat mencari produk berdasarkan nama.
- Admin dapat memfilter produk berdasarkan kategori.
- Daftar produk memakai pagination 10 data per halaman.
- Admin dapat membuat produk.
- Admin dapat mengedit produk.
- Admin dapat menghapus produk jika belum pernah dipakai transaksi.
- Jika produk sudah pernah dipakai transaksi, sistem harus menolak hapus atau menjadikannya nonaktif.
- Admin dapat toggle status aktif/nonaktif produk.
- Produk dapat memiliki gambar opsional.
- Produk nonaktif tidak ditampilkan sebagai produk yang bisa dijual di POS.

### Kategori

- Admin dapat melihat daftar kategori.
- Admin dapat membuat kategori.
- Admin dapat mengedit kategori.
- Admin dapat menghapus kategori jika tidak sedang dipakai produk.
- Sistem menolak penghapusan kategori yang masih dipakai produk.

### POS dan Transaksi

- Admin dan kasir dapat membuka halaman POS.
- POS hanya menampilkan produk aktif.
- User dapat mencari dan memfilter produk di POS.
- User dapat menambahkan produk ke keranjang.
- User dapat mengubah qty item di keranjang.
- User dapat menghapus item dari keranjang.
- Sistem menghitung subtotal per item.
- Sistem menghitung total transaksi.
- Sistem menghitung kembalian otomatis.
- Sistem menolak transaksi dengan keranjang kosong.
- Sistem menolak transaksi jika pembayaran kurang dari total.
- Sistem membuat `invoice_no` unik.
- Sistem menyimpan data ke `transactions` dan `transaction_items`.
- Item transaksi harus menyimpan snapshot `product_name` dan `price`.
- Sistem menampilkan struk ringkas setelah transaksi berhasil.

### Riwayat Transaksi

- Admin dapat melihat semua riwayat transaksi.
- Kasir hanya dapat melihat riwayat transaksi miliknya.
- User dapat memfilter riwayat berdasarkan tanggal.
- User dapat melihat detail transaksi.

### User Management

- Admin dapat melihat daftar user.
- Admin dapat membuat user baru.
- Admin dapat mengedit data user.
- Admin dapat mengubah status aktif user.
- Admin dapat mengubah role user.
- Sistem harus mencegah admin menonaktifkan akun sendiri.
- Sistem harus menyimpan password dengan hash bcrypt.

### Setting

- Admin dapat melihat setting toko.
- Admin dapat mengubah nama toko.
- Admin dapat mengubah alamat toko.
- Admin dapat mengubah nomor telepon.
- Admin dapat mengubah simbol mata uang.
- Setting dipakai di layout dan helper aplikasi.

## Data Requirements

- Database menggunakan SQLite.
- File database berada di `writable/db/warung.db`.
- Semua tabel dibuat lewat migration CI4.
- Data awal dibuat lewat seeder.
- Seeder minimal berisi:
  - user admin pertama,
  - setting default,
  - kategori awal.

## Validation Requirements

| Form | Field | Rule |
|---|---|---|
| Login | email | required, valid_email |
| Login | password | required |
| Produk | name | required, min_length 2, max_length 100 |
| Produk | category_id | required, kategori harus ada |
| Produk | price | required, numeric, >= 0 |
| Produk | stock | required, integer, >= 0 |
| Produk | image | optional, image, max 1MB, jpg/png/webp |
| Kategori | name | required, min_length 2, max_length 50, unique |
| User | name | required, min_length 2, max_length 100 |
| User | email | required, valid_email, unique |
| User | password | required saat create, min_length 8 |
| User | password_confirm | matches password |
| User | role | required, admin atau kasir |
| Transaksi | items | tidak boleh kosong |
| Transaksi | payment | harus >= total |

## Security Requirements

- CSRF protection aktif.
- Semua form POST memakai `csrf_field()`.
- AJAX POS mengirim token CSRF.
- Password wajib memakai `password_hash()` dan `password_verify()`.
- Output data user di view wajib memakai `esc()`.
- Query memakai Query Builder atau parameter binding.
- Session ID wajib regenerate setelah login.
- Upload file hanya menerima jpeg, png, dan webp.
- Nama file upload harus acak.
- Ukuran gambar maksimal 1MB.
- Controller tetap melakukan double-check untuk operasi sensitif.

## Access Requirements

| Halaman | Admin | Kasir |
|---|---|---|
| Login | Ya | Ya |
| Dashboard | Ya | Ya, terbatas |
| POS | Ya | Ya |
| Riwayat Transaksi | Ya | Ya, miliknya |
| Produk | Ya | Tidak |
| Kategori | Ya | Tidak |
| User Management | Ya | Tidak |
| Setting | Ya | Tidak |

## Non-Functional Requirements

- Aplikasi harus berjalan di PHP 8.1+.
- Aplikasi bisa dijalankan dengan `php spark serve`.
- UI harus sederhana, cepat dipahami, dan konsisten.
- Kode harus junior-friendly.
- Implementasi dilakukan bertahap per fase.
- Tidak boleh ada debug output di mode production.

