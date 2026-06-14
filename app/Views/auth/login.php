<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<section class="auth-card">
    <div class="auth-logo">
        <span class="brand-mark" aria-hidden="true">
            <span></span><span></span><span></span><span></span>
        </span>
        <strong><?= esc(setting('store_name', 'Warung Sederhana')) ?></strong>
    </div>
    <div class="auth-heading">
        <h1>Masuk</h1>
        <p>Gunakan akun yang dibuat oleh admin.</p>
    </div>

    <form method="post" action="<?= site_url('login') ?>" class="form-stack">
        <?= csrf_field() ?>

        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input class="form-input" type="email" id="email" name="email" value="<?= esc(old('email')) ?>" autocomplete="email" required>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input class="form-input" type="password" id="password" name="password" autocomplete="current-password" required>
        </div>

        <button class="btn btn-primary" type="submit">Masuk</button>
    </form>
</section>
<?= $this->endSection() ?>
