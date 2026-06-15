# Tasks - POS Warung Sederhana

Sumber: `prd.md` versi 1.0.0, 14 Juni 2026.

Gunakan checklist ini sebagai backlog implementasi. Selesaikan fase secara berurutan.

## Fase 1 - Setup dan Fondasi

- [x] Konfigurasi `.env` untuk development.
- [x] Konfigurasi SQLite.
- [x] Buat folder `writable/db`.
- [x] Pastikan file database `writable/db/warung.db` tersedia atau bisa dibuat.
- [x] Buat migration tabel `users`.
- [x] Buat migration tabel `categories`.
- [x] Buat migration tabel `products`.
- [x] Buat migration tabel `transactions`.
- [x] Buat migration tabel `transaction_items`.
- [x] Buat migration tabel `settings`.
- [x] Jalankan `php spark migrate`.
- [x] Buat `InitialSeeder`.
- [x] Seed user admin pertama.
- [x] Seed setting default.
- [x] Seed kategori awal.
- [x] Jalankan `php spark db:seed InitialSeeder`.
- [x] Buat helper `app_helper.php`.
- [x] Tambahkan helper `url`, `form`, dan `app` ke autoload.
- [x] Buat folder `public/assets/css`.
- [x] Buat folder `public/assets/js`.
- [x] Buat folder `public/assets/img/products`.
- [x] Buat `public/assets/css/app.css`.
- [x] Buat `public/assets/js/app.js`.
- [x] Buat layout `app/Views/layouts/main.php`.
- [x] Buat layout `app/Views/layouts/auth.php`.
- [x] Buat partial `sidebar.php`.
- [x] Buat partial `topbar.php`.
- [x] Buat partial `alerts.php`.
- [x] Test `php spark serve` berjalan tanpa error.

## Fase 2 - Auth dan Keamanan

- [x] Buat `UserModel`.
- [x] Buat `AuthController`.
- [x] Implementasikan method `login`.
- [x] Implementasikan method `processLogin`.
- [x] Implementasikan method `logout`.
- [x] Buat view `auth/login.php`.
- [x] Buat `AuthFilter`.
- [x] Buat `RoleFilter`.
- [x] Daftarkan filter di `app/Config/Filters.php`.
- [x] Definisikan route login dan logout.
- [x] Lindungi route utama dengan filter `auth`.
- [x] Lindungi route admin dengan filter `role:admin`.
- [x] Aktifkan CSRF protection.
- [x] Test login sukses redirect ke dashboard.
- [x] Test login gagal menampilkan error.
- [x] Test user nonaktif tidak bisa login.
- [x] Test akses dashboard tanpa login redirect ke login.
- [x] Test kasir tidak bisa akses route admin.

## Desain UI - Light Dashboard

- [x] Ubah tema dari dark grayscale ke light grayscale sesuai `inpirasi.html`.
- [x] Buat sidebar fixed putih dengan ikon dan search box.
- [x] Buat topbar sticky dengan breadcrumb dan action.
- [x] Ubah dashboard menjadi KPI cards putih dan tabel bersih.
- [x] Ubah login menjadi card putih terpusat.
- [x] Terapkan komponen light theme ke halaman CRUD setelah Fase 3 dibuat.

## Fase 3 - CRUD Master Data

### Kategori

- [x] Buat `CategoryModel`.
- [x] Buat `CategoryController` placeholder.
- [x] Implementasikan `CategoryController` CRUD penuh.
- [x] Implementasikan `index`.
- [x] Implementasikan `store`.
- [x] Implementasikan `update`.
- [x] Implementasikan `delete`.
- [x] Buat view `category/index.php`.
- [x] Validasi nama kategori.
- [x] Cegah hapus kategori yang masih dipakai produk.
- [x] Test CRUD kategori sebagai admin.

### Produk

