<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Wida Collection</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>

<body class="font-poppins bg-gray-50">
    <div class="relative bg-gradient-to-br from-primary via-secondary to-dark text-white">
        <div class="max-w-6xl mx-auto px-4 py-12 pb-32 md:pb-40 space-y-6">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-white/70 wc-reveal"
                        style="--reveal-delay: 40ms;">Dashboard Thrifting</p>
                    <h1 class="text-4xl font-bold wc-reveal" style="--reveal-delay: 120ms;">Profil Pengguna</h1>
                    <p class="text-white/80 wc-reveal" style="--reveal-delay: 190ms;">Pantau aktivitasmu mulai dari
                        riwayat pesanan, wishlist, hingga preferensi live drop.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="body"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 border border-white/30 font-semibold hover:bg-white/20 transition wc-reveal"
                        style="--reveal-delay: 250ms;">
                        <i class="fas fa-store"></i> Kembali ke Beranda
                    </a>
                    <a href="cart"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white text-primary font-semibold shadow-lg wc-reveal"
                        style="--reveal-delay: 300ms;">
                        <i class="fas fa-shopping-bag"></i> Lihat Keranjang
                    </a>
                    <button id="logoutBtn"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-dark/40 border border-white/30 font-semibold hover:bg-dark/60 transition wc-reveal"
                        style="--reveal-delay: 350ms;">
                        <i class="fas fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <main class="relative z-10 max-w-6xl mx-auto px-4 -mt-20 md:-mt-32 pb-16 space-y-10">
        <section
            class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8 flex flex-col lg:flex-row gap-8">
            <div class="flex flex-col items-center text-center gap-4 lg:w-1/3">
                <div
                    class="w-32 h-32 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-4xl font-bold text-white shadow-lg overflow-hidden skeleton-shimmer">
                    <img data-wc-avatar-img class="hidden w-full h-full object-cover" alt="Foto profil" />
                    <span id="profileAvatarInitial" data-wc-avatar-fallback></span>
                </div>
                <div>
                    <h2 id="profileName"
                        class="text-2xl font-semibold text-dark min-h-[32px] rounded-md skeleton-shimmer w-48 mb-2">
                    </h2>
                    <p id="profileBio" class="text-gray-500 min-h-[24px] rounded-md skeleton-shimmer w-32"></p>
                </div>
                <div class="flex flex-wrap justify-center gap-3 text-sm text-gray-500">
                    <span id="profileTier" class="px-3 py-1 rounded-full bg-light text-dark font-medium hidden"></span>
                    <span id="profileSince"
                        class="px-3 py-1 rounded-full bg-primary/10 text-primary font-medium hidden"></span>
                </div>
                <a href="edit-profile" class="btn-main px-8 text-center">Edit Profil</a>
            </div>
            <div class="flex-1 grid sm:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-light to-white rounded-2xl border border-white shadow p-5 space-y-3">
                    <div class="flex items-center gap-3 text-gray-400 text-sm uppercase tracking-[0.3em]">
                        <i class="fas fa-envelope text-primary"></i> Kontak
                    </div>
                    <p id="profileEmail"
                        class="text-dark font-semibold min-h-[24px] rounded skeleton-shimmer w-3/4 mb-2"></p>
                    <p id="profilePhone" class="text-gray-500 min-h-[24px] rounded skeleton-shimmer w-1/2 mb-2"></p>
                    <p id="profileCity" class="text-gray-500 min-h-[24px] rounded skeleton-shimmer w-2/3"></p>
                </div>
                <div class="bg-gradient-to-br from-light to-white rounded-2xl border border-white shadow p-5 space-y-3">
                    <div class="flex items-center gap-3 text-gray-400 text-sm uppercase tracking-[0.3em]">
                        <i class="fas fa-fire text-primary"></i> Progress
                    </div>
                    <p class="text-dark font-semibold">Tier berikutnya: Iconic</p>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="h-3 bg-gradient-to-r from-primary to-secondary rounded-full" style="width: 72%">
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Belanja 3 kali lagi untuk membuka akses drop eksklusif.</p>
                </div>
                <div class="bg-gradient-to-br from-light to-white rounded-2xl border border-white shadow p-5 space-y-3">
                    <div class="flex items-center gap-3 text-gray-400 text-sm uppercase tracking-[0.3em]">
                        <i class="fas fa-coins text-primary"></i> Points
                    </div>
                    <p class="text-3xl font-bold text-dark">0</p>
                    <p class="text-sm text-gray-500">Tukar 1.000 poin untuk free ongkir kapan saja.</p>
                </div>
                <div class="bg-gradient-to-br from-light to-white rounded-2xl border border-white shadow p-5 space-y-3">
                    <div class="flex items-center gap-3 text-gray-400 text-sm uppercase tracking-[0.3em]">
                        <i class="fas fa-ticket text-primary"></i> Voucher aktif
                    </div>
                    <p class="text-dark font-semibold">Drop Friday Fever</p>
                    <p class="text-sm text-gray-500">Diskon 12% untuk kategori outerwear hingga 30 Nov.</p>
                </div>
            </div>
        </section>

        <section class="grid lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl shadow border border-gray-100 p-6 space-y-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-dark">Pesanan Terakhir</h3>
                        <p class="text-gray-500 text-sm">Pantau status paket dalam satu tempat.</p>
                    </div>
                    <a href="orders" class="text-primary text-sm font-semibold">Lihat Semua</a>
                </div>
                <div id="recentOrdersList" class="space-y-4"></div>
                <p id="recentOrdersEmpty" class="text-sm text-gray-400 hidden">Belum ada riwayat pesanan.</p>
            </div>

            <div class="bg-white rounded-3xl shadow border border-gray-100 p-6 space-y-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-dark">Alamat & Pengiriman</h3>
                        <p class="text-gray-500 text-sm">Kelola alamat favorit untuk checkout cepat.</p>
                    </div>
                    <a href="edit-profile#addressForm" class="text-primary text-sm font-semibold">Kelola Alamat</a>
                </div>
                <div id="profileAddresses" class="space-y-4"></div>
            </div>
        </section>

        <section class="grid lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl shadow border border-gray-100 p-6 space-y-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-semibold text-dark">Wishlist Aktif</h3>
                        <p class="text-gray-500 text-sm">Item akan diprioritaskan saat buka bal.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="wishlist"
                            class="inline-flex items-center gap-1 px-4 py-2 rounded-full border border-primary/40 text-primary text-sm font-semibold hover:bg-primary/5 transition">
                            <i class="fas fa-list-check"></i>
                            Kelola Wishlist
                        </a>
                        <a href="body#products"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary text-white text-sm font-semibold shadow hover:bg-primary/90 transition">
                            <i class="fas fa-plus"></i>
                            Tambah Produk
                        </a>
                    </div>
                </div>
                <div id="wishlistList" class="space-y-4"></div>
                <p id="wishlistEmpty" class="text-sm text-gray-400 hidden">Belum ada item wishlist. Tambahkan melalui
                    katalog produk.</p>
            </div>
        </section>
    </main>

    <script src="js/reveal.js" defer></script>
    <script src="js/profile-data.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const REDIRECT_KEY = 'wc_login_redirect';
            const setLoginRedirect = (value) => {
                try {
                    sessionStorage.setItem(REDIRECT_KEY, String(value || ''));
                } catch (_) { }
            };
            const currentRelativeUrl = () => {
                const file = window.location.pathname.split('/').pop() || 'profile';
                return `${file}${window.location.search || ''}${window.location.hash || ''}`;
            };

            const apiFetchJson = async (url, options = {}) => {
                const res = await fetch(url, {
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        ...(options.headers || {}),
                    },
                    ...options,
                });
                const data = await res.json().catch(() => null);
                if (!res.ok) {
                    const err = new Error(data?.message || `Request failed (${res.status})`);
                    err.status = res.status;
                    err.data = data;
                    throw err;
                }
                return data;
            };

            const escapeHTML = (value = '') =>
                String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');

            const formatCurrency = (value = 0) => {
                try {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(Number(value) || 0);
                } catch (_) {
                    return `Rp ${(Number(value) || 0).toLocaleString('id-ID')}`;
                }
            };

            const setText = (id, value) => {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = value || '-';
                    el.classList.remove('hidden');
                    el.classList.remove('skeleton-shimmer');
                    el.style.width = 'auto'; // Reset width from skeleton state
                }
            };

            const addressContainer = document.getElementById('profileAddresses');
            const wishlistContainer = document.getElementById('wishlistList');
            const wishlistEmpty = document.getElementById('wishlistEmpty');
            const recentOrdersContainer = document.getElementById('recentOrdersList');
            const recentOrdersEmpty = document.getElementById('recentOrdersEmpty');

            const renderAddresses = (profile) => {
                if (!addressContainer) return;
                const addresses = Array.isArray(profile?.addresses) ? profile.addresses : [];
                if (!addresses.length) {
                    addressContainer.innerHTML = '<p class="text-gray-400 text-sm">Belum ada alamat tersimpan.</p>';
                    return;
                }
                addressContainer.innerHTML = addresses
                    .map(
                        (addr) => `
                        <article class="p-4 border ${addr.isPrimary ? 'border-primary/30 bg-primary/5' : 'border-gray-100'} rounded-2xl">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-dark">${escapeHTML(addr.label)}</p>
                                    <p class="text-gray-500 text-sm">${escapeHTML(addr.detail)}</p>
                                    <p class="text-sm text-gray-400">${escapeHTML(addr.recipient)} &middot; ${escapeHTML(addr.phone)}</p>
                                </div>
                                ${addr.isPrimary ? '<span class="text-xs px-3 py-1 rounded-full bg-primary text-white">Utama</span>' : ''}
                            </div>
                        </article>
                    `,
                    )
                    .join('');
            };

            const renderWishlist = () => {
                if (!wishlistContainer || !wishlistEmpty) return;
                if (!window.ProfileStore || typeof ProfileStore.getWishlist !== 'function') {
                    wishlistContainer.innerHTML = '';
                    wishlistEmpty.classList.remove('hidden');
                    return;
                }
                const wishlist = ProfileStore.getWishlist();
                if (!wishlist.length) {
                    wishlistContainer.innerHTML = '';
                    wishlistEmpty.classList.remove('hidden');
                    return;
                }
                wishlistEmpty.classList.add('hidden');
                wishlistContainer.innerHTML = wishlist
                    .map(
                        (item) => `
                        <article class="p-4 rounded-2xl border border-gray-100 flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-50 to-white flex items-center justify-center overflow-hidden">
                                ${item.image ? `<img src="${escapeHTML(item.image)}" alt="${escapeHTML(item.title)}" class="w-12 h-12 object-contain" loading="lazy">` : '<span class="text-xs text-gray-400">No Image</span>'}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-dark">${escapeHTML(item.title)}</p>
                                <p class="text-sm text-gray-500">${escapeHTML(item.note || 'Belum ada catatan')}</p>
                            </div>
                            <div class="text-right">
                                ${item.badge ? `<p class="text-xs text-primary font-semibold mb-1">${escapeHTML(item.badge)}</p>` : ''}
                                <span class="text-primary font-bold">${formatCurrency(item.price)}</span>
                            </div>
                        </article>
                    `,
                    )
                    .join('');
            };

            const renderRecentOrders = async () => {
                if (!recentOrdersContainer || !recentOrdersEmpty) return;

                try {
                    const res = await apiFetchJson('/api/orders');
                    const orders = Array.isArray(res?.data) ? res.data : [];
                    const recent = orders.slice(0, 2);

                    if (!recent.length) {
                        recentOrdersContainer.innerHTML = '';
                        recentOrdersEmpty.classList.remove('hidden');
                        return;
                    }

                    recentOrdersEmpty.classList.add('hidden');
                    recentOrdersContainer.innerHTML = recent
                        .map((order) => {
                            const status = String(order?.status || '');
                            const statusNote = String(order?.status_note || '');
                            const statusClass = status === 'delivered' ? 'text-green-500' : status === 'shipped' ? 'text-secondary' : 'text-gray-500';
                            const statusIcon = status === 'delivered' ? 'fa-box-open text-secondary' : 'fa-box text-primary';
                            const image = String(order?.product_image || '');
                            const productTitle = String(order?.product_title || 'Produk');
                            const orderId = String(order?.public_id || order?.uuid || '');
                            const total = Number(order?.total) || 0;

                            return `
                            <div class="p-4 rounded-2xl border border-gray-100 flex gap-4 items-center">
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                    ${image ? `<img src="${escapeHTML(image)}" alt="${escapeHTML(productTitle)}" class="w-full h-full object-cover" loading="lazy" referrerpolicy="no-referrer">` : `<i class="fas ${statusIcon} text-xl"></i>`}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-dark">#${escapeHTML(orderId)}</p>
                                    <p class="text-sm text-gray-500">${escapeHTML(productTitle)}</p>
                                    <div class="text-xs ${statusClass} font-semibold">${escapeHTML(statusNote)}</div>
                                </div>
                                <span class="text-primary font-bold">${formatCurrency(total)}</span>
                            </div>
                        `;
                        })
                        .join('');
                } catch (err) {
                    recentOrdersContainer.innerHTML = '<p class="text-sm text-gray-400">Riwayat pesanan belum tersedia.</p>';
                    recentOrdersEmpty.classList.add('hidden');
                }
            };

            (async () => {
                if (!window.ProfileStore || !window.AuthStore) {
                    alert('Data profil tidak tersedia. Muat ulang halaman.');
                    return;
                }
                const me = await AuthStore.me();
                if (!me) {
                    const next = currentRelativeUrl();
                    setLoginRedirect(next);
                    window.location.href = `login?next=${encodeURIComponent(next)}`;
                    return;
                }

                await ProfileStore.ready;

                if (typeof AuthStore.isAdmin === 'function' && AuthStore.isAdmin()) {
                    window.location.href = 'profile-admin';
                    return;
                }

                const logoutBtn = document.getElementById('logoutBtn');
                logoutBtn?.addEventListener('click', async () => {
                    await AuthStore.logout();
                    window.location.href = 'body';
                });

                const profile = ProfileStore.getProfileData();
                const initials = profile?.name
                    ? String(profile.name)
                        .split(' ')
                        .map((part) => part.charAt(0))
                        .join('')
                        .slice(0, 2)
                        .toUpperCase()
                    : 'WC';

                if (profile?.avatarImage) {
                    const imgEl = document.querySelector('[data-wc-avatar-img]');
                    const fallbackEl = document.querySelector('[data-wc-avatar-fallback]');
                    if (imgEl) {
                        imgEl.src = profile.avatarImage;
                        imgEl.classList.remove('hidden');
                        if (fallbackEl) fallbackEl.classList.add('hidden');
                    }
                } else {
                    setText('profileAvatarInitial', initials);
                }

                // Remove loading skeleton from avatar container if needed
                const avatarContainer = document.querySelector('.w-32.h-32');
                if (avatarContainer) {
                    avatarContainer.classList.remove('animate-pulse');
                    avatarContainer.classList.remove('skeleton-shimmer');
                }

                setText('profileName', profile?.name);
                setText('profileBio', profile?.bio || 'Tambahkan bio di halaman edit profil.');
                setText('profileEmail', profile?.email);
                setText('profilePhone', profile?.phone);
                setText('profileCity', profile?.city);

                // Tier and Since badges are hidden by default to prevent FOUC
                const tierEl = document.getElementById('profileTier');
                if (tierEl) {
                    tierEl.textContent = profile?.tierLabel || 'Member Wida Collection';
                    tierEl.classList.remove('hidden');
                }
                const sinceEl = document.getElementById('profileSince');
                if (sinceEl) {
                    sinceEl.textContent = profile?.memberSince ? `Since ${profile.memberSince}` : 'Since 2021';
                    sinceEl.classList.remove('hidden');
                }

                renderAddresses(profile);
                renderWishlist();
                await renderRecentOrders();
            })();
        });
    </script>
</body>

</html>