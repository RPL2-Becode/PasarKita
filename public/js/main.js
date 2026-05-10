/**
 * PasarKita - Main JavaScript
 * Utility functions and interactive behaviors
 */

// ---- Flash Message Auto-dismiss ----
document.addEventListener('DOMContentLoaded', function() {
    const flashMsg = document.getElementById('msg-flash');
    if (flashMsg) {
        setTimeout(() => {
            flashMsg.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            flashMsg.style.opacity = '0';
            flashMsg.style.transform = 'translateY(-10px)';
            setTimeout(() => flashMsg.remove(), 500);
        }, 4000);
    }
});

// ---- Format currency to Rupiah ----
function formatRupiah(number) {
    return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// ---- Confirm Delete ----
function confirmDelete(formId, itemName) {
    if (confirm('Apakah Anda yakin ingin menghapus ' + (itemName || 'item ini') + '?')) {
        document.getElementById(formId).submit();
    }
}

// ---- Mobile Menu Toggle ----
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// ---- Cart Quantity Update ----
function updateQuantity(productId, action) {
    const qtyInput = document.getElementById('qty-' + productId);
    if (!qtyInput) return;

    let currentQty = parseInt(qtyInput.value) || 1;
    
    if (action === 'increase') {
        currentQty++;
    } else if (action === 'decrease' && currentQty > 1) {
        currentQty--;
    }

    qtyInput.value = currentQty;
    recalculateCart();
}

// ---- Recalculate Cart Totals ----
function recalculateCart() {
    let subtotal = 0;
    const items = document.querySelectorAll('.cart-item');
    
    items.forEach(item => {
        const price = parseFloat(item.dataset.price) || 0;
        const qty = parseInt(item.querySelector('.qty-input')?.value) || 1;
        subtotal += price * qty;
    });

    const fee = subtotal * 0.02;
    const shipping = 5000;
    const total = subtotal + fee + shipping;

    const elSubtotal = document.getElementById('cart-subtotal');
    const elFee = document.getElementById('cart-fee');
    const elShipping = document.getElementById('cart-shipping');
    const elTotal = document.getElementById('cart-total');

    if (elSubtotal) elSubtotal.textContent = formatRupiah(subtotal);
    if (elFee) elFee.textContent = formatRupiah(fee);
    if (elShipping) elShipping.textContent = formatRupiah(shipping);
    if (elTotal) elTotal.textContent = formatRupiah(total);

    // Update hidden fields
    const hiddenSubtotal = document.getElementById('hidden-subtotal');
    const hiddenFee = document.getElementById('hidden-fee');
    const hiddenShipping = document.getElementById('hidden-shipping');
    const hiddenTotal = document.getElementById('hidden-total');

    if (hiddenSubtotal) hiddenSubtotal.value = subtotal;
    if (hiddenFee) hiddenFee.value = fee;
    if (hiddenShipping) hiddenShipping.value = shipping;
    if (hiddenTotal) hiddenTotal.value = total;
}

// ---- Image Preview on Upload ----
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ---- Smooth Scroll ----
function scrollToElement(elementId) {
    const el = document.getElementById(elementId);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// ---- Search Debounce ----
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
