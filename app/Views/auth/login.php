<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<section class="login-shell">
    <div class="login-hero">
        <div class="auth-logo">
            <span class="brand-mark" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
            </span>
            <strong><?= esc(setting('store_name', 'Warung Sederhana')) ?></strong>
        </div>

        <div class="login-copy">
            <span class="login-kicker">POS Warungku</span>
            <h1>Kelola transaksi warung dari satu dashboard.</h1>
            <p>Catat penjualan, pantau transaksi harian, dan siapkan data master produk dengan akses aman untuk admin dan kasir.</p>
        </div>

        <div class="login-preview" aria-hidden="true">
            <div class="preview-toolbar">
                <span></span><span></span><span></span>
            </div>
            <div class="preview-stat">
                <small>Penjualan hari ini</small>
                <strong>Rp 0</strong>
            </div>
            <div class="preview-list">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>

    <div class="auth-card">
        <div class="auth-heading">
            <span class="login-kicker">Masuk akun</span>
            <h2>Selamat datang kembali</h2>
            <p>Gunakan akun yang dibuat oleh admin untuk masuk ke aplikasi.</p>
        </div>

        <form method="post" action="<?= site_url('login') ?>" class="form-stack">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="form-input" type="email" id="email" name="email" value="<?= esc(old('email')) ?>" placeholder="admin@warung.com" autocomplete="email" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Masuk ke Dashboard</button>
        </form>

        <p class="auth-footnote">Belum punya akun? Hubungi admin untuk dibuatkan akses.</p>
    </div>
</section>
<?= $this->endSection() ?>
