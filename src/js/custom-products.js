(function () {
  const STORAGE_KEY = 'wc_custom_products_v1';

  const MENS_GROUP = 'Mens Clothing';
  const WOMENS_GROUP = 'Womens Clothing';
  const MENS_SUBCATEGORIES = [
    'T-Shirts',
    'Jeans',
    'Pants',
    'Jacket',
    'Long Sleeves',
    'Sweaters',
    'Tank Top',
  ];
  const WOMENS_SUBCATEGORIES = [
    'Dresses',
    'Skirts',
    'T-Shirts',
    'Jeans',
    'Pants',
    'Jacket',
    'Long Sleeves',
    'Sweaters',
    'Tank Top',
    'Crop Top',
  ];

  const normalizeGroup = (rawCategory = '', title = '') => {
    const hay = `${rawCategory || ''} ${title || ''}`.toLowerCase();
    if (hay.includes('women') || hay.includes("women's") || hay.includes('woman')) return WOMENS_GROUP;
    if (hay.includes('men') || hay.includes("men's") || hay.includes('man')) return MENS_GROUP;
    if (/\b(dress|skirt|crop)\b/i.test(hay)) return WOMENS_GROUP;
    return MENS_GROUP;
  };

  const normalizeType = (rawType = '', title = '', group = '') => {
    const list = group === WOMENS_GROUP ? WOMENS_SUBCATEGORIES : MENS_SUBCATEGORIES;
    const trimmed = String(rawType || '').trim();
    if (trimmed && list.includes(trimmed)) return trimmed;
    const hay = String(title || '').toLowerCase();
    if (group === WOMENS_GROUP) {
      if (/\bcrop\b/.test(hay)) return 'Crop Top';
      if (/\bdress\b/.test(hay)) return 'Dresses';
      if (/\bskirt\b/.test(hay)) return 'Skirts';
    }
    if (/\bjean\b/.test(hay)) return 'Jeans';
    if (/\bjacket\b|\bcoat\b|\bhoodie\b/.test(hay)) return 'Jacket';
    if (/\blong\s*sleeve\b|\blongsleeve\b/.test(hay)) return 'Long Sleeves';
    if (/\bsweater\b|\bcardigan\b|\bknit\b/.test(hay)) return 'Sweaters';
    if (/\btank\b/.test(hay)) return 'Tank Top';
    if (/\bt[- ]?shirt\b|\btee\b/.test(hay)) return 'T-Shirts';
    if (/\bpant\b|\btrouser\b|\bchino\b/.test(hay)) return 'Pants';
    return 'T-Shirts';
  };

  const clone = (value) => JSON.parse(JSON.stringify(value));

  const read = () => {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      const parsed = raw ? JSON.parse(raw) : [];
      const list = Array.isArray(parsed) ? parsed : [];
      // Lightweight migration: normalize legacy categories/types into the new taxonomy.
      let mutated = false;
      const normalized = list.map((item) => {
        const title = (item && item.title) || '';
        const group = normalizeGroup(item && item.category, title);
        const nextType = normalizeType(item && item.type, title, group);
        const nextCategory = group;
        if ((item && item.category) !== nextCategory || (item && item.type) !== nextType) mutated = true;
        return { ...item, category: nextCategory, type: nextType };
      });
      if (mutated) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(normalized));
      }
      return normalized;
    } catch (error) {
      console.warn('Custom products corrupted', error);
      return [];
    }
  };

  const write = (list) => {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
  };

  const normalizeProduct = (payload) => {
    const id = payload.id || `cust-${Date.now().toString(36)}-${Math.random().toString(16).slice(2, 6)}`;
    const title = (payload.title || '').trim();
    const categoryGroup = normalizeGroup(payload.category || '', title);
    const type = normalizeType(payload.type || '', title, categoryGroup);
    return {
      id,
      title,
      price: Number(payload.price) || 0,
      category: categoryGroup,
      description: (payload.description || '').trim(),
      image: (payload.image || '').trim(),
      type,
      stock: Number(payload.stock) || 0,
      rating: {
        rate: 0,
        count: 0,
      },
      createdAt: payload.createdAt || new Date().toISOString(),
      updatedAt: new Date().toISOString(),
    };
  };

  const upsert = (data) => {
    const list = read();
    const normalized = normalizeProduct(data);
    const idx = list.findIndex((item) => item.id === normalized.id);
    if (idx >= 0) {
      list[idx] = { ...list[idx], ...normalized, createdAt: list[idx].createdAt };
    } else {
      list.push(normalized);
    }
    write(list);
    return clone(normalized);
  };

  const remove = (id) => {
    const list = read().filter((item) => item.id !== id);
    write(list);
    return clone(list);
  };

  const findById = (id) => read().find((item) => item.id === id) || null;

  window.CustomProductStore = {
    getAll: () => clone(read()),
    save: upsert,
    remove,
    findById,
    STORAGE_KEY,
  };
})();
