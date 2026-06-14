<?php
$role = session()->get('user_role');
$roleLabel = $role === 'admin' ? 'Administrator' : 'Kasir';
$currentPath = trim(uri_string(), '/');

$isActive = static function (string $path) use ($currentPath): string {
    $path = trim($path, '/');

    return $currentPath === $path || str_starts_with($currentPath, $path . '/') ? ' active' : '';
};
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="brand-mark" aria-hidden="true">
            <span></span><span></span><span></span><span></span>
        </span>
        <div>
            <strong><?= esc(setting('store_name', 'Warung Sederhana')) ?></strong>
            <small>POS Warung</small>
        </div>
        <button class="sidebar-close" type="button" aria-label="Tutup menu" data-sidebar-close>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
        </button>
    </div>

    <div class="sidebar-search">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
        <span>Cari menu</span>
        <kbd>/</kbd>
    </div>

    <nav class="sidebar-nav">
        <span class="nav-section">Main Navigation</span>
        <a class="nav-item<?= $isActive('dashboard') ?>" href="<?= site_url('dashboard') ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
            Dashboard
        </a>
        <a class="nav-item<?= $isActive('pos') ?>" href="<?= site_url('pos') ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 2h12v20l-3-2-3 2-3-2-3 2z"></path><path d="M9 7h6M9 11h6M9 15h4"></path></svg>
            Kasir
        </a>
        <a class="nav-item<?= $isActive('transaction') ?>" href="<?= site_url('transaction') ?>">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3v18h18"></path><path d="m7 14 4-4 3 3 5-6"></path></svg>
            Transaksi
        </a>

        <?php if ($role === 'admin'): ?>
            <span class="nav-section">Admin</span>
            <a class="nav-item<?= $isActive('product') ?>" href="<?= site_url('product') ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><path d="m3.3 7 8.7 5 8.7-5M12 22V12"></path></svg>
                Produk
            </a>
            <a class="nav-item<?= $isActive('category') ?>" href="<?= site_url('category') ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.59 13.41 13.42 20.58a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><path d="M7 7h.01"></path></svg>
                Kategori
            </a>
            <a class="nav-item<?= $isActive('user') ?>" href="<?= site_url('user') ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                User
            </a>
            <a class="nav-item<?= $isActive('setting') ?>" href="<?= site_url('setting') ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-.4-1.1 1.65 1.65 0 0 0-1-.6 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.1-.4 1.65 1.65 0 0 0 .6-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-.6 1.65 1.65 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 .4 1.1 1.65 1.65 0 0 0 1 .6 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.12.38.33.72.6 1 .3.27.7.4 1.1.4H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.1.4c-.27.28-.48.62-.6 1z"></path></svg>
                Setting
            </a>
        <?php endif ?>
    </nav>

    <div class="sidebar-user">
        <span class="avatar"><?= esc(strtoupper(substr((string) session()->get('user_name'), 0, 1))) ?></span>
        <div>
            <strong><?= esc(session()->get('user_name')) ?></strong>
            <small><?= esc($roleLabel) ?></small>
        </div>
    </div>
</aside>
