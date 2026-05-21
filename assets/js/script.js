 // ========== SIDE MENU (HAMBERGER) ==========
const menuToggle = document.getElementById('menuToggle');
const sideMenu = document.getElementById('sideMenu');
const menuOverlay = document.getElementById('menuOverlay');
const closeMenuBtn = document.getElementById('closeMenu');

function openSideMenu() {
    sideMenu.classList.add('active');
    menuOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSideMenu() {
    sideMenu.classList.remove('active');
    menuOverlay.classList.remove('active');
    document.body.style.overflow = '';
}

if (menuToggle) menuToggle.addEventListener('click', openSideMenu);
if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeSideMenu);
if (menuOverlay) menuOverlay.addEventListener('click', closeSideMenu);

document.querySelectorAll('.side-menu-links a').forEach(link => {
    link.addEventListener('click', closeSideMenu);
});

// ========== CART FUNCTIONALITY (with size & PHP Backend) ==========
let cart = []; // سيتم سحبها من الخادم

function fetchCart() {
    fetch('cart_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'get' })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            cart = data.cart;
            updateCartUI();
        }
    })
    .catch(err => console.error('Error fetching cart:', err));
}

function addToCart(productElement) {
    const id = productElement.getAttribute('data-id');
    const name = productElement.getAttribute('data-name');
    const price = parseFloat(productElement.getAttribute('data-price'));
    const img = productElement.getAttribute('data-img');
    const sizeSelect = productElement.querySelector('.ring-size-select');
    const selectedSize = sizeSelect ? sizeSelect.value : '';
    
    if (!selectedSize) {
        showNotification('الرجاء اختيار المقاس أولاً', 'error');
        return;
    }
    
    fetch('cart_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'add', id, name, price, img, size: selectedSize })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            cart = data.cart;
            updateCartUI();
            showNotification(`تم إضافة "${name} (مقاس ${selectedSize})" إلى السلة`, 'success');
            openCartSidebar();
        } else {
            showNotification(data.message || 'حدث خطأ', 'error');
            if (data.redirect === 'login') {
                setTimeout(() => {
                    // Check if we are in pages/ or root directory
                    if (window.location.pathname.includes('/pages/')) {
                        window.location.href = 'login.php';
                    } else {
                        window.location.href = 'pages/login.php';
                    }
                }, 2000);
            }
        }
    })
    .catch(err => console.error('Error adding to cart:', err));
}

function removeFromCart(cart_id) {
    fetch('cart_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'remove', cart_id })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            cart = data.cart;
            updateCartUI();
        }
    })
    .catch(err => console.error('Error removing from cart:', err));
}

function updateCartQuantity(cart_id, new_quantity) {
    if (new_quantity < 1) {
        removeFromCart(cart_id);
        return;
    }
    fetch('cart_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'update_quantity', cart_id: cart_id, quantity: new_quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            cart = data.cart;
            updateCartUI();
        }
    })
    .catch(err => console.error('Error updating cart quantity:', err));
}

function updateCartUI() {
    const cartItemsList = document.querySelector('.cart-content');
    const cartTotalSpan = document.getElementById('cartTotal');
    const cartCountSpans = document.querySelectorAll('.cart-count');
    
    if (!cartItemsList) return;
    
    if (!cart || cart.length === 0) {
        cartItemsList.innerHTML = '<p>السلة فارغة</p>';
        if (cartTotalSpan) cartTotalSpan.innerText = '0';
        cartCountSpans.forEach(span => span.innerText = '0');
        return;
    }
    
    let total = 0;
    let totalItems = 0;
    cartItemsList.innerHTML = '';
    cart.forEach(item => {
        total += parseFloat(item.price) * parseInt(item.quantity);
        totalItems += parseInt(item.quantity);
        const itemDiv = document.createElement('div');
        itemDiv.className = 'cart-item';
        itemDiv.innerHTML = `
            <div class="cart-item-info">
                <h4>${item.name}</h4>
                <small>مقاس: ${item.size} | ${currentCurrency === 'YER' ? Math.round(parseFloat(item.price) * currencyRate) : (parseFloat(item.price) * currencyRate).toFixed(2)}${currencySymbol}</small>
                
                <div class="cart-item-quantity">
                    <button class="qty-btn minus" data-cart-id="${item.cart_id}" data-qty="${item.quantity - 1}">-</button>
                    <span class="qty-val">${item.quantity}</span>
                    <button class="qty-btn plus" data-cart-id="${item.cart_id}" data-qty="${parseInt(item.quantity) + 1}">+</button>
                </div>
            </div>
            <button class="remove-item" data-cart-id="${item.cart_id}"><i class="fas fa-trash-alt"></i></button>
        `;
        cartItemsList.appendChild(itemDiv);
    });
    
    const displayTotal = currentCurrency === 'YER' ? Math.round(total * currencyRate) : (total * currencyRate).toFixed(2);
    if (cartTotalSpan) cartTotalSpan.innerText = displayTotal + currencySymbol;
    cartCountSpans.forEach(span => span.innerText = totalItems);
    
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const cart_id = btn.getAttribute('data-cart-id');
            removeFromCart(cart_id);
        });
    });

    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const cart_id = btn.getAttribute('data-cart-id');
            const new_qty = parseInt(btn.getAttribute('data-qty'));
            updateCartQuantity(cart_id, new_qty);
        });
    });
}

