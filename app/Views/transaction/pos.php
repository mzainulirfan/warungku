<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$availableProducts = is_countable($products) ? count($products) : 0;
$availableCategories = is_countable($categories) ? count($categories) : 0;
?>

<section class="page-header">
    <div>
        <h2>Kasir</h2>
        <p>Pilih produk, cek keranjang, dan simpan transaksi dari satu layar.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-outline" href="<?= site_url('transaction') ?>">Riwayat</a>
    </div>
</section>

<section class="card pos-hero">
    <div class="pos-hero-copy">
        <span class="summary-label">Mode transaksi</span>
        <h3><?= esc(setting('store_name', 'Warung Sederhana')) ?></h3>
        <p>Tambahkan item dari katalog, nominal bayar akan disarankan otomatis, lalu simpan transaksi.</p>
    </div>
    <div class="pos-hero-stats">
        <div>
            <span>Produk tersedia</span>
            <strong><?= esc((string) $availableProducts) ?></strong>
        </div>
        <div>
            <span>Kategori</span>
            <strong><?= esc((string) $availableCategories) ?></strong>
        </div>
    </div>
</section>

<section class="pos-layout" data-pos data-currency-symbol="<?= esc(setting('currency_symbol', 'Rp'), 'attr') ?>">
    <div class="pos-products">
        <section class="card pos-toolbar">
            <div class="card-header transaction-tool-header">
                <div>
                    <h3 class="card-title">Cari Produk</h3>
                    <p>Filter katalog tanpa mengubah isi keranjang.</p>
                </div>
            </div>
            <div class="card-body padded-card-body">
                <div class="pos-scan-box">
                    <div class="form-group">
                        <label class="form-label" for="barcode_scan">Scan Barcode</label>
                        <input
                            class="form-input"
                            type="search"
                            id="barcode_scan"
                            placeholder="Scan barcode lalu Enter"
                            autocomplete="off"
                            data-barcode-scan
                            data-barcode-url="<?= site_url('pos/barcode') ?>"
                        >
                        <span class="form-help">Produk langsung masuk keranjang jika barcode ditemukan.</span>
                    </div>
                    <div class="pos-scan-message" data-barcode-message hidden></div>
                </div>
                <form method="get" action="<?= site_url('pos') ?>" class="transaction-filter-form">
                    <div class="form-group">
                        <label class="form-label" for="q">Cari Produk</label>
                        <input class="form-input" type="search" id="q" name="q" value="<?= esc($filters['q']) ?>" placeholder="Nama produk atau barcode">
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
                        <a class="btn btn-outline" href="<?= site_url('pos') ?>">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        <div class="pos-product-grid">
            <?php if ($products === []): ?>
                <section class="card empty-state">
                    <p>Produk tidak ditemukan.</p>
                </section>
            <?php endif ?>

            <?php foreach ($products as $product): ?>
                <article class="card pos-product-card">
                    <div class="pos-product-media">
                        <?php if ($product->image): ?>
                            <img src="<?= base_url('assets/img/products/' . rawurlencode($product->image)) ?>" alt="<?= esc($product->name) ?>">
                        <?php else: ?>
                            <div class="pos-product-empty" aria-hidden="true"><?= esc(strtoupper(substr((string) $product->name, 0, 1))) ?></div>
                        <?php endif ?>
                    </div>
                    <div class="pos-product-info">
                        <div class="pos-product-main">
                            <div class="pos-product-meta">
                                <span class="badge badge-muted"><?= esc($product->category_name ?? '-') ?></span>
                                <span class="stock-pill<?= (int) $product->stock <= 5 ? ' stock-pill-low' : '' ?>">Stok <?= esc((string) $product->stock) ?></span>
                            </div>
                            <h3><?= esc($product->name) ?></h3>
                        </div>
                        <div class="pos-product-footer">
                            <strong><?= esc(rupiah($product->price)) ?></strong>
                            <button
                                class="btn btn-primary btn-sm"
                                type="button"
                                data-add-product
                                data-id="<?= esc((string) $product->id, 'attr') ?>"
                                data-name="<?= esc($product->name, 'attr') ?>"
                                data-barcode="<?= esc((string) $product->barcode, 'attr') ?>"
                                data-price="<?= esc((string) $product->price, 'attr') ?>"
                                data-stock="<?= esc((string) $product->stock, 'attr') ?>"
                            >
                                Tambah
                            </button>
                        </div>
                    </div>
                </article>
            <?php endforeach ?>
        </div>
    </div>

    <aside class="card pos-cart">
        <div class="card-header pos-cart-header">
            <div>
                <h3 class="card-title">Keranjang</h3>
                <p><span data-cart-panel-count>0 item</span> siap diproses.</p>
            </div>
            <button class="btn btn-outline btn-sm" type="button" data-cart-clear data-confirm="Kosongkan semua isi keranjang?">Kosongkan</button>
        </div>
        <div class="card-body pos-cart-body">
            <form method="post" action="<?= site_url('pos/store') ?>" class="form-stack" data-pos-form>
                <?= csrf_field() ?>
                <input type="hidden" name="cart_json" data-cart-json value="<?= esc(old('cart_json', '[]')) ?>">

                <div class="cart-list pos-cart-scroll" data-cart-list>
                    <div class="empty-state">
                        <p>Keranjang masih kosong.</p>
                    </div>
                </div>

                <div class="pos-cart-checkout">
                    <div class="cart-summary">
                        <div>
                            <span>Total</span>
                            <strong data-cart-total>Rp 0</strong>
                        </div>
                        <div>
                            <span>Kembalian</span>
                            <strong data-cart-change>Rp 0</strong>
                        </div>
                    </div>

                    <div class="checkout-divider">
                        <span>Pembayaran</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="payment_amount">Pembayaran</label>
                        <input class="form-input" type="number" id="payment_amount" name="payment_amount" value="<?= esc(old('payment_amount', '0')) ?>" min="0" step="1" data-payment-input>
                        <span class="form-help">Nominal otomatis disarankan, tetap bisa diubah manual.</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="note">Catatan</label>
                        <textarea class="form-input" id="note" name="note" rows="3" maxlength="255"><?= esc(old('note', '')) ?></textarea>
                    </div>

                    <label class="checkbox-option" for="print_receipt">
                        <input
                            type="checkbox"
                            id="print_receipt"
                            name="print_receipt"
                            value="1"
                            <?= old('print_receipt') === '1' ? 'checked' : '' ?>
                        >
                        <span>
                            <strong>Cetak struk setelah simpan</strong>
                            <small>Dialog print akan terbuka otomatis di halaman detail.</small>
                        </span>
                    </label>

                    <button class="btn btn-primary btn-block pos-submit-btn" type="submit" data-confirm="Simpan transaksi ini?">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </aside>
</section>
<?= $this->endSection() ?>
