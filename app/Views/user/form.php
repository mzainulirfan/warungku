<?php
$errors = session()->getFlashdata('errors') ?? [];
$isEdit = $user !== null;
?>

<section class="card form-card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Data User</h3>
            <p><?= $isEdit ? 'Kosongkan password jika tidak ingin mengubahnya.' : 'Password wajib diisi saat membuat user baru.' ?></p>
        </div>
    </div>
    <div class="card-body padded-card-body">
        <form method="post" action="<?= esc($action) ?>" class="form-stack" novalidate>
            <?= csrf_field() ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="name">Nama</label>
                    <input
                        class="form-input<?= isset($errors['name']) ? ' is-invalid' : '' ?>"
                        type="text"
                        id="name"
                        name="name"
                        value="<?= esc(old('name', $user->name ?? '')) ?>"
                        maxlength="100"
                        aria-invalid="<?= isset($errors['name']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['name']) ? 'name-error' : '' ?>"
                    >
                    <?php if (isset($errors['name'])): ?>
                        <span class="form-error" id="name-error"><?= esc($errors['name']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        class="form-input<?= isset($errors['email']) ? ' is-invalid' : '' ?>"
                        type="email"
                        id="email"
                        name="email"
                        value="<?= esc(old('email', $user->email ?? '')) ?>"
                        aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['email']) ? 'email-error' : '' ?>"
                    >
                    <?php if (isset($errors['email'])): ?>
                        <span class="form-error" id="email-error"><?= esc($errors['email']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Role</label>
                    <select
                        class="form-input<?= isset($errors['role']) ? ' is-invalid' : '' ?>"
                        id="role"
                        name="role"
                        aria-invalid="<?= isset($errors['role']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['role']) ? 'role-error' : '' ?>"
                    >
                        <option value="">Pilih role</option>
                        <option value="admin" <?= (string) old('role', $user->role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="kasir" <?= (string) old('role', $user->role ?? '') === 'kasir' ? 'selected' : '' ?>>Kasir</option>
                    </select>
                    <?php if (isset($errors['role'])): ?>
                        <span class="form-error" id="role-error"><?= esc($errors['role']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="is_active">Status</label>
                    <select
                        class="form-input<?= isset($errors['is_active']) ? ' is-invalid' : '' ?>"
                        id="is_active"
                        name="is_active"
                        aria-invalid="<?= isset($errors['is_active']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['is_active']) ? 'is-active-error' : '' ?>"
                    >
                        <option value="1" <?= (string) old('is_active', $user->is_active ?? '1') === '1' ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= (string) old('is_active', $user->is_active ?? '1') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                    <?php if (isset($errors['is_active'])): ?>
                        <span class="form-error" id="is-active-error"><?= esc($errors['is_active']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        class="form-input<?= isset($errors['password']) ? ' is-invalid' : '' ?>"
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['password']) ? 'password-error' : '' ?>"
                    >
                    <?php if (isset($errors['password'])): ?>
                        <span class="form-error" id="password-error"><?= esc($errors['password']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirm">Konfirmasi Password</label>
                    <input
                        class="form-input<?= isset($errors['password_confirm']) ? ' is-invalid' : '' ?>"
                        type="password"
                        id="password_confirm"
                        name="password_confirm"
                        autocomplete="new-password"
                        aria-invalid="<?= isset($errors['password_confirm']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['password_confirm']) ? 'password-confirm-error' : '' ?>"
                    >
                    <?php if (isset($errors['password_confirm'])): ?>
                        <span class="form-error" id="password-confirm-error"><?= esc($errors['password_confirm']) ?></span>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit"><?= esc($submitText) ?></button>
                <a class="btn btn-outline" href="<?= site_url('user') ?>">Batal</a>
            </div>
        </form>
    </div>
</section>