- [x] Buat `ProductModel`.
- [x] Buat `ProductController` placeholder.
- [x] Implementasikan `ProductController` CRUD penuh.
- [x] Implementasikan `index`.
- [x] Implementasikan filter kategori.
- [x] Implementasikan search nama produk.
- [x] Implementasikan pagination.
- [x] Implementasikan filter produk stok rendah.
- [x] Implementasikan `create`.
- [x] Implementasikan `store`.
- [x] Implementasikan upload gambar.
- [x] Implementasikan `edit`.
- [x] Implementasikan `update`.
- [x] Implementasikan `delete`.
- [x] Implementasikan `toggle`.
- [x] Implementasikan halaman detail produk.
- [x] Tambahkan field barcode unik produk.
- [x] Implementasikan auto-generate barcode jika field kosong.
- [x] Implementasikan preview barcode di form, list, dan detail produk.
- [x] Implementasikan cetak label barcode dari detail produk.
- [x] Implementasikan import CSV produk dengan kolom barcode opsional.
- [x] Implementasikan export CSV produk sesuai filter aktif.
- [x] Buat view `product/index.php`.
- [x] Buat view `product/create.php`.
- [x] Buat view `product/edit.php`.
- [x] Buat view `product/detail.php`.
- [x] Validasi semua field produk.
- [x] Validasi barcode maksimal 64 karakter dan unique.
- [x] Cegah hapus produk yang sudah pernah masuk transaksi.
- [x] Test CRUD produk sebagai admin.

### User

- [x] Buat `UserController` placeholder.
- [x] Implementasikan `UserController` CRUD penuh.
- [x] Implementasikan `index`.
- [x] Implementasikan `create`.
- [x] Implementasikan `store`.
- [x] Implementasikan `edit`.
- [x] Implementasikan `update`.
- [x] Implementasikan `toggle`.
- [x] Buat view `user/index.php`.
- [x] Buat view `user/create.php`.
- [x] Buat view `user/edit.php`.
- [x] Validasi nama, email, password, konfirmasi password, role, dan status.
- [x] Hash password sebelum simpan.
- [x] Cegah admin menonaktifkan akun sendiri.
- [x] Test CRUD user sebagai admin.

### Setting

- [x] Buat `SettingModel`.
- [x] Buat `SettingController` placeholder.
- [x] Implementasikan `SettingController` penuh.
- [x] Implementasikan `index`.
- [x] Implementasikan `update`.
- [x] Buat view `setting/index.php`.
- [x] Validasi setting.
- [x] Test update setting sebagai admin.

## Fase 4 - POS dan Transaksi

- [x] Buat `TransactionModel`.
- [x] Buat `TransactionItemModel`.
- [x] Buat `TransactionController` placeholder.
- [x] Implementasikan `TransactionController` transaksi penuh.
- [x] Implementasikan method `pos`.
- [x] Implementasikan method `store`.
- [x] Implementasikan method `history`.
- [x] Implementasikan method `detail`.
- [x] Buat view `transaction/pos.php`.
- [x] Buat layout POS 2 kolom.
- [x] Tampilkan produk aktif saja.
- [x] Buat search produk di POS.
- [x] Buat search produk berdasarkan nama atau barcode di POS.
- [x] Buat input scan barcode di POS.
- [x] Implementasikan endpoint lookup barcode POS.
- [x] Buat filter kategori di POS.
- [x] Implementasikan keranjang dengan vanilla JS.
- [x] Implementasikan keranjang tersimpan di localStorage saat pindah halaman.
- [x] Implementasikan tambah item ke keranjang.
- [x] Implementasikan tambah item ke keranjang dari scan barcode.
- [x] Implementasikan ubah qty item.
- [x] Implementasikan hapus item.
- [x] Implementasikan kosongkan semua isi keranjang.
- [x] Hitung subtotal, total, pembayaran, dan kembalian otomatis.
- [x] Sarankan nominal pembayaran berdasarkan pecahan rupiah terdekat.
- [x] Kirim transaksi via AJAX atau POST biasa.
- [x] Sertakan CSRF token pada request transaksi.
- [x] Validasi keranjang tidak kosong.
- [x] Validasi pembayaran cukup.
- [x] Generate `invoice_no` unik.
- [x] Simpan transaksi dalam database transaction.
- [x] Simpan item transaksi dengan snapshot nama dan harga.
- [x] Tampilkan struk ringkas setelah transaksi berhasil.
- [x] Tambahkan opsi cetak struk setelah transaksi.
- [x] Buat view `transaction/history.php`.
- [x] Buat filter riwayat berdasarkan tanggal.
- [x] Buat view `transaction/detail.php`.
- [x] Terapkan aturan admin semua transaksi, kasir transaksi miliknya.
- [x] Test transaksi sebagai admin.
- [x] Test transaksi sebagai kasir.

