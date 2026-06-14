<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Kategori</h2>
        <p>Kelola pengelompokan produk untuk kasir dan katalog warung.</p>
    </div>
    <?php if ($editCategory): ?>
        <a class="btn btn-outline" href="<?= site_url('category') ?>">Batal Edit</a>
    <?php endif ?>
</section>

<section class="content-grid content-grid-sidebar">
    <article class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title"><?= $editCategory ? 'Edit Kategori' : 'Tambah Kategori' ?></h3>
                <p><?= $editCategory ? 'Perbarui nama kategori yang dipilih.' : 'Buat kategori baru untuk produk.' ?></p>
            </div>
        </div>
        <div class="card-body padded-card-body">
            <form
                method="post"
                action="<?= $editCategory ? site_url('category/update/' . $editCategory->id) : site_url('category/store') ?>"
                class="form-stack"
            >
                <?= csrf_field() ?>

                <div class="form-group">
                    <label class="form-label" for="name">Nama Kategori</label>
                    <input
                        class="form-input"
                        type="text"
                        id="name"
                        name="name"
                        value="<?= esc(old('name', $editCategory->name ?? '')) ?>"
                        placeholder="Contoh: Makanan"
                        maxlength="50"
                        required
                    >
                </div>

                <button class="btn btn-primary" type="submit">
                    <?= $editCategory ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
                </button>
            </form>
        </div>
    </article>

    <article class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Daftar Kategori</h3>
                <p>Total <?= esc((string) count($categories)) ?> kategori.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Dibuat</th>
                            <th class="table-actions">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($categories === []): ?>
                            <tr>
                                <td colspan="3" class="empty-cell">Belum ada kategori.</td>
                            </tr>
                        <?php endif ?>

                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($category->name) ?></strong>
                                </td>
                                <td><?= esc($category->created_at ?? '-') ?></td>
                                <td class="table-actions">
                                    <a class="btn btn-outline btn-sm" href="<?= site_url('category?edit=' . $category->id) ?>">Edit</a>
                                    <form method="post" action="<?= site_url('category/delete/' . $category->id) ?>" class="inline-form">
                                        <?= csrf_field() ?>
                                        <button
                                            class="btn btn-outline btn-sm"
                                            type="submit"
                                            data-confirm="Hapus kategori <?= esc($category->name) ?>?"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </article>
</section>
<?= $this->endSection() ?>
