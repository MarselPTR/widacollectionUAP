(function () {
  const STORAGE_KEY = 'wc_reviews_v1';

  const clone = (value) => JSON.parse(JSON.stringify(value));

  const read = () => {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : [];
      return Array.isArray(parsed) ? parsed : [];
    } catch (error) {
      console.warn('Review data corrupted', error);
      return [];
    }
  };

  const write = (list) => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
    window.dispatchEvent(new Event('wc-reviews-updated'));
  };

  const normalize = (payload = {}) => {
    const rating = Math.min(5, Math.max(1, Number(payload.rating) || 0));
    return {
      id: payload.id || `rev-${Date.now().toString(36)}-${Math.random().toString(16).slice(2, 6)}`,
      productId: payload.productId ? String(payload.productId) : null,
      orderId: payload.orderId || null,
      rating,
      comment: (payload.comment || '').trim(),
      author: (payload.author || 'Pelanggan Wida').trim(),
      email: (payload.email || '').trim().toLowerCase(),
      createdAt: payload.createdAt || new Date().toISOString(),
    };
  };

  const save = (payload) => {
    const list = read();
    const normalized = normalize(payload);
    if (!normalized.productId) {
      throw new Error('Review membutuhkan productId.');
    }
    let idx = -1;
    if (normalized.orderId) {
      idx = list.findIndex((item) => item.orderId === normalized.orderId);
    }
    if (idx < 0) {
      idx = list.findIndex((item) => item.id === normalized.id);
    }
    if (idx >= 0) {
      list[idx] = { ...list[idx], ...normalized, id: list[idx].id };
    } else {
      list.push(normalized);
    }
    write(list);
    return clone(idx >= 0 ? list[idx] : normalized);
  };

  const remove = (id) => {
    const filtered = read().filter((review) => review.id !== id);
    write(filtered);
    return clone(filtered);
  };

  const getByProduct = (productId) => {
    if (!productId) return [];
    return clone(read().filter((review) => String(review.productId) === String(productId))).sort(
      (a, b) => new Date(b.createdAt) - new Date(a.createdAt),
    );
  };

  const getSummaryMap = () => {
    const map = read().reduce((acc, review) => {
      if (!review.productId) return acc;
      const key = String(review.productId);
      if (!acc[key]) {
        acc[key] = { count: 0, total: 0 };
      }
      acc[key].count += 1;
      acc[key].total += Number(review.rating) || 0;
      return acc;
    }, {});
    Object.keys(map).forEach((key) => {
      const entry = map[key];
      entry.avg = entry.count ? Number((entry.total / entry.count).toFixed(1)) : 0;
    });
    return map;
  };

  const getSummary = (productId) => {
    const map = getSummaryMap();
    const data = map[String(productId)];
    if (!data || !data.count) {
      return { avg: 0, count: 0 };
    }
    return { avg: Number((data.total / data.count).toFixed(1)), count: data.count };
  };

  window.ReviewStore = {
    STORAGE_KEY,
    getAll: () => clone(read()),
    getByProduct,
    getSummary,
    getSummaryMap,
    save,
    remove,
  };
})();
