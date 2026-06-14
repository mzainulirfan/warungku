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
- [ ] Test user nonaktif tidak bisa login.
- [x] Test akses dashboard tanpa login redirect ke login.
- [ ] Test kasir tidak bisa akses route admin.

## Desain UI - Light Dashboard

- [x] Ubah tema dari dark grayscale ke light grayscale sesuai `inpirasi.html`.
- [x] Buat sidebar fixed putih dengan ikon dan search box.
- [x] Buat topbar sticky dengan breadcrumb dan action.
- [x] Ubah dashboard menjadi KPI cards putih dan tabel bersih.
- [x] Ubah login menjadi card putih terpusat.
- [ ] Terapkan komponen light theme ke halaman CRUD setelah Fase 3 dibuat.

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

- [ ] Buat `ProductModel`.
- [x] Buat `ProductController` placeholder.
- [ ] Implementasikan `ProductController` CRUD penuh.
- [ ] Implementasikan `index`.
- [ ] Implementasikan filter kategori.
- [ ] Implementasikan search nama produk.
- [ ] Implementasikan pagination.
- [ ] Implementasikan `create`.
- [ ] Implementasikan `store`.
- [ ] Implementasikan upload gambar.
- [ ] Implementasikan `edit`.
- [ ] Implementasikan `update`.
- [ ] Implementasikan `delete`.
- [ ] Implementasikan `toggle`.
- [ ] Buat view `product/index.php`.
- [ ] Buat view `product/create.php`.
- [ ] Buat view `product/edit.php`.
- [ ] Validasi semua field produk.
- [ ] Cegah hapus produk yang sudah pernah masuk transaksi.
- [ ] Test CRUD produk sebagai admin.

### User

- [x] Buat `UserController` placeholder.
- [ ] Implementasikan `UserController` CRUD penuh.
- [ ] Implementasikan `index`.
- [ ] Implementasikan `create`.
- [ ] Implementasikan `store`.
- [ ] Implementasikan `edit`.
- [ ] Implementasikan `update`.
- [ ] Implementasikan `toggle`.
- [ ] Buat view `user/index.php`.
- [ ] Buat view `user/create.php`.
- [ ] Buat view `user/edit.php`.
- [ ] Validasi nama, email, password, konfirmasi password, role, dan status.
- [ ] Hash password sebelum simpan.
- [ ] Cegah admin menonaktifkan akun sendiri.
- [ ] Test CRUD user sebagai admin.

### Setting

- [ ] Buat `SettingModel`.
- [x] Buat `SettingController` placeholder.
- [ ] Implementasikan `SettingController` penuh.
- [ ] Implementasikan `index`.
- [ ] Implementasikan `update`.
- [ ] Buat view `setting/index.php`.
- [ ] Validasi setting.
- [ ] Test update setting sebagai admin.

## Fase 4 - POS dan Transaksi

- [ ] Buat `TransactionModel`.
- [ ] Buat `TransactionItemModel`.
- [x] Buat `TransactionController` placeholder.
- [ ] Implementasikan `TransactionController` transaksi penuh.
- [ ] Implementasikan method `pos`.
- [ ] Implementasikan method `store`.
- [ ] Implementasikan method `history`.
- [ ] Implementasikan method `detail`.
- [ ] Buat view `transaction/pos.php`.
- [ ] Buat layout POS 2 kolom.
- [ ] Tampilkan produk aktif saja.
- [ ] Buat search produk di POS.
- [ ] Buat filter kategori di POS.
- [ ] Implementasikan keranjang dengan vanilla JS.
- [ ] Implementasikan tambah item ke keranjang.
- [ ] Implementasikan ubah qty item.
- [ ] Implementasikan hapus item.
- [ ] Hitung subtotal, total, pembayaran, dan kembalian otomatis.
- [ ] Kirim transaksi via AJAX atau POST biasa.
- [ ] Sertakan CSRF token pada request transaksi.
- [ ] Validasi keranjang tidak kosong.
- [ ] Validasi pembayaran cukup.
- [ ] Generate `invoice_no` unik.
- [ ] Simpan transaksi dalam database transaction.
- [ ] Simpan item transaksi dengan snapshot nama dan harga.
- [ ] Tampilkan struk ringkas setelah transaksi berhasil.
- [ ] Buat view `transaction/history.php`.
- [ ] Buat filter riwayat berdasarkan tanggal.
- [ ] Buat view `transaction/detail.php`.
- [ ] Terapkan aturan admin semua transaksi, kasir transaksi miliknya.
- [ ] Test transaksi sebagai admin.
- [ ] Test transaksi sebagai kasir.

## Fase 5 - Dashboard dan Polish

- [x] Buat `DashboardController`.
- [x] Query total penjualan hari ini.
- [x] Query jumlah transaksi hari ini.
- [x] Query jumlah produk aktif.
- [x] Query jumlah kategori.
- [x] Query lima transaksi terakhir.
- [x] Buat dashboard admin.
- [x] Buat dashboard kasir.
- [x] Buat view `dashboard/index.php`.
- [x] Pastikan sidebar menampilkan menu sesuai role.
- [x] Pastikan flash message tampil konsisten.
- [x] Pastikan semua halaman punya title.
- [ ] Review semua form POST memiliki CSRF token.
- [ ] Review semua output dinamis memakai `esc()`.
- [ ] Test validasi form kosong.
- [ ] Test akses role secara manual.
- [ ] Test upload gambar valid dan invalid.
- [ ] Test mode production tidak menampilkan debug output.
- [ ] Set `CI_ENVIRONMENT = production` untuk konfigurasi production.

## Acceptance Checklist

- [x] Admin bisa login.
- [ ] Kasir bisa login.
- [ ] User nonaktif tidak bisa login.
- [ ] Admin bisa mengelola produk.
- [ ] Admin bisa mengelola kategori.
- [ ] Admin bisa mengelola user.
- [ ] Admin bisa mengubah setting.
- [ ] Kasir tidak bisa membuka menu admin.
- [ ] Admin dan kasir bisa membuat transaksi.
- [ ] Total transaksi dan kembalian benar.
- [ ] Riwayat transaksi tersimpan.
- [ ] Detail transaksi menampilkan snapshot nama dan harga produk.
- [x] Dashboard menampilkan data sesuai role.
- [x] Tidak ada password plain text di database.
- [ ] Tidak ada query raw dengan string concatenation dari input user.
- [ ] Tidak ada output user-generated tanpa `esc()`.
