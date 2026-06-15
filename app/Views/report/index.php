<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<section class="page-header">
    <div>
        <h2>Laporan Penjualan</h2>
        <p>Ringkasan omzet, transaksi, item terjual, dan produk terlaris.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-outline" href="<?= site_url('report/export?' . http_build_query($filters)) ?>">Export CSV</a>
        <a class="btn btn-primary" href="<?= site_url('transaction') ?>">Riwayat Transaksi</a>
    </div>
</section>

<section class="transaction-overview">
    <article class="card transaction-summary-card">
        <span class="summary-label">Omzet</span>
        <strong><?= esc(rupiah($summary->total_sales)) ?></strong>
        <p>Periode <?= esc($filters['date_from']) ?> sampai <?= esc($filters['date_to']) ?>.</p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Transaksi</span>
        <strong><?= esc((string) $summary->total_transactions) ?></strong>
        <p>Jumlah invoice pada periode aktif.</p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Item Terjual</span>
        <strong><?= esc((string) $summary->total_items) ?></strong>
        <p>Total kuantitas dari semua item transaksi.</p>
    </article>
    <article class="card transaction-summary-card">
        <span class="summary-label">Rata-rata</span>
        <strong><?= esc(rupiah($summary->total_transactions > 0 ? $summary->total_sales / $summary->total_transactions : 0)) ?></strong>
        <p>Rata-rata omzet per transaksi.</p>
    </article>
</section>

<section class="card transaction-tool-card">
    <div class="card-header transaction-tool-header">
        <div>
            <h3 class="card-title">Filter Laporan</h3>
            <p>Gunakan rentang tanggal untuk membaca performa penjualan.</p>
        </div>
    </div>
    <div class="card-body padded-card-body">
        <form method="get" action="<?= site_url('report') ?>" class="transaction-filter-form">
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
                <a class="btn btn-outline" href="<?= site_url('report') ?>">Hari Ini</a>
            </div>
        </form>
    </div>
</section>

<section class="receipt-layout">
    <section class="card transaction-list-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Penjualan Harian</h3>
                <p>Agregasi transaksi berdasarkan tanggal.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table responsive-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Transaksi</th>
                            <th>Item</th>
                            <th>Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($dailySales === []): ?>
                            <tr>
                                <td colspan="4" class="empty-cell">Belum ada transaksi pada periode ini.</td>
                            </tr>
                        <?php endif ?>

                        <?php foreach ($dailySales as $row): ?>
                            <tr>
                                <td data-label="Tanggal"><strong><?= esc($row->date) ?></strong></td>
                                <td data-label="Transaksi"><?= esc((string) $row->transaction_count) ?></td>
                                <td data-label="Item"><?= esc((string) $row->total_items) ?></td>
                                <td data-label="Omzet"><strong><?= esc(rupiah($row->total_sales)) ?></strong></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="card transaction-items-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Produk Terlaris</h3>
                <p>Top 10 produk berdasarkan qty terjual.</p>
            </div>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table responsive-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($topProducts === []): ?>
                            <tr>
                                <td colspan="3" class="empty-cell">Belum ada produk terjual.</td>
                            </tr>
                        <?php endif ?>

                        <?php foreach ($topProducts as $row): ?>
                            <tr>
                                <td data-label="Produk"><strong><?= esc($row->product_name) ?></strong></td>
                                <td data-label="Qty">
                                    <span class="stock-pill"><?= esc((string) $row->total_qty) ?></span>
                                </td>
                                <td data-label="Omzet"><strong><?= esc(rupiah($row->total_sales)) ?></strong></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</section>
<?= $this->endSection() ?>
