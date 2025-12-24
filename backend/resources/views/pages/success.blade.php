<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Pembayaran Berhasil - Wida Collection</title>
        <link rel="stylesheet" href="output.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="app.css" />
    </head>
    <body class="font-poppins bg-gray-50 min-h-screen relative">
        <canvas id="confettiCanvas" class="pointer-events-none fixed inset-0 z-0"></canvas>
        <div class="relative z-10 max-w-5xl mx-auto px-4 py-12 space-y-8">
            <header class="text-center space-y-4">
                <div class="inline-flex items-center justify-center rounded-full bg-primary/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-primary wc-reveal" style="--reveal-delay: 40ms;">
                    <i class="fas fa-circle-check mr-2"></i> Pembayaran sukses
                </div>
                <p class="text-sm font-semibold text-primary wc-reveal" style="--reveal-delay: 100ms;">Wida <span class="text-secondary">Collection</span></p>
                <h1 class="text-3xl md:text-4xl font-bold text-dark wc-reveal" style="--reveal-delay: 160ms;">Terima kasih sudah berbelanja di Wida Collection</h1>
                <p class="text-gray-500 max-w-2xl mx-auto wc-reveal" style="--reveal-delay: 230ms;">Pesanan kamu sedang kami proses. Rincian di bawah ini juga dikirim ke email/WhatsApp yang kamu masukkan saat checkout.</p>
            </header>

            <section class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
                <article class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">ID Pesanan</p>
                            <h2 id="successOrderId" class="text-2xl font-bold text-dark">-</h2>
                        </div>
                        <div class="text-right text-sm text-gray-500">
                            <p>Diterima pada</p>
                            <p id="successOrderDate" class="font-semibold text-dark">-</p>
                        </div>
                    </div>
                    <ul id="successItemList" class="divide-y divide-gray-100 text-sm text-gray-600"></ul>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center justify-between">
                            <span>Subtotal</span>
                            <strong id="successSubtotal">Rp0</strong>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Pengiriman</span>
                            <strong id="successShipping">Rp0</strong>
                        </div>
                        <div class="flex items-center justify-between text-lg">
                            <span>Total</span>
                            <span id="successTotal" class="text-2xl font-bold text-dark">Rp0</span>
                        </div>
                    </div>
                </article>

                <aside class="space-y-4">
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                        <h3 class="text-xl font-semibold text-dark">Informasi Pengiriman</h3>
                        <dl class="space-y-3 text-sm text-gray-600">
                            <div>
                                <dt class="text-gray-400 text-xs uppercase tracking-[0.3em]">Nama penerima</dt>
                                <dd id="successCustomer" class="text-lg font-semibold text-dark">-</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-xs uppercase tracking-[0.3em]">Kontak</dt>
                                <dd id="successContact">-</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-xs uppercase tracking-[0.3em]">Alamat</dt>
                                <dd id="successAddress">-</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-xs uppercase tracking-[0.3em]">Pengiriman</dt>
                                <dd id="successShippingLabel">-</dd>
                            </div>
                            <div>
                                <dt class="text-gray-400 text-xs uppercase tracking-[0.3em]">Metode bayar</dt>
                                <dd id="successPayment">-</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="bg-white rounded-3xl border border-gray-100 p-6 space-y-4 text-sm text-gray-600">
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-full bg-primary/10 text-primary flex items-center justify-center"><i class="fas fa-shield-heart"></i></span>
                            <div>
                                <p class="font-semibold text-dark">Proteksi Wida Collection</p>
                                <p>Barang dikemas dan diasuransikan sampai tiba di alamat tujuan.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 rounded-full bg-secondary/10 text-secondary flex items-center justify-center"><i class="fas fa-leaf"></i></span>
                            <div>
                                <p class="font-semibold text-dark">Impact berkelanjutan</p>
                                <p>Belanja thrift membantu mengurangi limbah tekstil di Indonesia.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </section>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="body" class="inline-flex items-center gap-2 rounded-full bg-secondary text-white px-8 py-3 font-semibold shadow hover:bg-secondary/90 wc-reveal" style="--reveal-delay: 280ms;">
                    <i class="fas fa-house"></i> Kembali beranda
                </a>
                <a href="profile" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-8 py-3 font-semibold text-gray-600 hover:border-primary hover:text-primary wc-reveal" style="--reveal-delay: 340ms;">
                    <i class="fas fa-user"></i> Lihat profil
                </a>
            </div>

            <div id="successFallback" class="hidden text-center space-y-3">
                <p class="text-lg font-semibold text-dark">Riwayat pesanan tidak ditemukan.</p>
                <p class="text-gray-500">Silakan kembali ke katalog dan lakukan pemesanan baru.</p>
                <a href="body" class="inline-flex items-center gap-2 rounded-full bg-primary text-white px-6 py-3 font-semibold">Mulai belanja</a>
            </div>
        </div>

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
                    const file = window.location.pathname.split('/').pop() || 'success';
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

                const showFallback = () => {
                    document.getElementById('successFallback').classList.remove('hidden');
                };

                const ensureAuthed = async () => {
                    if (!window.AuthStore) return false;
                    const me = await AuthStore.me();
                    return !!me;
                };

                const orderUuid = new URLSearchParams(window.location.search || '').get('order');
                if (!orderUuid) {
                    showFallback();
                    return;
                }
                const canvas = document.getElementById('confettiCanvas');
                const ctx = canvas.getContext('2d');
                const fitCanvas = () => {
                    canvas.width = window.innerWidth;
                    canvas.height = window.innerHeight;
                };
                fitCanvas();
                window.addEventListener('resize', fitCanvas);

                const renderConfetti = () => {
                    const pieces = Array.from({ length: 70 }).map(() => ({
                        x: Math.random() * canvas.width,
                        y: Math.random() * canvas.height,
                        size: 6 + Math.random() * 6,
                        color: `hsl(${Math.random() * 360},80%,70%)`,
                        speed: 1 + Math.random() * 3,
                        angle: Math.random() * Math.PI * 2,
                    }));
                    const draw = () => {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        pieces.forEach((piece) => {
                            ctx.save();
                            ctx.translate(piece.x, piece.y);
                            ctx.rotate(piece.angle);
                            ctx.fillStyle = piece.color;
                            ctx.fillRect(-piece.size / 2, -piece.size / 2, piece.size, piece.size);
                            ctx.restore();
                            piece.y += piece.speed;
                            piece.angle += 0.01;
                            if (piece.y > canvas.height) piece.y = -10;
                        });
                        requestAnimationFrame(draw);
                    };
                    draw();
                };

                const currency = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
                const escapeHTML = (value = '') =>
                    String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');

                (async () => {
                    if (!window.AuthStore) {
                        alert('Sistem auth belum siap. Muat ulang halaman.');
                        return;
                    }

                    const isAuthed = await ensureAuthed();
                    if (!isAuthed) {
                        const next = currentRelativeUrl();
                        setLoginRedirect(next);
                        window.location.href = `login?next=${encodeURIComponent(next)}`;
                        return;
                    }

                    try {
                        const res = await apiFetchJson(`/api/orders/${encodeURIComponent(orderUuid)}`);
                        const order = res?.data?.order;
                        const items = Array.isArray(res?.data?.items) ? res.data.items : [];
                        if (!order || !items.length) {
                            showFallback();
                            return;
                        }

                        renderConfetti();

                        let snapshot = null;
                        try {
                            snapshot = order.customer_snapshot ? JSON.parse(order.customer_snapshot) : null;
                        } catch (_) {
                            snapshot = null;
                        }
                        const customer = snapshot?.customer || null;
                        const shippingValue = Number(snapshot?.shipping_value) || 0;
                        const shippingLabel = snapshot?.shipping_label || order.shipping_label || '-';
                        const payment = snapshot?.payment || '-';

                        const subtotal = Math.max(0, (Number(order.total) || 0) - shippingValue);

                        const itemList = document.getElementById('successItemList');
                        itemList.innerHTML = items
                            .map(
                                (item) => `
                                    <li class="py-3 flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                                ${item.image ? `<img src="${escapeHTML(item.image)}" alt="${escapeHTML(item.name || 'Produk')}" class="w-full h-full object-cover" loading="lazy" referrerpolicy="no-referrer">` : '<span class="text-[10px] text-gray-400">No Image</span>'}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-semibold text-dark truncate">${escapeHTML(item.name || 'Produk')}</p>
                                                <p class="text-xs text-gray-400">Qty ${Number(item.quantity) || 1}</p>
                                            </div>
                                        </div>
                                        <span class="font-semibold">${currency.format((Number(item.price) || 0) * (Number(item.quantity) || 1))}</span>
                                    </li>
                                `,
                            )
                            .join('');

                        document.getElementById('successOrderId').textContent = order.public_id || order.uuid || '-';
                        document.getElementById('successOrderDate').textContent = new Date(order.placed_at || order.created_at || Date.now()).toLocaleString('id-ID', {
                            dateStyle: 'medium',
                            timeStyle: 'short',
                        });
                        document.getElementById('successSubtotal').textContent = currency.format(subtotal);
                        document.getElementById('successShipping').textContent = currency.format(shippingValue);
                        document.getElementById('successTotal').textContent = currency.format(Number(order.total) || 0);
                        document.getElementById('successCustomer').textContent = customer?.fullname || '-';
                        document.getElementById('successContact').textContent = customer?.phone || customer?.email || '-';
                        document.getElementById('successAddress').textContent = customer?.address || '-';
                        document.getElementById('successShippingLabel').textContent = shippingLabel || '-';
                        document.getElementById('successPayment').textContent = payment || '-';
                    } catch (error) {
                        console.error('Gagal memuat pesanan', error);
                        showFallback();
                    }
                })();
            });
        </script>
    </body>
</html>