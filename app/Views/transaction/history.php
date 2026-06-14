<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$transactionCount = is_countable($transactions) ? count($transactions) : 0;
$totalShown = 0;
foreach ($transactions as $transaction) {
    $totalShown += (float) $transaction->total_amount;
}
?>

<section class="page-header">
    <div>
        <h2>Riwayat Transaksi</h2>
        <p>Lihat transaksi berdasarkan tanggal dan akses role pengguna.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-primary" href="<?= site_url('pos') ?>">Buka Kasir</a>
    </div>
</section>

<section class="transaction-overview">
    <article class="card transaction-summary-card">
        <span class="summary-label">Transaksi tampil</span>
        <strong><?= esc((string) $transactionCount) ?></strong>
        <p>Jumlah transaksi pada halaman saat ini.</p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Omzet halaman ini</span>
        <strong><?= esc(rupiah($totalShown)) ?></strong>
        <p>Akumulasi total dari transaksi yang sedang tampil.</p>
    </article>
</section>

<section class="card transaction-tool-card">
    <div class="card-header transaction-tool-header">
        <div>
            <h3 class="card-title">Filter Riwayat</h3>
            <p>Rentang aktif: <?= esc($filters['date_from']) ?> sampai <?= esc($filters['date_to']) ?>.</p>
        </div>
    </div>
    <div class="card-body padded-card-body">
        <form method="get" action="<?= site_url('transaction') ?>" class="transaction-filter-form">
            <div class="form-group">
                <label class="form-label" for="date_from">Dari Tanggal</label>
                <input class="form-input" type="date" id="date_from" name="date_from" value="<?= esc($filters['date_from']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="date_to">Sampai Tanggal</label>
                <input class="form-input" type="date" id="date_to" name="date_to" value="<?= esc($filters['date_to']) ?>">
            </div>
            <div class="filter-actions">
                <button class="btn btn-primary" type="submit">Terapkan</button>
                <a class="btn btn-outline" href="<?= site_url('transaction') ?>">Hari Ini</a>
            </div>
        </form>
    </div>
</section>

<section class="card transaction-list-card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Daftar Transaksi</h3>
            <p>Transaksi terbaru ditampilkan paling atas.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table responsive-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Kembalian</th>
                        <th>Tanggal</th>
                        <th class="table-actions">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($transactions === []): ?>
                        <tr>
                            <td colspan="7" class="empty-cell">Belum ada transaksi.</td>
                        </tr>
                    <?php endif ?>

                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td data-label="Invoice">
                                <div class="invoice-cell">
                                    <strong><?= esc($transaction->invoice_no) ?></strong>
                                    <small><?= esc($transaction->created_at) ?></small>
                                </div>
                            </td>
                            <td data-label="Kasir"><?= esc($transaction->cashier_name) ?></td>
                            <td data-label="Total"><strong><?= esc(rupiah($transaction->total_amount)) ?></strong></td>
                            <td data-label="Pembayaran"><?= esc(rupiah($transaction->payment_amount)) ?></td>
                            <td data-label="Kembalian"><?= esc(rupiah($transaction->change_amount)) ?></td>
                            <td data-label="Tanggal"><?= esc($transaction->created_at) ?></td>
                            <td class="table-actions" data-label="Aksi">
                                <a class="btn btn-outline btn-sm" href="<?= site_url('transaction/detail/' . $transaction->id) ?>">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php if ($pager->getPageCount('transactions') > 1): ?>
            <div class="pagination-wrapper">
                <?= $pager->links('transactions', 'app_full') ?>
            </div>
        <?php endif ?>
    </div>
</section>
<?= $this->endSection() ?>
