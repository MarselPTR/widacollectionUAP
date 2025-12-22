// API-backed auth/profile store.
// - No localStorage usage.
// - Auth uses JWT HttpOnly cookie (wc_token) set by backend.
(function () {
  const clone = (value) => JSON.parse(JSON.stringify(value));

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
      const message = (data && data.message) ? String(data.message) : `Request failed (${res.status})`;
      const err = new Error(message);
      err.status = res.status;
      err.data = data;
      throw err;
    }

    return data;
  };

  let meCache = null;
  let meLoadPromise = null;

  const loadMe = async () => {
    try {
      const res = await apiFetchJson('/api/auth/me');
      meCache = res && res.user ? res.user : null;
      return meCache;
    } catch (_) {
      meCache = null;
      return null;
    }
  };

  const ensureMe = () => {
    if (!meLoadPromise) {
      meLoadPromise = loadMe().finally(() => {
        meLoadPromise = null;
      });
    }
    return meLoadPromise;
  };

  let profileCache = null;
  let addressesCache = [];
  let wishlistCache = [];
  let allLoadPromise = null;

  const loadAll = async () => {
    const me = await ensureMe();
    if (!me) {
      profileCache = null;
      addressesCache = [];
      wishlistCache = [];
      return null;
    }

    const [profileRes, addrRes, wishRes] = await Promise.all([
      apiFetchJson('/api/profile'),
      apiFetchJson('/api/addresses'),
      apiFetchJson('/api/wishlist'),
    ]);

    profileCache = profileRes?.data || null;
    addressesCache = Array.isArray(addrRes?.data) ? addrRes.data : [];
    wishlistCache = Array.isArray(wishRes?.data) ? wishRes.data : [];
    return true;
  };

  const ensureAll = () => {
    if (!allLoadPromise) {
      allLoadPromise = loadAll().finally(() => {
        allLoadPromise = null;
      });
    }
    return allLoadPromise;
  };

  const normalizeProfileForUi = () => {
    const me = meCache;
    const p = profileCache;

    const name = String(p?.display_name || me?.name || '').trim();
    const email = String(me?.email || '').trim();
    const phone = String(p?.phone || '').trim();
    const bio = String(p?.bio || '').trim();
    const city = String(p?.city || '').trim();
    const avatarImage = String(p?.avatar_data_url || '').trim();

    const avatar = name
      ? name
          .split(/\s+/)
          .filter(Boolean)
          .map((x) => x[0])
          .join('')
          .slice(0, 2)
          .toUpperCase()
      : 'WC';

    const addresses = addressesCache.map((a) => ({
      id: a.uuid,
      label: a.label,
      recipient: a.recipient,
      phone: a.phone,
      detail: a.detail,
      postal: a.postal,
      lat: a.lat === null || typeof a.lat === 'undefined' ? null : Number(a.lat),
      lng: a.lng === null || typeof a.lng === 'undefined' ? null : Number(a.lng),
      mapsAddress: a.maps_address || a.detail,
      isPrimary: !!a.is_primary,
    }));

    const wishlist = wishlistCache.map((w) => ({
      id: w.uuid,
      title: w.title,
      note: '',
      price: Number(w.price) || 0,
      badge: w.badge || '',
      priority: 'normal',
      image: w.image || '',
      status: 'waiting',
      addedAt: w.added_at || w.created_at,
      refId: w.product_id,
    }));

    return {
      name,
      email,
      phone,
      bio,
      memberSince: '2025',
      tierLabel: me?.is_admin ? 'Admin Wida Collection' : 'Member Wida Collection',
      city,
      avatar,
      avatarImage,
      addresses,
      wishlist,
    };
  };

  const ProfileStore = {
    ready: ensureAll(),
    refresh: async () => {
      await loadAll();
      return ProfileStore.getProfileData();
    },
    getProfileData: () => clone(normalizeProfileForUi()),
    saveProfileData: async (payload = {}) => {
      await ensureAll();
      if (!meCache) throw new Error('Unauthorized');
      await apiFetchJson('/api/profile', {
        method: 'PUT',
        body: JSON.stringify({
          display_name: payload.name ?? payload.display_name ?? undefined,
          phone: payload.phone ?? undefined,
          bio: payload.bio ?? undefined,
          city: payload.city ?? undefined,
          avatar_data_url: payload.avatarImage ?? payload.avatar_data_url ?? undefined,
        }),
      });
      await loadAll();
      return ProfileStore.getProfileData();
    },
    upsertAddress: async (payload = {}) => {
      await ensureAll();
      const isUpdate = !!payload.id;
      const body = {
        label: payload.label,
        recipient: payload.recipient,
        phone: payload.phone,
        detail: payload.detail,
        maps_address: payload.mapsAddress ?? payload.maps_address,
        postal: payload.postal,
        lat: payload.lat,
        lng: payload.lng,
        is_primary: !!payload.isPrimary,
      };
      if (isUpdate) {
        await apiFetchJson(`/api/addresses/${encodeURIComponent(payload.id)}`, { method: 'PUT', body: JSON.stringify(body) });
      } else {
        await apiFetchJson('/api/addresses', { method: 'POST', body: JSON.stringify(body) });
      }
      await loadAll();
      return clone(addressesCache);
    },
    removeAddress: async (id) => {
      await apiFetchJson(`/api/addresses/${encodeURIComponent(id)}`, { method: 'DELETE' });
      await loadAll();
      return clone(addressesCache);
    },
    setPrimaryAddress: async (id) => {
      await apiFetchJson(`/api/addresses/${encodeURIComponent(id)}/primary`, { method: 'POST' });
      await loadAll();
      return clone(addressesCache);
    },
    getWishlist: () => clone(normalizeProfileForUi().wishlist || []),
    upsertWishlistItem: async (payload = {}) => {
      await apiFetchJson('/api/wishlist', {
        method: 'POST',
        body: JSON.stringify({
          product_id: payload.refId || payload.productId || payload.product_id || null,
          title: payload.title || null,
        }),
      });
      await loadAll();
      return ProfileStore.getWishlist();
    },
    removeWishlistItem: async (id) => {
      await apiFetchJson(`/api/wishlist/${encodeURIComponent(id)}`, { method: 'DELETE' });
      await loadAll();
      return ProfileStore.getWishlist();
    },
    clearWishlist: async () => {
      await apiFetchJson('/api/wishlist', { method: 'DELETE' });
      await loadAll();
      return [];
    },
  };

  const AuthStore = {
    me: async () => {
      await ensureMe();
      return clone(meCache);
    },
    getAccountData: () => (meCache ? clone(meCache) : null),
    login: async (email, password) => {
      await apiFetchJson('/api/auth/login', { method: 'POST', body: JSON.stringify({ email, password }) });
      await loadAll();
      return clone(meCache);
    },
    registerAccount: async (payload = {}) => {
      const name = String(payload.name || '').trim();
      const email = String(payload.email || '').trim();
      const password = String(payload.password || '').trim();
      await apiFetchJson('/api/auth/register', { method: 'POST', body: JSON.stringify({ name, email, password }) });
      await loadAll();
      return clone(meCache);
    },
    updateAccountCredentials: async (payload = {}) => {
      await apiFetchJson('/api/auth/credentials', {
        method: 'PUT',
        body: JSON.stringify({
          name: payload.name ?? undefined,
          email: payload.email ?? undefined,
          old_password: payload.oldPassword ?? payload.old_password ?? undefined,
          new_password: payload.newPassword ?? payload.new_password ?? undefined,
        }),
      });
      await loadAll();
      return clone(meCache);
    },
    logout: async () => {
      try {
        await apiFetchJson('/api/auth/logout', { method: 'POST' });
      } catch (_) {
        // ignore
      }
      meCache = null;
      profileCache = null;
      addressesCache = [];
      wishlistCache = [];
      return true;
    },
    isLoggedIn: () => !!meCache,
    isAdmin: () => !!meCache?.is_admin,
  };

  window.ProfileStore = ProfileStore;
  window.AuthStore = AuthStore;

  document.addEventListener('DOMContentLoaded', () => {
    if (!window.ProfileStore) return;
    ProfileStore.ready
      .then(() => {
        const profile = window.ProfileStore.getProfileData();
        const avatarImage = String(profile.avatarImage || '').trim();

        const imgs = Array.from(document.querySelectorAll('[data-wc-avatar-img]'));
        const fallbacks = Array.from(document.querySelectorAll('[data-wc-avatar-fallback]'));
        if (!imgs.length && !fallbacks.length) return;

        if (avatarImage) {
          imgs.forEach((img) => {
            img.src = avatarImage;
            img.classList.remove('hidden');
          });
          fallbacks.forEach((el) => el.classList.add('hidden'));
        } else {
          imgs.forEach((img) => {
            img.removeAttribute('src');
            img.classList.add('hidden');
          });
          fallbacks.forEach((el) => el.classList.remove('hidden'));
        }
      })
      .catch(() => {});
  });
})();
