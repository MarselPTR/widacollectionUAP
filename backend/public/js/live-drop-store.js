(function () {
  const STORAGE_KEY = 'wc_live_drop_settings_v1';

  const defaultSettings = {
    heroTitle: 'Buka Bal Selanjutnya',
    heroDescription: 'Jangan lewatkan sesi buka bal eksklusif kami via TikTok Live dengan penawaran spesial!',
    eventTitle: 'Buka Bal Spesial Kaos Band Vintage',
    eventSubtitle: 'Sabtu, 15 Juli 2023 - Pukul 20:00 WIB',
    eventDateTime: '2025-12-31T20:00',
    ctaLabel: 'Ingatkan Saya',
  };

  const clone = (value) => JSON.parse(JSON.stringify(value));

  const read = () => {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : null;
      if (!parsed || typeof parsed !== 'object') {
        return clone(defaultSettings);
      }
      return { ...clone(defaultSettings), ...parsed };
    } catch (error) {
      console.warn('LiveDrop settings corrupted', error);
      return clone(defaultSettings);
    }
  };

  const write = (settings) => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
    window.dispatchEvent(new Event('wc-live-drop-updated'));
  };

  const getSettings = () => clone(read());

  const saveSettings = (partial = {}) => {
    const current = read();
    const next = { ...current, ...partial };
    write(next);
    return clone(next);
  };

  const resetSettings = () => {
    write(clone(defaultSettings));
    return getSettings();
  };

  window.LiveDropStore = {
    STORAGE_KEY,
    getSettings,
    saveSettings,
    resetSettings,
    defaultSettings: clone(defaultSettings),
  };
})();
