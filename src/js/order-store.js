(function () {
  const STORAGE_KEY = 'wc_orders_v1';
  const DEFAULT_EMAIL = 'widacollection@gmail.com';

  const STATUS = {
    packed: 'packed',
    shipped: 'shipped',
    delivered: 'delivered',
  };

  const STATUS_LABEL = {
    [STATUS.packed]: 'Dikemas',
    [STATUS.shipped]: 'Dalam pengiriman',
    [STATUS.delivered]: 'Diterima',
  };

  const clone = (value) => JSON.parse(JSON.stringify(value));

  const dispatchUpdate = () => {
    try {
      window.dispatchEvent(new Event('wc-orders-updated'));
    } catch (_) {}
  };

  const read = () => {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : {};
      return typeof parsed === 'object' && parsed !== null ? parsed : {};
    } catch (error) {
      console.warn('Order data corrupted', error);
      return {};
    }
  };

  const write = (data) => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    dispatchUpdate();
  };

  const normalizeEmail = (value) => (value ? String(value).trim().toLowerCase() : DEFAULT_EMAIL);

  const defaultOrders = () => ([
    {
      id: 'WC20250928',
      productId: '1',
      productTitle: 'Kaos Band Vintage + Aksesoris',
      productImage: 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=200&q=80',
      price: 325000,
      currency: 'IDR',
      status: 'shipped',
      statusNote: 'Sedang dikirim - Est. 2 hari',
      createdAt: '2025-09-28T08:00:00.000Z',
      deliveredAt: null,
      reviewId: null,
    },
    {
      id: 'WC20250914',
      productId: '2',
      productTitle: 'Jaket Coach x Nike',
      productImage: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=200&q=80',
      price: 480000,
      currency: 'IDR',
      status: 'delivered',
      statusNote: 'Selesai - Diterima 14 Nov',
      createdAt: '2025-09-14T10:00:00.000Z',
      deliveredAt: '2025-11-14T10:00:00.000Z',
      reviewId: null,
    },
  ]);

  const ensureSeed = (email) => {
    const data = read();
    if (!data[email]) {
      data[email] = email === DEFAULT_EMAIL ? defaultOrders() : [];
      write(data);
    }
  };

  const buildTitleFromItems = (items = []) => {
    if (!items.length) return 'Produk Wida Collection';
    const first = items[0];
    const extra = items.length > 1 ? ` +${items.length - 1} lainnya` : '';
    return `${first.name || first.title || 'Produk Wida Collection'}${extra}`;
  };

  const normalizeNewOrder = (payload = {}) => {
    const items = Array.isArray(payload.items) ? payload.items : [];
    const first = items[0] || {};
    const resolvePrice = () => {
      if (typeof payload.total === 'number') return payload.total;
      return items.reduce((sum, item) => sum + (Number(item.price) || 0) * (Number(item.quantity || item.qty || 1)), 0);
    };
    const status = payload.status || STATUS.packed;
    const deliveredAt = payload.deliveredAt || null;
    const createdAt = payload.createdAt || new Date().toISOString();
    const shippingLabel = payload.shippingLabel || 'Reguler';
    const formatDeliveredNote = (dateValue) => {
      try {
        const label = new Date(dateValue).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        return `Selesai - Diterima ${label}`;
      } catch (_) {
        return 'Selesai - Diterima';
      }
    };
    const statusNote =
      payload.statusNote ||
      (status === STATUS.packed
        ? 'Sedang dikemas'
        : status === STATUS.shipped
          ? `${shippingLabel} - Dalam pengiriman`
          : deliveredAt
            ? formatDeliveredNote(deliveredAt)
            : 'Selesai - Diterima');
    return {
      id: payload.id || `WC${Date.now().toString().slice(-6)}`,
      productId: String(payload.productId || first.id || payload.id || Date.now()),
      productTitle: payload.productTitle || buildTitleFromItems(items),
      productImage: payload.productImage || first.image || '',
      price: resolvePrice(),
      currency: 'IDR',
      status,
      statusNote,
      createdAt,
      deliveredAt,
      reviewId: payload.reviewId || null,
      shippingLabel,
      customer: payload.customer || null,
      items,
    };
  };

  const getOrders = (email) => {
    const normalized = normalizeEmail(email);
    ensureSeed(normalized);
    const data = read();
    const list = Array.isArray(data[normalized]) ? data[normalized] : [];
    return clone(list).sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
  };

  const saveOrders = (email, list) => {
    const normalized = normalizeEmail(email);
    const data = read();
    data[normalized] = clone(list);
    write(data);
    return clone(data[normalized]);
  };

  const updateOrder = (email, orderId, updater) => {
    const list = getOrders(email);
    const idx = list.findIndex((order) => order.id === orderId);
    if (idx < 0) return null;
    const updated = { ...list[idx], ...updater(list[idx]) };
    list[idx] = updated;
    saveOrders(email, list);
    return clone(updated);
  };

  const formatDeliveredNote = (deliveredAt) => {
    try {
      const label = new Date(deliveredAt).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
      return `Selesai - Diterima ${label}`;
    } catch (_) {
      return 'Selesai - Diterima';
    }
  };

  const markShipped = (email, orderId, options = {}) => {
    const list = getOrders(email);
    const existing = list.find((order) => order.id === orderId);
    if (!existing) return null;
    if (existing.status !== STATUS.packed) return null;
    return updateOrder(email, orderId, (order) => {
      const shippingLabel = options.shippingLabel || order.shippingLabel || 'Reguler';
      return {
        status: STATUS.shipped,
        statusNote: `${shippingLabel} - Dalam pengiriman`,
      };
    });
  };

  const markDelivered = (email, orderId) => {
    const list = getOrders(email);
    const existing = list.find((order) => order.id === orderId);
    if (!existing) return null;
    if (existing.status !== STATUS.shipped) return null;
    return updateOrder(email, orderId, () => {
      const deliveredAt = new Date().toISOString();
      return {
        status: STATUS.delivered,
        deliveredAt,
        statusNote: formatDeliveredNote(deliveredAt),
      };
    });
  };

  const getAllMap = () => clone(read());

  const getAllAdmin = () => {
    const data = read();
    const flat = [];
    Object.keys(data || {}).forEach((email) => {
      const list = Array.isArray(data[email]) ? data[email] : [];
      list.forEach((order) => {
        flat.push({ ...clone(order), customerEmail: email });
      });
    });
    return flat.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
  };

  const markReviewed = (orderId, reviewId, email) => {
    return updateOrder(email, orderId, (order) => ({ reviewId: reviewId || order.reviewId }));
  };

  const appendOrder = (email, payload) => {
    const normalized = normalizeEmail(email);
    ensureSeed(normalized);
    const data = read();
    const list = Array.isArray(data[normalized]) ? data[normalized] : [];
    const summary = normalizeNewOrder(payload);
    data[normalized] = [summary, ...list];
    write(data);
    return clone(summary);
  };

  window.OrderStore = {
    STORAGE_KEY,
    STATUS,
    STATUS_LABEL,
    getAll: getOrders,
    getRecent: (email, limit = 2) => getOrders(email).slice(0, limit),
    getAllMap,
    getAllAdmin,
    markShipped,
    markDelivered,
    markReviewed,
    append: appendOrder,
  };
})();
