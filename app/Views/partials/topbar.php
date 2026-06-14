<header class="topbar">
    <div class="breadcrumb">
        <span>Overview</span>
        <span>/</span>
        <strong><?= esc($title ?? 'POS Warung') ?></strong>
    </div>
    <div class="topbar-actions">
        <a class="icon-btn" href="<?= site_url('pos') ?>" aria-label="Keranjang">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h8.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span class="cart-count" data-cart-count hidden>0</span>
        </a>
        <button class="icon-btn" type="button" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
            <span class="notification-dot"></span>
        </button>
        <a class="btn btn-outline btn-sm" href="<?= site_url('logout') ?>">Logout</a>
    </div>
</header>
