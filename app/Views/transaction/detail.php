<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$itemCount = is_countable($items) ? count($items) : 0;
$qtyCount = 0;
foreach ($items as $item) {
    $qtyCount += (int) $item->qty;
}
$autoPrint = (string) service('request')->getGet('print') === '1';
?>

<section class="page-header">
    <div>
        <h2>Detail Transaksi</h2>
        <p><?= esc($transaction->invoice_no) ?> - <?= esc($transaction->created_at) ?></p>
    </div>
    <div class="page-actions">
        <button class="btn btn-outline" type="button" onclick="window.print()">Cetak</button>
        <a class="btn btn-outline" href="<?= site_url('transaction') ?>">Kembali</a>
    </div>
</section>

<?php if ($autoPrint): ?>
    <section class="alert alert-success print-notice" data-auto-print>
        Struk siap dicetak. Dialog print akan terbuka otomatis.
    </section>
<?php endif ?>

<section class="print-receipt" aria-label="Struk belanja">
    <div class="print-receipt-header">
        <h1><?= esc(setting('store_name', 'Warung Sederhana')) ?></h1>
        <p><?= esc($transaction->invoice_no) ?></p>
        <p><?= esc($transaction->created_at) ?> | Kasir: <?= esc($transaction->cashier_name) ?></p>
    </div>

    <table class="print-receipt-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= esc($item->product_name) ?></td>
                    <td><?= esc((string) $item->qty) ?></td>
                    <td><?= esc(rupiah($item->price)) ?></td>
                    <td><?= esc(rupiah($item->subtotal)) ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <div class="print-receipt-summary">
        <div>
            <span>Total</span>
            <strong><?= esc(rupiah($transaction->total_amount)) ?></strong>
        </div>
        <div>
            <span>Bayar</span>
            <strong><?= esc(rupiah($transaction->payment_amount)) ?></strong>
        </div>
        <div>
            <span>Kembali</span>
            <strong><?= esc(rupiah($transaction->change_amount)) ?></strong>
        </div>
    </div>

    <?php if ($transaction->note): ?>
        <p class="print-receipt-note">Catatan: <?= esc($transaction->note) ?></p>
    <?php endif ?>

    <p class="print-receipt-footer">Terima kasih.</p>
</section>

<section class="transaction-overview">
    <article class="card transaction-summary-card">
        <span class="summary-label">Total transaksi</span>
        <strong><?= esc(rupiah($transaction->total_amount)) ?></strong>
        <p>Dibayar <?= esc(rupiah($transaction->payment_amount)) ?>.</p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Jumlah item</span>
        <strong><?= esc((string) $qtyCount) ?></strong>
        <p><?= esc((string) $itemCount) ?> jenis produk dalam transaksi.</p>
    </article>
</section>

<section class="receipt-layout">
    <section class="card receipt-card">
        <div class="card-header receipt-header">
            <div>
                <h3 class="card-title"><?= esc(setting('store_name', 'Warung Sederhana')) ?></h3>
                <p>Ringkasan pembayaran transaksi.</p>
            </div>
            <span class="badge badge-dark">Lunas</span>
        </div>
        <div class="card-body padded-card-body">
            <dl class="detail-list">
                <div>
                    <dt>Invoice</dt>
                    <dd><?= esc($transaction->invoice_no) ?></dd>
                </div>
                <div>
                    <dt>Kasir</dt>
                    <dd><?= esc($transaction->cashier_name) ?></dd>
                </div>
                <div>
                    <dt>Tanggal</dt>
                    <dd><?= esc($transaction->created_at) ?></dd>
                </div>
                <div>
                    <dt>Total</dt>
                    <dd class="receipt-total"><?= esc(rupiah($transaction->total_amount)) ?></dd>
                </div>
                <div>
                    <dt>Pembayaran</dt>
                    <dd><?= esc(rupiah($transaction->payment_amount)) ?></dd>
                </div>
                <div>
                    <dt>Kembalian</dt>
                    <dd><?= esc(rupiah($transaction->change_amount)) ?></dd>
                </div>
                <?php if ($transaction->note): ?>
                    <div>
                        <dt>Catatan</dt>
                        <dd><?= esc($transaction->note) ?></dd>
                    </div>
                <?php endif ?>
            </dl>
        </div>
    </section>

    <section class="card transaction-items-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Item</h3>
                <p>Nama dan harga adalah snapshot saat transaksi dibuat.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table responsive-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td data-label="Produk"><strong><?= esc($item->product_name) ?></strong></td>
                                <td data-label="Harga"><?= esc(rupiah($item->price)) ?></td>
                                <td data-label="Qty">
                                    <span class="stock-pill"><?= esc((string) $item->qty) ?></span>
                                </td>
                                <td data-label="Subtotal"><strong><?= esc(rupiah($item->subtotal)) ?></strong></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="receipt-grand-total">
                <span>Grand Total</span>
                <strong><?= esc(rupiah($transaction->total_amount)) ?></strong>
            </div>
        </div>
    </section>
</section>

<?php if ($autoPrint): ?>
    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
<?php endif ?>
<?= $this->endSection() ?>
