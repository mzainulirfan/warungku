<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$errors = session()->getFlashdata('errors') ?? [];
$importErrors = array_filter($errors, static fn ($key) => is_int($key), ARRAY_FILTER_USE_KEY);
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
        <p>Kelola katalog produk, stok, status penjualan, dan import data massal.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-outline" href="<?= site_url('product/export?' . http_build_query(array_filter($filters, static fn ($value) => $value !== null && $value !== ''))) ?>">Export CSV</a>
        <a class="btn btn-outline" href="<?= site_url('product/template') ?>">Download Template</a>
        <a class="btn btn-primary" href="<?= site_url('product/create') ?>">Tambah Produk</a>
    </div>
</section>

<section class="card product-hero">
    <div class="product-hero-copy">
        <span class="summary-label">Inventori produk</span>
        <h3><?= esc((string) $shownProducts) ?> produk ditampilkan</h3>
        <p><?= esc((string) $activeShownProducts) ?> aktif di halaman ini. Gunakan pencarian dan filter kategori untuk mengelola katalog besar.</p>
    </div>
    <div class="product-hero-stats">
        <div>
            <span>Kategori</span>
            <strong><?= esc((string) count($categories)) ?></strong>
        </div>
        <div>
            <span>Per halaman</span>
            <strong>10</strong>
        </div>
    </div>
</section>

<section class="card product-workbench">
    <div class="product-workbench-main">
        <div class="product-section-heading">
            <h3 class="card-title">Filter Produk</h3>
            <p>Cari cepat berdasarkan nama atau kategori.</p>
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
                <div class="form-group">
                    <label class="form-label" for="stock_status">Status Stok</label>
                    <select class="form-input" id="stock_status" name="stock_status">
                        <option value="">Semua stok</option>
                        <option value="low" <?= ($filters['stock_status'] ?? '') === 'low' ? 'selected' : '' ?>>Stok rendah</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button class="btn btn-primary" type="submit">Terapkan</button>
                    <a class="btn btn-outline" href="<?= site_url('product') ?>">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="product-workbench-side">
        <div class="product-section-heading product-import-heading">
            <div>
                <h3 class="card-title">Import CSV</h3>
                <p>Tambah banyak produk dari template.</p>
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
                    <?php if ($importErrors !== []): ?>
                        <span class="form-error" id="product-file-error"><?= esc(implode(' ', $importErrors)) ?></span>
                    <?php endif ?>
                    <small class="form-help" id="product-file-help">Kolom: category_name, name, barcode, price, stock, is_active.</small>
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
            <p>Menampilkan 10 produk per halaman. Produk terbaru tampil paling atas.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table responsive-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok / Status</th>
                        <th class="table-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products === []): ?>
                        <tr>
                            <td colspan="4" class="empty-cell">Belum ada produk.</td>
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
                                        <small class="barcode-text"><?= esc($product->barcode ?? '-') ?></small>
                                    </span>
                                </div>
                            </td>
                            <td data-label="Harga"><strong><?= esc(rupiah($product->price)) ?></strong></td>
                            <td data-label="Stok / Status">
                                <div class="product-state">
                                    <span class="stock-pill<?= (int) $product->stock <= 5 ? ' stock-pill-low' : '' ?>">
                                        Stok <?= esc((string) $product->stock) ?>
                                    </span>
                                    <span class="badge <?= (int) $product->is_active === 1 ? 'badge-dark' : 'badge-muted' ?>">
                                        <?= (int) $product->is_active === 1 ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </div>
                            </td>
                            <td class="table-actions" data-label="Aksi">
                                <button
                                    class="btn btn-outline btn-sm"
                                    type="button"
                                    data-barcode-preview
                                    data-barcode-url="<?= site_url('product/barcode-preview') ?>"
                                    data-barcode-code="<?= esc((string) $product->barcode, 'attr') ?>"
                                >
                                    Barcode
                                </button>
                                <a class="btn btn-outline btn-sm" href="<?= site_url('product/detail/' . $product->id) ?>">Detail</a>
                                <a class="btn btn-primary btn-sm" href="<?= site_url('product/edit/' . $product->id) ?>">Edit</a>
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