// Cart Sidebar
const cartBtn = document.getElementById('cartBtn');
const cartSidebar = document.getElementById('cartSidebar');
const cartOverlay = document.getElementById('cartOverlay');
const closeCart = document.getElementById('closeCart');

function openCartSidebar() {
    if (cartSidebar) cartSidebar.classList.add('active');
    if (cartOverlay) cartOverlay.style.display = 'block';
}

function closeCartSidebar() {
    if (cartSidebar) cartSidebar.classList.remove('active');
    if (cartOverlay) cartOverlay.style.display = 'none';
}

if (cartBtn) cartBtn.addEventListener('click', (e) => {
    e.preventDefault();
    openCartSidebar();
});
if (closeCart) closeCart.addEventListener('click', closeCartSidebar);
if (cartOverlay) cartOverlay.addEventListener('click', closeCartSidebar);

// Checkout
const checkoutBtn = document.getElementById('checkoutBtn');
if (checkoutBtn) {
    checkoutBtn.addEventListener('click', () => {
        if (!cart || cart.length === 0) {
            showNotification('السلة فارغة، أضف منتجات أولاً.', 'error');
            return;
        }
        window.location.href = 'checkout.php'; // توجيه لصفحة إتمام الطلب
    });
}

// ========== PRODUCT INFO MODAL ==========
const infoModal = document.getElementById('infoModal');
const modalImg = document.getElementById('modalImg');
const modalTitle = document.getElementById('modalTitle');
const modalDesc = document.getElementById('modalDesc');
const modalPrice = document.getElementById('modalPrice');
const closeModalBtn = document.getElementById('closeModalBtn');
const shareProductBtn = document.getElementById('shareProductBtn');
let currentProductShareData = null;

function showProductInfo(productElement) {
    const name = productElement.getAttribute('data-name');
    const price = productElement.getAttribute('data-price');
    const desc = productElement.getAttribute('data-desc');
    const imgSrc = productElement.getAttribute('data-img');
    const convertedPrice = currentCurrency === 'YER' ? Math.round(parseFloat(price) * currencyRate) : (parseFloat(price) * currencyRate).toFixed(2);

    if (modalTitle) modalTitle.textContent = name;
    if (modalDesc) modalDesc.textContent = desc;
    if (modalPrice) modalPrice.textContent = `السعر: ${convertedPrice}${currencySymbol}`;
    if (modalImg) {
        modalImg.src = imgSrc;
        modalImg.alt = name;
    }
    
    // إعداد بيانات المشاركة
    const productId = productElement.getAttribute('data-id');
    const productUrl = window.location.href;
    
    currentProductShareData = {
        title: name,
        text: desc,
        url: productUrl
    };

    if (infoModal) infoModal.classList.add('active');
}

if (shareProductBtn) {
    shareProductBtn.addEventListener('click', () => {
        if (currentProductShareData && navigator.share) {
            navigator.share(currentProductShareData)
            .catch(err => console.error('Error sharing:', err));
        } else if (currentProductShareData) {
            // Fallback: Copy to clipboard
            navigator.clipboard.writeText(currentProductShareData.url)
            .then(() => showNotification('تم نسخ الرابط!', 'success'))
            .catch(() => showNotification('فشل نسخ الرابط', 'error'));
        }
    });
}

document.querySelectorAll('.info-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const product = btn.closest('.product');
        if (product) showProductInfo(product);
    });
});
if (closeModalBtn) closeModalBtn.addEventListener('click', () => infoModal.classList.remove('active'));
if (infoModal) infoModal.addEventListener('click', (e) => {
    if (e.target === infoModal) infoModal.classList.remove('active');
});

// ========== SIZE GUIDE MODAL ==========
const sizeGuideModal = document.getElementById('sizeGuideModal');
const closeSizeGuide = document.getElementById('closeSizeGuide');
const closeSizeGuideBtn = document.getElementById('closeSizeGuideBtn');

function openSizeGuide(e) {
    e.preventDefault();
    if (sizeGuideModal) sizeGuideModal.classList.add('active');
}
function closeSizeGuideModal() {
    if (sizeGuideModal) sizeGuideModal.classList.remove('active');
}

document.querySelectorAll('.size-guide-link').forEach(link => {
    link.addEventListener('click', openSizeGuide);
});
if (closeSizeGuide) closeSizeGuide.addEventListener('click', closeSizeGuideModal);
if (closeSizeGuideBtn) closeSizeGuideBtn.addEventListener('click', closeSizeGuideModal);
if (sizeGuideModal) sizeGuideModal.addEventListener('click', (e) => {
    if (e.target === sizeGuideModal) closeSizeGuideModal();
});

