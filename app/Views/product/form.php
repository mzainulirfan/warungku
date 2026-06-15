<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<section class="product-form-layout">
    <form method="post" action="<?= esc($action) ?>" enctype="multipart/form-data" class="product-form" novalidate>
        <?= csrf_field() ?>

        <div class="card product-form-main">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Data Produk</h3>
                    <p>Isi informasi utama produk untuk ditampilkan di kasir.</p>
                </div>
            </div>
            <div class="card-body padded-card-body">
                <div class="form-grid">
                    <div class="form-group form-group-span">
                        <label class="form-label" for="name">Nama Produk</label>
                        <input
                            class="form-input<?= isset($errors['name']) ? ' is-invalid' : '' ?>"
                            type="text"
                            id="name"
                            name="name"
                            value="<?= esc(old('name', $product->name ?? '')) ?>"
                            placeholder="Contoh: Nasi Goreng"
                            maxlength="100"
                            aria-invalid="<?= isset($errors['name']) ? 'true' : 'false' ?>"
                            aria-describedby="<?= isset($errors['name']) ? 'name-error' : '' ?>"
                        >
                        <?php if (isset($errors['name'])): ?>
                            <span class="form-error" id="name-error"><?= esc($errors['name']) ?></span>
                        <?php endif ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="category_id">Kategori</label>
                        <select
                            class="form-input<?= isset($errors['category_id']) ? ' is-invalid' : '' ?>"
                            id="category_id"
                            name="category_id"
                            aria-invalid="<?= isset($errors['category_id']) ? 'true' : 'false' ?>"
                            aria-describedby="<?= isset($errors['category_id']) ? 'category-id-error' : '' ?>"
                        >
                            <option value="">Pilih kategori</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= esc((string) $category->id) ?>" <?= (string) old('category_id', $product->category_id ?? '') === (string) $category->id ? 'selected' : '' ?>>
                                    <?= esc($category->name) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <?php if (isset($errors['category_id'])): ?>
                            <span class="form-error" id="category-id-error"><?= esc($errors['category_id']) ?></span>
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
                            <option value="1" <?= (string) old('is_active', $product->is_active ?? '1') === '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= (string) old('is_active', $product->is_active ?? '1') === '0' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                        <?php if (isset($errors['is_active'])): ?>
                            <span class="form-error" id="is-active-error"><?= esc($errors['is_active']) ?></span>
                        <?php endif ?>
                    </div>

                    <div class="form-group form-group-span">
                        <label class="form-label" for="barcode">Barcode</label>
                        <input
                            class="form-input<?= isset($errors['barcode']) ? ' is-invalid' : '' ?>"
                            type="text"
                            id="barcode"
                            name="barcode"
                            value="<?= esc(old('barcode', $product->barcode ?? '')) ?>"
                            placeholder="Kosongkan untuk dibuat otomatis"
                            maxlength="64"
                            aria-invalid="<?= isset($errors['barcode']) ? 'true' : 'false' ?>"
                            aria-describedby="<?= isset($errors['barcode']) ? 'barcode-error' : 'barcode-help' ?>"
                        >
                        <?php if (isset($errors['barcode'])): ?>
                            <span class="form-error" id="barcode-error"><?= esc($errors['barcode']) ?></span>
                        <?php endif ?>
                        <span class="form-help" id="barcode-help">Bisa diisi dari barcode produk. Jika kosong, sistem membuat kode otomatis.</span>
                        <button
                            class="btn btn-outline btn-sm barcode-preview-button"
                            type="button"
                            data-barcode-preview
                            data-barcode-url="<?= site_url('product/barcode-preview') ?>"
                            data-barcode-input="barcode"
                        >
                            Preview Barcode
                        </button>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="price">Harga</label>
                        <input
                            class="form-input<?= isset($errors['price']) ? ' is-invalid' : '' ?>"
                            type="number"
                            id="price"
                            name="price"
                            value="<?= esc(old('price', $product->price ?? '0')) ?>"
                            min="0"
                            step="1"
                            aria-invalid="<?= isset($errors['price']) ? 'true' : 'false' ?>"
                            aria-describedby="<?= isset($errors['price']) ? 'price-error' : '' ?>"
                        >
                        <?php if (isset($errors['price'])): ?>
                            <span class="form-error" id="price-error"><?= esc($errors['price']) ?></span>
                        <?php endif ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="stock">Stok</label>
                        <input
                            class="form-input<?= isset($errors['stock']) ? ' is-invalid' : '' ?>"
                            type="number"
                            id="stock"
                            name="stock"
                            value="<?= esc(old('stock', $product->stock ?? '0')) ?>"
                            min="0"
                            step="1"
                            aria-invalid="<?= isset($errors['stock']) ? 'true' : 'false' ?>"
                            aria-describedby="<?= isset($errors['stock']) ? 'stock-error' : '' ?>"
                        >
                        <?php if (isset($errors['stock'])): ?>
                            <span class="form-error" id="stock-error"><?= esc($errors['stock']) ?></span>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>

        <aside class="product-form-side">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Gambar Produk</h3>
                        <p>Opsional, maksimal 1MB.</p>
                    </div>
                </div>
                <div class="card-body padded-card-body">
                    <div class="product-image-preview">
                        <?php if ($product && $product->image): ?>
                            <img src="<?= base_url('assets/img/products/' . rawurlencode($product->image)) ?>" alt="<?= esc($product->name) ?>">
                        <?php else: ?>
                            <span><?= esc(strtoupper(substr((string) old('name', $product->name ?? 'P'), 0, 1))) ?></span>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="image">Upload Gambar</label>
                        <input
                            class="form-input<?= isset($errors['image']) ? ' is-invalid' : '' ?>"
                            type="file"
                            id="image"
                            name="image"
                            accept="image/jpeg,image/png,image/webp"
                            aria-invalid="<?= isset($errors['image']) ? 'true' : 'false' ?>"
                            aria-describedby="<?= isset($errors['image']) ? 'image-error' : 'image-help' ?>"
                        >
                        <?php if (isset($errors['image'])): ?>
                            <span class="form-error" id="image-error"><?= esc($errors['image']) ?></span>
                        <?php endif ?>
                        <span class="form-help" id="image-help">Format JPG, PNG, atau WEBP.</span>
                        <?php if ($product && $product->image): ?>
                            <span class="form-help">Gambar saat ini: <?= esc($product->image) ?></span>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <div class="card product-form-tips">
                <div class="card-body padded-card-body">
                    <h3 class="card-title">Catatan</h3>
                    <ul>
                        <li>Produk nonaktif tidak muncul di halaman kasir.</li>
                        <li>Stok kosong tidak dapat dijual.</li>
                        <li>Barcode harus unik untuk scan di halaman POS.</li>
                        <li>Harga dan stok hanya menerima angka.</li>
                    </ul>
                </div>
            </div>

            <div class="form-actions product-form-actions">
                <button class="btn btn-primary" type="submit"><?= esc($submitText) ?></button>
                <a class="btn btn-outline" href="<?= site_url('product') ?>">Batal</a>
            </div>
        </aside>
    </form>
</section>
