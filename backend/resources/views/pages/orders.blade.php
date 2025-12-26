<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan & Ulasan - Wida Collection</title>
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>

<body class="font-poppins bg-gray-50">
    <header class="bg-gradient-to-r from-primary via-secondary to-dark text-white">
        <div class="max-w-6xl mx-auto px-4 py-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.4em] text-white/70 wc-reveal" style="--reveal-delay: 40ms;">
                    Dashboard Pesanan</p>
                <h1 class="text-3xl md:text-4xl font-bold wc-reveal" style="--reveal-delay: 120ms;">Riwayat Pesanan &
                    Ulasan</h1>
                <p class="text-white/80 wc-reveal" style="--reveal-delay: 190ms;">Berikan ulasan jujur untuk membantu
                    kurator memilih koleksi terbaik.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="profile"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 border border-white/30 font-semibold hover:bg-white/20 transition wc-reveal"
                    style="--reveal-delay: 260ms;">
                    <i class="fas fa-user"></i> Kembali ke Profil
                </a>
                <a href="body"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white text-primary font-semibold shadow wc-reveal"
                    style="--reveal-delay: 320ms;">
                    <i class="fas fa-store"></i> Belanja Lagi
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-12 space-y-10">
        <section class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-dark">Pesanan Terakhir</h2>
                    <p class="text-gray-500 text-sm">Tuliskan ulasan setelah pesanan diterima untuk meningkatkan
                        reputasi produk.</p>
                </div>
                <div class="flex gap-3 text-sm text-gray-500">
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        Dikemas</div>
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                        Sedang dikirim</div>
                    <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        Selesai</div>
                </div>
            </div>
            <div id="ordersList" class="space-y-5"></div>
            <p id="ordersEmpty" class="text-center text-sm text-gray-400 py-6 hidden">Belum ada pesanan yang dapat
                ditampilkan.</p>
        </section>
    </main>

    <div id="reviewModal" class="fixed inset-0 z-50 hidden modal-bg place-items-center p-4">
        <div class="modal modal-content-custom w-full max-w-lg relative">
            <button id="closeReviewModal"
                class="absolute top-3 right-3 modal-close text-xl text-gray-500 w-8 h-8 rounded-full flex items-center justify-center">&times;</button>
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-r from-primary to-secondary text-white grid place-items-center">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-gray-400">Ulasan Pelanggan</p>
                    <h3 id="reviewModalTitle" class="text-lg font-semibold text-dark">Produk</h3>
                    <p id="reviewModalProduct" class="text-xs text-gray-400">#Order</p>
                </div>
            </div>
            <form id="reviewForm" class="space-y-4">
                <label class="text-sm font-semibold text-gray-600 block">
                    Beri Rating
                    <select name="rating" id="reviewRating"
                        class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                        required>
                        <option value="5">5 - Sangat puas</option>
                        <option value="4">4 - Puas</option>
                        <option value="3">3 - Cukup</option>
                        <option value="2">2 - Kurang</option>
                        <option value="1">1 - Tidak puas</option>
                    </select>
                </label>
                <label class="text-sm font-semibold text-gray-600 block">
                    Ceritakan pengalamanmu
                    <textarea name="comment" id="reviewComment" rows="4"
                        class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                        placeholder="Kualitas produk, kondisi paket, pelayanan, dll." required></textarea>
                </label>
                <p id="reviewStatus" class="text-sm text-green-600 hidden">Ulasan tersimpan.</p>
                <button type="submit" id="reviewSubmitBtn"
                    class="w-full rounded-full bg-secondary text-white font-semibold py-3 shadow hover:bg-secondary/90">Simpan
                    Ulasan</button>
            </form>
        </div>
    </div>

    <script src="js/profile-data.js"></script>
    <script src="js/fast-transitions.js"></script>
    <script src="js/reveal.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const REDIRECT_KEY = 'wc_login_redirect';
            const setLoginRedirect = (value) => {
                try {
                    sessionStorage.setItem(REDIRECT_KEY, String(value || ''));
                } catch (_) { }
            };
            const currentRelativeUrl = () => {
                const file = window.location.pathname.split('/').pop() || 'orders';
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

            let profile = null;
            const ordersList = document.getElementById('ordersList');
            const ordersEmpty = document.getElementById('ordersEmpty');
            const modal = document.getElementById('reviewModal');
            const modalTitle = document.getElementById('reviewModalTitle');
            const modalOrder = document.getElementById('reviewModalProduct');
            const closeModalBtn = document.getElementById('closeReviewModal');
            const form = document.getElementById('reviewForm');
            const ratingField = document.getElementById('reviewRating');
            const commentField = document.getElementById('reviewComment');
            const statusEl = document.getElementById('reviewStatus');
            const submitBtn = document.getElementById('reviewSubmitBtn');

            const escapeHTML = (value = '') => String(value ?? '')
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

            let currentOrders = [];
            let myReviews = [];

            const buildReviewMap = () => {
                const map = {};
                myReviews.forEach((review) => {
                    if (review?.order_uuid) {
                        map[String(review.order_uuid)] = review;
                    }
                });
                return map;
            };

            const renderOrders = () => {
                currentOrders = Array.isArray(currentOrders) ? currentOrders : [];
                const reviewMap = buildReviewMap();
                if (!currentOrders.length) {
                    ordersList.innerHTML = '';
                    ordersEmpty.classList.remove('hidden');
                    return;
                }
                ordersEmpty.classList.add('hidden');
                ordersList.innerHTML = currentOrders.map((order) => {
                    const review = reviewMap[order.uuid];
                    const statusBadge = order.status === 'delivered'
                        ? '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-600">Selesai</span>'
                        : order.status === 'packed'
                            ? '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Dikemas</span>'
                            : '<span class="px-3 py-1 rounded-full text-xs font-semibold bg-secondary/10 text-secondary">Dalam pengiriman</span>';
                    const reviewInfo = review
                        ? `<p class="text-sm text-gray-500 mt-1">Rating ${review.rating}/5 · "${escapeHTML(review.comment || '')}"</p>`
                        : '<p class="text-sm text-gray-400 mt-1">Belum ada ulasan. Bantu pelanggan lain dengan pengalamanmu.</p>';
                    const btnLabel = review ? 'Perbarui Ulasan' : 'Tulis Ulasan';
                    const btnClass = review
                        ? 'bg-secondary text-white hover:bg-secondary/90'
                        : 'border border-primary text-primary hover:bg-primary/5';
                    const canReview = order.status === 'delivered';
                    const reviewDisabledClass = canReview ? '' : 'opacity-60 cursor-not-allowed';
                    const receiveButton = order.status === 'shipped'
                        ? `<button data-receive-order="${escapeHTML(order.uuid)}" class="px-5 py-2 rounded-full font-semibold transition bg-primary text-white hover:bg-primary/90">Terima Pesanan</button>`
                        : '';
                    return `
                    <article class="p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Kode Pesanan</p>
                                <h3 class="text-xl font-semibold text-dark">#${escapeHTML(order.public_id || order.uuid)}</h3>
                                <p class="text-sm text-gray-500">${escapeHTML(order.placed_at ? new Date(order.placed_at).toLocaleDateString('id-ID') : '')}</p>
                            </div>
                            ${statusBadge}
                        </div>
                        <div class="flex flex-col gap-4 md:flex-row md:items-center">
                            <div class="w-24 h-24 rounded-2xl bg-gray-50 flex items-center justify-center overflow-hidden">
                                ${order.product_image ? `<img src="${escapeHTML(order.product_image)}" alt="${escapeHTML(order.product_title || 'Produk')}" class="w-full h-full object-cover">` : '<i class="fas fa-box text-2xl text-gray-300"></i>'}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-dark">${escapeHTML(order.product_title || 'Produk Wida Collection')}</p>
                                <p class="text-sm text-gray-500">${escapeHTML(order.status_note || '')}</p>
                                <p class="text-primary font-bold mt-1">${formatCurrency(order.total)}</p>
                                ${reviewInfo}
                            </div>
                            <div class="flex flex-wrap gap-3 justify-end">
                                ${receiveButton}
                                <button data-review-order="${escapeHTML(order.uuid)}" data-review-disabled="${canReview ? '0' : '1'}" class="px-5 py-2 rounded-full font-semibold transition ${btnClass} ${reviewDisabledClass}">${btnLabel}</button>
                            </div>
                        </div>
                    </article>
                `;
                }).join('');
            };

            const closeModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('grid');
                form.reset();
                statusEl.classList.add('hidden');
                submitBtn.textContent = 'Simpan Ulasan';
                activeOrder = null;
            };

            let activeOrder = null;

            const openModal = (order, review) => {
                activeOrder = order;
                modalTitle.textContent = order.product_title || 'Produk';
                modalOrder.textContent = `#${order.public_id || order.uuid}`;
                ratingField.value = review ? review.rating : '5';
                commentField.value = review ? review.comment : '';
                submitBtn.textContent = review ? 'Perbarui Ulasan' : 'Simpan Ulasan';
                statusEl.classList.add('hidden');
                modal.classList.remove('hidden');
                modal.classList.add('grid');
            };

            ordersList.addEventListener('click', (event) => {
                const receiveBtn = event.target.closest('[data-receive-order]');
                if (receiveBtn) {
                    const order = currentOrders.find((item) => item.uuid === receiveBtn.dataset.receiveOrder);
                    if (!order) return;
                    if (order.status !== 'shipped') return;
                    if (!confirm('Konfirmasi pesanan sudah diterima?')) return;
                    (async () => {
                        try {
                            await apiFetchJson(`/api/orders/${encodeURIComponent(order.uuid)}/received`, { method: 'PATCH' });
                            await loadOrdersAndReviews();
                            renderOrders();
                        } catch (error) {
                            const msg = error?.data?.message || error?.message || 'Gagal mengubah status.';
                            alert(msg);
                        }
                    })();
                    return;
                }

                const btn = event.target.closest('[data-review-order]');
                if (!btn) return;
                if (btn.dataset.reviewDisabled === '1') {
                    alert('Ulasan bisa diberikan setelah pesanan diterima.');
                    return;
                }
                const order = currentOrders.find((item) => item.uuid === btn.dataset.reviewOrder);
                if (!order) return;
                if (order.status !== 'delivered') {
                    alert('Ulasan bisa diberikan setelah pesanan diterima.');
                    return;
                }
                const reviewMap = buildReviewMap();
                openModal(order, reviewMap[order.uuid]);
            });

            closeModalBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                if (!activeOrder) return;
                const rating = Number(ratingField.value);
                if (!(rating >= 1 && rating <= 5)) {
                    alert('Pilih rating antara 1 sampai 5.');
                    return;
                }
                const comment = commentField.value.trim();
                if (!comment) {
                    alert('Tulis ulasan singkat.');
                    return;
                }
                try {
                    await apiFetchJson('/api/reviews', {
                        method: 'POST',
                        body: JSON.stringify({
                            product_id: String(activeOrder.product_id || ''),
                            order_uuid: String(activeOrder.uuid || ''),
                            rating,
                            comment,
                            author: String(profile?.name || ''),
                        }),
                    });
                    statusEl.textContent = 'Terima kasih! Ulasan tersimpan.';
                    statusEl.classList.remove('hidden');
                    await loadOrdersAndReviews();
                    renderOrders();
                    setTimeout(closeModal, 1200);
                } catch (error) {
                    const msg = error?.data?.message || error?.message || 'Gagal menyimpan ulasan.';
                    alert(msg);
                }
            });

            const loadOrdersAndReviews = async () => {
                const [ordersRes, reviewsRes] = await Promise.all([
                    apiFetchJson('/api/orders'),
                    apiFetchJson('/api/reviews/mine'),
                ]);
                currentOrders = Array.isArray(ordersRes?.data) ? ordersRes.data : [];
                myReviews = Array.isArray(reviewsRes?.data) ? reviewsRes.data : [];
            };

            (async () => {
                if (!window.AuthStore || !window.ProfileStore) {
                    alert('Sistem auth belum siap. Muat ulang halaman.');
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
                profile = ProfileStore.getProfileData();

                try {
                    await loadOrdersAndReviews();
                } catch (error) {
                    console.error('Gagal memuat data orders/reviews', error);
                    currentOrders = [];
                    myReviews = [];
                }
                renderOrders();
            })();
        });
    </script>
</body>

</html>