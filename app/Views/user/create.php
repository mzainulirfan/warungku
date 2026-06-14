<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Tambah User</h2>
        <p>Buat akun baru untuk admin atau kasir.</p>
    </div>
    <a class="btn btn-outline" href="<?= site_url('user') ?>">Kembali</a>
</section>

<?= view('user/form', [
    'action'     => site_url('user/store'),
    'user'       => $user,
    'submitText' => 'Tambah User',
]) ?>
<?= $this->endSection() ?>