// ========== ADD TO CART BUTTONS ==========
document.querySelectorAll('.add-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const product = this.closest('.product');
        if (product) addToCart(product);
    });
});

// ========== CATEGORY MODAL ==========
const categoryModal = document.getElementById('categoryModal');
if (categoryModal) {
    const closeModal = categoryModal.querySelector('.close');
    if (closeModal) closeModal.addEventListener('click', () => categoryModal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === categoryModal) categoryModal.style.display = 'none';
    });
}

// ========== NOTIFICATION FUNCTION ==========
function showNotification(message, type = 'success') {
    const existing = document.querySelector('.notification');
    if (existing) existing.remove();
    const notif = document.createElement('div');
    notif.className = `notification ${type}`;
    notif.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i><span>${message}</span>`;
    document.body.appendChild(notif);
    setTimeout(() => notif.classList.add('show'), 10);
    setTimeout(() => {
        notif.classList.remove('show');
        setTimeout(() => notif.remove(), 300);
    }, 3000);
}

// ========== HEADER SCROLL EFFECT ==========
window.addEventListener('scroll', () => {
    const header = document.getElementById('header');
    if (header && window.scrollY > 50) header.classList.add('scrolled');
    else if (header) header.classList.remove('scrolled');
});

// ========== INITIALIZE ==========
document.addEventListener('DOMContentLoaded', () => {
    fetchCart();
});

// ========== LIVE SEARCH ==========
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
let searchTimeout;

if (searchInput && searchResults) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            // Check if we are in pages/ or root directory for correct base url
            const depth = window.location.pathname.split('/').length - 3; // roughly estimate depth, or simpler:
            let baseUrl = '';
            if (window.location.pathname.includes('/pages/pages_footer/')) {
                baseUrl = '../../';
            } else if (window.location.pathname.includes('/pages/') || window.location.pathname.includes('/admin/')) {
                baseUrl = '../';
            }
            
            fetch(`${baseUrl}ajax_search.php?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                searchResults.innerHTML = '';
                let html = '';
                
                if (data.categories && data.categories.length > 0) {
                    html += '<div class="search-section-title">أقسام مطابقة</div>';
                    data.categories.forEach(cat => {
                        html += `<a href="${baseUrl}category.php?slug=${cat.slug}" class="search-item cat-item"><i class="fas fa-list"></i> ${cat.name}</a>`;
                    });
                }
                
                if (data.products && data.products.length > 0) {
                    html += '<div class="search-section-title">منتجات مطابقة</div>';
                    data.products.forEach(prod => {
                        html += `<a href="${baseUrl}search.php?q=${encodeURIComponent(prod.title)}" class="search-item prod-item">
                                    <img src="${baseUrl}${prod.image_url}" alt="${prod.title}">
                                    <div>
                                        <div class="search-prod-title">${prod.title}</div>
                                        <div class="search-prod-price">${currentCurrency === 'YER' ? Math.round(parseFloat(prod.price) * currencyRate) : (parseFloat(prod.price) * currencyRate).toFixed(2)}${currencySymbol}</div>
                                    </div>
                                 </a>`;
                    });
                }
                
                if (html === '') {
                    html = '<div class="search-no-results">لا توجد نتائج مطابقة</div>';
                } else {
                    html += `<a href="${baseUrl}search.php?q=${encodeURIComponent(query)}" class="search-all-btn">عرض كل النتائج</a>`;
                }
                
                searchResults.innerHTML = html;
                searchResults.style.display = 'block';
            })
            .catch(err => console.error('Search error:', err));
        }, 300); // 300ms debounce
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
}

// ========== COOKIES BANNER ==========
document.addEventListener("DOMContentLoaded", function() {
    const cookieBanner = document.getElementById("cookieConsentBanner");
    const acceptCookiesBtn = document.getElementById("acceptCookies");
    const declineCookiesBtn = document.getElementById("declineCookies");

    if (cookieBanner && acceptCookiesBtn && declineCookiesBtn) {
        // Check if user has already made a choice
        const cookieConsent = localStorage.getItem("cookieConsent");
        
        if (!cookieConsent) {
            // Show banner after a short delay
            setTimeout(() => {
                cookieBanner.classList.add("show");
            }, 1500);
        }

        acceptCookiesBtn.addEventListener("click", function() {
            localStorage.setItem("cookieConsent", "accepted");
            cookieBanner.classList.remove("show");
        });

        declineCookiesBtn.addEventListener("click", function() {
            localStorage.setItem("cookieConsent", "declined");
            cookieBanner.classList.remove("show");
        });
    }
});

// ========== HERO SLIDESHOW ==========
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('#heroSlideshow .slide');
    if (slides.length > 0) {
        let currentSlide = 0;
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 3000); // تغيير الصورة كل 3 ثواني
    }
});