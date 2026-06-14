document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-confirm]');

    if (trigger && ! window.confirm(trigger.dataset.confirm)) {
        event.preventDefault();
    }
});

const sidebar = document.querySelector('.sidebar');
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarCloseButtons = document.querySelectorAll('[data-sidebar-close]');

const closeSidebar = () => {
    sidebar?.classList.remove('is-open');
    sidebarToggle?.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('sidebar-open');
};

if (sidebar && sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        const isOpen = sidebar.classList.toggle('is-open');
        sidebarToggle.setAttribute('aria-expanded', String(isOpen));
        document.body.classList.toggle('sidebar-open', isOpen);
    });

    sidebar.querySelectorAll('.nav-item').forEach((item) => {
        item.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 980px)').matches) {
                closeSidebar();
            }
        });
    });
}

sidebarCloseButtons.forEach((button) => {
    button.addEventListener('click', closeSidebar);
});

window.addEventListener('resize', () => {
    if (! window.matchMedia('(max-width: 980px)').matches) {
        closeSidebar();
    }
});
