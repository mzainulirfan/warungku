<?php $errors = session()->getFlashdata('errors') ?? []; ?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Setting</h2>
        <p>Atur identitas toko yang tampil di aplikasi dan struk transaksi.</p>
    </div>
</section>

<section class="card form-card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Profil Toko</h3>
            <p>Perubahan nama toko langsung dipakai pada judul dan sidebar.</p>
        </div>
    </div>
    <div class="card-body padded-card-body">
        <form method="post" action="<?= site_url('setting/update') ?>" class="form-stack" novalidate>
            <?= csrf_field() ?>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label" for="store_name">Nama Toko</label>
                    <input
                        class="form-input<?= isset($errors['store_name']) ? ' is-invalid' : '' ?>"
                        type="text"
                        id="store_name"
                        name="store_name"
                        value="<?= esc(old('store_name', $settings['store_name'] ?? '')) ?>"
                        maxlength="100"
                        aria-invalid="<?= isset($errors['store_name']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['store_name']) ? 'store-name-error' : '' ?>"
                    >
                    <?php if (isset($errors['store_name'])): ?>
                        <span class="form-error" id="store-name-error"><?= esc($errors['store_name']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="store_phone">Nomor Telepon</label>
                    <input
                        class="form-input<?= isset($errors['store_phone']) ? ' is-invalid' : '' ?>"
                        type="text"
                        id="store_phone"
                        name="store_phone"
                        value="<?= esc(old('store_phone', $settings['store_phone'] ?? '')) ?>"
                        maxlength="20"
                        aria-invalid="<?= isset($errors['store_phone']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['store_phone']) ? 'store-phone-error' : '' ?>"
                    >
                    <?php if (isset($errors['store_phone'])): ?>
                        <span class="form-error" id="store-phone-error"><?= esc($errors['store_phone']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label class="form-label" for="currency_symbol">Simbol Mata Uang</label>
                    <input
                        class="form-input<?= isset($errors['currency_symbol']) ? ' is-invalid' : '' ?>"
                        type="text"
                        id="currency_symbol"
                        name="currency_symbol"
                        value="<?= esc(old('currency_symbol', $settings['currency_symbol'] ?? 'Rp')) ?>"
                        maxlength="10"
                        aria-invalid="<?= isset($errors['currency_symbol']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['currency_symbol']) ? 'currency-symbol-error' : '' ?>"
                    >
                    <?php if (isset($errors['currency_symbol'])): ?>
                        <span class="form-error" id="currency-symbol-error"><?= esc($errors['currency_symbol']) ?></span>
                    <?php endif ?>
                </div>

                <div class="form-group form-group-span">
                    <label class="form-label" for="store_address">Alamat Toko</label>
                    <textarea
                        class="form-input<?= isset($errors['store_address']) ? ' is-invalid' : '' ?>"
                        id="store_address"
                        name="store_address"
                        rows="4"
                        maxlength="255"
                        aria-invalid="<?= isset($errors['store_address']) ? 'true' : 'false' ?>"
                        aria-describedby="<?= isset($errors['store_address']) ? 'store-address-error' : '' ?>"
                    ><?= esc(old('store_address', $settings['store_address'] ?? '')) ?></textarea>
                    <?php if (isset($errors['store_address'])): ?>
                        <span class="form-error" id="store-address-error"><?= esc($errors['store_address']) ?></span>
                    <?php endif ?>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Simpan Setting</button>
            </div>
        </form>
    </div>
</section>
<?= $this->endSection() ?>
