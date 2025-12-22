<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Keranjang Belanja - Wida Collection</title>
    <link rel="stylesheet" href="output.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="app.css" />
  </head>
  <body class="font-poppins bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8 space-y-8">
      <header class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between bg-white/80 backdrop-blur rounded-2xl shadow-lg p-6 border border-white/60">
        <div>
          <p class="text-sm font-semibold text-primary wc-reveal" style="--reveal-delay: 40ms;">Wida <span class="text-secondary">Collection</span></p>
          <p class="text-xs uppercase tracking-[0.4em] text-secondary font-semibold wc-reveal" style="--reveal-delay: 90ms;">Langkah 1 dari 3</p>
          <h1 class="text-3xl font-bold text-dark wc-reveal" style="--reveal-delay: 140ms;">Keranjang Belanja</h1>
          <p class="text-gray-500 wc-reveal" style="--reveal-delay: 210ms;">Review ulang item favoritmu sebelum lanjut ke pembayaran.</p>
        </div>
        <div class="flex flex-wrap gap-3">
          <a href="body.html" class="inline-flex items-center gap-2 px-5 py-2 rounded-full border border-primary/40 text-primary font-semibold hover:bg-primary/5 transition wc-reveal" style="--reveal-delay: 260ms;">
            <i class="fas fa-arrow-left"></i> Lanjut Belanja
          </a>
          <a href="profile.html" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-secondary text-white font-semibold shadow-md hover:opacity-90 transition wc-reveal" style="--reveal-delay: 320ms;">
            <i class="fas fa-user"></i> Profil
          </a>
        </div>
      </header>

      <section class="grid lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-5">
          <div class="flex flex-col gap-4 bg-white rounded-2xl shadow p-5 border border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-4">
              <div>
                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Proses pesanan</p>
                <div class="flex items-center gap-3 text-sm font-semibold text-gray-500 uppercase tracking-[0.2em]">
                  <span class="flex items-center gap-2 text-primary"><i class="fas fa-cart-plus"></i> Keranjang</span>
                  <span class="h-px w-8 bg-gray-200"></span>
                  <span>Checkout</span>
                  <span class="h-px w-8 bg-gray-200"></span>
                  <span>Berhasil</span>
                </div>
              </div>
              <div class="text-right">
                <p class="text-xs text-gray-400">Status</p>
                <p id="cartStatusLabel" class="text-sm font-semibold text-dark">0 item tersimpan</p>
              </div>
            </div>
            <button type="button" id="clearCart" class="self-start inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary/80">
              <i class="fas fa-trash"></i> Bersihkan keranjang
            </button>
          </div>

          <div id="cartLines" class="space-y-5"></div>

          <div id="cartEmpty" class="hidden bg-white rounded-2xl p-10 text-center border border-dashed border-primary/40">
            <div class="mx-auto h-20 w-20 rounded-full bg-primary/10 flex items-center justify-center text-primary text-3xl">
              <i class="fas fa-box-open"></i>
            </div>
            <h3 class="mt-6 text-2xl font-semibold text-dark">Keranjangmu masih kosong</h3>
            <p class="mt-2 text-gray-500">Tambahkan produk pilihanmu melalui halaman katalog atau detail produk.</p>
            <a href="body.html#products" class="mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary text-white font-semibold shadow hover:bg-primary/90">
              Jelajahi Produk <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>

        <aside class="space-y-5">
          <div class="bg-white rounded-2xl shadow border border-gray-100 p-6 space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">Ringkasan</p>
                <h2 class="text-xl font-semibold text-dark">Rincian Pembayaran</h2>
              </div>
              <span class="px-3 py-1 text-xs font-semibold rounded-full bg-secondary/10 text-secondary">Aman & transparan</span>
            </div>
            <dl class="space-y-3 text-sm text-gray-600">
              <div class="flex items-center justify-between">
                <dt>Subtotal</dt>
                <dd id="subtotalAmount">Rp0</dd>
              </div>
              <div class="flex items-center justify-between">
                <dt>Diskon</dt>
                <dd class="text-secondary">- Rp0</dd>
              </div>
              <div class="flex items-center justify-between">
                <dt>Ongkos kirim</dt>
                <dd id="shippingAmount">Rp0</dd>
              </div>
            </dl>
            <div class="border-t border-gray-100 pt-4">
              <div class="flex items-center justify-between text-lg font-bold text-dark">
                <span>Total</span>
                <span id="totalAmount">Rp0</span>
              </div>
              <div class="mt-4">
                <label for="shippingSpeed" class="block text-xs uppercase tracking-[0.3em] text-gray-400">Pilih Pengiriman</label>
                <select id="shippingSpeed" class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2 focus:outline-none focus:border-primary">
                  <option value="0" data-label="Reguler 3-4 hari">Reguler (3-4 hari) - Gratis</option>
                  <option value="20000" data-label="Kilat 1-2 hari">Kilat (1-2 hari) - Rp20.000</option>
                  <option value="50000" data-label="Same day">Same day - Rp50.000</option>
                </select>
              </div>
              <button
                type="button"
                id="checkoutBtn"
                class="mt-5 w-full rounded-full bg-secondary text-white font-semibold py-3 shadow hover:bg-secondary/90 transition"
              >
                Lanjut Pembayaran
              </button>
              <p class="mt-3 text-xs text-center text-gray-400">Pembayaran dilindungi SSL & rekening resmi</p>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow border border-gray-100 p-5 space-y-3 text-sm text-gray-500">
            <div class="flex items-center gap-3">
              <span class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                <i class="fas fa-shield-check"></i>
              </span>
              <div>
                <p class="text-dark font-semibold">Garansi Kualitas</p>
                <p>Barang dikurasi langsung oleh tim Wida Collection sebelum dikirim.</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <span class="h-10 w-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center">
                <i class="fas fa-truck"></i>
              </span>
              <div>
                <p class="text-dark font-semibold">Pengiriman Cepat</p>
                <p>Sameday tersedia untuk area Jabodetabek.</p>
              </div>
            </div>
          </div>
        </aside>
      </section>
    </div>

    <script src="js/reveal.js" defer></script>

    <template id="cart-line-template">
      <article class="bg-white rounded-2xl p-5 shadow border border-gray-100 flex flex-col sm:flex-row gap-5">
        <div class="w-full sm:w-32 bg-gradient-to-br from-gray-50 to-white rounded-xl flex items-center justify-center overflow-hidden">
          <img data-line-image src="" alt="" class="w-24 h-24 object-contain" loading="lazy" />
        </div>
        <div class="flex-1 flex flex-col gap-3">
          <div class="flex items-start justify-between gap-3">
            <div>
              <p data-line-category class="text-xs uppercase tracking-[0.3em] text-gray-400">Kategori</p>
              <h3 data-line-name class="text-lg font-semibold text-dark">Nama Produk</h3>
              <p data-line-note class="text-sm text-gray-500">Keterangan</p>
            </div>
            <button type="button" class="text-gray-400 hover:text-primary transition" aria-label="Hapus item" data-line-remove>
              <i class="fas fa-xmark"></i>
            </button>
          </div>
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="inline-flex items-center gap-3 bg-gray-100 rounded-full px-3 py-1 text-sm font-medium text-gray-600">
              <i class="fas fa-certificate text-primary"></i> Kurasi Grade A
            </div>
            <div class="flex items-center gap-4">
              <div class="flex items-center gap-3 bg-gray-100 rounded-full px-3 py-1">
                <button type="button" class="qty-btn text-gray-500" data-line-decrease>-</button>
                <span data-line-qty class="w-10 text-center font-semibold">1</span>
                <button type="button" class="qty-btn text-gray-500" data-line-increase>+</button>
              </div>
              <div class="text-right">
                <p class="text-xs text-gray-400">Harga</p>
                <p data-line-price class="text-xl font-bold text-primary">Rp0</p>
              </div>
            </div>
          </div>
        </div>
      </article>
    </template>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const LOGIN_KEY = 'wc_logged_in';
        const CART_KEY = 'wc_cart_items';
        const REDIRECT_KEY = 'wc_login_redirect';
        const setLoginRedirect = (value) => {
          try {
            localStorage.setItem(REDIRECT_KEY, String(value || ''));
          } catch (_) {}
        };
        const currentRelativeUrl = () => {
          const file = window.location.pathname.split('/').pop() || 'cart.html';
          return `${file}${window.location.search || ''}${window.location.hash || ''}`;
        };
        if (localStorage.getItem(LOGIN_KEY) !== '1') {
          setLoginRedirect(currentRelativeUrl());
          window.location.href = 'login.html';
          return;
        }

        const cartLinesEl = document.getElementById('cartLines');
        const cartEmptyEl = document.getElementById('cartEmpty');
        const cartStatusLabel = document.getElementById('cartStatusLabel');
        const subtotalAmount = document.getElementById('subtotalAmount');
        const shippingAmount = document.getElementById('shippingAmount');
        const totalAmount = document.getElementById('totalAmount');
        const shippingSpeed = document.getElementById('shippingSpeed');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const clearCartBtn = document.getElementById('clearCart');
        const lineTemplate = document.getElementById('cart-line-template');
        const IMAGE_FALLBACK = 'https://placehold.co/320x320?text=Wida+Collection';
        const SHIPPING_PREF_KEY = 'wc_shipping_pref';

        const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

        const loadCart = () => {
          try {
            const raw = localStorage.getItem(CART_KEY) || '[]';
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
          } catch (error) {
            console.warn('Cart data corrupted, resetting...', error);
            return [];
          }
        };

        const parseNumber = (value) => {
          if (typeof value === 'number' && Number.isFinite(value)) return value;
          if (typeof value === 'string') {
            const sanitized = value.replace(/[^0-9,-]/g, '').replace(/,/g, '.');
            const numeric = Number(sanitized.replace(/\.(?=.*\.)/g, ''));
            if (Number.isFinite(numeric)) return numeric;
          }
          return 0;
        };

        const loadShippingPref = () => {
          try {
            const raw = localStorage.getItem(SHIPPING_PREF_KEY);
            const parsed = raw ? JSON.parse(raw) : null;
            return parsed && typeof parsed === 'object' ? parsed : null;
          } catch (error) {
            console.warn('Gagal membaca preferensi pengiriman', error);
            return null;
          }
        };

        const persistShippingPref = () => {
          if (!shippingSpeed) return;
          const selected = shippingSpeed.selectedOptions[0];
          const payload = {
            value: shippingSpeed.value,
            label: selected?.dataset.label || selected?.textContent?.trim() || '',
          };
          localStorage.setItem(SHIPPING_PREF_KEY, JSON.stringify(payload));
        };

        const normalizeLine = (raw, idx) => {
          if (!raw) return null;
          const quantity = Number(raw.quantity ?? raw.qty ?? 1);
          const price = parseNumber(raw.price ?? raw.priceRaw ?? raw.priceDisplay);
          return {
            id: String(raw.id ?? `wc-${idx}-${Date.now()}`),
            name: raw.name ?? raw.title ?? 'Produk Wida Collection',
            category: raw.category ?? raw.type ?? 'Kurasi Wida Collection',
            description: raw.description ?? raw.note ?? '',
            image: raw.image || IMAGE_FALLBACK,
            price: price > 0 ? price : 0,
            quantity: Number.isFinite(quantity) && quantity > 0 ? quantity : 1,
          };
        };

        let cartLines = loadCart().map(normalizeLine).filter(Boolean);

        const applySavedShipping = () => {
          const pref = loadShippingPref();
          if (pref?.value && shippingSpeed?.querySelector(`option[value="${pref.value}"]`)) {
            shippingSpeed.value = String(pref.value);
          }
          persistShippingPref();
        };

        const persist = () => {
          const legacyFriendly = cartLines.map((line) => ({
            ...line,
            qty: line.quantity,
            priceRaw: line.price,
          }));
          localStorage.setItem(CART_KEY, JSON.stringify(legacyFriendly));
        };

        const updateCountLabel = () => {
          const totalItems = cartLines.reduce((acc, item) => acc + (item.quantity || 0), 0);
          if (cartStatusLabel) {
            cartStatusLabel.textContent = totalItems > 0 ? `${totalItems} item tersimpan` : 'Tidak ada item';
          }
        };

        const updateEmptyState = () => {
          if (cartLines.length === 0) {
            cartLinesEl.innerHTML = '';
            cartEmptyEl.classList.remove('hidden');
          } else {
            cartEmptyEl.classList.add('hidden');
          }
        };

        const recalcTotals = () => {
          const subtotal = cartLines.reduce((acc, line) => acc + (line.price || 0) * (line.quantity || 0), 0);
          const shippingCost = Number(shippingSpeed?.value || 0);
          subtotalAmount.textContent = currency.format(subtotal);
          shippingAmount.textContent = currency.format(shippingCost);
          totalAmount.textContent = currency.format(subtotal + shippingCost);
          const disabled = cartLines.length === 0;
          checkoutBtn.disabled = disabled;
          checkoutBtn.classList.toggle('opacity-60', disabled);
        };

        const handleQuantityChange = (id, delta) => {
          const target = cartLines.find((line) => line.id === id);
          if (!target) return;
          target.quantity = Math.max(1, (target.quantity || 1) + delta);
          persist();
          render();
        };

        const handleRemove = (id) => {
          cartLines = cartLines.filter((line) => line.id !== id);
          persist();
          render();
        };

        const buildLineElement = (line) => {
          const fragment = lineTemplate.content.cloneNode(true);
          fragment.querySelector('[data-line-image]').src = line.image;
          fragment.querySelector('[data-line-image]').alt = line.name;
          fragment.querySelector('[data-line-category]').textContent = line.category;
          fragment.querySelector('[data-line-name]').textContent = line.name;
          fragment.querySelector('[data-line-note]').textContent = line.description || 'Pilihan terbaik minggu ini';
          fragment.querySelector('[data-line-price]').textContent = currency.format(line.price);
          fragment.querySelector('[data-line-qty]').textContent = line.quantity;
          fragment.querySelector('[data-line-remove]').dataset.id = line.id;
          fragment.querySelector('[data-line-decrease]').dataset.id = line.id;
          fragment.querySelector('[data-line-increase]').dataset.id = line.id;
          return fragment;
        };

        const bindLineEvents = () => {
          cartLinesEl.querySelectorAll('[data-line-remove]').forEach((btn) => {
            btn.addEventListener('click', () => handleRemove(btn.dataset.id));
          });
          cartLinesEl.querySelectorAll('[data-line-decrease]').forEach((btn) => {
            btn.addEventListener('click', () => handleQuantityChange(btn.dataset.id, -1));
          });
          cartLinesEl.querySelectorAll('[data-line-increase]').forEach((btn) => {
            btn.addEventListener('click', () => handleQuantityChange(btn.dataset.id, 1));
          });
        };

        const render = () => {
          updateCountLabel();
          updateEmptyState();
          if (!cartLines.length) {
            recalcTotals();
            return;
          }
          cartLinesEl.innerHTML = '';
          cartLines.forEach((line) => {
            cartLinesEl.appendChild(buildLineElement(line));
          });
          bindLineEvents();
          recalcTotals();
        };

        shippingSpeed.addEventListener('change', () => {
          recalcTotals();
          persistShippingPref();
        });
        checkoutBtn.addEventListener('click', () => {
          if (cartLines.length === 0) return;
          window.location.href = 'checkout.html';
        });
        clearCartBtn.addEventListener('click', () => {
          if (!cartLines.length) return;
          cartLines = [];
          persist();
          render();
        });

        applySavedShipping();
        render();
      });
    </script>
  </body>
</html>
