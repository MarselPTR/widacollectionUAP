(function () {
  const STORAGE_KEYS = {
    profiles: 'wc_profiles_v2',
    accounts: 'wc_accounts_v2',
    active: 'wc_active_account',
    logged: 'wc_logged_in',
    legacyProfile: 'wc_profile',
    legacyAccount: 'wc_account',
  };

  const defaultProfile = {
    name: 'Wida Bagaskara',
    email: 'widacollection@gmail.com',
    phone: '+62 812-3456-7890',
    bio: 'Collector & Style Curator',
    memberSince: '2021',
    tierLabel: 'Member Wida Collection',
    city: 'Jakarta Selatan, Indonesia',
    avatar: 'WB',
    avatarImage: '',
    addresses: [
      {
        id: 'addr-home',
        label: 'Rumah - Jakarta Selatan',
        recipient: 'Wida Bagaskara',
        phone: '+62 812-3456-7890',
        detail: 'Jl. Thrift No.123, Kebayoran Baru, Jakarta Selatan 12110',
        postal: '12110',
        isPrimary: true,
      },
      {
        id: 'addr-studio',
        label: 'Studio Konten - BSD',
        recipient: 'Wida Bagaskara',
        phone: '+62 811-2222-3333',
        detail: 'Ruko Creative Hub Blok B7, BSD City, Tangerang Selatan',
        postal: '15345',
        isPrimary: false,
      },
    ],
    wishlist: [
      {
        id: 'wish-varsity',
        title: 'Varsity Jacket NY 1998',
        note: 'High demand · Ukuran preferensi: L',
        price: 520000,
        badge: 'Prioritas',
        priority: 'high',
        image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=200&q=80',
        status: 'waiting',
        refId: null,
      },
      {
        id: 'wish-midi',
        title: "Midi Dress Floral 70's",
        note: 'Diprioritaskan untuk live drop sore.',
        price: 310000,
        badge: 'Wishlist',
        priority: 'medium',
        image: 'https://images.unsplash.com/photo-1521579971123-1192931a1452?auto=format&fit=crop&w=200&q=80',
        status: 'waiting',
        refId: null,
      },
    ],
  };

  const defaultAccount = {
    name: 'Wida Bagaskara',
    email: 'widacollection@gmail.com',
    phone: '+62 812-3456-7890',
    password: 'wida123',
  };

  const ADMIN_EMAILS = ['widacollection@gmail.com'];

  const clone = (value) => JSON.parse(JSON.stringify(value));
  const normalizeEmail = (value) => (value ? String(value).trim().toLowerCase() : '');
  const isAdminEmail = (email) => {
    const normalized = normalizeEmail(email);
    return !!normalized && ADMIN_EMAILS.includes(normalized);
  };
  const deriveAvatar = (name) => {
    if (!name) return 'WC';
    return name
      .split(' ')
      .filter(Boolean)
      .map((part) => part.charAt(0))
      .join('')
      .slice(0, 2)
      .toUpperCase();
  };

  const normalizeAvatarImage = (value) => {
    if (typeof value !== 'string') return '';
    const trimmed = value.trim();
    if (!trimmed) return '';
    const looksLikeDataImage = trimmed.startsWith('data:image/');
    const looksLikeHttp = /^https?:\/\//i.test(trimmed);
    const looksLikeRelative = /^\//.test(trimmed) || /^\.?\//.test(trimmed);
    if (!looksLikeDataImage && !looksLikeHttp && !looksLikeRelative) return '';
    // Guard localStorage size (roughly) to avoid breaking saves on huge images.
    if (looksLikeDataImage && trimmed.length > 350_000) return '';
    return trimmed;
  };

  const readJson = (key, fallback) => {
    try {
      const raw = localStorage.getItem(key);
      if (!raw) return clone(fallback);
      const parsed = JSON.parse(raw);
      if (parsed === null || typeof parsed !== 'object') return clone(fallback);
      return parsed;
    } catch (error) {
      console.warn(`Gagal membaca data ${key}`, error);
      return clone(fallback);
    }
  };

  const writeJson = (key, value) => {
    localStorage.setItem(key, JSON.stringify(value));
  };

  const generateAddressId = () => `addr-${Date.now().toString(36)}-${Math.random().toString(16).slice(2, 6)}`;
  const generateWishlistId = () => `wish-${Date.now().toString(36)}-${Math.random().toString(16).slice(2, 6)}`;

  const normalizeAddressList = (list, fallback = []) => {
    const source = Array.isArray(list) && list.length ? list : fallback;
    const normalized = source.map((addr, index) => ({
      id: addr.id || generateAddressId() || `addr-${index}`,
      label: addr.label || `Alamat ${index + 1}`,
      recipient: addr.recipient || defaultProfile.name,
      phone: addr.phone || defaultProfile.phone,
      detail: addr.detail || '',
      postal: addr.postal || '',
      lat: typeof addr.lat === 'number' ? addr.lat : addr.lat === 0 ? 0 : addr.lat ? Number(addr.lat) : null,
      lng: typeof addr.lng === 'number' ? addr.lng : addr.lng === 0 ? 0 : addr.lng ? Number(addr.lng) : null,
      mapsAddress: addr.mapsAddress || addr.detail || '',
      isPrimary: Boolean(addr.isPrimary),
    }));
    if (normalized.length && !normalized.some((addr) => addr.isPrimary)) {
      normalized[0].isPrimary = true;
    }
    return normalized;
  };

  const readAccounts = () => readJson(STORAGE_KEYS.accounts, {});
  const readProfiles = () => readJson(STORAGE_KEYS.profiles, {});
  const writeAccounts = (data) => writeJson(STORAGE_KEYS.accounts, data);
  const writeProfiles = (data) => writeJson(STORAGE_KEYS.profiles, data);

  const normalizeWishlist = (list, fallback = [], options = {}) => {
    const { allowEmpty = false } = options;
    const hasList = Array.isArray(list) && (list.length || allowEmpty);
    const source = hasList ? list : fallback;
    return source.map((item, index) => {
      const priceNumber = typeof item.price === 'number' ? item.price : Number(item.price) || 0;
      return {
        id: item.id || generateWishlistId() || `wish-${index}`,
        title: item.title || `Wishlist Item ${index + 1}`,
        note: item.note || '',
        price: priceNumber,
        badge: item.badge || '',
        priority: item.priority || 'normal',
        image: item.image || '',
        status: item.status || 'waiting',
        addedAt: item.addedAt || new Date().toISOString(),
        refId: item.refId || null,
      };
    });
  };

  const migrateLegacyData = () => {
    const alreadyMigrated = localStorage.getItem(STORAGE_KEYS.accounts) || localStorage.getItem(STORAGE_KEYS.profiles);
    if (alreadyMigrated) return;

    let legacyProfile = null;
    let legacyAccount = null;
    try {
      const rawProfile = localStorage.getItem(STORAGE_KEYS.legacyProfile);
      legacyProfile = rawProfile ? JSON.parse(rawProfile) : null;
    } catch (error) {
      console.warn('Profil lama korup', error);
    }
    try {
      const rawAccount = localStorage.getItem(STORAGE_KEYS.legacyAccount);
      legacyAccount = rawAccount ? JSON.parse(rawAccount) : null;
    } catch (error) {
      console.warn('Akun lama korup', error);
    }

    if (!legacyProfile && !legacyAccount) return;

    const email = normalizeEmail(legacyAccount?.email || legacyProfile?.email || defaultAccount.email);
    const accounts = {
      [email]: {
        name: legacyAccount?.name || legacyProfile?.name || defaultAccount.name,
        email,
        phone: legacyAccount?.phone || legacyProfile?.phone || defaultAccount.phone,
        password: legacyAccount?.password || defaultAccount.password,
      },
    };
    writeAccounts(accounts);

    const profile = {
      ...clone(defaultProfile),
      ...legacyProfile,
      email,
      name: accounts[email].name,
      phone: accounts[email].phone,
      addresses: normalizeAddressList(legacyProfile?.addresses, clone(defaultProfile.addresses)),
      wishlist: normalizeWishlist(legacyProfile?.wishlist, clone(defaultProfile.wishlist)),
    };
    writeProfiles({ [email]: profile });
    localStorage.setItem(STORAGE_KEYS.active, email);

    localStorage.removeItem(STORAGE_KEYS.legacyProfile);
    localStorage.removeItem(STORAGE_KEYS.legacyAccount);
  };

  const ensureSeedData = () => {
    migrateLegacyData();
    const accounts = readAccounts();
    const profiles = readProfiles();
    const defaultEmail = normalizeEmail(defaultAccount.email);

    if (!accounts[defaultEmail]) {
      accounts[defaultEmail] = { ...clone(defaultAccount), email: defaultEmail };
      writeAccounts(accounts);
    }

    if (!profiles[defaultEmail]) {
      profiles[defaultEmail] = {
        ...clone(defaultProfile),
        email: defaultEmail,
        addresses: normalizeAddressList(defaultProfile.addresses),
        avatar: defaultProfile.avatar || deriveAvatar(defaultProfile.name),
        avatarImage: normalizeAvatarImage(defaultProfile.avatarImage),
        wishlist: normalizeWishlist(defaultProfile.wishlist),
      };
      writeProfiles(profiles);
    }

    const activeEmail = localStorage.getItem(STORAGE_KEYS.active);
    if (!activeEmail || !accounts[activeEmail]) {
      localStorage.setItem(STORAGE_KEYS.active, defaultEmail);
    }

    if (!localStorage.getItem(STORAGE_KEYS.logged)) {
      localStorage.setItem(STORAGE_KEYS.logged, '0');
    }
  };

  ensureSeedData();

  const getActiveEmail = () => {
    ensureSeedData();
    const accounts = readAccounts();
    const activeEmail = localStorage.getItem(STORAGE_KEYS.active);
    if (activeEmail && accounts[activeEmail]) {
      return activeEmail;
    }
    const fallback = Object.keys(accounts)[0] || normalizeEmail(defaultAccount.email);
    localStorage.setItem(STORAGE_KEYS.active, fallback);
    return fallback;
  };

  const setActiveEmail = (email) => {
    const normalized = normalizeEmail(email);
    const accounts = readAccounts();
    if (normalized && accounts[normalized]) {
      localStorage.setItem(STORAGE_KEYS.active, normalized);
    }
  };

  const buildProfileFromAccount = (email) => {
    const account = getAccountData(email);
    const profile = {
      ...clone(defaultProfile),
      email: normalizeEmail(email) || account.email,
      name: account.name || defaultProfile.name,
      phone: account.phone || defaultProfile.phone,
      addresses: normalizeAddressList(defaultProfile.addresses),
      wishlist: normalizeWishlist(defaultProfile.wishlist),
    };
    profile.avatar = deriveAvatar(profile.name);
    profile.avatarImage = normalizeAvatarImage(profile.avatarImage);
    profile.memberSince = profile.memberSince || new Date().getFullYear().toString();
    return profile;
  };

  const getProfileData = (emailParam) => {
    ensureSeedData();
    const profiles = readProfiles();
    const targetEmail = normalizeEmail(emailParam) || getActiveEmail();
    if (!profiles[targetEmail]) {
      profiles[targetEmail] = buildProfileFromAccount(targetEmail);
      writeProfiles(profiles);
    }
    const profile = clone(profiles[targetEmail]);
    profile.addresses = normalizeAddressList(profile.addresses, clone(defaultProfile.addresses));
    profile.avatar = profile.avatar || deriveAvatar(profile.name);
    profile.avatarImage = normalizeAvatarImage(profile.avatarImage);
    profile.wishlist = normalizeWishlist(profile.wishlist, clone(defaultProfile.wishlist), { allowEmpty: true });
    return profile;
  };

  const getAccountData = (emailParam) => {
    ensureSeedData();
    const accounts = readAccounts();
    const targetEmail = normalizeEmail(emailParam) || getActiveEmail();
    const account = accounts[targetEmail] || accounts[getActiveEmail()] || { ...clone(defaultAccount), email: targetEmail };
    return clone(account);
  };

  const persistProfile = (email, data) => {
    const profiles = readProfiles();
    profiles[email] = data;
    writeProfiles(profiles);
  };

  const persistAccount = (oldEmail, newEmail, data) => {
    const accounts = readAccounts();
    if (newEmail && newEmail !== oldEmail) {
      delete accounts[oldEmail];
    }
    accounts[newEmail] = data;
    writeAccounts(accounts);
  };

  const syncAccountWithProfile = (previousEmail, nextProfile) => {
    const accounts = readAccounts();
    const oldEmail = normalizeEmail(previousEmail);
    const newEmail = normalizeEmail(nextProfile.email) || oldEmail;
    if (newEmail !== oldEmail && accounts[newEmail]) {
      throw new Error('Email sudah terdaftar pada akun lain.');
    }
    const currentAccount = accounts[oldEmail] || { ...clone(defaultAccount), email: oldEmail };
    const updatedAccount = {
      ...currentAccount,
      name: nextProfile.name || currentAccount.name,
      phone: nextProfile.phone || currentAccount.phone,
      email: newEmail,
    };
    persistAccount(oldEmail, newEmail, updatedAccount);
    setActiveEmail(newEmail);
  };

  const syncProfileWithAccount = (email) => {
    const normalized = normalizeEmail(email) || getActiveEmail();
    const profiles = readProfiles();
    const targetProfile = profiles[normalized] || buildProfileFromAccount(normalized);
    const account = getAccountData(normalized);
    const updatedProfile = {
      ...targetProfile,
      name: account.name,
      email: account.email,
      phone: account.phone,
      avatar: targetProfile.avatar || deriveAvatar(account.name),
      avatarImage: normalizeAvatarImage(targetProfile.avatarImage),
    };
    profiles[normalized] = updatedProfile;
    writeProfiles(profiles);
  };

  const saveProfileData = (partialData = {}) => {
    ensureSeedData();
    const activeEmail = getActiveEmail();
    const targetEmail = normalizeEmail(partialData.email) || activeEmail;
    const profiles = readProfiles();
    const accounts = readAccounts();
    if (targetEmail !== activeEmail && accounts[targetEmail]) {
      throw new Error('Email sudah digunakan akun lain.');
    }

    const currentProfile = profiles[activeEmail] ? clone(profiles[activeEmail]) : buildProfileFromAccount(activeEmail);
    const nextProfile = {
      ...currentProfile,
      ...partialData,
      email: targetEmail,
    };

    const addressesSource = Array.isArray(partialData.addresses) ? partialData.addresses : currentProfile.addresses;
    nextProfile.addresses = normalizeAddressList(addressesSource, clone(defaultProfile.addresses));
    const wishlistSource = Array.isArray(partialData.wishlist) ? partialData.wishlist : currentProfile.wishlist;
    nextProfile.wishlist = normalizeWishlist(wishlistSource, clone(defaultProfile.wishlist), { allowEmpty: true });
    nextProfile.memberSince = nextProfile.memberSince || currentProfile.memberSince || new Date().getFullYear().toString();
    nextProfile.avatar = nextProfile.avatar || deriveAvatar(nextProfile.name);
    nextProfile.avatarImage = normalizeAvatarImage(nextProfile.avatarImage);

    if (targetEmail !== activeEmail) {
      delete profiles[activeEmail];
    }
    profiles[targetEmail] = nextProfile;
    writeProfiles(profiles);

    syncAccountWithProfile(activeEmail, nextProfile);
    return clone(nextProfile);
  };

  const upsertAddress = (address) => {
    ensureSeedData();
    const profile = getProfileData();
    const addresses = normalizeAddressList(profile.addresses, clone(defaultProfile.addresses));
    const latValue = address.lat === 0 || address.lat ? Number(address.lat) : null;
    const lngValue = address.lng === 0 || address.lng ? Number(address.lng) : null;
    const payload = {
      id: address.id || generateAddressId(),
      label: address.label?.trim() || 'Alamat Baru',
      recipient: address.recipient?.trim() || profile.name,
      phone: address.phone?.trim() || profile.phone,
      detail: address.detail?.trim() || '',
      postal: address.postal?.trim() || '',
      lat: Number.isFinite(latValue) ? latValue : null,
      lng: Number.isFinite(lngValue) ? lngValue : null,
      mapsAddress: (address.mapsAddress || address.detail || '').trim(),
      isPrimary: Boolean(address.isPrimary),
    };
    const idx = addresses.findIndex((item) => item.id === payload.id);
    if (idx >= 0) {
      addresses[idx] = { ...addresses[idx], ...payload };
    } else {
      addresses.push(payload);
    }
    if (payload.isPrimary) {
      addresses.forEach((item) => {
        item.isPrimary = item.id === payload.id;
      });
    } else if (!addresses.some((item) => item.isPrimary)) {
      addresses[0].isPrimary = true;
    }

    const activeEmail = getActiveEmail();
    const profiles = readProfiles();
    const updatedProfile = {
      ...profile,
      addresses,
    };
    profiles[activeEmail] = updatedProfile;
    writeProfiles(profiles);
    return clone(addresses.find((item) => item.id === payload.id));
  };

  const removeAddress = (id) => {
    ensureSeedData();
    const profile = getProfileData();
    let addresses = normalizeAddressList(profile.addresses, clone(defaultProfile.addresses));
    addresses = addresses.filter((item) => item.id !== id);
    if (!addresses.length) {
      addresses = normalizeAddressList(clone(defaultProfile.addresses));
    } else if (!addresses.some((item) => item.isPrimary)) {
      addresses[0].isPrimary = true;
    }

    const activeEmail = getActiveEmail();
    const profiles = readProfiles();
    profiles[activeEmail] = {
      ...profile,
      addresses,
    };
    writeProfiles(profiles);
    return clone(addresses);
  };

  const setPrimaryAddress = (id) => {
    ensureSeedData();
    const profile = getProfileData();
    const addresses = normalizeAddressList(profile.addresses, clone(defaultProfile.addresses)).map((addr) => ({
      ...addr,
      isPrimary: addr.id === id,
    }));
    const activeEmail = getActiveEmail();
    const profiles = readProfiles();
    profiles[activeEmail] = {
      ...profile,
      addresses,
    };
    writeProfiles(profiles);
    return clone(addresses.find((addr) => addr.id === id));
  };

  const getWishlist = () => {
    ensureSeedData();
    const profile = getProfileData();
    return clone(profile.wishlist || []);
  };

  const saveWishlist = (items) => {
    ensureSeedData();
    const activeEmail = getActiveEmail();
    const profiles = readProfiles();
    const profile = profiles[activeEmail] || buildProfileFromAccount(activeEmail);
    const normalized = normalizeWishlist(items, [], { allowEmpty: true });
    profile.wishlist = normalized;
    profiles[activeEmail] = profile;
    writeProfiles(profiles);
    return clone(normalized);
  };

  const upsertWishlistItem = (item) => {
    ensureSeedData();
    const profile = getProfileData();
    const wishlist = normalizeWishlist(profile.wishlist, [], { allowEmpty: true });
    const targetId = item.id || generateWishlistId();
    const payload = {
      id: targetId,
      title: (item.title || '').trim() || 'Wishlist Item',
      note: (item.note || '').trim(),
      price: typeof item.price === 'number' ? item.price : Number(item.price) || 0,
      badge: (item.badge || '').trim(),
      priority: item.priority || 'normal',
      image: item.image || '',
      status: item.status || 'waiting',
      addedAt: item.addedAt || new Date().toISOString(),
      refId: item.refId || null,
    };
    let idx = wishlist.findIndex((wish) => wish.id === targetId);
    if (idx < 0 && payload.refId) {
      idx = wishlist.findIndex((wish) => wish.refId && wish.refId === payload.refId);
      if (idx >= 0) {
        payload.id = wishlist[idx].id;
      }
    }
    if (idx >= 0) {
      wishlist[idx] = { ...wishlist[idx], ...payload };
    } else {
      wishlist.push(payload);
    }
    saveWishlist(wishlist);
    return clone(payload);
  };

  const removeWishlistItem = (id) => {
    ensureSeedData();
    const profile = getProfileData();
    const wishlist = normalizeWishlist(profile.wishlist, [], { allowEmpty: true }).filter((item) => item.id !== id);
    return saveWishlist(wishlist);
  };

  const clearWishlist = () => saveWishlist([]);

  const saveAccountData = (partialData = {}) => {
    ensureSeedData();
    const activeEmail = getActiveEmail();
    const accounts = readAccounts();
    const currentAccount = accounts[activeEmail] ? { ...accounts[activeEmail] } : { ...clone(defaultAccount), email: activeEmail };
    let targetEmail = activeEmail;

    if (partialData.email) {
      const normalizedEmail = normalizeEmail(partialData.email);
      if (normalizedEmail && normalizedEmail !== activeEmail) {
        if (accounts[normalizedEmail]) {
          throw new Error('Email sudah terdaftar.');
        }
        targetEmail = normalizedEmail;
      }
    }

    if (partialData.name) currentAccount.name = partialData.name.trim();
    if (partialData.phone) currentAccount.phone = partialData.phone.trim();
    if (partialData.password) currentAccount.password = partialData.password;
    currentAccount.email = targetEmail;

    if (targetEmail !== activeEmail) {
      delete accounts[activeEmail];
    }
    accounts[targetEmail] = currentAccount;
    writeAccounts(accounts);
    setActiveEmail(targetEmail);
    syncProfileWithAccount(targetEmail);
    return clone(currentAccount);
  };

  const verifyCredentials = (email, password) => {
    ensureSeedData();
    if (!email || !password) return null;
    const normalized = normalizeEmail(email);
    const accounts = readAccounts();
    const account = accounts[normalized];
    if (account && account.password === password) {
      setActiveEmail(normalized);
      return clone(account);
    }
    return null;
  };

  const registerAccount = ({ name, email, password, phone, city }) => {
    ensureSeedData();
    if (!email || !password) {
      throw new Error('Email dan password wajib diisi.');
    }
    const normalizedEmail = normalizeEmail(email);
    const accounts = readAccounts();
    if (accounts[normalizedEmail]) {
      throw new Error('Email sudah terdaftar.');
    }

    const accountData = {
      name: name?.trim() || defaultAccount.name,
      email: normalizedEmail,
      phone: phone?.trim() || defaultAccount.phone,
      password,
      createdAt: new Date().toISOString(),
    };
    accounts[normalizedEmail] = accountData;
    writeAccounts(accounts);

    const profileData = {
      ...clone(defaultProfile),
      name: accountData.name,
      email: normalizedEmail,
      phone: accountData.phone,
      city: city?.trim() || defaultProfile.city,
      memberSince: new Date().getFullYear().toString(),
      avatar: deriveAvatar(accountData.name),
      avatarImage: normalizeAvatarImage(defaultProfile.avatarImage),
      addresses: normalizeAddressList(defaultProfile.addresses),
    };
    const profiles = readProfiles();
    profiles[normalizedEmail] = profileData;
    writeProfiles(profiles);

    setActiveEmail(normalizedEmail);
    return clone(accountData);
  };

  const updateAccountCredentials = ({ email, newEmail, password, newPassword, currentPassword, oldPassword, name, phone }) => {
    ensureSeedData();
    const activeEmail = getActiveEmail();
    const accounts = readAccounts();
    if (!accounts[activeEmail]) {
      throw new Error('Akun aktif tidak ditemukan.');
    }

    const account = { ...accounts[activeEmail] };
    let targetEmail = normalizeEmail(email || newEmail) || activeEmail;
    if (targetEmail !== activeEmail && accounts[targetEmail]) {
      throw new Error('Email sudah terdaftar.');
    }

    const desiredPassword = typeof newPassword === 'string' && newPassword.length ? newPassword : typeof password === 'string' ? password : '';
    const verifier = currentPassword || oldPassword;
    if (desiredPassword) {
      if (!verifier) {
        throw new Error('Password lama wajib diisi.');
      }
      if (account.password !== verifier) {
        throw new Error('Password lama tidak cocok.');
      }
      account.password = desiredPassword;
    }

    if (name) account.name = name.trim();
    if (phone) account.phone = phone.trim();
    account.email = targetEmail;

    if (targetEmail !== activeEmail) {
      delete accounts[activeEmail];
    }
    accounts[targetEmail] = account;
    writeAccounts(accounts);
    setActiveEmail(targetEmail);
    syncProfileWithAccount(targetEmail);
    return clone(account);
  };

  const setLoggedIn = (status) => {
    localStorage.setItem(STORAGE_KEYS.logged, status ? '1' : '0');
  };

  const logout = () => {
    setLoggedIn(false);
  };

  const isLoggedIn = () => localStorage.getItem(STORAGE_KEYS.logged) === '1';

  window.ProfileStore = {
    getProfileData,
    saveProfileData,
    upsertAddress,
    removeAddress,
    setPrimaryAddress,
    getWishlist,
    saveWishlist,
    upsertWishlistItem,
    removeWishlistItem,
    clearWishlist,
  };

  window.AuthStore = {
    getAccountData,
    saveAccountData,
    verifyCredentials,
    registerAccount,
    updateAccountCredentials,
    logout,
    setLoggedIn,
    isLoggedIn,
    ADMIN_EMAILS,
    isAdminEmail,
    isAdmin: () => {
      try {
        return isAdminEmail(getAccountData()?.email);
      } catch (_) {
        return false;
      }
    },
  };

  // Optional DOM helper: pages can provide
  // - <img data-wc-avatar-img>
  // - <span data-wc-avatar-fallback>
  // and we'll toggle based on stored avatarImage.
  document.addEventListener('DOMContentLoaded', () => {
    if (!window.ProfileStore) return;
    const profile = window.ProfileStore.getProfileData();
    const avatarImage = normalizeAvatarImage(profile.avatarImage);

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
  });
})();
