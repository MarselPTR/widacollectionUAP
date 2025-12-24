<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Produk - Wida Collection</title>
    <link rel="stylesheet" href="output.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="app.css" />
</head>

<body class="bg-gray-50 font-poppins min-h-screen">
    <header class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-gray-400 wc-reveal" style="--reveal-delay: 40ms;">
                    panel admin</p>
                <h1 class="text-2xl font-bold text-dark wc-reveal" style="--reveal-delay: 120ms;">Manajemen Produk
                    Kustom</h1>
            </div>
            <div class="flex gap-3">
                <a href="body"
                    class="px-4 py-2 rounded-full border border-gray-200 text-sm font-semibold text-gray-600 hover:text-primary wc-reveal"
                    style="--reveal-delay: 200ms;">Lihat Katalog</a>
                <button id="resetProducts"
                    class="px-4 py-2 rounded-full bg-red-50 text-red-500 text-sm font-semibold hover:bg-red-100 wc-reveal"
                    style="--reveal-delay: 260ms;">Reset Data Lokal</button>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-10 grid lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 space-y-4">
            <article class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-dark">Daftar Produk Kustom</h2>
                        <p class="text-sm text-gray-500">Produk ini akan ditampilkan berdampingan dengan data API.</p>
                    </div>
                    <span id="customTotal"
                        class="px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-semibold">0 item</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="text-gray-500 border-b">
                                <th class="py-2 pr-4">Nama</th>
                                <th class="py-2 pr-4">Kategori</th>
                                <th class="py-2 pr-4">Harga</th>
                                <th class="py-2 pr-4">Rating</th>
                                <th class="py-2 pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTable" class="divide-y"></tbody>
                    </table>
                </div>
            </article>

            <article class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-dark">Tracking Pesanan</h2>
                        <p class="text-sm text-gray-500">Ubah status dari <span class="font-semibold">Dikemas</span> ke
                            <span class="font-semibold">Dikirim</span>.</p>
                    </div>
                    <span id="ordersAdminTotal"
                        class="px-3 py-1 rounded-full bg-secondary/10 text-secondary text-sm font-semibold">0
                        pesanan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="text-gray-500 border-b">
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Pelanggan</th>
                                <th class="py-2 pr-4">Produk</th>
                                <th class="py-2 pr-4">Qty</th>
                                <th class="py-2 pr-4">Total</th>
                                <th class="py-2 pr-4">Status</th>
                                <th class="py-2 pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="ordersAdminTable" class="divide-y"></tbody>
                    </table>
                </div>
            </article>
        </section>

        <aside class="space-y-6">
            <article class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-semibold text-dark mb-4">Tambah / Edit Produk</h2>
                <form id="productForm" class="space-y-4">
                    <input type="hidden" name="productId" />
                    <label class="text-sm font-semibold text-gray-600 block">
                        Nama Produk
                        <input type="text" name="title"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            required />
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Kategori
                        <select name="category" id="categorySelect"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            required>
                            <option value="Womens Clothing">Womens Clothing</option>
                            <option value="Mens Clothing">Mens Clothing</option>
                        </select>
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Harga
                        <input type="number" min="0" step="0.01" name="price"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            required />
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Stok
                        <input type="number" min="0" name="stock"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" />
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Gambar Produk
                        <input type="file" name="image" accept="image/*"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20" />
                        <span class="text-xs text-gray-400">Upload file JPG/PNG max 2MB. Kosongkan jika tidak ingin
                            mengubah gambar.</span>
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Tipe
                        <select name="type" id="typeSelect"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            required></select>
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Deskripsi
                        <textarea name="description" rows="4"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            required></textarea>
                    </label>
                    <button type="submit"
                        class="w-full rounded-full bg-secondary text-white font-semibold py-3 shadow hover:bg-secondary/90">Simpan
                        Produk</button>
                    <button type="button" id="resetForm"
                        class="w-full rounded-full border border-gray-200 text-gray-500 font-semibold py-3">Bersihkan
                        Form</button>
                    <p id="formStatus" class="text-sm text-green-600 hidden">Produk tersimpan.</p>
                </form>
            </article>

            <article class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-semibold text-dark">Pengaturan Jadwal Buka Bal</h2>
                        <p class="text-sm text-gray-500">Kontrol konten hero "Buka Bal" di halaman utama.</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600">Live
                        Drop</span>
                </div>
                <form id="liveDropForm" class="space-y-4">
                    <label class="text-sm font-semibold text-gray-600 block">
                        Judul Bagian
                        <input type="text" name="heroTitle"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            placeholder="Contoh: Buka Bal Selanjutnya" required />
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Deskripsi Singkat
                        <textarea name="heroDescription" rows="2"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            placeholder="Ajakan untuk join live" required></textarea>
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Judul Event
                        <input type="text" name="eventTitle"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            placeholder="Contoh: Buka Bal Spesial" required />
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Subjudul / Highlight
                        <input type="text" name="eventSubtitle"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            placeholder="Contoh: Sabtu, 12 Mei 20:00 WIB" />
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Waktu Live (WIB)
                        <input type="datetime-local" name="eventDateTime"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            required />
                        <span class="text-xs text-gray-400">Digunakan untuk menghitung countdown otomatis.</span>
                    </label>
                    <label class="text-sm font-semibold text-gray-600 block">
                        Teks Tombol CTA
                        <input type="text" name="ctaLabel"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary"
                            placeholder="Contoh: Ingatkan Saya" required />
                    </label>
                    <div class="grid sm:grid-cols-2 gap-3 pt-2">
                        <button type="submit"
                            class="rounded-full bg-primary text-white font-semibold py-3 shadow hover:bg-primary/90">Simpan
                            Jadwal</button>
                        <button type="button" id="liveDropReset"
                            class="rounded-full border border-gray-200 text-gray-600 font-semibold py-3 hover:border-gray-400">Reset
                            Default</button>
                    </div>
                    <p id="liveDropStatus" class="text-sm hidden">Pengaturan tersimpan.</p>
                </form>
            </article>
        </aside>
    </main>

    <script src="js/profile-data.js"></script>
    <script src="js/reveal.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ensureAdminAccess = () => {
                const REDIRECT_KEY = 'wc_login_redirect';
                const setLoginRedirect = (value) => {
                    try {
                        sessionStorage.setItem(REDIRECT_KEY, String(value || ''));
                    } catch (_) { }
                };
                const currentRelativeUrl = () => {
                    const file = window.location.pathname.split('/').pop() || 'admin';
                    return `${file}${window.location.search || ''}${window.location.hash || ''}`;
                };
                return { setLoginRedirect, currentRelativeUrl };
            };

            const { setLoginRedirect, currentRelativeUrl } = ensureAdminAccess();

            const form = document.getElementById('productForm');
            const table = document.getElementById('productTable');
            const totalBadge = document.getElementById('customTotal');
            const statusEl = document.getElementById('formStatus');
            const resetBtn = document.getElementById('resetForm');
            const resetProducts = document.getElementById('resetProducts');

            const ordersAdminTotal = document.getElementById('ordersAdminTotal');
            const ordersAdminTable = document.getElementById('ordersAdminTable');
            const liveDropForm = document.getElementById('liveDropForm');
            const liveDropStatus = document.getElementById('liveDropStatus');
            const liveDropReset = document.getElementById('liveDropReset');

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

            const liveDropDefaults = {
                heroTitle: 'Buka Bal Selanjutnya',
                heroDescription: 'Jangan lewatkan sesi buka bal eksklusif kami via TikTok Live dengan penawaran spesial!',
                eventTitle: 'Buka Bal Spesial Kaos Band Vintage',
                eventSubtitle: 'Sabtu, 15 Juli 2023 - Pukul 20:00 WIB',
                eventDateTime: '',
                ctaLabel: 'Ingatkan Saya',
            };

            const reviewSummaryCache = new Map();
            const getReviewSummary = async (productPublicId) => {
                if (!productPublicId) return { avg: 0, count: 0 };
                if (reviewSummaryCache.has(productPublicId)) return reviewSummaryCache.get(productPublicId);
                try {
                    const res = await apiFetchJson(`/api/reviews?product_id=${encodeURIComponent(productPublicId)}`);
                    const rows = Array.isArray(res?.data) ? res.data : [];
                    const count = rows.length;
                    const avg = count ? rows.reduce((acc, r) => acc + (Number(r.rating) || 0), 0) / count : 0;
                    const summary = { avg, count };
                    reviewSummaryCache.set(productPublicId, summary);
                    return summary;
                } catch (_) {
                    const summary = { avg: 0, count: 0 };
                    reviewSummaryCache.set(productPublicId, summary);
                    return summary;
                }
            };

            const escapeHTML = (value = '') => String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');

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

            const allowedTypesFor = (group) => (group === WOMENS_GROUP ? WOMENS_SUBCATEGORIES : MENS_SUBCATEGORIES);

            const normalizeType = (rawType = '', title = '', group = '') => {
                const list = allowedTypesFor(group);
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

            const categorySelect = document.getElementById('categorySelect');
            const typeSelect = document.getElementById('typeSelect');
            const renderTypeOptions = (group, selectedValue = '') => {
                if (!typeSelect) return;
                const list = allowedTypesFor(group);
                const safeSelected = list.includes(selectedValue) ? selectedValue : list[0];
                typeSelect.innerHTML = list
                    .map((t) => `<option value="${escapeHTML(t)}" ${t === safeSelected ? 'selected' : ''}>${escapeHTML(t)}</option>`)
                    .join('');
            };

            if (categorySelect) {
                categorySelect.addEventListener('change', () => {
                    renderTypeOptions(categorySelect.value, typeSelect ? typeSelect.value : '');
                });
            }

            const formatCurrency = (value = 0) => {
                try {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(Number(value) || 0);
                } catch (_) {
                    return `Rp ${(Number(value) || 0).toLocaleString('id-ID')}`;
                }
            };

            const fillForm = (product) => {
                form.productId.value = product.uuid;
                form.title.value = product.title;
                const group = normalizeGroup(product.category, product.title);
                form.category.value = group;
                form.price.value = Number(product.price) || 0;
                form.stock.value = product.stock || 0;
                form.image.value = product.image;
                renderTypeOptions(group, normalizeType(product.type, product.title, group));
                form.type.value = normalizeType(product.type, product.title, group);
                form.description.value = product.description;
            };

            const clearForm = () => {
                form.reset();
                form.productId.value = '';
                statusEl.classList.add('hidden');
                const group = form.category?.value || WOMENS_GROUP;
                renderTypeOptions(group);
            };

            const isoLocalPattern = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/;
            const toInputDateTimeValue = (value = '') => {
                if (!value) return '';
                if (isoLocalPattern.test(value)) {
                    return value.slice(0, 16);
                }
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) return '';
                const local = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
                return local.toISOString().slice(0, 16);
            };

            const setLiveDropStatus = (message, variant = 'success') => {
                if (!liveDropStatus) return;
                liveDropStatus.textContent = message;
                liveDropStatus.classList.remove('hidden', 'text-green-600', 'text-red-500');
                liveDropStatus.classList.add(variant === 'error' ? 'text-red-500' : 'text-green-600');
            };

            const hydrateLiveDropForm = async () => {
                if (!liveDropForm) return;
                try {
                    const res = await apiFetchJson('/api/live-drop');
                    const row = res?.data || {};
                    liveDropForm.heroTitle.value = row.hero_title || liveDropDefaults.heroTitle || '';
                    liveDropForm.heroDescription.value = row.hero_description || liveDropDefaults.heroDescription || '';
                    liveDropForm.eventTitle.value = row.event_title || liveDropDefaults.eventTitle || '';
                    liveDropForm.eventSubtitle.value = row.event_subtitle || liveDropDefaults.eventSubtitle || '';
                    liveDropForm.eventDateTime.value = toInputDateTimeValue(row.event_date_time || liveDropDefaults.eventDateTime);
                    liveDropForm.ctaLabel.value = row.cta_label || liveDropDefaults.ctaLabel || '';
                    setLiveDropStatus('', 'success');
                } catch (error) {
                    console.error('Gagal memuat Live Drop settings', error);
                    setLiveDropStatus('Gagal memuat pengaturan live drop.', 'error');
                }
            };

            const productsState = { list: [] };

            const loadProducts = async () => {
                const res = await apiFetchJson('/api/admin/products');
                productsState.list = Array.isArray(res?.data) ? res.data : [];
            };

            const loadOrdersAdmin = async () => {
                const res = await apiFetchJson('/api/admin/orders');
                return Array.isArray(res?.data) ? res.data : [];
            };

            const renderTable = async () => {
                const list = Array.isArray(productsState.list) ? productsState.list : [];
                totalBadge.textContent = `${list.length} item`;
                if (!list.length) {
                    table.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-gray-400">Belum ada produk kustom.</td></tr>';
                    return;
                }
                const summaries = await Promise.all(list.map((p) => getReviewSummary(p.public_id)));
                table.innerHTML = list.map((product, idx) => {
                    const summary = summaries[idx] || { avg: 0, count: 0 };
                    const rate = Number(summary.avg || 0).toFixed(1);
                    const count = summary.count || 0;
                    const group = normalizeGroup(product.category, product.title);
                    const type = normalizeType(product.type, product.title, group);
                    return `
                    <tr>
                        <td class="py-2 pr-4 font-semibold text-dark">${escapeHTML(product.title)}</td>
                        <td class="py-2 pr-4 text-gray-500">${escapeHTML(group)} • ${escapeHTML(type)}</td>
                        <td class="py-2 pr-4 text-primary font-semibold">${escapeHTML(formatCurrency(product.price))}</td>
                        <td class="py-2 pr-4 text-gray-500">${rate} (${count})</td>
                        <td class="py-2 pr-4 space-x-3">
                            <button data-action="edit" data-id="${product.uuid}" class="text-sm text-primary font-semibold">Edit</button>
                            <button data-action="delete" data-id="${product.uuid}" class="text-sm text-red-500 font-semibold">Hapus</button>
                        </td>
                    </tr>
                `;
                }).join('');
            };

            const renderOrdersAdmin = () => {
                if (!ordersAdminTable || !ordersAdminTotal) return;
                ordersAdminTotal.textContent = 'Memuat...';
                ordersAdminTable.innerHTML = '<tr><td colspan="7" class="py-6 text-center text-gray-400">Memuat pesanan...</td></tr>';
            };

            table.addEventListener('click', (event) => {
                const btn = event.target.closest('button[data-action]');
                if (!btn) return;
                const { action, id } = btn.dataset;
                if (action === 'edit') {
                    const product = (productsState.list || []).find((p) => p.uuid === id);
                    if (product) fillForm(product);
                }
                if (action === 'delete') {
                    if (!confirm('Hapus produk ini?')) return;
                    (async () => {
                        try {
                            await apiFetchJson(`/api/admin/products/${encodeURIComponent(id)}`, { method: 'DELETE' });
                            await loadProducts();
                            await renderTable();
                        } catch (error) {
                            alert(error?.data?.message || error?.message || 'Gagal menghapus produk.');
                        }
                    })();
                    if (form.productId.value === id) clearForm();
                }
            });

            ordersAdminTable?.addEventListener('click', (event) => {
                const btn = event.target.closest('button[data-action]');
                if (!btn) return;
                const action = btn.dataset.action;
                const uuid = btn.dataset.id;
                if (!uuid) return;

                (async () => {
                    try {
                        if (action === 'ship') {
                            await apiFetchJson(`/api/admin/orders/${encodeURIComponent(uuid)}/ship`, { method: 'PATCH', body: JSON.stringify({}) });
                        } else if (action === 'deliver') {
                            await apiFetchJson(`/api/admin/orders/${encodeURIComponent(uuid)}/deliver`, { method: 'PATCH' });
                        } else {
                            return;
                        }
                        await refreshOrdersAdmin();
                    } catch (error) {
                        alert(error?.data?.message || error?.message || 'Gagal mengubah status pesanan.');
                    }
                })();
            });

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const group = normalizeGroup(form.category.value, form.title.value);
                const type = normalizeType(form.type.value, form.title.value, group);
                const uuid = String(form.productId.value || '').trim();
                
                const formData = new FormData();
                formData.append('title', String(form.title.value || '').trim());
                formData.append('category', group);
                formData.append('type', type);
                formData.append('price', Math.max(0, parseInt(form.price.value || '0', 10) || 0));
                formData.append('stock', Math.max(0, parseInt(form.stock.value || '0', 10) || 0));
                formData.append('description', String(form.description.value || '').trim());
                
                if (form.image.files[0]) {
                    formData.append('image', form.image.files[0]);
                }

                // If updating, we need _method=PUT because HTML forms/FormData only natively support POST/GET
                // Laravel handles method spoofing via _method field.
                if (uuid) {
                    formData.append('_method', 'PUT');
                }

                (async () => {
                    try {
                        let url = '/api/admin/products';
                        if (uuid) {
                            url = `/api/admin/products/${encodeURIComponent(uuid)}`;
                        }

                        // Use fetch directly to avoid apiFetchJson setting Content-Type: application/json
                        const res = await fetch(url, {
                            method: 'POST', // Always POST when using FormData (with _method check for PUT)
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                // Do NOT set Content-Type, browser sets it with boundary for FormData
                            }
                        });

                        const data = await res.json().catch(() => null);
                        if (!res.ok) {
                            throw new Error(data?.message || `Request failed (${res.status})`);
                        }

                        statusEl.textContent = 'Produk tersimpan.';
                        statusEl.classList.remove('hidden', 'text-red-500');
                        statusEl.classList.add('text-green-600');
                        await loadProducts();
                        await renderTable();
                        clearForm();
                    } catch (error) {
                        statusEl.textContent = error?.message || 'Gagal menyimpan produk.';
                        statusEl.classList.remove('hidden', 'text-green-600');
                        statusEl.classList.add('text-red-500');
                    }
                })();
            });

            resetBtn.addEventListener('click', clearForm);
            resetProducts.addEventListener('click', () => {
                alert('Data sudah tersimpan di database (bukan localStorage).');
            });

            const refreshOrdersAdmin = async () => {
                if (!ordersAdminTable || !ordersAdminTotal) return;
                try {
                    const orders = await loadOrdersAdmin();
                    ordersAdminTotal.textContent = `${orders.length} pesanan`;
                    if (!orders.length) {
                        ordersAdminTable.innerHTML = '<tr><td colspan="7" class="py-6 text-center text-gray-400">Belum ada pesanan masuk.</td></tr>';
                        return;
                    }

                    const statusLabel = (status) => {
                        if (status === 'packed') return 'Dikemas';
                        if (status === 'delivered') return 'Diterima';
                        if (status === 'shipped') return 'Dalam pengiriman';
                        return String(status || '-');
                    };

                    const readCustomer = (snapshotRaw) => {
                        try {
                            const snap = snapshotRaw ? JSON.parse(snapshotRaw) : null;
                            return snap?.customer || null;
                        } catch (_) {
                            return null;
                        }
                    };

                    ordersAdminTable.innerHTML = orders.map((order) => {
                        const customer = readCustomer(order.customer_snapshot);
                        const customerLabel = customer?.fullname || customer?.email || '-';
                        const qty = Number(order.total_qty) || 1;
                        const canShip = order.status === 'packed';
                        const canDeliver = order.status === 'shipped';
                        const shipBtn = canShip
                            ? `<button data-action="ship" data-id="${escapeHTML(order.uuid)}" class="text-sm font-semibold text-white bg-secondary px-4 py-1.5 rounded-full hover:bg-secondary/90">Tandai Dikirim</button>`
                            : '';
                        const deliverBtn = canDeliver
                            ? `<button data-action="deliver" data-id="${escapeHTML(order.uuid)}" class="text-sm font-semibold text-white bg-primary px-4 py-1.5 rounded-full hover:bg-primary/90">Tandai Diterima</button>`
                            : '';
                        const actions = (shipBtn || deliverBtn) ? `${shipBtn}${shipBtn && deliverBtn ? ' ' : ''}${deliverBtn}` : '<span class="text-xs text-gray-400">-</span>';
                        return `
                            <tr>
                                <td class="py-2 pr-4 font-semibold text-dark">#${escapeHTML(order.public_id || order.uuid)}</td>
                                <td class="py-2 pr-4 text-gray-500">${escapeHTML(customerLabel)}</td>
                                <td class="py-2 pr-4 text-gray-500">${escapeHTML(order.product_title || 'Produk Wida Collection')}</td>
                                <td class="py-2 pr-4 text-gray-500">${qty}</td>
                                <td class="py-2 pr-4 text-primary font-semibold">${escapeHTML(formatCurrency(order.total))}</td>
                                <td class="py-2 pr-4 text-gray-500">${escapeHTML(statusLabel(order.status))}</td>
                                <td class="py-2 pr-4">${actions}</td>
                            </tr>
                        `;
                    }).join('');
                } catch (error) {
                    ordersAdminTotal.textContent = '0 pesanan';
                    ordersAdminTable.innerHTML = '<tr><td colspan="7" class="py-6 text-center text-gray-400">Gagal memuat pesanan.</td></tr>';
                }
            };

            (async () => {
                if (!window.AuthStore) {
                    alert('Sistem auth belum siap. Muat ulang halaman.');
                    window.location.href = 'login';
                    return;
                }
                const me = await AuthStore.me();
                if (!me) {
                    const next = currentRelativeUrl();
                    setLoginRedirect(next);
                    window.location.href = `login?next=${encodeURIComponent(next)}`;
                    return;
                }
                if (!AuthStore.isAdmin || !AuthStore.isAdmin()) {
                    alert('Halaman ini khusus admin Wida Collection.');
                    window.location.href = 'body';
                    return;
                }

                try {
                    await loadProducts();
                    await renderTable();
                } catch (error) {
                    console.error('Gagal memuat produk admin', error);
                }

                renderOrdersAdmin();
                await refreshOrdersAdmin();

                // Init default nested type.
                if (form && form.category) {
                    const group = normalizeGroup(form.category.value, '');
                    form.category.value = group;
                    renderTypeOptions(group);
                }

                if (liveDropForm) {
                    await hydrateLiveDropForm();
                    liveDropForm.addEventListener('submit', async (event) => {
                        event.preventDefault();
                        try {
                            const payload = {
                                hero_title: liveDropForm.heroTitle.value.trim() || liveDropDefaults.heroTitle || '',
                                hero_description: liveDropForm.heroDescription.value.trim() || liveDropDefaults.heroDescription || '',
                                event_title: liveDropForm.eventTitle.value.trim() || liveDropDefaults.eventTitle || '',
                                event_subtitle: liveDropForm.eventSubtitle.value.trim(),
                                event_date_time: liveDropForm.eventDateTime.value ? new Date(liveDropForm.eventDateTime.value).toISOString() : null,
                                cta_label: liveDropForm.ctaLabel.value.trim() || liveDropDefaults.ctaLabel || '',
                            };
                            if (!payload.event_date_time) {
                                setLiveDropStatus('Isi waktu live terlebih dahulu.', 'error');
                                return;
                            }
                            await apiFetchJson('/api/admin/live-drop', { method: 'PUT', body: JSON.stringify(payload) });
                            setLiveDropStatus('Pengaturan live drop diperbarui.', 'success');
                        } catch (error) {
                            console.error('Gagal menyimpan live drop', error);
                            setLiveDropStatus(error?.data?.message || error?.message || 'Gagal menyimpan pengaturan live drop.', 'error');
                        }
                    });

                    if (liveDropReset) {
                        liveDropReset.addEventListener('click', async () => {
                            if (!confirm('Kembalikan ke pengaturan bawaan?')) return;
                            liveDropForm.heroTitle.value = liveDropDefaults.heroTitle;
                            liveDropForm.heroDescription.value = liveDropDefaults.heroDescription;
                            liveDropForm.eventTitle.value = liveDropDefaults.eventTitle;
                            liveDropForm.eventSubtitle.value = liveDropDefaults.eventSubtitle;
                            liveDropForm.eventDateTime.value = '';
                            liveDropForm.ctaLabel.value = liveDropDefaults.ctaLabel;
                            setLiveDropStatus('Pengaturan direset. Klik Simpan Jadwal untuk menerapkan.', 'success');
                        });
                    }
                }
            })();
        });
    </script>
</body>

</html>