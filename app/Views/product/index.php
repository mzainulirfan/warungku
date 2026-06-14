<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Produk</h2>
        <p>Kelola produk, harga, stok, gambar, dan status penjualan.</p>
    </div>
    <a class="btn btn-primary" href="<?= site_url('product/create') ?>">Tambah Produk</a>
</section>

<section class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Filter Produk</h3>
            <p>Cari berdasarkan nama atau kategori produk.</p>
        </div>
    </div>
    <div class="card-body padded-card-body">
        <form method="get" action="<?= site_url('product') ?>" class="filter-form">
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
</section>

<section class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Daftar Produk</h3>
            <p>Menampilkan 10 produk per halaman.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table">
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
                            <td>
                                <div class="product-cell">
                                    <?php if ($product->image): ?>
                                        <img class="product-thumb" src="<?= base_url('assets/img/products/' . rawurlencode($product->image)) ?>" alt="<?= esc($product->name) ?>">
                                    <?php else: ?>
                                        <span class="product-thumb product-thumb-empty">P</span>
                                    <?php endif ?>
                                    <strong><?= esc($product->name) ?></strong>
                                </div>
                            </td>
                            <td><?= esc($product->category_name ?? '-') ?></td>
                            <td><?= esc(rupiah($product->price)) ?></td>
                            <td><?= esc((string) $product->stock) ?></td>
                            <td>
                                <span class="badge badge-muted"><?= (int) $product->is_active === 1 ? 'Aktif' : 'Nonaktif' ?></span>
                            </td>
                            <td class="table-actions">
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
        <div class="pagination-wrapper">
            <?= $pager->links('products') ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
