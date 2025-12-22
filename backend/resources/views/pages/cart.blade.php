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
        <div class="w-full sm:w-32 bg-linear-to-br from-gray-50 to-white rounded-xl flex items-center justify-center overflow-hidden">
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
        const REDIRECT_KEY = 'wc_login_redirect';
        const setLoginRedirect = (value) => {
          try {
            sessionStorage.setItem(REDIRECT_KEY, String(value || ''));
          } catch (_) {}
        };
        const currentRelativeUrl = () => {
          const file = window.location.pathname.split('/').pop() || 'cart.html';
          return `${file}${window.location.search || ''}${window.location.hash || ''}`;
        };

        (async () => {
          if (!window.AuthStore) {
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

        const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

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
        let items = [];
        let summary = { subtotal: 0, shipping: 0, total: 0 };

        const updateCountLabel = () => {
          const totalItems = items.reduce((acc, item) => acc + (Number(item.quantity) || 0), 0);
          if (cartStatusLabel) {
            cartStatusLabel.textContent = totalItems > 0 ? `${totalItems} item tersimpan` : 'Tidak ada item';
          }
        };

        const updateEmptyState = () => {
          if (!items.length) {
            cartLinesEl.innerHTML = '';
            cartEmptyEl.classList.remove('hidden');
          } else {
            cartEmptyEl.classList.add('hidden');
          }
        };

        const updateTotals = () => {
          subtotalAmount.textContent = currency.format(Number(summary.subtotal) || 0);
          shippingAmount.textContent = currency.format(Number(summary.shipping) || 0);
          totalAmount.textContent = currency.format(Number(summary.total) || 0);
          const disabled = items.length === 0;
          checkoutBtn.disabled = disabled;
          checkoutBtn.classList.toggle('opacity-60', disabled);
        };

        const buildLineElement = (line) => {
          const fragment = lineTemplate.content.cloneNode(true);
          const image = line.image || IMAGE_FALLBACK;
          fragment.querySelector('[data-line-image]').src = image;
          fragment.querySelector('[data-line-image]').alt = line.name || 'Produk';
          fragment.querySelector('[data-line-category]').textContent = line.category || 'Kurasi Wida Collection';
          fragment.querySelector('[data-line-name]').textContent = line.name || 'Produk Wida Collection';
          fragment.querySelector('[data-line-note]').textContent = line.description || 'Pilihan terbaik minggu ini';
          fragment.querySelector('[data-line-price]').textContent = currency.format(Number(line.price) || 0);
          fragment.querySelector('[data-line-qty]').textContent = Number(line.quantity) || 1;
          fragment.querySelector('[data-line-remove]').dataset.uuid = line.uuid;
          fragment.querySelector('[data-line-decrease]').dataset.uuid = line.uuid;
          fragment.querySelector('[data-line-increase]').dataset.uuid = line.uuid;
          return fragment;
        };

        const bindLineEvents = () => {
          cartLinesEl.querySelectorAll('[data-line-remove]').forEach((btn) => {
            btn.addEventListener('click', async () => {
              const uuid = btn.dataset.uuid;
              if (!uuid) return;
              await apiFetchJson(`/api/cart/items/${encodeURIComponent(uuid)}`, { method: 'DELETE' });
              await loadAndRender();
            });
          });
          cartLinesEl.querySelectorAll('[data-line-decrease]').forEach((btn) => {
            btn.addEventListener('click', async () => {
              const uuid = btn.dataset.uuid;
              const item = items.find((x) => x.uuid === uuid);
              if (!uuid || !item) return;
              const nextQty = Math.max(1, (Number(item.quantity) || 1) - 1);
              await apiFetchJson(`/api/cart/items/${encodeURIComponent(uuid)}`, { method: 'PATCH', body: JSON.stringify({ quantity: nextQty }) });
              await loadAndRender();
            });
          });
          cartLinesEl.querySelectorAll('[data-line-increase]').forEach((btn) => {
            btn.addEventListener('click', async () => {
              const uuid = btn.dataset.uuid;
              const item = items.find((x) => x.uuid === uuid);
              if (!uuid || !item) return;
              const nextQty = (Number(item.quantity) || 1) + 1;
              await apiFetchJson(`/api/cart/items/${encodeURIComponent(uuid)}`, { method: 'PATCH', body: JSON.stringify({ quantity: nextQty }) });
              await loadAndRender();
            });
          });
        };

        const render = () => {
          updateCountLabel();
          updateEmptyState();
          cartLinesEl.innerHTML = '';
          items.forEach((line) => {
            cartLinesEl.appendChild(buildLineElement(line));
          });
          bindLineEvents();
          updateTotals();
        };

        const applyShippingFromCart = () => {
          if (!shippingSpeed || !cart) return;
          const value = String(cart.shipping_value ?? '0');
          if (shippingSpeed.querySelector(`option[value="${value}"]`)) {
            shippingSpeed.value = value;
          }
        };

        const loadAndRender = async () => {
          const res = await apiFetchJson('/api/cart');
          cart = res?.data?.cart || null;
          items = Array.isArray(res?.data?.items) ? res.data.items : [];
          summary = res?.data?.summary || { subtotal: 0, shipping: 0, total: 0 };
          applyShippingFromCart();
          render();
        };

        shippingSpeed?.addEventListener('change', async () => {
          const selected = shippingSpeed.selectedOptions?.[0];
          const shipping_value = Number(shippingSpeed.value || 0);
          const shipping_label = selected?.dataset?.label || selected?.textContent?.trim() || null;
          await apiFetchJson('/api/cart/shipping', {
            method: 'PATCH',
            body: JSON.stringify({ shipping_value, shipping_label }),
          });
          await loadAndRender();
        });

        checkoutBtn?.addEventListener('click', () => {
          if (!items.length) return;
          window.location.href = 'checkout.html';
        });

        clearCartBtn?.addEventListener('click', async () => {
          await apiFetchJson('/api/cart/clear', { method: 'DELETE' });
          await loadAndRender();
        });

        await loadAndRender();
        })();
      });
    </script>
  </body>
</html>