## Fase 5 - Dashboard dan Polish

- [x] Buat `DashboardController`.
- [x] Query total penjualan hari ini.
- [x] Query jumlah transaksi hari ini.
- [x] Query jumlah produk aktif.
- [x] Query jumlah produk stok rendah.
- [x] Query jumlah kategori.
- [x] Query lima transaksi terakhir.
- [x] Buat dashboard admin.
- [x] Buat dashboard kasir.
- [x] Buat view `dashboard/index.php`.
- [x] Redesign dashboard dengan hero ringkas, KPI responsif, transaksi terakhir, stok rendah, dan akses cepat.
- [x] Pastikan sidebar menampilkan menu sesuai role.
- [x] Pastikan flash message tampil konsisten.
- [x] Pastikan semua halaman punya title.
- [x] Review semua form POST memiliki CSRF token.
- [x] Review semua output dinamis memakai `esc()`.
- [x] Test validasi form kosong.
- [x] Test akses role secara manual.
- [x] Test upload gambar valid dan invalid.
- [x] Test mode production tidak menampilkan debug output.
- [x] Set `CI_ENVIRONMENT = production` untuk konfigurasi production.
- [x] Seed katalog demo 10 kategori dan 1000 produk.
- [x] Pastikan header menampilkan jumlah item keranjang.
- [x] Tampilkan KPI dan tabel produk stok rendah di dashboard admin.

## Fase 6 - Laporan Sederhana

- [x] Buat `ReportController`.
- [x] Buat route `/report`.
- [x] Buat route `/report/export`.
- [x] Buat view `report/index.php`.
- [x] Buat filter laporan berdasarkan rentang tanggal.
- [x] Tampilkan ringkasan omzet, transaksi, item terjual, dan rata-rata transaksi.
- [x] Tampilkan agregasi penjualan harian.
- [x] Tampilkan top 10 produk terlaris.
- [x] Terapkan aturan admin semua transaksi, kasir transaksi miliknya.
- [x] Tambahkan menu Laporan di sidebar.
- [x] Implementasikan export laporan CSV.

## Acceptance Checklist

- [x] Admin bisa login.
- [x] Kasir bisa login.
- [x] User nonaktif tidak bisa login.
- [x] Admin bisa mengelola produk.
- [x] Admin bisa mengelola barcode produk.
- [x] Admin bisa mengelola kategori.
- [x] Admin bisa mengelola user.
- [x] Admin bisa mengubah setting.
- [x] Kasir tidak bisa membuka menu admin.
- [x] Admin dan kasir bisa membuat transaksi.
- [x] Admin dan kasir bisa menambahkan produk lewat scan barcode.
- [x] Total transaksi dan kembalian benar.
- [x] Riwayat transaksi tersimpan.
- [x] Detail transaksi menampilkan snapshot nama dan harga produk.
- [x] Dashboard menampilkan data sesuai role.
- [x] Tidak ada password plain text di database.
- [x] Tidak ada query raw dengan string concatenation dari input user.
- [x] Tidak ada output user-generated tanpa `esc()`.
