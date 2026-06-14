<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="page-header">
    <div>
        <h2>Halo, <?= esc(session()->get('user_name')) ?></h2>
        <p>Ringkasan performa warung hari ini.</p>
    </div>
    <div class="page-actions">
        <span class="date-pill"><?= esc(date('d M Y')) ?></span>
        <a class="btn btn-primary" href="<?= site_url('pos') ?>">Buka Kasir</a>
    </div>
</section>

<section class="stats-grid">
    <article class="card stat-card">
        <div class="stat-card-header">
            <span>Penjualan Hari Ini</span>
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </span>
        </div>
        <strong><?= esc(rupiah($totalSales)) ?></strong>
        <p><span class="trend trend-up">Hari ini</span> dari transaksi tercatat</p>
    </article>
    <article class="card stat-card">
        <div class="stat-card-header">
            <span>Transaksi Hari Ini</span>
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 2h12v20l-3-2-3 2-3-2-3 2z"></path><path d="M9 7h6M9 11h6M9 15h4"></path></svg>
            </span>
        </div>
        <strong><?= esc((string) $totalTransactions) ?></strong>
        <p><span class="trend trend-neutral">Nota</span> sudah dibuat</p>
    </article>

    <?php if ($isAdmin): ?>
        <article class="card stat-card">
            <div class="stat-card-header">
                <span>Produk Aktif</span>
                <span class="stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                </span>
            </div>
            <strong><?= esc((string) $activeProducts) ?></strong>
            <p><span class="trend trend-up">Aktif</span> siap dijual</p>
        </article>
        <article class="card stat-card">
            <div class="stat-card-header">
                <span>Kategori</span>
                <span class="stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path></svg>
                </span>
            </div>
            <strong><?= esc((string) $totalCategories) ?></strong>
            <p><span class="trend trend-neutral">Grup</span> produk tersedia</p>
        </article>
    <?php endif ?>
</section>

<section class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Transaksi Terakhir Hari Ini</h3>
            <p>Menampilkan 5 transaksi terbaru.</p>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($latestTransactions === []): ?>
                        <tr>
                            <td colspan="4" class="empty-cell">Belum ada transaksi hari ini.</td>
                        </tr>
                    <?php endif ?>

                    <?php foreach ($latestTransactions as $transaction): ?>
                        <tr>
                            <td><?= esc($transaction->invoice_no) ?></td>
                            <td><?= esc($transaction->cashier_name) ?></td>
                            <td><?= esc(rupiah($transaction->total_amount)) ?></td>
                            <td><?= esc($transaction->created_at) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
