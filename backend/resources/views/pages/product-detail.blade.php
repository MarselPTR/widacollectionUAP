<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Wida Collection</title>
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>
<body class="font-poppins bg-gray-50">
    <header class="bg-linear-to-r from-primary via-secondary to-dark text-white">
        <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.4em] text-white/70 wc-reveal" style="--reveal-delay: 40ms;">Detail Produk</p>
                <h1 id="detailTitle" class="text-3xl md:text-4xl font-bold wc-reveal" style="--reveal-delay: 120ms;">Memuat produk...</h1>
                <p id="detailCategory" class="text-white/80 wc-reveal" style="--reveal-delay: 180ms;">Kategori | Tipe</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="body.html" class="inline-flex items-center gap-2 bg-white/15 border border-white/30 px-5 py-2 rounded-full font-semibold hover:bg-white/25 transition wc-reveal" style="--reveal-delay: 240ms;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Katalog
                </a>
                <a id="goToCartBtn" href="cart.html" class="inline-flex items-center gap-2 bg-white text-primary px-5 py-2 rounded-full font-semibold shadow wc-reveal" style="--reveal-delay: 300ms;">
                    <i class="fas fa-shopping-bag"></i> Lihat Keranjang
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10 space-y-10">
        <section class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-10 grid lg:grid-cols-2 gap-8">
            <div class="bg-linear-to-br from-gray-50 to-white rounded-2xl p-6 flex items-center justify-center">
                <img id="detailImage" src="" alt="Detail produk" class="w-full h-96 object-contain" loading="lazy">
            </div>
            <div class="flex flex-col gap-5">
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <span id="detailType" class="px-3 py-1 rounded-full bg-primary/10 text-primary font-semibold">Fashion</span>
                    <span id="detailSku" class="px-3 py-1 rounded-full bg-gray-100 text-gray-600">SKU: -</span>
                </div>
                <h2 id="detailName" class="text-3xl font-bold text-dark leading-tight">Memuat...</h2>
                <p id="detailDesc" class="text-gray-600 leading-relaxed">Deskripsi produk akan ditampilkan di sini.</p>
                <div class="flex flex-wrap items-center gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Harga</p>
                        <p id="detailPrice" class="text-3xl font-bold text-primary">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rating</p>
                        <p id="detailRating" class="text-2xl font-semibold text-yellow-500">0.0</p>
                        <p id="detailReviewCount" class="text-xs text-gray-400">0 ulasan</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Stock</p>
                        <p id="detailStock" class="text-2xl font-semibold text-dark">-</p>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Material</p>
                        <p id="detailMaterial" class="font-semibold text-dark">Premium Mix</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Kondisi</p>
                        <p id="detailCondition" class="font-semibold text-dark">Grade A Thrift</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4">
                    <button id="detailAddCart" class="btn-main flex-1">Tambah ke Keranjang</button>
                    <button id="detailWishlist" class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-gray-200 text-gray-600 font-semibold hover:border-secondary hover:text-secondary transition">
                        <i class="fas fa-heart"></i>
                        Simpan Wishlist
                    </button>
                    <button id="detailBuyNow" class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-primary text-primary font-semibold hover:bg-primary/5 transition">
                        Beli Sekarang <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </section>

        <section class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-3xl shadow border border-gray-100 p-6 space-y-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-star text-yellow-400 text-2xl"></i>
                    <div>
                        <p class="text-sm text-gray-500">Ulasan Terverifikasi</p>
                        <h3 class="text-2xl font-semibold text-dark">Apa kata mereka?</h3>
                    </div>
                </div>
                <div id="reviewList" class="space-y-4">
                    <!-- Filled by JS -->
                </div>
            </div>
            <div class="bg-white rounded-3xl shadow border border-gray-100 p-6 space-y-5">
                <h3 class="text-xl font-semibold text-dark">Highlight Produk</h3>
                <ul id="highlightList" class="space-y-3 text-gray-600">
                    <li class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>Material tebal & breathable</span></li>
                    <li class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>Standar kurasi Wida Collection</span></li>
                    <li class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>Bonus dust bag eksklusif</span></li>
                </ul>
                <div class="bg-linear-to-br from-secondary to-primary text-white rounded-2xl p-5 space-y-2">
                    <p class="text-sm uppercase tracking-[0.3em] text-white/70">Live Drop Picks</p>
                    <p class="text-2xl font-semibold">Masuk ke wishlistmu sebelum sesi berikutnya!</p>
                    <a href="body.html#live-drop" class="inline-flex items-center gap-2 underline font-semibold">Lihat jadwal <i class="fas fa-arrow-up-right-from-square"></i></a>
                </div>
            </div>
        </section>
    </main>

    <script src="js/reveal.js" defer></script>
    <script src="js/profile-data.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const params = new URLSearchParams(window.location.search);
        const productId = params.get('id') || '1';
        const API_URL = `/api/products/${encodeURIComponent(productId)}`;

        const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });
        const escapeHTML = (value = '') => String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
        const el = (id) => document.getElementById(id);
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
            let data = null;
            try {
                data = await res.json();
            } catch (_) {
                data = null;
            }
            if (!res.ok) {
                const message = data?.message || `Request failed (${res.status})`;
                const err = new Error(message);
                err.status = res.status;
                err.data = data;
                throw err;
            }
            return data;
        };

        const flashButtonLabel = (btn, label, restoreHtml, durationMs = 2000) => {
            if (!btn) return;
            const prev = Number(btn.dataset.wcLabelTimer || 0);
            if (prev) window.clearTimeout(prev);
            btn.textContent = String(label ?? '');
            const timerId = window.setTimeout(() => {
                if (typeof restoreHtml === 'string') btn.innerHTML = restoreHtml;
                delete btn.dataset.wcLabelTimer;
            }, Math.max(200, Number(durationMs) || 2000));
            btn.dataset.wcLabelTimer = String(timerId);
        };

        async function ensureLogin(action, redirectTo) {
            if (!window.AuthStore) {
                alert('Sistem auth belum siap. Muat ulang halaman.');
                return false;
            }
            const me = await AuthStore.me();
            if (me) return true;

            const file = window.location.pathname.split('/').pop() || 'product-detail.html';
            const current = `${file}${window.location.search || ''}${window.location.hash || ''}`;
            const next = String(redirectTo || current);
            try {
                sessionStorage.setItem('wc_login_redirect', next);
            } catch (_) {}
            alert('Silakan login terlebih dahulu untuk ' + action + '.');
            window.location.href = `login.html?next=${encodeURIComponent(next)}`;
            return false;
        }

        const goToCartBtn = document.getElementById('goToCartBtn');
        goToCartBtn?.addEventListener('click', async (e) => {
            e.preventDefault();
            if (!(await ensureLogin('melihat keranjang', 'cart.html'))) return;
            window.location.href = 'cart.html';
        });

        const computeReviewSummary = (reviews) => {
            const list = Array.isArray(reviews) ? reviews : [];
            const count = list.length;
            const sum = list.reduce((acc, r) => acc + (Number(r?.rating) || 0), 0);
            const avg = count ? sum / count : 0;
            return { avg, count };
        };

        const MENS_GROUP = 'Mens Clothing';
        const WOMENS_GROUP = 'Womens Clothing';
        const MENS_SUBCATEGORIES = ['T-Shirts', 'Jeans', 'Pants', 'Jacket', 'Long Sleeves', 'Sweaters', 'Tank Top'];
        const WOMENS_SUBCATEGORIES = ['Dresses', 'Skirts', 'T-Shirts', 'Jeans', 'Pants', 'Jacket', 'Long Sleeves', 'Sweaters', 'Tank Top', 'Crop Top'];

        const normalizeGroup = (rawCategory = '', title = '') => {
            const hay = `${rawCategory || ''} ${title || ''}`.toLowerCase();
            if (hay.includes('women') || hay.includes("women's") || hay.includes('woman')) return WOMENS_GROUP;
            if (hay.includes('men') || hay.includes("men's") || hay.includes('man')) return MENS_GROUP;
            if (/\b(dress|skirt|crop)\b/i.test(hay)) return WOMENS_GROUP;
            return MENS_GROUP;
        };

        const normalizeType = (rawType = '', title = '', group = '') => {
            const list = group === WOMENS_GROUP ? WOMENS_SUBCATEGORIES : MENS_SUBCATEGORIES;
            const trimmed = String(rawType || '').trim();
            if (trimmed && list.includes(trimmed)) return trimmed;
            const hay = String(title || '').toLowerCase();
            if (group === WOMENS_GROUP) {
                if (/\bcrop\b/.test(hay)) return 'Crop Top';
                if (/\bdress\b/.test(hay)) return 'Dresses';
                if (/\bskirt\b/.test(hay)) return 'Skirts';
            }
            if (/\bjean\b/.test(hay)) return 'Jeans';
            if (/\bjacket\b|\bcoat\b|\bhoodie\b/.test(hay)) return 'Jacket';
            if (/\blong\s*sleeve\b|\blongsleeve\b/.test(hay)) return 'Long Sleeves';
            if (/\bsweater\b|\bcardigan\b|\bknit\b/.test(hay)) return 'Sweaters';
            if (/\btank\b/.test(hay)) return 'Tank Top';
            if (/\bt[- ]?shirt\b|\btee\b/.test(hay)) return 'T-Shirts';
            if (/\bpant\b|\btrouser\b|\bchino\b/.test(hay)) return 'Pants';
            return 'T-Shirts';
        };

        let lastProduct = null;

        const hydrateProduct = (product) => {
            lastProduct = product;
            const priceIdr = Number(product.price) || 0;
            const ratingValue = 0;
            const reviewCount = 0;
            const group = String(product.category || 'Produk');
            const subType = String(product.type || 'Item');
            el('detailTitle').textContent = group;
            el('detailCategory').textContent = `${group} • ${subType}`;
            el('detailName').textContent = product.title;
            el('detailDesc').textContent = product.description || 'Produk kustom dari admin.';
            el('detailPrice').textContent = formatter.format(priceIdr);
            el('detailRating').textContent = Number(ratingValue || 0).toFixed(1);
            el('detailReviewCount').textContent = `${reviewCount} ulasan`;
            el('detailStock').textContent = typeof product.stock === 'number' ? String(product.stock) : (product.stock ?? '-');
            el('detailImage').src = product.image;
            el('detailImage').alt = product.title;
            el('detailType').textContent = subType;
            el('detailSku').textContent = `SKU: ${product.public_id || product.slug || product.uuid || '-'}`;

            const highlights = [
                `${product.title.split(' ').slice(0, 3).join(' ')} pilihan kurator`,
                `Rating ${Number(ratingValue || 0).toFixed(1)} / 5 dari ${reviewCount} ulasan`,
                `Stok disiapkan: ${product.stock ?? '-'}`,
            ];
            const highlightList = document.getElementById('highlightList');
            highlightList.innerHTML = highlights.map(text => `<li class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>${text}</span></li>`).join('');

            // Reviews are loaded from API after product render.

            document.title = `${product.title} - Detail Produk`;

            el('detailAddCart').onclick = async () => {
                if (!(await ensureLogin('menambahkan ke keranjang'))) return;
                try {
                    await apiFetchJson('/api/cart/items', {
                        method: 'POST',
                        body: JSON.stringify({
                            product_id: product.public_id,
                            quantity: 1,
                        }),
                    });
                    window.location.href = 'cart.html';
                } catch (error) {
                    alert(error?.message || 'Gagal menambahkan ke keranjang.');
                }
            };
            el('detailBuyNow').onclick = async () => {
                if (!(await ensureLogin('melanjutkan pembelian', 'checkout.html'))) return;
                try {
                    await apiFetchJson('/api/cart/items', {
                        method: 'POST',
                        body: JSON.stringify({
                            product_id: product.public_id,
                            quantity: 1,
                        }),
                    });
                    window.location.href = 'checkout.html';
                } catch (error) {
                    alert(error?.message || 'Gagal melanjutkan pembelian.');
                }
            };

            const wishlistRef = String(product.public_id || '');
            const wishlistBtn = el('detailWishlist');
            const getWishlistSnapshot = () => {
                if (!window.ProfileStore || typeof ProfileStore.getWishlist !== 'function') return [];
                return ProfileStore.getWishlist();
            };
            const findWishlistItem = () => getWishlistSnapshot().find((item) => String(item.refId) === wishlistRef) || null;
            const setWishlistState = (exists) => {
                if (!wishlistBtn) return;
                if (exists) {
                    wishlistBtn.classList.add('bg-secondary', 'text-white', 'border-secondary');
                    wishlistBtn.classList.remove('text-gray-600');
                    wishlistBtn.innerHTML = '<i class="fas fa-heart"></i> Tersimpan';
                } else {
                    wishlistBtn.classList.remove('bg-secondary', 'text-white', 'border-secondary');
                    wishlistBtn.classList.add('text-gray-600');
                    wishlistBtn.innerHTML = '<i class="fas fa-heart"></i> Simpan Wishlist';
                }
            };
            setWishlistState(false);

            const refreshWishlistState = async () => {
                if (!window.AuthStore || !window.ProfileStore) return;
                const me = await AuthStore.me();
                if (!me) {
                    setWishlistState(false);
                    return;
                }
                await ProfileStore.refresh();
                setWishlistState(Boolean(findWishlistItem()));
            };

            refreshWishlistState();

            wishlistBtn?.addEventListener('click', async () => {
                if (!(await ensureLogin('menyimpan wishlist'))) return;
                if (!window.ProfileStore || typeof ProfileStore.upsertWishlistItem !== 'function') {
                    alert('Fitur wishlist belum siap. Muat ulang halaman.');
                    return;
                }
                const existing = findWishlistItem();
                try {
                    if (existing) {
                        await ProfileStore.removeWishlistItem(existing.id);
                        setWishlistState(false);
                        return;
                    }
                    await ProfileStore.upsertWishlistItem({
                        refId: wishlistRef,
                        title: product.title,
                    });
                    setWishlistState(true);

                    const restoreHtml = wishlistBtn ? wishlistBtn.innerHTML : '';
                    flashButtonLabel(wishlistBtn, 'Telah Ditambahkan', restoreHtml, 2000);
                } catch (error) {
                    alert(error?.message || 'Gagal menyimpan wishlist.');
                }
            });
        };

        try {
            const productRes = await apiFetchJson(API_URL);
            const product = productRes?.data;
            if (!product) throw new Error('Produk tidak ditemukan');

            hydrateProduct(product);

            // Load reviews from API
            const reviewList = document.getElementById('reviewList');
            try {
                const reviewsRes = await apiFetchJson(`/api/reviews?product_id=${encodeURIComponent(String(product.public_id || ''))}`);
                const reviews = Array.isArray(reviewsRes?.data) ? reviewsRes.data : [];

                const summary = computeReviewSummary(reviews);
                el('detailRating').textContent = Number(summary.avg || 0).toFixed(1);
                el('detailReviewCount').textContent = `${Number(summary.count || 0)} ulasan`;

                const highlightList = document.getElementById('highlightList');
                if (highlightList) {
                    const highlights = [
                        `${product.title.split(' ').slice(0, 3).join(' ')} pilihan kurator`,
                        `Rating ${Number(summary.avg || 0).toFixed(1)} / 5 dari ${Number(summary.count || 0)} ulasan`,
                        `Stok disiapkan: ${product.stock ?? '-'}`,
                    ];
                    highlightList.innerHTML = highlights.map(text => `<li class="flex gap-3"><i class="fas fa-circle-check text-primary mt-1"></i><span>${text}</span></li>`).join('');
                }

                if (!reviews.length) {
                    reviewList.innerHTML = `
                        <article class="p-5 rounded-2xl border border-dashed border-gray-200 text-center">
                            <p class="text-sm text-gray-500 mb-3">Belum ada ulasan terverifikasi untuk produk ini. Setelah pesananmu tiba, tuliskan pengalaman melalui halaman Pesanan.</p>
                            <a href="orders.html" class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-primary text-primary font-semibold hover:bg-primary/5 transition">Buka Halaman Pesanan <i class="fas fa-arrow-right"></i></a>
                        </article>`;
                } else {
                    reviewList.innerHTML = reviews.map((review) => {
                        const ratingValue = Math.min(5, Math.max(0, Math.round(Number(review.rating) || 0)));
                        const stars = '★'.repeat(ratingValue) + '☆'.repeat(5 - ratingValue);
                        const reviewedAt = review.reviewed_at || review.created_at;
                        return `
                            <article class="p-4 rounded-2xl border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-dark">${escapeHTML(review.author || 'Pelanggan')}</p>
                                        <p class="text-xs text-gray-400">${escapeHTML(review.email || '')} · ${escapeHTML(reviewedAt ? new Date(reviewedAt).toLocaleDateString('id-ID') : '')}</p>
                                    </div>
                                    <span class="text-yellow-400 font-semibold">${stars}</span>
                                </div>
                                <p class="text-gray-600 mt-3">${escapeHTML(review.comment || '')}</p>
                            </article>`;
                    }).join('');
                }
            } catch (_) {
                // keep empty state
            }
        } catch (err) {
            alert(err.message || 'Terjadi kesalahan memuat detail produk.');
            window.location.href = 'body.html';
        }
    });
    </script>
</body>
</html>
