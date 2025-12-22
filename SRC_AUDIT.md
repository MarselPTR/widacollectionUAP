# Widacollection — Audit Ringkas (src)

Dokumen ini adalah “catatan ingatan” audit folder `src/` (halaman, modul JS, alur data, dan storage keys).

## 1) Struktur & Cara Menjalankan
- Project ini adalah website statis multi-halaman (HTML) di `src/`.
- Styling dibangun oleh Tailwind CLI.

Perintah penting (lihat `package.json`):
- Serve: `npm run serve` (live-server untuk `src/`)
- CSS build: `npm run build:css`
- CSS watch: `npm run watch:css`

## 2) “Routes” (Halaman HTML)
Halaman-halaman utama:
- Landing: `src/body.html`
- Auth: `src/login.html`, `src/register.html`
- Commerce: `src/cart.html`, `src/checkout.html`, `src/success.html`, `src/product-detail.html`
- User profile: `src/profile.html`, `src/edit-profile.html`, `src/orders.html`, `src/wishlist.html`
- Admin: `src/admin.html`, `src/profile-admin.html`

Pola proteksi halaman (auth gating):
- Banyak halaman mengecek flag login (mis. `wc_logged_in === '1'`).
- Jika belum login: simpan redirect target di `wc_login_redirect`, lalu redirect ke `login.html`.

## 3) Styling (Tailwind + Theme Tokens)
- Input Tailwind: `src/input.css`.
- Output build: `src/output.css` (minified).
- Theme tokens didefinisikan via Tailwind v4 `@theme` (contoh: `--color-primary`, `--font-poppins`).

## 4) Pola State Management
- Hampir semua fitur memakai `localStorage` sebagai “database”.
- Modul store diekspos sebagai singleton global di `window` (mis. `window.AuthStore`).

Modul store utama:
- `src/js/profile-data.js`: `AuthStore` + `ProfileStore`
- `src/js/order-store.js`: `OrderStore`
- `src/js/review-store.js`: `ReviewStore`
- `src/js/custom-products.js`: `CustomProductStore`
- `src/js/live-drop-store.js`: `LiveDropStore`
- `src/js/reveal.js`: sistem reveal/animasi
- `src/js/main.js`: controller landing page (produk, search, cart badge, contact form, dll.)

## 5) Skema localStorage (Kunci Penting)
Auth/Profile:
- `wc_profiles_v2`: map profile per email
- `wc_accounts_v2`: map account per email (credential)
- `wc_active_account`: email account aktif
- `wc_logged_in`: '1' atau '0'
- (legacy) `wc_profile`, `wc_account` (dimigrasi)

Cart/Checkout:
- `wc_cart_items`: item cart
- `wc_shipping_pref`: preferensi shipping
- `wc_buy_now_items_v1`: item untuk flow buy-now (jika dipakai)
- `wc_last_order`: order terakhir (untuk `success.html`)

Orders/Reviews:
- `wc_orders_v1`: orders per email
- `wc_reviews_v1`: reviews

Admin/Marketing:
- `wc_custom_products_v1`: produk custom (admin)
- `wc_live_drop_settings_v1`: setting live-drop (admin)
- `wc_contact_messages_v1`: pesan dari contact form (dibaca admin inbox)

## 6) Integrasi Data Produk
- Produk utama diambil dari FakeStore API: `https://fakestoreapi.com/products`.
- Produk custom dari `CustomProductStore` digabung ke katalog.
- Rating ringkas dari `ReviewStore` dipakai untuk tampilan rating produk.

## 7) Alur Fitur (Ringkas)
- Register/Login → set login flag → redirect (user: profile, admin: profile-admin).
- Landing (`body.html`) → fetch produk → render katalog → add-to-cart (localStorage).
- Cart (`cart.html`) → normalisasi item → qty +/- → subtotal + shipping + total.
- Checkout (`checkout.html`) → alamat (Leaflet) + create order → simpan `wc_last_order`.
- Success (`success.html`) → tampilkan ringkasan `wc_last_order`.
- Orders (`orders.html`) → status shipped/delivered + tulis/perbarui ulasan.
- Admin (`admin.html`) → kelola custom products, live-drop settings, dan status pengiriman.
- Admin inbox (`profile-admin.html`) → baca/hapus contact messages.

---
Catatan: Dokumen ini sengaja ringkas sebagai “memory anchor”.
