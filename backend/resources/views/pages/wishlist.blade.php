<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wishlist - Wida Collection</title>
    <link rel="stylesheet" href="output.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="app.css" />
</head>
<body class="bg-gray-50 font-poppins">
    <header class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <a href="body.html" class="text-2xl font-bold text-primary wc-reveal" style="--reveal-delay: 40ms;">Wida<span class="text-secondary">Collection</span></a>
                <p class="text-sm text-gray-500 wc-reveal" style="--reveal-delay: 120ms;">Kelola wishlist favoritmu.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="profile.html" class="px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold text-gray-600 hover:text-primary wc-reveal" style="--reveal-delay: 180ms;">Kembali ke Profil</a>
                <a href="body.html#products" class="px-4 py-2 rounded-full bg-primary text-white text-sm font-semibold shadow-lg hover:bg-red-600 wc-reveal" style="--reveal-delay: 240ms;">Tambah dari Katalog</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10">
        <div class="grid lg:grid-cols-3 gap-6">
            <section class="lg:col-span-2 space-y-6">
                <article class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-semibold text-dark">Wishlist Kamu</h1>
                            <p class="text-sm text-gray-500">Sinkron otomatis dengan profilmu.</p>
                        </div>
                        <button id="wishlistClear" class="text-sm font-semibold text-red-500 hover:text-red-600">Bersihkan Semua</button>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="rounded-2xl bg-linear-to-br from-primary/10 to-white border border-primary/20 p-4">
                            <p class="text-sm text-gray-500">Total Item</p>
                            <p id="wishlistCount" class="text-3xl font-bold text-primary">0</p>
                        </div>
                        <div class="rounded-2xl bg-linear-to-br from-secondary/10 to-white border border-secondary/20 p-4">
                            <p class="text-sm text-gray-500">Prioritas Tinggi</p>
                            <p id="wishlistPriority" class="text-3xl font-bold text-secondary">0</p>
                        </div>
                    </div>
                    <div id="wishlistItems" class="space-y-4"></div>
                    <p id="wishlistEmpty" class="text-sm text-gray-400 text-center hidden">Belum ada wishlist tersimpan.</p>
                </article>
            </section>

            <aside class="space-y-6">
                <article class="bg-primary text-white rounded-3xl p-6 shadow-lg">
                    <h3 class="text-xl font-semibold mb-3">Tips wishlist efektif</h3>
                    <ul class="text-sm space-y-2 text-white/90 list-disc list-inside">
                        <li>Tulis detail ukuran & preferensi warna.</li>
                        <li>Tambahkan estimasi harga agar tim mudah kurasi.</li>
                        <li>Perbarui status setelah item berhasil didapat.</li>
                    </ul>
                </article>
            </aside>
        </div>
    </main>

    <script src="js/reveal.js" defer></script>
    <script src="js/profile-data.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const REDIRECT_KEY = 'wc_login_redirect';
            const setLoginRedirect = (value) => {
                try {
                    sessionStorage.setItem(REDIRECT_KEY, String(value || ''));
                } catch (_) {}
            };
            const currentRelativeUrl = () => {
                const file = window.location.pathname.split('/').pop() || 'wishlist.html';
                return `${file}${window.location.search || ''}${window.location.hash || ''}`;
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
                    window.location.href = `login.html?next=${encodeURIComponent(next)}`;
                    return;
                }
                await ProfileStore.ready;

            const listEl = document.getElementById('wishlistItems');
            const emptyEl = document.getElementById('wishlistEmpty');
            const countEl = document.getElementById('wishlistCount');
            const priorityEl = document.getElementById('wishlistPriority');
            const clearBtn = document.getElementById('wishlistClear');

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

            const renderWishlist = () => {
                const wishlist = ProfileStore.getWishlist();
                countEl.textContent = wishlist.length;
                priorityEl.textContent = wishlist.filter((item) => item.priority === 'high').length;
                if (!wishlist.length) {
                    listEl.innerHTML = '';
                    emptyEl.classList.remove('hidden');
                    return;
                }
                emptyEl.classList.add('hidden');
                listEl.innerHTML = wishlist
                    .map((item) => `
                        <article class="p-4 border border-gray-100 rounded-2xl flex items-center gap-4 bg-white">
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-gray-50 to-white flex items-center justify-center overflow-hidden">
                                ${item.image ? `<img src="${escapeHTML(item.image)}" alt="${escapeHTML(item.title)}" class="w-12 h-12 object-contain" loading="lazy" />` : '<span class="text-xs text-gray-400">No Image</span>'}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-dark">${escapeHTML(item.title)}</p>
                                <p class="text-sm text-gray-500">${escapeHTML(item.note || 'Belum ada catatan')}</p>
                                <div class="flex flex-wrap gap-2 mt-2 text-xs font-semibold">
                                    ${item.badge ? `<span class="px-2 py-1 rounded-full bg-primary/10 text-primary">${escapeHTML(item.badge)}</span>` : ''}
                                    <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 capitalize">${escapeHTML(item.priority)}</span>
                                    <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600">${escapeHTML(item.status || 'waiting')}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-primary font-bold block mb-2">${formatCurrency(item.price)}</span>
                                <button data-action="remove" data-id="${item.id}" class="text-xs text-red-500 font-semibold hover:text-red-600">Hapus</button>
                            </div>
                        </article>
                    `)
                    .join('');
            };

            listEl.addEventListener('click', (event) => {
                const btn = event.target.closest('button[data-action="remove"]');
                if (!btn) return;
                ProfileStore.removeWishlistItem(btn.dataset.id);
                renderWishlist();
            });

            clearBtn.addEventListener('click', () => {
                if (!confirm('Hapus semua wishlist?')) return;
                ProfileStore.clearWishlist();
                renderWishlist();
            });

            renderWishlist();
            })();
        });
    </script>
</body>
</html>
