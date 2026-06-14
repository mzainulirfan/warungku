<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Edit User</h2>
        <p>Perbarui profil, role, status, atau password user.</p>
    </div>
    <a class="btn btn-outline" href="<?= site_url('user') ?>">Kembali</a>
</section>

<?= view('user/form', [
    'action'     => site_url('user/update/' . $user->id),
    'user'       => $user,
    'submitText' => 'Simpan Perubahan',
]) ?>
<?= $this->endSection() ?>
