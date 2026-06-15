<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0; ?>

<section class="card dashboard-hero">
    <div class="dashboard-hero-copy">
        <span class="summary-label">Dashboard hari ini</span>
        <h2>Halo, <?= esc(session()->get('user_name')) ?></h2>
        <p>
            <?= $isAdmin
                ? 'Pantau omzet, transaksi, stok, dan aktivitas kasir dalam satu layar.'
                : 'Pantau transaksi hari ini dan lanjutkan proses kasir dari sini.' ?>
        </p>
    </div>
    <div class="dashboard-hero-actions">
        <span class="date-pill"><?= esc(date('d M Y')) ?></span>
        <a class="btn btn-primary" href="<?= site_url('pos') ?>">Buka Kasir</a>
        <a class="btn btn-outline" href="<?= site_url('transaction') ?>">Riwayat</a>
    </div>
</section>

<section class="stats-grid dashboard-stats-grid">
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

    <article class="card stat-card">
        <div class="stat-card-header">
            <span>Rata-rata Nota</span>
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
            </span>
        </div>
        <strong><?= esc(rupiah($averageTransaction)) ?></strong>
        <p><span class="trend trend-neutral">Rata-rata</span> per transaksi</p>
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
        <article class="card stat-card">
            <div class="stat-card-header">
                <span>Stok Rendah</span>
                <span class="stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
                </span>
            </div>
            <strong><?= esc((string) $lowStockProducts) ?></strong>
            <p><span class="trend trend-neutral">Produk</span> stok 5 atau kurang</p>
        </article>
    <?php endif ?>
</section>

<section class="dashboard-grid">
    <section class="card dashboard-main-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Transaksi Terakhir Hari Ini</h3>
                <p>Menampilkan 5 transaksi terbaru.</p>
            </div>
            <a class="btn btn-outline btn-sm" href="<?= site_url('transaction') ?>">Lihat Semua</a>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table class="table responsive-table">
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
                                <td data-label="Invoice">
                                    <div class="invoice-cell">
                                        <strong><?= esc($transaction->invoice_no) ?></strong>
                                        <small>#<?= esc((string) $transaction->id) ?></small>
                                    </div>
                                </td>
                                <td data-label="Kasir"><?= esc($transaction->cashier_name) ?></td>
                                <td data-label="Total"><strong><?= esc(rupiah($transaction->total_amount)) ?></strong></td>
                                <td data-label="Waktu"><?= esc($transaction->created_at) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <aside class="dashboard-side">
        <?php if ($isAdmin): ?>
            <section class="card dashboard-side-card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Produk Stok Rendah</h3>
                        <p>5 produk aktif dengan stok paling sedikit.</p>
                    </div>
                    <a class="btn btn-outline btn-sm" href="<?= site_url('product?stock_status=low') ?>">Kelola</a>
                </div>
                <div class="dashboard-stock-list">
                    <?php if ($lowStockList === []): ?>
                        <div class="dashboard-empty-note">Tidak ada produk stok rendah.</div>
                    <?php endif ?>

                    <?php foreach ($lowStockList as $product): ?>
                        <a class="dashboard-stock-item" href="<?= site_url('product/detail/' . $product->id) ?>">
                            <span>
                                <strong><?= esc($product->name) ?></strong>
                                <small><?= esc($product->category_name ?? '-') ?></small>
                            </span>
                            <em><?= esc((string) $product->stock) ?></em>
                        </a>
                    <?php endforeach ?>
                </div>
            </section>
        <?php endif ?>

        <section class="card dashboard-side-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Akses Cepat</h3>
                    <p>Menu kerja yang paling sering dipakai.</p>
                </div>
            </div>
            <div class="dashboard-quick-actions">
                <a class="quick-action-card" href="<?= site_url('pos') ?>">
                    <span>
                        <strong>Kasir POS</strong>
                        <small>Buat transaksi baru</small>
                    </span>
                    <em>Mulai</em>
                </a>
                <a class="quick-action-card" href="<?= site_url('transaction') ?>">
                    <span>
                        <strong>Riwayat</strong>
                        <small>Cek transaksi tersimpan</small>
                    </span>
                    <em>Lihat</em>
                </a>
                <?php if ($isAdmin): ?>
                    <a class="quick-action-card" href="<?= site_url('product') ?>">
                        <span>
                            <strong>Produk</strong>
                            <small>Kelola katalog dan stok</small>
                        </span>
                        <em>Kelola</em>
                    </a>
                    <a class="quick-action-card" href="<?= site_url('report') ?>">
                        <span>
                            <strong>Laporan</strong>
                            <small>Ringkasan penjualan</small>
                        </span>
                        <em>Buka</em>
                    </a>
                <?php endif ?>
            </div>
        </section>
    </aside>
</section>
<?= $this->endSection() ?>
