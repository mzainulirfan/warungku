const createConfirmModal = () => {
    const modal = document.createElement('div');
    modal.className = 'confirm-modal';
    modal.setAttribute('role', 'dialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-labelledby', 'confirm-modal-title');
    modal.setAttribute('aria-describedby', 'confirm-modal-message');
    modal.hidden = true;
    modal.innerHTML = `
        <div class="confirm-modal__backdrop" data-confirm-cancel></div>
        <div class="confirm-modal__panel" role="document">
            <div class="confirm-modal__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 9v4"></path><path d="M12 17h.01"></path><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path></svg>
            </div>
            <div class="confirm-modal__content">
                <h3 id="confirm-modal-title">Konfirmasi aksi</h3>
                <p id="confirm-modal-message"></p>
            </div>
            <div class="confirm-modal__actions">
                <button class="btn btn-outline" type="button" data-confirm-cancel>Batal</button>
                <button class="btn btn-primary" type="button" data-confirm-accept>Ya, lanjutkan</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    return modal;
};

const confirmModal = createConfirmModal();
const confirmMessage = confirmModal.querySelector('#confirm-modal-message');
const confirmAccept = confirmModal.querySelector('[data-confirm-accept]');
let pendingConfirmTrigger = null;

const closeConfirmModal = () => {
    confirmModal.hidden = true;
    document.body.classList.remove('modal-open');
    pendingConfirmTrigger?.focus();
    pendingConfirmTrigger = null;
};

const openConfirmModal = (trigger) => {
    pendingConfirmTrigger = trigger;
    confirmMessage.textContent = trigger.dataset.confirm || 'Apakah Anda yakin ingin melanjutkan?';
    confirmModal.hidden = false;
    document.body.classList.add('modal-open');
    confirmAccept.focus();
};

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-confirm]');

    if (! trigger || trigger.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();
    openConfirmModal(trigger);
});

confirmModal.addEventListener('click', (event) => {
    if (event.target.closest('[data-confirm-cancel]')) {
        closeConfirmModal();
    }
});

confirmAccept.addEventListener('click', () => {
    if (! pendingConfirmTrigger) {
        closeConfirmModal();
        return;
    }

    const trigger = pendingConfirmTrigger;
    trigger.dataset.confirmed = 'true';
    closeConfirmModal();

    const form = trigger.closest('form');
    if (form) {
        if (typeof form.requestSubmit === 'function') {
            form.requestSubmit(trigger);
        } else {
            form.submit();
        }
        return;
    }

    if (trigger.href) {
        window.location.href = trigger.href;
    } else {
        trigger.click();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && ! confirmModal.hidden) {
        closeConfirmModal();
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

const posCartStorageKey = 'warungku.pos.cart';
const posPaymentStorageKey = 'warungku.pos.payment';
const posNoteStorageKey = 'warungku.pos.note';

const cartCountBadges = document.querySelectorAll('[data-cart-count]');

const getStoredCartCount = () => {
    try {
        const items = JSON.parse(localStorage.getItem(posCartStorageKey) || '[]');

        if (!Array.isArray(items)) {
            return 0;
        }

        return items.reduce((total, item) => total + Math.max(0, Number(item.qty) || 0), 0);
    } catch (error) {
        return 0;
    }
};

const updateHeaderCartCount = () => {
    const count = getStoredCartCount();

    cartCountBadges.forEach((badge) => {
        badge.textContent = count > 99 ? '99+' : String(count);
        badge.hidden = count === 0;
    });
};

if (new URLSearchParams(window.location.search).get('clear_cart') === '1') {
    localStorage.removeItem(posCartStorageKey);
    localStorage.removeItem(posPaymentStorageKey);
    localStorage.removeItem(posNoteStorageKey);
    window.history.replaceState({}, document.title, `${window.location.pathname}${window.location.hash}`);
}

updateHeaderCartCount();

const posRoot = document.querySelector('[data-pos]');

if (posRoot) {
    const currencySymbol = posRoot.dataset.currencySymbol || 'Rp';
    const formatter = new Intl.NumberFormat('id-ID', {
        maximumFractionDigits: 0,
    });
    const cart = new Map();
    const cartList = posRoot.querySelector('[data-cart-list]');
    const cartJson = posRoot.querySelector('[data-cart-json]');
    const cartTotal = posRoot.querySelector('[data-cart-total]');
    const cartChange = posRoot.querySelector('[data-cart-change]');
    const paymentInput = posRoot.querySelector('[data-payment-input]');
    const noteInput = posRoot.querySelector('[name="note"]');
    const posForm = posRoot.querySelector('[data-pos-form]');
    const cartPanelCount = posRoot.querySelector('[data-cart-panel-count]');
    let lastSuggestedPayment = 0;
    let paymentTouched = false;

    const escapeHtml = (value) => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const formatMoney = (amount) => `${currencySymbol} ${formatter.format(amount)}`;

    const suggestedPaymentFor = (total) => {
        if (total <= 0) {
            return 0;
        }

        if (total <= 10000) {
            return Math.ceil(total / 1000) * 1000;
        }

        if (total <= 50000) {
            return Math.ceil(total / 5000) * 5000;
        }

        if (total <= 100000) {
            return Math.ceil(total / 10000) * 10000;
        }

        return Math.ceil(total / 50000) * 50000;
    };

    const cartArray = () => Array.from(cart.values()).map((item) => ({
        id: item.id,
        qty: item.qty,
    }));

    const persistCart = () => {
        const items = Array.from(cart.values()).map((item) => ({
            id: item.id,
            name: item.name,
            price: item.price,
            qty: item.qty,
            stock: item.stock,
        }));

        localStorage.setItem(posCartStorageKey, JSON.stringify(items));
        localStorage.setItem(posPaymentStorageKey, paymentInput?.value || '0');
        localStorage.setItem(posNoteStorageKey, noteInput?.value || '');
        updateHeaderCartCount();
    };

    const restoreCart = () => {
        try {
            const savedItems = JSON.parse(localStorage.getItem(posCartStorageKey) || '[]');

            if (Array.isArray(savedItems)) {
                savedItems.forEach((item) => {
                    const id = Number(item.id);
                    const qty = Number(item.qty);
                    const price = Number(item.price);
                    const stock = Number(item.stock);

                    if (!id || qty <= 0 || price < 0 || stock <= 0) {
                        return;
                    }

                    cart.set(id, {
                        id,
                        name: item.name || 'Produk',
                        price,
                        qty: Math.min(qty, stock),
                        stock,
                    });
                });
            }
        } catch (error) {
            localStorage.removeItem(posCartStorageKey);
        }

        if (paymentInput) {
            paymentInput.value = localStorage.getItem(posPaymentStorageKey) || paymentInput.value || '0';
        }

        if (noteInput) {
            noteInput.value = localStorage.getItem(posNoteStorageKey) || noteInput.value || '';
        }
    };

    const calculateTotal = () => Array.from(cart.values()).reduce((total, item) => total + (item.price * item.qty), 0);

    const syncCart = () => {
        const total = calculateTotal();
        const currentPayment = Number(paymentInput?.value || 0);
        const suggestedPayment = suggestedPaymentFor(total);
        const shouldUseSuggestion = paymentInput
            && suggestedPayment > 0
            && (!paymentTouched || currentPayment === 0 || currentPayment === lastSuggestedPayment);

        if (shouldUseSuggestion) {
            paymentInput.value = String(suggestedPayment);
            lastSuggestedPayment = suggestedPayment;
        }

        const payment = Number(paymentInput?.value || 0);
        const change = Math.max(payment - total, 0);
        const itemCount = Array.from(cart.values()).reduce((count, item) => count + item.qty, 0);

        cartJson.value = JSON.stringify(cartArray());
        cartTotal.textContent = formatMoney(total);
        cartChange.textContent = formatMoney(change);
        if (cartPanelCount) {
            cartPanelCount.textContent = `${itemCount} item`;
        }
        persistCart();
    };

    const renderCart = () => {
        if (cart.size === 0) {
            cartList.innerHTML = '<div class="empty-state"><p>Keranjang masih kosong.</p></div>';
            syncCart();
            return;
        }

        cartList.innerHTML = Array.from(cart.values()).map((item) => `
            <div class="cart-item" data-cart-item="${item.id}">
                <div class="cart-item-header">
                    <div>
                        <h4>${escapeHtml(item.name)}</h4>
                        <p>${formatMoney(item.price)} x ${item.qty}</p>
                    </div>
                    <strong>${formatMoney(item.price * item.qty)}</strong>
                </div>
                <div class="cart-item-controls">
                    <input class="cart-qty" type="number" min="1" max="${item.stock}" value="${item.qty}" aria-label="Qty ${escapeHtml(item.name)}" data-cart-qty>
                    <button class="btn btn-outline btn-sm" type="button" data-cart-remove>Hapus</button>
                </div>
            </div>
        `).join('');
        syncCart();
    };

    const addProduct = (button) => {
        const id = Number(button.dataset.id);
        const existing = cart.get(id);
        const stock = Number(button.dataset.stock);

        if (existing) {
            existing.qty = Math.min(existing.qty + 1, existing.stock);
        } else {
            cart.set(id, {
                id,
                name: button.dataset.name,
                price: Number(button.dataset.price),
                qty: 1,
                stock,
            });
        }

        renderCart();
    };

    const clearCart = () => {
        cart.clear();
        lastSuggestedPayment = 0;
        paymentTouched = false;

        if (paymentInput) {
            paymentInput.value = '0';
        }

        if (noteInput) {
            noteInput.value = '';
        }

        localStorage.removeItem(posCartStorageKey);
        localStorage.removeItem(posPaymentStorageKey);
        localStorage.removeItem(posNoteStorageKey);
        renderCart();
    };

    posRoot.addEventListener('click', (event) => {
        const addButton = event.target.closest('[data-add-product]');
        if (addButton) {
            addProduct(addButton);
            return;
        }

        const clearButton = event.target.closest('[data-cart-clear]');
        if (clearButton) {
            if (clearButton.dataset.confirmed !== 'true') {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            clearButton.dataset.confirmed = 'false';
            clearCart();
            return;
        }

        const removeButton = event.target.closest('[data-cart-remove]');
        if (removeButton) {
            const item = removeButton.closest('[data-cart-item]');
            cart.delete(Number(item.dataset.cartItem));
            renderCart();
        }
    });

    posRoot.addEventListener('input', (event) => {
        if (event.target.matches('[data-cart-qty]')) {
            const item = event.target.closest('[data-cart-item]');
            const cartItem = cart.get(Number(item.dataset.cartItem));
            const qty = Math.max(1, Math.min(Number(event.target.value || 1), cartItem.stock));
            cartItem.qty = qty;
            event.target.value = String(qty);
            renderCart();
            return;
        }

        if (event.target === paymentInput) {
            paymentTouched = true;
            syncCart();
            return;
        }

        if (event.target === noteInput) {
            persistCart();
        }
    });

    posForm?.addEventListener('submit', () => {
        syncCart();
    });

    restoreCart();
    renderCart();
}
