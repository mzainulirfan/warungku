<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= esc($title ?? 'POS Warung') ?> - <?= esc(setting('store_name', 'Warung Sederhana')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <div class="app-layout">
        <div class="mobile-shellbar">
            <div class="mobile-shellbar-left">
                <button class="sidebar-toggle" type="button" aria-label="Buka menu" aria-expanded="false" data-sidebar-toggle>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16"></path><path d="M4 12h16"></path><path d="M4 18h16"></path></svg>
                </button>
                <div class="mobile-brand">
                    <span class="brand-mark" aria-hidden="true">
                        <span></span><span></span><span></span><span></span>
                    </span>
                    <strong><?= esc(setting('store_name', 'Warung Sederhana')) ?></strong>
                </div>
            </div>
            <div class="mobile-shellbar-actions">
                <button class="icon-btn" type="button" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <span></span>
                </button>
                <a class="btn btn-outline btn-sm" href="<?= site_url('logout') ?>">Logout</a>
            </div>
        </div>
        <div class="sidebar-backdrop" data-sidebar-close></div>
        <?= $this->include('partials/sidebar') ?>
        <main class="main-content">
            <?= $this->include('partials/topbar') ?>
            <div class="page-content">
                <?= $this->include('partials/alerts') ?>
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
