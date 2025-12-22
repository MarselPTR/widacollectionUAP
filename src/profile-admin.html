<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - Wida Collection</title>
    <link rel="stylesheet" href="output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
</head>
<body class="font-poppins bg-gray-50">
    <div class="relative bg-gradient-to-br from-dark via-secondary to-primary text-white">
        <div class="max-w-6xl mx-auto px-4 py-12 pb-32 md:pb-40 space-y-6">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-white/70 wc-reveal" style="--reveal-delay: 40ms;">Dashboard Wida Collection</p>
                    <h1 class="text-4xl font-bold wc-reveal" style="--reveal-delay: 120ms;">Profil Admin</h1>
                    <p class="text-white/80 wc-reveal" style="--reveal-delay: 190ms;">Akses khusus untuk mengelola produk dan konten.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="body.html" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white/10 border border-white/30 font-semibold hover:bg-white/20 transition wc-reveal" style="--reveal-delay: 250ms;">
                        <i class="fas fa-store"></i> Lihat Katalog
                    </a>
                    <a href="admin.html" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-white text-primary font-semibold shadow-lg wc-reveal" style="--reveal-delay: 300ms;">
                        <i class="fas fa-screwdriver-wrench"></i> Ke Admin Panel
                    </a>
                    <button id="logoutBtn" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-dark/40 border border-white/30 font-semibold hover:bg-dark/60 transition wc-reveal" style="--reveal-delay: 350ms;">
                        <i class="fas fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <main class="relative z-10 max-w-6xl mx-auto px-4 -mt-20 md:-mt-32 pb-16 space-y-10">
        <section class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8 flex flex-col lg:flex-row gap-8">
            <div class="flex flex-col items-center text-center gap-4 lg:w-1/3">
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-4xl font-bold text-white shadow-lg overflow-hidden">
                    <img data-wc-avatar-img class="hidden w-full h-full object-cover" alt="Foto profil" />
                    <span id="profileAvatarInitial" data-wc-avatar-fallback>WC</span>
                </div>
                <div>
                    <h2 id="profileName" class="text-2xl font-semibold text-dark">Admin</h2>
                    <p class="text-gray-500">Administrator</p>
                </div>
                <div class="flex flex-wrap justify-center gap-3 text-sm text-gray-500">
                    <span class="px-3 py-1 rounded-full bg-primary/10 text-primary font-medium">Role: Admin</span>
                </div>
                <a href="edit-profile.html" class="btn-main px-8 text-center">Edit Profil</a>
            </div>
            <div class="flex-1 grid sm:grid-cols-2 gap-6">
                <div class="bg-gradient-to-br from-light to-white rounded-2xl border border-white shadow p-5 space-y-3">
                    <div class="flex items-center gap-3 text-gray-400 text-sm uppercase tracking-[0.3em]">
                        <i class="fas fa-envelope text-primary"></i> Kontak
                    </div>
                    <p id="profileEmail" class="text-dark font-semibold">-</p>
                    <p id="profilePhone" class="text-gray-500">-</p>
                </div>
                <div class="bg-gradient-to-br from-light to-white rounded-2xl border border-white shadow p-5 space-y-3">
                    <div class="flex items-center gap-3 text-gray-400 text-sm uppercase tracking-[0.3em]">
                        <i class="fas fa-circle-check text-primary"></i> Akses
                    </div>
                    <p class="text-dark font-semibold">Panel Admin Produk</p>
                    <p class="text-sm text-gray-500">Kelola produk kustom & jadwal live drop.</p>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-dark">Pesan Masuk</h2>
                    <p class="text-sm text-gray-500">Laporan dari user melalui form kontak (admin tidak dapat membalas).</p>
                </div>
                <span id="adminMessagesTotal" class="px-3 py-1 rounded-full bg-primary/10 text-primary text-sm font-semibold">0 pesan</span>
            </div>
            <div id="adminMessagesList" class="mt-6 space-y-4"></div>
            <p id="adminMessagesEmpty" class="mt-6 text-sm text-gray-400 hidden">Belum ada pesan masuk.</p>
        </section>
    </main>

    <script src="js/reveal.js" defer></script>
    <script src="js/profile-data.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const REDIRECT_KEY = 'wc_login_redirect';
        const setLoginRedirect = (value) => {
            try {
                localStorage.setItem(REDIRECT_KEY, String(value || ''));
            } catch (_) {}
        };
        const currentRelativeUrl = () => {
            const file = window.location.pathname.split('/').pop() || 'profile-admin.html';
            return `${file}${window.location.search || ''}${window.location.hash || ''}`;
        };
        if (!window.AuthStore || typeof AuthStore.isLoggedIn !== 'function') {
            setLoginRedirect(currentRelativeUrl());
            window.location.href = 'login.html';
            return;
        }
        if (!AuthStore.isLoggedIn()) {
            setLoginRedirect(currentRelativeUrl());
            window.location.href = 'login.html';
            return;
        }
        if (typeof AuthStore.isAdmin !== 'function' || !AuthStore.isAdmin()) {
            alert('Halaman ini khusus admin Wida Collection.');
            window.location.href = 'profile.html';
            return;
        }

        const logoutBtn = document.getElementById('logoutBtn');
        logoutBtn?.addEventListener('click', () => {
            AuthStore.logout();
            window.location.href = 'body.html';
        });

        const profile = window.ProfileStore?.getProfileData?.() || {};
        const initials = profile.name
            ? profile.name
                  .split(' ')
                  .map((part) => part.charAt(0))
                  .join('')
                  .slice(0, 2)
                  .toUpperCase()
            : 'WC';

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value || '-';
        };

        setText('profileAvatarInitial', initials);
        setText('profileName', profile.name || 'Admin');
        setText('profileEmail', profile.email);
        setText('profilePhone', profile.phone);

        // Contact messages inbox (admin-only)
        const CONTACT_MESSAGES_KEY = 'wc_contact_messages_v1';
        const adminMessagesList = document.getElementById('adminMessagesList');
        const adminMessagesEmpty = document.getElementById('adminMessagesEmpty');
        const adminMessagesTotal = document.getElementById('adminMessagesTotal');

        const escapeHTML = (value = '') =>
            String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');

        const readMessages = () => {
            try {
                const raw = localStorage.getItem(CONTACT_MESSAGES_KEY);
                const parsed = raw ? JSON.parse(raw) : [];
                return Array.isArray(parsed) ? parsed : [];
            } catch (_) {
                return [];
            }
        };

        const writeMessages = (list) => {
            try {
                localStorage.setItem(CONTACT_MESSAGES_KEY, JSON.stringify(list));
            } catch (_) {}
        };

        const formatDate = (value) => {
            try {
                return new Date(value || Date.now()).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
            } catch (_) {
                return '';
            }
        };

        const renderMessages = () => {
            if (!adminMessagesList || !adminMessagesTotal) return;
            const messages = readMessages();
            adminMessagesTotal.textContent = `${messages.length} pesan`;
            if (!messages.length) {
                adminMessagesList.innerHTML = '';
                adminMessagesEmpty?.classList.remove('hidden');
                return;
            }
            adminMessagesEmpty?.classList.add('hidden');
            adminMessagesList.innerHTML = messages
                .map((msg) => {
                    const sender = msg.userEmail ? `${msg.name} (${msg.userEmail})` : msg.email ? `${msg.name} (${msg.email})` : msg.name;
                    return `
                        <article class="rounded-3xl border border-gray-100 bg-gradient-to-br from-light to-white p-5">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div class="min-w-0">
                                    <p class="text-xs uppercase tracking-[0.35em] text-gray-400">${escapeHTML(formatDate(msg.createdAt))}</p>
                                    <h3 class="text-lg font-semibold text-dark mt-1">${escapeHTML(msg.subject || 'Pesan')}</h3>
                                    <p class="text-sm text-gray-500 mt-1">Dari: <span class="font-semibold text-dark">${escapeHTML(sender || 'Pengunjung')}</span></p>
                                </div>
                                <button type="button" data-delete-message-id="${escapeHTML(msg.id)}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-red-200 text-red-500 font-semibold hover:bg-red-50 transition">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                            <p class="text-gray-600 mt-4 whitespace-pre-line">${escapeHTML(msg.message || '')}</p>
                        </article>
                    `;
                })
                .join('');
        };

        adminMessagesList?.addEventListener('click', (event) => {
            const btn = event.target.closest('[data-delete-message-id]');
            if (!btn) return;
            const id = btn.getAttribute('data-delete-message-id');
            if (!id) return;
            if (!confirm('Hapus pesan ini?')) return;
            const next = readMessages().filter((msg) => String(msg.id) !== String(id));
            writeMessages(next);
            renderMessages();
        });

        renderMessages();
        window.addEventListener('storage', (event) => {
            if (event.key === CONTACT_MESSAGES_KEY) renderMessages();
        });
        window.addEventListener('wc-contact-messages-updated', renderMessages);
    });
    </script>
</body>
</html>
