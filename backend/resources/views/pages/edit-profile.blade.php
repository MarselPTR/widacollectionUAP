<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Edit Profil - Wida Collection</title>
        <link rel="stylesheet" href="output.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="app.css" />
    </head>
    <body class="font-poppins bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 py-10 space-y-8">
            <header class="bg-white rounded-3xl shadow-lg border border-white/70 p-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold text-primary wc-reveal" style="--reveal-delay: 40ms;">Wida <span class="text-secondary">Collection</span></p>
                    <h1 class="text-3xl font-bold text-dark wc-reveal" style="--reveal-delay: 120ms;">Edit Profil & Alamat</h1>
                    <p class="text-gray-500 wc-reveal" style="--reveal-delay: 190ms;">Perbarui identitas dan alamat pengiriman favoritmu.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="profile.html" class="inline-flex items-center gap-2 rounded-full border border-gray-200 px-6 py-3 font-semibold text-gray-600 hover:border-primary hover:text-primary wc-reveal" style="--reveal-delay: 250ms;">
                        <i class="fas fa-arrow-left"></i> Kembali ke profil
                    </a>
                </div>
            </header>

            <section class="grid gap-8 lg:grid-cols-2">
                <article class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <div>
                        <h2 class="text-xl font-semibold text-dark">Informasi Pribadi</h2>
                        <p class="text-sm text-gray-500">Data ini akan muncul di halaman profil dan checkout.</p>
                    </div>

                    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-light/40 p-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-xl shadow">
                            <img data-wc-avatar-img class="hidden w-full h-full object-cover" alt="Foto profil" />
                            <span id="editProfileAvatarFallback" data-wc-avatar-fallback>WC</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-dark">Foto Profil</p>
                            <p class="text-xs text-gray-500">PNG/JPG, max 2MB. Akan diperkecil otomatis.</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <label class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 hover:border-primary hover:text-primary cursor-pointer">
                                    <i class="fas fa-camera"></i> Pilih foto
                                    <input id="avatarFile" type="file" accept="image/*" class="hidden" />
                                </label>
                                <button type="button" id="avatarRemove" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 hover:border-red-300 hover:text-red-500">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                            <p id="avatarStatus" class="mt-2 text-xs text-red-500 hidden"></p>
                        </div>
                    </div>

                    <form id="profileForm" class="space-y-4">
                        <label class="text-sm font-semibold text-gray-600">
                            Nama lengkap
                            <input type="text" name="name" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" required />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Email utama
                            <input type="email" name="email" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" required />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Nomor WhatsApp
                            <input type="tel" name="phone" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" required />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Bio singkat
                            <textarea name="bio" rows="3" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Contoh: Collector & Style Curator"></textarea>
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Kota domisili
                            <input type="text" name="city" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" />
                        </label>
                        <button type="submit" class="w-full rounded-full bg-secondary text-white font-semibold py-3 shadow hover:bg-secondary/90">Simpan perubahan</button>
                        <p id="profileStatus" class="text-sm text-green-600 hidden">Profil berhasil diperbarui.</p>
                    </form>
                </article>

                <article class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <div class="flex flex-col gap-1">
                        <h2 class="text-xl font-semibold text-dark">Alamat Pengiriman</h2>
                        <p class="text-sm text-gray-500">Tambah, edit, atau jadikan alamat utama.</p>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-light/40 p-4 space-y-3">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <label class="text-sm font-semibold text-gray-600">
                                    Cari lokasi (Maps)
                                    <div class="mt-2 flex gap-2">
                                        <input id="addressSearch" type="text" class="w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Contoh: Jalan Sudirman, Jakarta" />
                                        <button type="button" id="useMyLocation" class="shrink-0 rounded-2xl border border-gray-200 bg-white px-4 py-2 font-semibold text-gray-600 hover:border-primary hover:text-primary" title="Gunakan lokasi saya">
                                            <i class="fas fa-location-crosshairs"></i>
                                        </button>
                                    </div>
                                </label>
                                <div id="addressSearchResults" class="mt-2 hidden rounded-2xl border border-gray-100 bg-white p-2 text-sm text-gray-600 max-h-56 overflow-auto"></div>
                                <p id="addressMapStatus" class="mt-2 text-xs text-gray-500">Klik peta / geser pin untuk mengisi alamat otomatis.</p>
                            </div>
                        </div>
                        <div id="addressMap" class="w-full rounded-2xl overflow-hidden border border-gray-100" style="height: 260px;"></div>
                    </div>

                    <form id="addressForm" class="space-y-3">
                        <input type="hidden" name="addressId" />
                        <input type="hidden" name="lat" />
                        <input type="hidden" name="lng" />
                        <label class="text-sm font-semibold text-gray-600">
                            Nama alamat / label
                            <input type="text" name="label" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Contoh: Rumah - Jakarta" required />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Nama penerima
                            <input type="text" name="recipient" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" required />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Nomor kontak
                            <input type="tel" name="addressPhone" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" required />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Detail alamat lengkap
                            <textarea name="detail" rows="3" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Jalan, no rumah, patokan" required></textarea>
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Kode pos
                            <input type="text" name="postal" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" />
                        </label>
                        <label class="inline-flex items-center gap-3 text-sm text-gray-600">
                            <input type="checkbox" name="isPrimary" class="rounded border-gray-300 text-primary focus:ring-primary" />
                            Jadikan alamat utama
                        </label>
                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="flex-1 rounded-full bg-primary text-white font-semibold py-2 shadow hover:bg-primary/90">
                                Simpan alamat
                            </button>
                            <button type="button" id="addressReset" class="rounded-full border border-gray-200 px-6 py-2 font-semibold text-gray-500 hover:border-primary hover:text-primary">
                                Reset form
                            </button>
                        </div>
                        <p id="addressStatus" class="text-sm text-green-600 hidden">Alamat tersimpan.</p>
                    </form>
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-[0.3em]">Daftar alamat</h3>
                        <div id="addressList" class="space-y-3 text-sm text-gray-600"></div>
                    </div>
                </article>
            </section>

            <section>
                <article class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 space-y-4">
                    <div>
                        <h2 class="text-xl font-semibold text-dark">Keamanan Akun</h2>
                        <p class="text-sm text-gray-500">Perbarui email login dan password.</p>
                    </div>
                    <form id="credentialsForm" class="space-y-4">
                        <label class="text-sm font-semibold text-gray-600">
                            Email login
                            <input type="email" name="loginEmail" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" required />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Password lama
                            <input type="password" name="oldPassword" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Wajib diisi jika mengubah password" />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Password baru
                            <input type="password" name="newPassword" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Kosongkan jika tidak ingin mengubah" />
                        </label>
                        <label class="text-sm font-semibold text-gray-600">
                            Konfirmasi password baru
                            <input type="password" name="confirmPassword" class="mt-2 w-full rounded-2xl border border-gray-200 px-4 py-2 focus:border-primary focus:ring-primary" placeholder="Ulangi password baru" />
                        </label>
                        <button type="submit" class="w-full rounded-full bg-secondary text-white font-semibold py-3 shadow hover:bg-secondary/90">Simpan pengaturan</button>
                        <p id="credentialsStatus" class="text-sm text-green-600 hidden">Data login diperbarui.</p>
                    </form>
                </article>
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
                        localStorage.setItem(REDIRECT_KEY, String(value || ''));
                    } catch (_) {}
                };
                const currentRelativeUrl = () => {
                    const file = window.location.pathname.split('/').pop() || 'edit-profile.html';
                    return `${file}${window.location.search || ''}${window.location.hash || ''}`;
                };
                if (localStorage.getItem('wc_logged_in') !== '1') {
                    setLoginRedirect(currentRelativeUrl());
                    window.location.href = 'login.html';
                    return;
                }

                if (!window.ProfileStore || !window.AuthStore) {
                    alert('Data profil tidak tersedia. Muat ulang halaman.');
                    return;
                }

                const profileForm = document.getElementById('profileForm');
                const addressForm = document.getElementById('addressForm');
                const addressStatus = document.getElementById('addressStatus');
                const profileStatus = document.getElementById('profileStatus');
                const addressList = document.getElementById('addressList');
                const resetAddressBtn = document.getElementById('addressReset');
                const credentialsForm = document.getElementById('credentialsForm');
                const credentialsStatus = document.getElementById('credentialsStatus');

                const addressMapEl = document.getElementById('addressMap');
                const addressSearch = document.getElementById('addressSearch');
                const addressSearchResults = document.getElementById('addressSearchResults');
                const useMyLocationBtn = document.getElementById('useMyLocation');
                const addressMapStatus = document.getElementById('addressMapStatus');

                const avatarFile = document.getElementById('avatarFile');
                const avatarRemove = document.getElementById('avatarRemove');
                const avatarStatus = document.getElementById('avatarStatus');
                const avatarFallback = document.getElementById('editProfileAvatarFallback');

                const deriveInitials = (name = '') => {
                    const safeName = String(name || '').trim();
                    if (!safeName) return 'WC';
                    return safeName
                        .split(' ')
                        .filter(Boolean)
                        .map((part) => part.charAt(0))
                        .join('')
                        .slice(0, 2)
                        .toUpperCase();
                };

                const setAvatarError = (message) => {
                    if (!avatarStatus) return;
                    if (!message) {
                        avatarStatus.classList.add('hidden');
                        avatarStatus.textContent = '';
                        return;
                    }
                    avatarStatus.textContent = message;
                    avatarStatus.classList.remove('hidden');
                };

                const resizeToDataUrl = (file, options = {}) => {
                    const { maxSize = 256, quality = 0.85 } = options;
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onerror = () => reject(new Error('Gagal membaca file.'));
                        reader.onload = () => {
                            const img = new Image();
                            img.onerror = () => reject(new Error('File gambar tidak valid.'));
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');
                                if (!ctx) {
                                    reject(new Error('Browser tidak mendukung canvas.'));
                                    return;
                                }

                                const scale = Math.min(maxSize / img.width, maxSize / img.height, 1);
                                const width = Math.max(1, Math.round(img.width * scale));
                                const height = Math.max(1, Math.round(img.height * scale));
                                canvas.width = width;
                                canvas.height = height;
                                ctx.drawImage(img, 0, 0, width, height);

                                try {
                                    const dataUrl = canvas.toDataURL('image/jpeg', quality);
                                    resolve(dataUrl);
                                } catch (error) {
                                    reject(new Error('Gagal memproses gambar.'));
                                }
                            };
                            img.src = String(reader.result || '');
                        };
                        reader.readAsDataURL(file);
                    });
                };

                const escapeHTML = (value = '') =>
                    String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;');

                const fillProfileForm = (profile, account) => {
                    profileForm.name.value = account?.name || profile?.name || '';
                    profileForm.email.value = account?.email || profile?.email || '';
                    profileForm.phone.value = account?.phone || profile?.phone || '';
                    profileForm.bio.value = profile?.bio || '';
                    profileForm.city.value = profile?.city || '';

                    if (avatarFallback) {
                        avatarFallback.textContent = deriveInitials(profileForm.name.value);
                    }
                };

                const setMapStatus = (message, isError = false) => {
                    if (!addressMapStatus) return;
                    addressMapStatus.textContent = message || '';
                    addressMapStatus.classList.toggle('text-red-500', Boolean(isError));
                    addressMapStatus.classList.toggle('text-gray-500', !isError);
                };

                const reverseGeocode = async ({ lat, lng }) => {
                    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}&addressdetails=1`;
                    const res = await fetch(url, {
                        headers: {
                            Accept: 'application/json',
                        },
                    });
                    if (!res.ok) throw new Error('Gagal mengambil alamat dari maps.');
                    return res.json();
                };

                const searchGeocode = async (query) => {
                    const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&addressdetails=1&limit=6`;
                    const res = await fetch(url, {
                        headers: {
                            Accept: 'application/json',
                        },
                    });
                    if (!res.ok) throw new Error('Gagal mencari lokasi.');
                    return res.json();
                };

                const pickAddressFromGeocode = (data, lat, lng) => {
                    const displayName = data?.display_name || '';
                    const addr = data?.address || {};
                    const postcode = addr.postcode || '';
                    const city = addr.city || addr.town || addr.village || addr.county || addr.state || '';

                    if (addressForm.detail) addressForm.detail.value = displayName || addressForm.detail.value;
                    if (addressForm.postal) addressForm.postal.value = postcode || addressForm.postal.value;
                    if (addressForm.lat) addressForm.lat.value = String(lat);
                    if (addressForm.lng) addressForm.lng.value = String(lng);

                    if (city && profileForm.city && !profileForm.city.value.trim()) {
                        profileForm.city.value = city;
                    }
                    if (city && addressForm.label && !addressForm.label.value.trim()) {
                        addressForm.label.value = `Rumah - ${city}`;
                    }
                };

                const setupAddressMap = () => {
                    if (!addressMapEl) return;
                    if (!window.L) {
                        setMapStatus('Maps tidak dapat dimuat. Periksa koneksi internet.', true);
                        addressMapEl.classList.add('hidden');
                        return;
                    }

                    const profile = ProfileStore.getProfileData();
                    const primary = (profile.addresses || []).find((a) => a.isPrimary) || (profile.addresses || [])[0];
                    const startLat = Number(primary?.lat) || -6.200000;
                    const startLng = Number(primary?.lng) || 106.816666;

                    const map = L.map(addressMapEl, {
                        zoomControl: true,
                        scrollWheelZoom: false,
                    }).setView([startLat, startLng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap',
                    }).addTo(map);

                    const marker = L.marker([startLat, startLng], { draggable: true }).addTo(map);

                    const applyLatLng = async (lat, lng, reasonLabel = '') => {
                        setMapStatus(reasonLabel ? `Mengambil alamat… (${reasonLabel})` : 'Mengambil alamat…');
                        try {
                            const data = await reverseGeocode({ lat, lng });
                            pickAddressFromGeocode(data, lat, lng);
                            setMapStatus('Alamat terisi otomatis dari maps.');
                        } catch (error) {
                            setMapStatus(error?.message || 'Gagal mengisi alamat otomatis.', true);
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

                    useMyLocationBtn?.addEventListener('click', () => {
                        if (!navigator.geolocation) {
                            setMapStatus('Browser tidak mendukung GPS.', true);
                            return;
                        }
                        setMapStatus('Mengambil lokasi kamu…');
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
                                setMapStatus('Izin lokasi ditolak. Cek permission untuk situs ini (mis. 127.0.0.1:5500) di Chrome.', true);
                                return;
                            }
                            if (code === 2) {
                                setMapStatus(
                                    'Lokasi tidak tersedia. Biasanya karena Windows Location Services mati / device tidak bisa menentukan lokasi.',
                                    true,
                                );
                                return;
                            }
                            if (code === 3 && canRetry) {
                                setMapStatus('Timeout. Mencoba lagi dengan akurasi standar…');
                                navigator.geolocation.getCurrentPosition(
                                    handleSuccess,
                                    (e2) => handleError(e2, false),
                                    { enableHighAccuracy: false, timeout: 20000, maximumAge: 60000 },
                                );
                                return;
                            }
                            if (code === 3) {
                                setMapStatus('Permintaan lokasi timeout. Coba lagi atau pastikan Location Services Windows aktif.', true);
                                return;
                            }
                            setMapStatus(err?.message || 'Gagal mengambil lokasi.', true);
                        };

                        navigator.geolocation.getCurrentPosition(
                            handleSuccess,
                            (err) => handleError(err, true),
                            { enableHighAccuracy: true, timeout: 12000, maximumAge: 0 },
                        );
                    });

                    let searchTimer = null;
                    const renderSearchResults = (items) => {
                        if (!addressSearchResults) return;
                        if (!items || !items.length) {
                            addressSearchResults.innerHTML = '<p class="px-3 py-2 text-gray-400">Tidak ada hasil.</p>';
                            addressSearchResults.classList.remove('hidden');
                            return;
                        }
                        addressSearchResults.innerHTML = items
                            .map(
                                (it, idx) => `
                                    <button type="button" data-idx="${idx}" class="w-full text-left rounded-xl px-3 py-2 hover:bg-light">
                                        <p class="font-semibold text-dark">${escapeHTML(it.name || it.display_name || 'Lokasi')}</p>
                                        <p class="text-xs text-gray-500">${escapeHTML(it.display_name || '')}</p>
                                    </button>
                                `,
                            )
                            .join('');
                        addressSearchResults.classList.remove('hidden');
                        addressSearchResults._items = items;
                    };

                    addressSearch?.addEventListener('input', () => {
                        const q = addressSearch.value.trim();
                        if (searchTimer) clearTimeout(searchTimer);
                        if (!q) {
                            addressSearchResults?.classList.add('hidden');
                            return;
                        }
                        searchTimer = setTimeout(async () => {
                            try {
                                const results = await searchGeocode(q);
                                renderSearchResults(results);
                            } catch (error) {
                                setMapStatus(error?.message || 'Gagal mencari lokasi.', true);
                            }
                        }, 350);
                    });

                    addressSearchResults?.addEventListener('click', async (event) => {
                        const btn = event.target.closest('button[data-idx]');
                        if (!btn) return;
                        const idx = Number(btn.dataset.idx);
                        const items = addressSearchResults._items || [];
                        const it = items[idx];
                        if (!it) return;
                        const lat = Number(it.lat);
                        const lng = Number(it.lon);
                        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;
                        addressSearchResults.classList.add('hidden');
                        map.setView([lat, lng], 16);
                        marker.setLatLng([lat, lng]);
                        // Prefer reverse to get postcode/city consistently
                        await applyLatLng(lat, lng, 'pilih hasil');
                    });

                    // Fill initial if editing a saved address with coords
                    if (Number.isFinite(startLat) && Number.isFinite(startLng) && (primary?.lat || primary?.lng)) {
                        applyLatLng(startLat, startLng, 'alamat tersimpan');
                    }

                    return { map, marker, applyLatLng };
                };

                const fillCredentialsForm = (account) => {
                    if (!credentialsForm) return;
                    credentialsForm.loginEmail.value = account?.email || '';
                    credentialsForm.newPassword.value = '';
                    credentialsForm.confirmPassword.value = '';
                };

                const renderAddresses = () => {
                    const { addresses } = ProfileStore.getProfileData();
                    if (!addresses.length) {
                        addressList.innerHTML = '<p class="text-gray-400">Belum ada alamat. Tambahkan melalui form di atas.</p>';
                        return;
                    }
                    addressList.innerHTML = addresses
                        .map(
                            (addr) => `
                                <article class="p-4 border ${addr.isPrimary ? 'border-primary/40 bg-primary/5' : 'border-gray-100'} rounded-2xl">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-semibold text-dark">${escapeHTML(addr.label)}</p>
                                            <p>${escapeHTML(addr.detail)}</p>
                                            <p class="text-xs text-gray-400">${escapeHTML(addr.recipient)} &middot; ${escapeHTML(addr.phone)}</p>
                                        </div>
                                        <div class="flex flex-col gap-2 text-xs font-semibold">
                                            <button data-action="primary" data-id="${addr.id}" class="rounded-full px-3 py-1 border ${addr.isPrimary ? 'border-primary text-primary' : 'border-gray-200 text-gray-500'}">
                                                ${addr.isPrimary ? 'Utama' : 'Jadikan utama'}
                                            </button>
                                            <button data-action="edit" data-id="${addr.id}" class="rounded-full px-3 py-1 border border-gray-200 text-gray-500">
                                                Edit
                                            </button>
                                            <button data-action="delete" data-id="${addr.id}" class="rounded-full px-3 py-1 border border-red-200 text-red-500">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </article>
                            `,
                        )
                        .join('');
                };

                const handleAddressAction = (event) => {
                    const btn = event.target.closest('button[data-action]');
                    if (!btn) return;
                    const action = btn.dataset.action;
                    const id = btn.dataset.id;
                    const profile = ProfileStore.getProfileData();
                    const target = profile.addresses.find((addr) => addr.id === id);
                    if (action === 'edit' && target) {
                        addressForm.addressId.value = target.id;
                        addressForm.label.value = target.label;
                        addressForm.recipient.value = target.recipient;
                        addressForm.addressPhone.value = target.phone;
                        addressForm.detail.value = target.detail;
                        addressForm.postal.value = target.postal || '';
                        if (addressForm.lat) addressForm.lat.value = target.lat ?? '';
                        if (addressForm.lng) addressForm.lng.value = target.lng ?? '';
                        addressForm.isPrimary.checked = Boolean(target.isPrimary);
                        addressStatus.classList.add('hidden');
                    }
                    if (action === 'primary') {
                        ProfileStore.setPrimaryAddress(id);
                        renderAddresses();
                        addressStatus.textContent = 'Alamat utama diperbarui.';
                        addressStatus.classList.remove('hidden');
                    }
                    if (action === 'delete') {
                        ProfileStore.removeAddress(id);
                        renderAddresses();
                        addressStatus.textContent = 'Alamat dihapus.';
                        addressStatus.classList.remove('hidden');
                    }
                };

                profileForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    ProfileStore.saveProfileData({
                        name: profileForm.name.value.trim(),
                        email: profileForm.email.value.trim(),
                        phone: profileForm.phone.value.trim(),
                        bio: profileForm.bio.value.trim(),
                        city: profileForm.city.value.trim(),
                    });
                    profileStatus.classList.remove('hidden');
                    setTimeout(() => profileStatus.classList.add('hidden'), 3000);
                    fillCredentialsForm(AuthStore.getAccountData());
                });

                profileForm.name.addEventListener('input', () => {
                    if (avatarFallback) {
                        avatarFallback.textContent = deriveInitials(profileForm.name.value);
                    }
                });

                avatarFile?.addEventListener('change', async (event) => {
                    setAvatarError('');
                    const file = event.target.files && event.target.files[0];
                    if (!file) return;

                    if (!file.type || !file.type.startsWith('image/')) {
                        setAvatarError('File harus berupa gambar (PNG/JPG).');
                        avatarFile.value = '';
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        setAvatarError('Ukuran gambar terlalu besar. Maksimal 2MB.');
                        avatarFile.value = '';
                        return;
                    }

                    try {
                        const dataUrl = await resizeToDataUrl(file, { maxSize: 256, quality: 0.85 });
                        ProfileStore.saveProfileData({ avatarImage: dataUrl });
                        // DOM helper in profile-data.js will toggle the preview automatically.
                    } catch (error) {
                        setAvatarError(error?.message || 'Gagal memproses gambar.');
                    } finally {
                        avatarFile.value = '';
                    }
                });

                avatarRemove?.addEventListener('click', () => {
                    setAvatarError('');
                    ProfileStore.saveProfileData({ avatarImage: '' });
                });

                addressForm.addEventListener('submit', (event) => {
                    event.preventDefault();
                    ProfileStore.upsertAddress({
                        id: addressForm.addressId.value || undefined,
                        label: addressForm.label.value.trim(),
                        recipient: addressForm.recipient.value.trim(),
                        phone: addressForm.addressPhone.value.trim(),
                        detail: addressForm.detail.value.trim(),
                        postal: addressForm.postal.value.trim(),
                        lat: addressForm.lat?.value || undefined,
                        lng: addressForm.lng?.value || undefined,
                        mapsAddress: addressForm.detail.value.trim(),
                        isPrimary: addressForm.isPrimary.checked,
                    });
                    addressStatus.textContent = 'Alamat tersimpan.';
                    addressStatus.classList.remove('hidden');
                    addressForm.reset();
                    if (addressForm.lat) addressForm.lat.value = '';
                    if (addressForm.lng) addressForm.lng.value = '';
                    renderAddresses();
                });

                resetAddressBtn.addEventListener('click', () => {
                    addressForm.reset();
                    addressForm.addressId.value = '';
                    if (addressForm.lat) addressForm.lat.value = '';
                    if (addressForm.lng) addressForm.lng.value = '';
                    addressStatus.classList.add('hidden');
                });

                addressList.addEventListener('click', handleAddressAction);

                credentialsForm?.addEventListener('submit', (event) => {
                    event.preventDefault();
                    const email = credentialsForm.loginEmail.value.trim().toLowerCase();
                    const oldPassword = credentialsForm.oldPassword.value;
                    const newPassword = credentialsForm.newPassword.value;
                    const confirmPassword = credentialsForm.confirmPassword.value;
                    if (!email) {
                        credentialsStatus.textContent = 'Email login wajib diisi.';
                        credentialsStatus.classList.remove('hidden');
                        credentialsStatus.classList.replace('text-green-600', 'text-red-500');
                        return;
                    }
                    if (newPassword && newPassword.length < 6) {
                        credentialsStatus.textContent = 'Password minimal 6 karakter.';
                        credentialsStatus.classList.remove('hidden');
                        credentialsStatus.classList.replace('text-green-600', 'text-red-500');
                        return;
                    }
                    if (newPassword && newPassword !== confirmPassword) {
                        credentialsStatus.textContent = 'Konfirmasi password tidak cocok.';
                        credentialsStatus.classList.remove('hidden');
                        credentialsStatus.classList.replace('text-green-600', 'text-red-500');
                        return;
                    }
                    if (newPassword && !oldPassword) {
                        credentialsStatus.textContent = 'Password lama wajib diisi.';
                        credentialsStatus.classList.remove('hidden');
                        credentialsStatus.classList.replace('text-green-600', 'text-red-500');
                        return;
                    }
                    AuthStore.updateAccountCredentials({
                        email,
                        newPassword: newPassword || undefined,
                        oldPassword: oldPassword || undefined,
                    });
                    credentialsStatus.textContent = 'Data login diperbarui.';
                    credentialsStatus.classList.remove('hidden');
                    credentialsStatus.classList.remove('text-red-500');
                    credentialsStatus.classList.add('text-green-600');
                    setTimeout(() => credentialsStatus.classList.add('hidden'), 3000);
                    credentialsForm.oldPassword.value = '';
                    fillProfileForm(ProfileStore.getProfileData(), AuthStore.getAccountData());
                    fillCredentialsForm(AuthStore.getAccountData());
                });

                const profile = ProfileStore.getProfileData();
                const account = AuthStore.getAccountData();
                fillProfileForm(profile, account);
                fillCredentialsForm(account);
                renderAddresses();
                setupAddressMap();
            });
        </script>
    </body>
</html>
