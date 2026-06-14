<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>User Management</h2>
        <p>Kelola akun admin dan kasir yang dapat mengakses POS.</p>
    </div>
    <a class="btn btn-primary" href="<?= site_url('user/create') ?>">Tambah User</a>
</section>

<section class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Daftar User</h3>
            <p>Nonaktifkan akun yang sementara tidak boleh login.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table responsive-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="table-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($users === []): ?>
                        <tr>
                            <td colspan="5" class="empty-cell">Belum ada user.</td>
                        </tr>
                    <?php endif ?>

                    <?php foreach ($users as $user): ?>
                        <?php $confirmMessage = (int) $user->is_active === 1 ? 'Nonaktifkan user ' . $user->name . '?' : 'Aktifkan user ' . $user->name . '?'; ?>
                        <tr>
                            <td data-label="Nama">
                                <div class="user-cell">
                                    <span class="avatar"><?= esc(strtoupper(substr((string) $user->name, 0, 1))) ?></span>
                                    <strong><?= esc($user->name) ?></strong>
                                </div>
                            </td>
                            <td data-label="Email"><?= esc($user->email) ?></td>
                            <td data-label="Role">
                                <span class="badge badge-muted"><?= esc($user->role) ?></span>
                            </td>
                            <td data-label="Status">
                                <span class="badge badge-muted"><?= (int) $user->is_active === 1 ? 'Aktif' : 'Nonaktif' ?></span>
                            </td>
                            <td class="table-actions" data-label="Aksi">
                                <a class="btn btn-outline btn-sm" href="<?= site_url('user/edit/' . $user->id) ?>">Edit</a>
                                <form method="post" action="<?= site_url('user/toggle/' . $user->id) ?>" class="inline-form">
                                    <?= csrf_field() ?>
                                    <button
                                        class="btn btn-outline btn-sm"
                                        type="submit"
                                        data-confirm="<?= esc($confirmMessage, 'attr') ?>"
                                    >
                                        <?= (int) $user->is_active === 1 ? 'Nonaktifkan' : 'Aktifkan' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
