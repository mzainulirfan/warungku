<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$transactionCount = (int) ($sales->transaction_count ?? 0);
$totalQty = (int) ($sales->total_qty ?? 0);
$totalSales = (float) ($sales->total_sales ?? 0);
?>

<section class="page-header">
    <div>
        <h2>Detail Produk</h2>
        <p><?= esc($product->name) ?> - <?= esc($product->category_name ?? 'Tanpa kategori') ?></p>
    </div>
    <div class="page-actions">
        <?php if ($product->barcode): ?>
            <button class="btn btn-outline" type="button" data-print-barcode-label>
                Cetak Label
            </button>
            <button
                class="btn btn-outline"
                type="button"
                data-barcode-preview
                data-barcode-url="<?= site_url('product/barcode-preview') ?>"
                data-barcode-code="<?= esc((string) $product->barcode, 'attr') ?>"
            >
                Preview Barcode
            </button>
        <?php endif ?>
        <a class="btn btn-primary" href="<?= site_url('product/edit/' . $product->id) ?>">Edit Produk</a>
        <a class="btn btn-outline" href="<?= site_url('product') ?>">Kembali</a>
    </div>
</section>

<?php if ($barcodeSvg): ?>
    <section class="print-barcode-label" aria-label="Label barcode produk">
        <h1><?= esc(setting('store_name', 'Warung Sederhana')) ?></h1>
        <p><?= esc($product->name) ?></p>
        <div class="print-barcode-label__code">
            <?= $barcodeSvg ?>
            <strong><?= esc($product->barcode) ?></strong>
        </div>
        <small><?= esc(rupiah($product->price)) ?></small>
    </section>
<?php endif ?>

<section class="transaction-overview">
    <article class="card transaction-summary-card">
        <span class="summary-label">Harga jual</span>
        <strong><?= esc(rupiah($product->price)) ?></strong>
        <p>Status <?= (int) $product->is_active === 1 ? 'aktif' : 'nonaktif' ?>.</p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Stok tersedia</span>
        <strong><?= esc((string) $product->stock) ?></strong>
        <p><?= (int) $product->stock <= 5 ? 'Perlu dipantau karena stok rendah.' : 'Stok masih tersedia.' ?></p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Qty terjual</span>
        <strong><?= esc((string) $totalQty) ?></strong>
        <p>Dari <?= esc((string) $transactionCount) ?> item transaksi.</p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Total penjualan</span>
        <strong><?= esc(rupiah($totalSales)) ?></strong>
        <p>Akumulasi dari riwayat transaksi.</p>
    </article>
</section>

<section class="receipt-layout product-detail-layout">
    <aside class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Preview Produk</h3>
                <p>Gambar yang tampil di katalog dan kasir.</p>
            </div>
        </div>
        <div class="card-body padded-card-body">
            <div class="product-image-preview">
                <?php if ($product->image): ?>
                    <img src="<?= base_url('assets/img/products/' . rawurlencode($product->image)) ?>" alt="<?= esc($product->name) ?>">
                <?php else: ?>
                    <span><?= esc(strtoupper(substr((string) $product->name, 0, 1))) ?></span>
                <?php endif ?>
            </div>
            <span class="badge <?= (int) $product->is_active === 1 ? 'badge-dark' : 'badge-muted' ?>">
                <?= (int) $product->is_active === 1 ? 'Aktif dijual' : 'Nonaktif' ?>
            </span>
            <?php if ($barcodeSvg): ?>
                <div class="barcode-preview" aria-label="Barcode produk <?= esc($product->barcode, 'attr') ?>">
                    <?= $barcodeSvg ?>
                    <strong><?= esc($product->barcode) ?></strong>
                </div>
            <?php endif ?>
        </div>
    </aside>

    <section class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Informasi Produk</h3>
                <p>Data utama produk dan status inventori.</p>
            </div>
        </div>
        <div class="card-body padded-card-body">
            <dl class="detail-list">
                <div>
                    <dt>Nama</dt>
                    <dd><?= esc($product->name) ?></dd>
                </div>
                <div>
                    <dt>Kategori</dt>
                    <dd><?= esc($product->category_name ?? '-') ?></dd>
                </div>
                <div>
                    <dt>Barcode</dt>
                    <dd><?= esc($product->barcode ?? '-') ?></dd>
                </div>
                <div>
                    <dt>Harga</dt>
                    <dd class="receipt-total"><?= esc(rupiah($product->price)) ?></dd>
                </div>
                <div>
                    <dt>Stok</dt>
                    <dd>
                        <span class="stock-pill<?= (int) $product->stock <= 5 ? ' stock-pill-low' : '' ?>">
                            <?= esc((string) $product->stock) ?>
                        </span>
                    </dd>
                </div>
                <div>
                    <dt>Status</dt>
                    <dd><?= (int) $product->is_active === 1 ? 'Aktif' : 'Nonaktif' ?></dd>
                </div>
                <div>
                    <dt>File Gambar</dt>
                    <dd><?= esc($product->image ?: '-') ?></dd>
                </div>
                <div>
                    <dt>Dibuat</dt>
                    <dd><?= esc($product->created_at ?? '-') ?></dd>
                </div>
                <div>
                    <dt>Diperbarui</dt>
                    <dd><?= esc($product->updated_at ?? '-') ?></dd>
                </div>
            </dl>
        </div>
    </section>
</section>
<?= $this->endSection() ?>
