<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$errors = session()->getFlashdata('errors') ?? [];
$shownProducts = is_countable($products) ? count($products) : 0;
$activeShownProducts = 0;
foreach ($products as $product) {
    if ((int) $product->is_active === 1) {
        $activeShownProducts++;
    }
}
?>

<section class="page-header">
    <div>
        <h2>Produk</h2>
        <p>Kelola produk, harga, stok, gambar, dan status penjualan.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-outline" href="<?= site_url('product/template') ?>">Download Template</a>
        <a class="btn btn-primary" href="<?= site_url('product/create') ?>">Tambah Produk</a>
    </div>
</section>

<section class="product-overview">
    <article class="card product-summary-card">
        <span class="summary-label">Produk halaman ini</span>
        <strong><?= esc((string) $shownProducts) ?></strong>
        <p><?= esc((string) $activeShownProducts) ?> produk aktif pada daftar yang sedang ditampilkan.</p>
    </article>
    <article class="card product-summary-card">
        <span class="summary-label">Kategori tersedia</span>
        <strong><?= esc((string) count($categories)) ?></strong>
        <p>Digunakan untuk filter produk dan input data baru.</p>
    </article>
</section>

<section class="product-tools">
    <div class="card product-tool-card">
        <div class="card-header product-tool-header">
            <div>
                <h3 class="card-title">Filter Produk</h3>
                <p>Cari cepat berdasarkan nama atau kategori.</p>
            </div>
        </div>
        <div class="card-body padded-card-body">
            <form method="get" action="<?= site_url('product') ?>" class="product-filter-form">
                <div class="form-group">
                    <label class="form-label" for="q">Cari Produk</label>
                    <input class="form-input" type="search" id="q" name="q" value="<?= esc($filters['q']) ?>" placeholder="Nama produk">
                </div>
                <div class="form-group">
                    <label class="form-label" for="category_id">Kategori</label>
                    <select class="form-input" id="category_id" name="category_id">
                        <option value="">Semua kategori</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= esc((string) $category->id) ?>" <?= (string) $filters['category_id'] === (string) $category->id ? 'selected' : '' ?>>
                                <?= esc($category->name) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="filter-actions">
                    <button class="btn btn-primary" type="submit">Terapkan</button>
                    <a class="btn btn-outline" href="<?= site_url('product') ?>">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card product-tool-card product-import-card">
        <div class="card-header product-tool-header">
            <div>
                <h3 class="card-title">Upload Massal</h3>
                <p>Import CSV dari template Excel. Kategori baru dibuat otomatis.</p>
            </div>
            <a class="btn btn-outline btn-sm" href="<?= site_url('product/template') ?>">Template</a>
        </div>
        <div class="card-body padded-card-body">
            <form method="post" action="<?= site_url('product/import') ?>" enctype="multipart/form-data" class="product-import-form">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label" for="product_file">File Produk</label>
                    <input
                        class="form-input<?= isset($errors['product_file']) ? ' is-invalid' : '' ?>"
                        type="file"
                        id="product_file"
                        name="product_file"
                        accept=".csv,text/csv"
                        aria-invalid="<?= isset($errors['product_file']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['product_file']) ? 'product-file-error' : 'product-file-help' ?>"
                    >
                    <?php if (isset($errors['product_file'])): ?>
                        <span class="form-error" id="product-file-error"><?= esc($errors['product_file']) ?></span>
                    <?php endif ?>
                    <small class="form-help" id="product-file-help">Kolom: category_name, name, price, stock, is_active.</small>
                </div>
                <button class="btn btn-primary btn-block" type="submit" data-confirm="Import produk dari file ini?">Upload Produk</button>
            </form>
        </div>
    </div>
</section>

<section class="card product-list-card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Daftar Produk</h3>
            <p>Menampilkan 10 produk per halaman.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table responsive-table product-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="table-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products === []): ?>
                        <tr>
                            <td colspan="6" class="empty-cell">Belum ada produk.</td>
                        </tr>
                    <?php endif ?>

                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td data-label="Produk">
                                <div class="product-cell">
                                    <?php if ($product->image): ?>
                                        <img class="product-thumb" src="<?= base_url('assets/img/products/' . rawurlencode($product->image)) ?>" alt="<?= esc($product->name) ?>">
                                    <?php else: ?>
                                        <span class="product-thumb product-thumb-empty"><?= esc(strtoupper(substr((string) $product->name, 0, 1))) ?></span>
                                    <?php endif ?>
                                    <span>
                                        <strong><?= esc($product->name) ?></strong>
                                        <small><?= esc($product->category_name ?? '-') ?></small>
                                    </span>
                                </div>
                            </td>
                            <td data-label="Kategori"><?= esc($product->category_name ?? '-') ?></td>
                            <td data-label="Harga"><?= esc(rupiah($product->price)) ?></td>
                            <td data-label="Stok">
                                <span class="stock-pill<?= (int) $product->stock <= 5 ? ' stock-pill-low' : '' ?>">
                                    <?= esc((string) $product->stock) ?>
                                </span>
                            </td>
                            <td data-label="Status">
                                <span class="badge <?= (int) $product->is_active === 1 ? 'badge-dark' : 'badge-muted' ?>">
                                    <?= (int) $product->is_active === 1 ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td class="table-actions" data-label="Aksi">
                                <a class="btn btn-outline btn-sm" href="<?= site_url('product/edit/' . $product->id) ?>">Edit</a>
                                <form method="post" action="<?= site_url('product/toggle/' . $product->id) ?>" class="inline-form">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline btn-sm" type="submit">
                                        <?= (int) $product->is_active === 1 ? 'Nonaktifkan' : 'Aktifkan' ?>
                                    </button>
                                </form>
                                <form method="post" action="<?= site_url('product/delete/' . $product->id) ?>" class="inline-form">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-outline btn-sm" type="submit" data-confirm="Hapus produk <?= esc($product->name) ?>?">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php if ($pager->getPageCount('products') > 1): ?>
            <div class="pagination-wrapper">
                <?= $pager->links('products', 'app_full') ?>
            </div>
        <?php endif ?>
    </div>
</section>
<?= $this->endSection() ?>
