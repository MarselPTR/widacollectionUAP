<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Checkout & Pembayaran - Wida Collection</title>
        <link rel="stylesheet" href="output.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="app.css" />
    </head>
    <body class="font-poppins bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
            <header class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between bg-white rounded-2xl shadow-lg border border-white/70 p-6">
                <div>
                    <p class="text-sm font-semibold text-primary wc-reveal" style="--reveal-delay: 40ms;">Wida <span class="text-secondary">Collection</span></p>
                    <p class="text-xs uppercase tracking-[0.4em] text-secondary font-semibold wc-reveal" style="--reveal-delay: 90ms;">Langkah 2 dari 3</p>
                    <h1 class="text-3xl font-bold text-dark wc-reveal" style="--reveal-delay: 140ms;">Checkout & Pembayaran</h1>
                    <p class="text-gray-500 wc-reveal" style="--reveal-delay: 210ms;">Isi detail pengiriman dan konfirmasi pesananmu.</p>
                </div>
                <div class="flex flex-wrap gap-3 text-sm font-semibold text-gray-500 uppercase tracking-[0.3em]">
                    <span class="flex items-center gap-2 text-primary"><i class="fas fa-circle-check"></i> Keranjang</span>
                    <span class="h-px w-10 bg-gray-200"></span>
                    <span class="flex items-center gap-2 text-secondary"><i class="fas fa-circle"></i> Checkout</span>
                    <span class="h-px w-10 bg-gray-200"></span>
                    <span class="flex items-center gap-2 text-gray-300"><i class="fas fa-circle"></i> Berhasil</span>
                </div>
            </header>

            <section class="grid gap-8 lg:grid-cols-[1.2fr,0.8fr]">
                <article class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-5">
                    <div>
                        <h2 class="text-xl font-semibold text-dark">Detail Pengiriman</h2>
                        <p class="text-sm text-gray-500">Pastikan informasi berikut sesuai sebelum melakukan pembayaran.</p>
                    </div>
                    <form id="checkoutForm" class="space-y-4">
                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="text-sm font-semibold text-gray-600">
                                Nama lengkap
                                <input type="text" name="fullname" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Contoh: Rahma Adinda" required />
                            </label>
                            <label class="text-sm font-semibold text-gray-600">
                                Nomor WhatsApp
                                <input type="tel" name="phone" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="08xxxxxxxxxx" required />
                            </label>
                        </div>
                        <label class="text-sm font-semibold text-gray-600">
                            Email aktif
                            <input type="email" name="email" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="kamu@email.com" />
                        </label>
                        <div class="text-sm font-semibold text-gray-600 space-y-3">
                            <div class="flex items-center justify-between">
                                <span>Alamat lengkap</span>
                                <button type="button" id="toggleAddressForm" class="text-xs font-semibold text-primary hover:text-secondary">Tambah alamat baru</button>
                            </div>
                            <div id="savedAddressList" class="space-y-3 text-sm text-gray-600"></div>
                            <textarea name="address" rows="3" class="w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Tulis alamat lengkap beserta patokan" required></textarea>
                            <div id="quickAddressForm" class="hidden border border-gray-100 rounded-2xl p-4 bg-gray-50 space-y-3 text-sm text-gray-600">
                                <p class="font-semibold text-dark">Tambah alamat pengiriman</p>

                                <div class="rounded-2xl border border-gray-100 bg-white p-3 space-y-2">
                                    <label class="text-xs font-semibold text-gray-600 block">
                                        Cari lokasi (Maps)
                                        <div class="mt-2 flex gap-2">
                                            <input id="quickAddressSearch" type="text" class="w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Contoh: Jalan Sudirman, Jakarta" />
                                            <button type="button" id="quickUseMyLocation" class="shrink-0 rounded-2xl border border-gray-200 bg-white px-4 py-2 font-semibold text-gray-600 hover:border-primary hover:text-primary" title="Gunakan lokasi saya">
                                                <i class="fas fa-location-crosshairs"></i>
                                            </button>
                                        </div>
                                    </label>
                                    <div id="quickAddressSearchResults" class="hidden rounded-2xl border border-gray-100 bg-white p-2 text-sm text-gray-600 max-h-56 overflow-auto"></div>
                                    <p id="quickAddressMapStatus" class="text-xs text-gray-500">Klik peta / geser pin untuk mengisi alamat otomatis.</p>
                                    <div id="quickAddressMap" class="w-full rounded-2xl overflow-hidden border border-gray-100" style="height: 220px;"></div>
                                </div>

                                <input type="text" id="quickAddressLabel" class="w-full rounded-2xl border border-gray-200 px-4 py-2" placeholder="Label alamat (contoh: Rumah)" />
                                <input type="text" id="quickAddressRecipient" class="w-full rounded-2xl border border-gray-200 px-4 py-2" placeholder="Nama penerima" />
                                <input type="tel" id="quickAddressPhone" class="w-full rounded-2xl border border-gray-200 px-4 py-2" placeholder="Nomor kontak" />
                                <textarea id="quickAddressDetail" rows="2" class="w-full rounded-2xl border border-gray-200 px-4 py-2" placeholder="Detail alamat"></textarea>
                                <input type="text" id="quickAddressPostal" class="w-full rounded-2xl border border-gray-200 px-4 py-2" placeholder="Kode pos" />
                                <input type="hidden" id="quickAddressLat" />
                                <input type="hidden" id="quickAddressLng" />
                                <label class="inline-flex items-center gap-3 text-xs text-gray-500">
                                    <input type="checkbox" id="quickAddressPrimary" class="rounded border-gray-300 text-primary focus:ring-primary" />
                                    Jadikan alamat utama
                                </label>
                                <div class="flex flex-wrap gap-3">
                                    <button type="button" id="quickAddressSave" class="flex-1 rounded-full bg-primary text-white font-semibold py-2">Simpan alamat</button>
                                    <button type="button" id="quickAddressCancel" class="rounded-full border border-gray-200 px-5 py-2 font-semibold text-gray-500 hover:border-primary hover:text-primary">Batal</button>
                                </div>
                                <p id="quickAddressStatus" class="text-xs text-green-600 hidden">Alamat ditambahkan.</p>
                            </div>
                        </div>
                        <label class="text-sm font-semibold text-gray-600">
                            Catatan tambahan
                            <textarea name="notes" rows="2" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Opsional"></textarea>
                        </label>
                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="text-sm font-semibold text-gray-600">
                                Metode pengiriman
                                <select id="shippingSelect" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary">
                                    <option value="0" data-label="Reguler 3-4 hari">Reguler (3-4 hari) - Gratis</option>
                                    <option value="20000" data-label="Kilat 1-2 hari">Kilat (1-2 hari) - Rp20.000</option>
                                    <option value="50000" data-label="Same day">Same day - Rp50.000</option>
                                </select>
                            </label>
                            <label class="text-sm font-semibold text-gray-600">
                                Metode pembayaran
                                <select name="payment" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" required>
                                    <option value="">Pilih metode</option>
                                    <option value="transfer">Transfer bank</option>
                                    <option value="ewallet">E-Wallet (OVO, DANA, dll)</option>
                                    <option value="cod">COD / Bayar ditempat</option>
                                </select>
                            </label>
                        </div>
                        <label class="flex items-start gap-3 text-sm text-gray-500">
                            <input type="checkbox" required class="mt-1 rounded border-gray-300 text-primary focus:ring-primary" />
                            Saya menyetujui S&K Wida Collection serta kebijakan pengembalian barang.
                        </label>
                        <p id="checkoutError" class="text-sm text-red-500 hidden">Terjadi kesalahan. Silakan coba lagi.</p>
                        <div class="flex flex-wrap gap-4">
                            <a href="cart" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-6 py-3 font-semibold text-gray-500 hover:border-primary hover:text-primary">
                                <i class="fas fa-arrow-left"></i> Kembali ke keranjang
                            </a>
                            <button type="submit" id="payButton" class="inline-flex flex-1 items-center justify-center rounded-full bg-secondary text-white font-semibold py-3 shadow-lg hover:bg-secondary/90 disabled:opacity-60 disabled:cursor-not-allowed">
                                Bayar sekarang
                            </button>
                        </div>
                    </form>
                </article>

                <aside class="space-y-5">
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-dark">Ringkasan Pesanan</h2>
                            <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">Real-time</span>
                        </div>
                        <ul id="summaryList" class="mt-5 space-y-4 text-sm text-gray-600"></ul>
                        <div class="mt-5 space-y-3 text-sm text-gray-600">
                            <div class="flex items-center justify-between">
                                <span>Subtotal</span>
                                <strong id="summarySubtotal">Rp0</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Pengiriman</span>
                                <strong id="summaryShipping">Rp0</strong>
                            </div>
                            <div class="flex items-center justify-between text-base">
                                <span>Total</span>
                                <span id="summaryTotal" class="text-2xl font-bold text-dark">Rp0</span>
                            </div>
                        </div>
                        <p class="mt-4 text-xs text-gray-400">Harga otomatis mengikuti keranjang dan sudah termasuk pajak.</p>
                    </div>
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-lg font-semibold text-dark">Tambah Produk Lain</h3>
                            <span id="moreProductsStatus" class="text-xs text-gray-400">Memuat...</span>
                        </div>
                        <label class="block mt-4">
                            <span class="sr-only">Cari produk</span>
                            <input id="moreProductsSearch" type="text" class="w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Cari produk untuk ditambahkan..." />
                        </label>
                        <div id="moreProductsGrid" class="mt-4 grid gap-3 sm:grid-cols-2"></div>
                    </div>
                    <div class="bg-white rounded-3xl border border-gray-100 p-5 text-sm text-gray-600 space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center"><i class="fas fa-shield-heart"></i></span>
                            <div>
                                <p class="font-semibold text-dark">Proteksi barang</p>
                                <p>Setiap pesanan dikurasi dan dibersihkan sebelum dikirim.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center"><i class="fas fa-recycle"></i></span>
                            <div>
                                <p class="font-semibold text-dark">Gerakan berkelanjutan</p>
                                <p>Belanja thrift = bantu kurangi limbah fashion.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>
        </div>

        <script src="js/reveal.js" defer></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                    const file = window.location.pathname.split('/').pop() || 'checkout';
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
                        window.location.href = `login?next=${encodeURIComponent(next)}`;
                        return;
                    }

                    await ProfileStore.ready;

                const summaryList = document.getElementById('summaryList');
                const summarySubtotal = document.getElementById('summarySubtotal');
                const summaryShipping = document.getElementById('summaryShipping');
                const summaryTotal = document.getElementById('summaryTotal');
                const shippingSelect = document.getElementById('shippingSelect');
                const checkoutForm = document.getElementById('checkoutForm');
                const checkoutError = document.getElementById('checkoutError');
                const payButton = document.getElementById('payButton');
                const moreProductsGrid = document.getElementById('moreProductsGrid');
                const moreProductsSearch = document.getElementById('moreProductsSearch');
                const moreProductsStatus = document.getElementById('moreProductsStatus');
                const savedAddressList = document.getElementById('savedAddressList');
                const toggleAddressFormBtn = document.getElementById('toggleAddressForm');
                const quickAddressForm = document.getElementById('quickAddressForm');
                const quickAddressSave = document.getElementById('quickAddressSave');
                const quickAddressCancel = document.getElementById('quickAddressCancel');
                const quickAddressStatus = document.getElementById('quickAddressStatus');
                const quickAddressLabel = document.getElementById('quickAddressLabel');
                const quickAddressRecipient = document.getElementById('quickAddressRecipient');
                const quickAddressPhone = document.getElementById('quickAddressPhone');
                const quickAddressDetail = document.getElementById('quickAddressDetail');
                const quickAddressPostal = document.getElementById('quickAddressPostal');
                const quickAddressPrimary = document.getElementById('quickAddressPrimary');

                const quickAddressSearch = document.getElementById('quickAddressSearch');
                const quickUseMyLocation = document.getElementById('quickUseMyLocation');
                const quickAddressSearchResults = document.getElementById('quickAddressSearchResults');
                const quickAddressMapStatus = document.getElementById('quickAddressMapStatus');
                const quickAddressMapEl = document.getElementById('quickAddressMap');
                const quickAddressLat = document.getElementById('quickAddressLat');
                const quickAddressLng = document.getElementById('quickAddressLng');
                const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
                let profileData = ProfileStore.getProfileData();

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

                let cart = null;
                let cartItems = [];
                let cartSummary = { subtotal: 0, shipping: 0, total: 0 };

                const applyShippingFromCart = () => {
                    if (!shippingSelect || !cart) return;
                    const value = String(cart.shipping_value ?? '0');
                    if (shippingSelect.querySelector(`option[value="${value}"]`)) {
                        shippingSelect.value = value;
                    }
                };

                const loadCart = async () => {
                    const res = await apiFetchJson('/api/cart');
                    cart = res?.data?.cart || null;
                    cartItems = Array.isArray(res?.data?.items) ? res.data.items : [];
                    cartSummary = res?.data?.summary || { subtotal: 0, shipping: 0, total: 0 };
                    applyShippingFromCart();

                    if (!cartItems.length) {
                        window.location.href = 'cart';
                        return false;
                    }
                    return true;
                };

                const applyAddressSelection = (address) => {
                    if (!address) return;
                    const profile = ProfileStore.getProfileData();
                    if (checkoutForm) {
                        checkoutForm.address.value = address.detail || '';
                        if (!checkoutForm.fullname.value) {
                            checkoutForm.fullname.value = address.recipient || profile.name || '';
                        }
                        if (!checkoutForm.phone.value && address.phone) {
                            checkoutForm.phone.value = address.phone;
                        }
                    }
                };

                const escapeHTML = (value = '') =>
                    String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');

                const renderSavedAddresses = () => {
                    if (!savedAddressList) return;
                    const { addresses } = ProfileStore.getProfileData();
                    if (!addresses.length) {
                        savedAddressList.innerHTML = '<p class="text-xs text-gray-400">Belum ada alamat tersimpan.</p>';
                        return;
                    }
                    savedAddressList.innerHTML = addresses
                        .map(
                            (addr) => `
                                <label class="flex items-start gap-3 rounded-2xl border ${addr.isPrimary ? 'border-primary/40 bg-primary/5' : 'border-gray-100'} p-3">
                                    <input type="radio" name="addressOption" value="${addr.id}" ${addr.isPrimary ? 'checked' : ''} class="mt-1 accent-primary" />
                                    <div>
                                        <p class="font-semibold text-dark">${escapeHTML(addr.label)}${addr.isPrimary ? ' <span class="text-xs text-primary font-semibold">(Utama)</span>' : ''}</p>
                                        <p class="text-xs text-gray-500">${escapeHTML(addr.detail)}</p>
                                        <p class="text-xs text-gray-400">${escapeHTML(addr.recipient)} &middot; ${escapeHTML(addr.phone)}</p>
                                    </div>
                                </label>
                            `,
                        )
                        .join('');

                    savedAddressList.querySelectorAll('input[name="addressOption"]').forEach((radio) => {
                        radio.addEventListener('change', (event) => {
                            const selectedId = event.target.value;
                            const selected = ProfileStore.getProfileData().addresses.find((addr) => addr.id === selectedId);
                            applyAddressSelection(selected);
                        });
                    });
                };

                const applyProfileDefaults = () => {
                    const profile = ProfileStore.getProfileData();
                    if (!checkoutForm) return;
                    checkoutForm.fullname.value = checkoutForm.fullname.value || profile.name || '';
                    checkoutForm.phone.value = checkoutForm.phone.value || profile.phone || '';
                    checkoutForm.email.value = checkoutForm.email.value || profile.email || '';
                    checkoutForm.notes.value = checkoutForm.notes.value || '';
                    const primaryAddress = profile.addresses?.find((addr) => addr.isPrimary) || profile.addresses?.[0];
                    if (primaryAddress && !checkoutForm.address.value) {
                        checkoutForm.address.value = primaryAddress.detail;
                    }
                };

                const setQuickStatus = (message, isError = false) => {
                    if (!quickAddressStatus) return;
                    quickAddressStatus.textContent = message;
                    if (!message) {
                        quickAddressStatus.classList.add('hidden');
                        return;
                    }
                    quickAddressStatus.classList.remove('hidden');
                    if (isError) {
                        quickAddressStatus.classList.add('text-red-500');
                        quickAddressStatus.classList.remove('text-green-600');
                    } else {
                        quickAddressStatus.classList.remove('text-red-500');
                        quickAddressStatus.classList.add('text-green-600');
                    }
                };

                const toggleQuickAddressForm = (forceHide = false) => {
                    if (!quickAddressForm) return;
                    const shouldHide = forceHide || !quickAddressForm.classList.contains('hidden');
                    if (shouldHide) {
                        quickAddressForm.classList.add('hidden');
                    } else {
                        quickAddressForm.classList.remove('hidden');
                        initQuickAddressMap();
                    }
                    setQuickStatus('');
                };

                let quickMapState = null;

                const setQuickMapStatus = (message, isError = false) => {
                    if (!quickAddressMapStatus) return;
                    quickAddressMapStatus.textContent = message || '';
                    quickAddressMapStatus.classList.toggle('text-red-500', Boolean(isError));
                    quickAddressMapStatus.classList.toggle('text-gray-500', !isError);
                };

                const reverseGeocode = async ({ lat, lng }) => {
                    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}&addressdetails=1`;
                    const res = await fetch(url, { headers: { Accept: 'application/json' } });
                    if (!res.ok) throw new Error('Gagal mengambil alamat dari maps.');
                    return res.json();
                };

                const searchGeocode = async (query) => {
                    const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&addressdetails=1&limit=6`;
                    const res = await fetch(url, { headers: { Accept: 'application/json' } });
                    if (!res.ok) throw new Error('Gagal mencari lokasi.');
                    return res.json();
                };

                const pickAddressFromGeocode = (data, lat, lng) => {
                    const displayName = data?.display_name || '';
                    const addr = data?.address || {};
                    const postcode = addr.postcode || '';
                    const city = addr.city || addr.town || addr.village || addr.county || addr.state || '';

                    if (quickAddressDetail && displayName) quickAddressDetail.value = displayName;
                    if (quickAddressPostal && postcode && !quickAddressPostal.value.trim()) quickAddressPostal.value = postcode;
                    if (quickAddressLat) quickAddressLat.value = String(lat);
                    if (quickAddressLng) quickAddressLng.value = String(lng);
                    if (quickAddressLabel && city && !quickAddressLabel.value.trim()) quickAddressLabel.value = `Rumah - ${city}`;
                };

                const renderQuickSearchResults = (items) => {
                    if (!quickAddressSearchResults) return;
                    if (!items || !items.length) {
                        quickAddressSearchResults.innerHTML = '<p class="px-3 py-2 text-gray-400">Tidak ada hasil.</p>';
                        quickAddressSearchResults.classList.remove('hidden');
                        return;
                    }
                    quickAddressSearchResults.innerHTML = items
                        .map(
                            (it, idx) => `
                                <button type="button" data-idx="${idx}" class="w-full text-left rounded-xl px-3 py-2 hover:bg-light">
                                    <p class="font-semibold text-dark">${escapeHTML(it.name || it.display_name || 'Lokasi')}</p>
                                    <p class="text-xs text-gray-500">${escapeHTML(it.display_name || '')}</p>
                                </button>
                            `,
                        )
                        .join('');
                    quickAddressSearchResults.classList.remove('hidden');
                    quickAddressSearchResults._items = items;
                };

                const initQuickAddressMap = () => {
                    if (!quickAddressMapEl) return;
                    if (!window.L) {
                        setQuickMapStatus('Maps tidak dapat dimuat. Periksa koneksi internet.', true);
                        quickAddressMapEl.classList.add('hidden');
                        return;
                    }

                    if (quickMapState?.map) {
                        // If map already created, just refresh sizing when form becomes visible.
                        setTimeout(() => quickMapState.map.invalidateSize(), 80);
                        return;
                    }

                    const profile = ProfileStore.getProfileData();
                    const primary = (profile.addresses || []).find((a) => a.isPrimary) || (profile.addresses || [])[0];
                    const startLat = Number(primary?.lat) || -6.200000;
                    const startLng = Number(primary?.lng) || 106.816666;

                    const map = L.map(quickAddressMapEl, { zoomControl: true, scrollWheelZoom: false }).setView([startLat, startLng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap',
                    }).addTo(map);

                    const marker = L.marker([startLat, startLng], { draggable: true }).addTo(map);

                    const applyLatLng = async (lat, lng, reasonLabel = '') => {
                        setQuickMapStatus(reasonLabel ? `Mengambil alamat… (${reasonLabel})` : 'Mengambil alamat…');
                        try {
                            const data = await reverseGeocode({ lat, lng });
                            pickAddressFromGeocode(data, lat, lng);
                            setQuickMapStatus('Alamat terisi otomatis dari maps.');
                        } catch (error) {
                            setQuickMapStatus(error?.message || 'Gagal mengisi alamat otomatis.', true);
                        }
                    };

                    map.on('click', (e) => {
                        const lat = e.latlng.lat;
                        const lng = e.latlng.lng;
                        marker.setLatLng([lat, lng]);
                        applyLatLng(lat, lng, 'klik peta');
                    });

                    marker.on('dragend', () => {
                        const pos = marker.getLatLng();
                        applyLatLng(pos.lat, pos.lng, 'geser pin');
                    });

                    quickUseMyLocation?.addEventListener('click', () => {
                        if (!navigator.geolocation) {
                            setQuickMapStatus('Browser tidak mendukung GPS.', true);
                            return;
                        }
                        setQuickMapStatus('Mengambil lokasi kamu…');
                        const handleSuccess = (pos) => {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            map.setView([lat, lng], 16);
                            marker.setLatLng([lat, lng]);
                            applyLatLng(lat, lng, 'lokasi saya');
                        };

                        const handleError = (err, canRetry) => {
                            const code = err?.code;
                            if (code === 1) {
                                setQuickMapStatus(
                                    'Izin lokasi ditolak. Cek permission untuk situs ini (mis. 127.0.0.1:5500) di Chrome.',
                                    true,
                                );
                                return;
                            }
                            if (code === 2) {
                                setQuickMapStatus(
                                    'Lokasi tidak tersedia. Biasanya karena Windows Location Services mati / device tidak bisa menentukan lokasi.',
                                    true,
                                );
                                return;
                            }
                            if (code === 3 && canRetry) {
                                setQuickMapStatus('Timeout. Mencoba lagi dengan akurasi standar…');
                                navigator.geolocation.getCurrentPosition(
                                    handleSuccess,
                                    (e2) => handleError(e2, false),
                                    { enableHighAccuracy: false, timeout: 20000, maximumAge: 60000 },
                                );
                                return;
                            }
                            if (code === 3) {
                                setQuickMapStatus('Permintaan lokasi timeout. Coba lagi atau pastikan Location Services Windows aktif.', true);
                                return;
                            }
                            setQuickMapStatus(err?.message || 'Gagal mengambil lokasi.', true);
                        };

                        navigator.geolocation.getCurrentPosition(
                            handleSuccess,
                            (err) => handleError(err, true),
                            { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 },
                        );
                    });

                    let searchTimer = null;
                    quickAddressSearch?.addEventListener('input', () => {
                        const q = quickAddressSearch.value.trim();
                        if (searchTimer) clearTimeout(searchTimer);
                        if (!q) {
                            quickAddressSearchResults?.classList.add('hidden');
                            return;
                        }
                        searchTimer = setTimeout(async () => {
                            try {
                                const results = await searchGeocode(q);
                                renderQuickSearchResults(results);
                            } catch (error) {
                                setQuickMapStatus(error?.message || 'Gagal mencari lokasi.', true);
                            }
                        }, 350);
                    });

                    quickAddressSearchResults?.addEventListener('click', async (event) => {
                        const btn = event.target.closest('button[data-idx]');
                        if (!btn) return;
                        const idx = Number(btn.dataset.idx);
                        const items = quickAddressSearchResults._items || [];
                        const it = items[idx];
                        if (!it) return;
                        const lat = Number(it.lat);
                        const lng = Number(it.lon);
                        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
                        quickAddressSearchResults.classList.add('hidden');
                        map.setView([lat, lng], 16);
                        marker.setLatLng([lat, lng]);
                        await applyLatLng(lat, lng, 'pilih hasil');
                    });

                    // If there is stored coords, prefer to hydrate detail once.
                    if (Number.isFinite(startLat) && Number.isFinite(startLng) && (primary?.lat || primary?.lng)) {
                        applyLatLng(startLat, startLng, 'alamat tersimpan');
                    }

                    quickMapState = { map, marker, applyLatLng };
                    setTimeout(() => map.invalidateSize(), 80);
                };

                toggleAddressFormBtn?.addEventListener('click', () => toggleQuickAddressForm());
                quickAddressCancel?.addEventListener('click', () => {
                    quickAddressLabel.value = '';
                    quickAddressRecipient.value = '';
                    quickAddressPhone.value = '';
                    quickAddressDetail.value = '';
                    quickAddressPostal.value = '';
                    if (quickAddressLat) quickAddressLat.value = '';
                    if (quickAddressLng) quickAddressLng.value = '';
                    if (quickAddressSearch) quickAddressSearch.value = '';
                    quickAddressSearchResults?.classList.add('hidden');
                    quickAddressPrimary.checked = false;
                    toggleQuickAddressForm(true);
                });

                quickAddressSave?.addEventListener('click', async () => {
                    const detail = quickAddressDetail.value.trim();
                    if (!detail) {
                        setQuickStatus('Detail alamat wajib diisi.', true);
                        return;
                    }
                    await ProfileStore.upsertAddress({
                        label: quickAddressLabel.value.trim() || 'Alamat baru',
                        recipient: quickAddressRecipient.value.trim() || checkoutForm.fullname.value.trim(),
                        phone: quickAddressPhone.value.trim() || checkoutForm.phone.value.trim(),
                        detail,
                        postal: quickAddressPostal.value.trim(),
                        lat: quickAddressLat?.value || undefined,
                        lng: quickAddressLng?.value || undefined,
                        mapsAddress: detail,
                        isPrimary: quickAddressPrimary.checked,
                    });
                    await ProfileStore.refresh();
                    profileData = ProfileStore.getProfileData();
                    setQuickStatus('Alamat tersimpan.');
                    renderSavedAddresses();
                    const primaryAddress = profileData.addresses?.find((addr) => addr.isPrimary) || profileData.addresses?.[profileData.addresses.length - 1];
                    applyAddressSelection(primaryAddress);
                    quickAddressLabel.value = '';
                    quickAddressRecipient.value = '';
                    quickAddressPhone.value = '';
                    quickAddressDetail.value = '';
                    quickAddressPostal.value = '';
                    if (quickAddressLat) quickAddressLat.value = '';
                    if (quickAddressLng) quickAddressLng.value = '';
                    if (quickAddressSearch) quickAddressSearch.value = '';
                    quickAddressSearchResults?.classList.add('hidden');
                    quickAddressPrimary.checked = false;
                    toggleQuickAddressForm(true);
                });

                const renderSummary = () => {
                    summaryList.innerHTML = cartItems
                        .map(
                            (line) => `
                                <li class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                            ${line.image ? `<img src="${escapeHTML(line.image)}" alt="${escapeHTML(line.name || 'Produk') }" class="w-full h-full object-cover" loading="lazy" referrerpolicy="no-referrer">` : '<span class="text-[10px] text-gray-400">No Image</span>'}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-dark truncate">${escapeHTML(line.name || 'Produk')}</p>
                                            <p class="text-xs text-gray-400">Qty ${line.quantity || 1}</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold">${currency.format((Number(line.price) || 0) * (Number(line.quantity) || 1))}</span>
                                </li>
                            `,
                        )
                        .join('');
                };

                const normalizeAvailableProduct = (p) => {
                    if (!p) return null;
                    const id = String(p.public_id ?? p.id ?? '');
                    const title = String(p.title || '').trim() || 'Produk Wida Collection';
                    const image = String(p.image || '').trim();
                    const priceIdr = Math.max(0, Number(p.price) || 0);
                    return { id, title, image, price: priceIdr };
                };

                let availableProducts = [];

                const renderMoreProducts = () => {
                    if (!moreProductsGrid) return;
                    const query = String(moreProductsSearch?.value || '').trim().toLowerCase();
                    const filtered = availableProducts
                        .filter((p) => !query || p.title.toLowerCase().includes(query))
                        .slice(0, 8);

                    if (!filtered.length) {
                        moreProductsGrid.innerHTML = '<p class="text-sm text-gray-400">Produk tidak ditemukan.</p>';
                        return;
                    }

                    moreProductsGrid.innerHTML = filtered
                        .map(
                            (p) => `
                                <button type="button" class="w-full text-left rounded-2xl border border-gray-100 hover:border-primary/40 hover:bg-primary/5 transition p-3 flex gap-3 items-center" data-add-product-id="${escapeHTML(p.id)}">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                        ${p.image ? `<img src="${escapeHTML(p.image)}" alt="${escapeHTML(p.title)}" class="w-full h-full object-cover" loading="lazy" referrerpolicy="no-referrer">` : '<span class="text-[10px] text-gray-400">No Image</span>'}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-dark truncate">${escapeHTML(p.title)}</p>
                                        <p class="text-xs text-gray-400">${escapeHTML(currency.format(p.price))}</p>
                                    </div>
                                    <span class="text-xs font-semibold text-primary">Tambah</span>
                                </button>
                            `,
                        )
                        .join('');
                };

                const addProductToCheckout = async (product) => {
                    await apiFetchJson('/api/cart/items', {
                        method: 'POST',
                        body: JSON.stringify({ product_id: String(product.id), quantity: 1 }),
                    });
                    await loadCart();
                    renderSummary();
                    recalcTotals();
                };

                const loadMoreProducts = async () => {
                    if (moreProductsStatus) moreProductsStatus.textContent = 'Memuat...';
                    let apiProducts = [];
                    try {
                        const res = await apiFetchJson('/api/products');
                        apiProducts = Array.isArray(res?.data) ? res.data.map(normalizeAvailableProduct).filter(Boolean) : [];
                    } catch (_) {
                        apiProducts = [];
                    }
                    availableProducts = apiProducts.filter((p) => p && p.id);

                    if (moreProductsStatus) {
                        moreProductsStatus.textContent = availableProducts.length ? `${availableProducts.length} produk` : 'Tidak ada data';
                    }
                    renderMoreProducts();
                };

                const recalcTotals = () => {
                    summarySubtotal.textContent = currency.format(Number(cartSummary.subtotal) || 0);
                    summaryShipping.textContent = currency.format(Number(cartSummary.shipping) || 0);
                    summaryTotal.textContent = currency.format(Number(cartSummary.total) || 0);
                    return { subtotal: Number(cartSummary.subtotal) || 0, shippingCost: Number(cartSummary.shipping) || 0, total: Number(cartSummary.total) || 0 };
                };

                renderSavedAddresses();
                applyProfileDefaults();
                const hasCart = await loadCart();
                if (!hasCart) return;
                renderSummary();
                recalcTotals();

                loadMoreProducts();
                moreProductsSearch?.addEventListener('input', renderMoreProducts);
                moreProductsGrid?.addEventListener('click', async (event) => {
                    const btn = event.target.closest('[data-add-product-id]');
                    if (!btn) return;
                    const id = btn.getAttribute('data-add-product-id');
                    const found = availableProducts.find((p) => String(p.id) === String(id));
                    if (!found) return;
                    try {
                        await addProductToCheckout(found);
                    } catch (error) {
                        console.error('Gagal menambahkan produk', error);
                    }
                });
                shippingSelect.addEventListener('change', async () => {
                    try {
                        const selected = shippingSelect.selectedOptions?.[0];
                        const shipping_value = Number(shippingSelect.value || 0);
                        const shipping_label = selected?.dataset?.label || selected?.textContent?.trim() || null;
                        await apiFetchJson('/api/cart/shipping', {
                            method: 'PATCH',
                            body: JSON.stringify({ shipping_value, shipping_label }),
                        });
                        await loadCart();
                        renderSummary();
                        recalcTotals();
                    } catch (error) {
                        console.error('Gagal menyimpan pengiriman', error);
                    }
                });

                checkoutForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    checkoutError.classList.add('hidden');
                    payButton.disabled = true;

                    const formData = new FormData(checkoutForm);
                    const selected = shippingSelect.selectedOptions?.[0];
                    const payload = {
                        customer: {
                            fullname: String(formData.get('fullname') || ''),
                            phone: String(formData.get('phone') || ''),
                            email: String(formData.get('email') || '') || null,
                            address: String(formData.get('address') || ''),
                            notes: String(formData.get('notes') || '') || null,
                        },
                        payment: String(formData.get('payment') || '') || null,
                        shipping_value: Number(shippingSelect.value || 0),
                        shipping_label: selected?.dataset?.label || selected?.textContent?.trim() || null,
                    };

                    try {
                        const res = await apiFetchJson('/api/orders/checkout', {
                            method: 'POST',
                            body: JSON.stringify(payload),
                        });
                        const orderUuid = res?.data?.order?.uuid;
                        if (!orderUuid) {
                            throw new Error('Order berhasil dibuat, tapi UUID tidak ditemukan.');
                        }
                        window.location.href = `success?order=${encodeURIComponent(orderUuid)}`;
                    } catch (error) {
                        const msg = error?.data?.message || error?.message || 'Terjadi kesalahan. Silakan coba lagi.';
                        checkoutError.textContent = msg;
                        checkoutError.classList.remove('hidden');
                        payButton.disabled = false;
                    }
                });
                })();
            });
        </script>
    </body>
</html>