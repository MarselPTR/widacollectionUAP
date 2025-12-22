document.addEventListener('DOMContentLoaded', function() {
  // Body.html-only behaviors
  try {
    if (document.body && /\/body\.html(\?.*)?$/.test(window.location.pathname)) {
      // Header theme toggle: when header overlaps the hero, use a dark translucent background + light text;
      // after scrolling past hero, switch to light background + dark text.
      const headerEl = document.querySelector('header.wc-header') || document.querySelector('header');
      const heroEl = document.getElementById('home');

      // Smooth in-page scrolling with sticky-header offset
      const getStickyHeaderHeight = () => (headerEl ? headerEl.getBoundingClientRect().height : 0);
      const scrollToSection = (sectionId, behavior = 'smooth') => {
        if (!sectionId) return;
        const target = document.getElementById(sectionId);
        if (!target) return;
        const headerH = getStickyHeaderHeight();
        const top = target.getBoundingClientRect().top + window.scrollY - headerH - 12;
        window.scrollTo({ top: Math.max(0, top), behavior });
      };
      const setHeaderTheme = () => {
        if (!headerEl || !heroEl) return;
        const headerH = headerEl.getBoundingClientRect().height || 0;
        const heroRect = heroEl.getBoundingClientRect();

        // If the hero still extends beneath the header (i.e., we're "in" hero), stay in hero theme.
        // When hero bottom is above the header bottom, we have scrolled past it.
        const inHero = heroRect.bottom > headerH + 8;
        headerEl.classList.toggle('wc-header-on-hero', inHero);
        headerEl.classList.toggle('wc-header-on-light', !inHero);
      };

      let headerThemeTicking = false;
      const requestHeaderThemeUpdate = () => {
        if (headerThemeTicking) return;
        headerThemeTicking = true;
        requestAnimationFrame(() => {
          headerThemeTicking = false;
          setHeaderTheme();
        });
      };

      // Initial + reactive updates
      setHeaderTheme();
      window.addEventListener('scroll', requestHeaderThemeUpdate, { passive: true });
      window.addEventListener('resize', requestHeaderThemeUpdate, { passive: true });
      window.addEventListener('load', requestHeaderThemeUpdate, { once: true });

      // Scrollspy navbar animation (Beranda/Tentang/Produk/Buka Bal/Testimoni/Kontak)
      const navLinks = Array.from(document.querySelectorAll('header nav a[href^="#"]'));
      const linkBySectionId = new Map();
      for (const link of navLinks) {
        const href = link.getAttribute('href') || '';
        const sectionId = decodeURIComponent(href.replace(/^#/, '')).trim();
        if (!sectionId) continue;
        link.classList.add('wc-nav-link');
        linkBySectionId.set(sectionId, link);
      }

      const setActiveNav = (activeId) => {
        for (const [sectionId, link] of linkBySectionId.entries()) {
          const isActive = sectionId === activeId;
          link.classList.toggle('wc-nav-active', isActive);
          link.classList.toggle('text-primary', isActive);
          if (isActive) link.setAttribute('aria-current', 'true');
          else link.removeAttribute('aria-current');
        }
      };

      const sections = Array.from(linkBySectionId.keys())
        .map((id) => document.getElementById(id))
        .filter(Boolean);

      const activateFromHash = () => {
        const activeId = decodeURIComponent((window.location.hash || '').replace(/^#/, '')).trim();
        if (activeId && linkBySectionId.has(activeId)) setActiveNav(activeId);
      };

      // Deterministic scrollspy (more in-sync than IntersectionObserver)
      if (sections.length) {
        const headerEl = document.querySelector('header');
        const getHeaderHeight = () => (headerEl ? headerEl.getBoundingClientRect().height : 0);

        let sectionMeta = [];
        const recomputeSectionTops = () => {
          sectionMeta = sections
            .map((section) => ({
              id: section.id,
              top: section.getBoundingClientRect().top + window.scrollY,
            }))
            .sort((a, b) => a.top - b.top);
        };

        const findActiveId = () => {
          if (!sections.length) return null;

          // Use a probe line inside the viewport (below the sticky header).
          // This prevents the active nav from switching too early when the next section's top
          // barely crosses the header line (common with dynamic content / large gaps).
          const headerH = getHeaderHeight();
          const probeY = headerH + Math.min(220, Math.max(80, window.innerHeight * 0.33));

          let lastAboveProbe = null;
          for (const section of sections) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= probeY && rect.bottom > probeY) return section.id;
            if (rect.top <= probeY) lastAboveProbe = section.id;
          }

          return lastAboveProbe || sections[0].id;
        };

        let ticking = false;
        const requestUpdate = () => {
          if (ticking) return;
          ticking = true;
          requestAnimationFrame(() => {
            ticking = false;
            const activeId = findActiveId();
            if (activeId) setActiveNav(activeId);
          });
        };

        // Smooth-scroll + sync on click (prevents default jump/blink)
        for (const link of navLinks) {
          link.addEventListener('click', (e) => {
            // Let the browser handle new tab / modified clicks
            if (e && (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey)) return;
            const href = link.getAttribute('href') || '';
            const sectionId = decodeURIComponent(href.replace(/^#/, '')).trim();
            if (!sectionId || !linkBySectionId.has(sectionId)) return;
            e.preventDefault();
            setActiveNav(sectionId);
            // update URL without triggering an instant jump
            try {
              history.pushState(null, '', `#${encodeURIComponent(sectionId)}`);
            } catch (_) {
              // no-op
            }
            scrollToSection(sectionId, 'smooth');
            // re-sync after scroll settles
            setTimeout(() => requestUpdate(), 250);
            setTimeout(() => requestUpdate(), 650);
          });
        }

        // Handle hero CTA and any other in-page anchors outside the header nav
        document.addEventListener('click', (e) => {
          const target = e.target && e.target.closest ? e.target.closest('a[href^="#"]') : null;
          if (!target) return;
          if (target.closest('header nav')) return; // handled above
          if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

          const href = target.getAttribute('href') || '';
          const sectionId = decodeURIComponent(href.replace(/^#/, '')).trim();
          if (!sectionId) return;
          if (!document.getElementById(sectionId)) return;

          e.preventDefault();
          try {
            history.pushState(null, '', `#${encodeURIComponent(sectionId)}`);
          } catch (_) {
            // no-op
          }
          scrollToSection(sectionId, 'smooth');
        }, { passive: false });

        recomputeSectionTops();
        requestUpdate();
        activateFromHash();

        // If the page loads with a hash, correct the position with header offset (no blink).
        if (window.location.hash) {
          const initialId = decodeURIComponent(window.location.hash.replace(/^#/, '')).trim();
          if (initialId && document.getElementById(initialId)) {
            // Run after layout is stable
            setTimeout(() => scrollToSection(initialId, 'auto'), 0);
          }
        }

        window.addEventListener('scroll', requestUpdate, { passive: true });
        window.addEventListener('resize', () => {
          recomputeSectionTops();
          requestUpdate();
        }, { passive: true });
        window.addEventListener('load', () => {
          recomputeSectionTops();
          requestUpdate();
        }, { once: true });
        if (document.fonts && document.fonts.ready) {
          document.fonts.ready.then(() => {
            recomputeSectionTops();
            requestUpdate();
          }).catch(() => {});
        }
        window.addEventListener('hashchange', () => {
          activateFromHash();
          // hash changes usually scroll; update after layout settles
          setTimeout(() => requestUpdate(), 0);
          setTimeout(() => requestUpdate(), 250);
        }, { passive: true });
      }
    }
  } catch (_) {
    // no-op
  }

  const API_URL = 'https://fakestoreapi.com/products';
  const RATE_IDR = 16000; 
  let page = 1;
  const pageSize = 6;
  const productsGrid = document.getElementById('productsGrid');
  const productsLoading = document.getElementById('productsLoading');
  let loadMoreBtn = document.getElementById('loadMoreBtn');
  let dataCache = [];
  const CART_KEY = 'wc_cart_items';
  const wishlistBadge = document.getElementById('wishlistCountBadge');
  const headerUserFullName = document.getElementById('headerUserFullName');
  const headerUserInitials = document.getElementById('headerUserInitials');
  const liveDropElements = {
    sectionTitle: document.getElementById('liveDropSectionTitle'),
    sectionDesc: document.getElementById('liveDropSectionDesc'),
    eventTitle: document.getElementById('liveDropEventTitle'),
    eventSubtitle: document.getElementById('liveDropEventSubtitle'),
    ctaBtn: document.getElementById('liveDropCtaBtn'),
    ctaLabel: document.getElementById('liveDropCtaLabel'),
    countdownDays: document.getElementById('countdown-days'),
    countdownHours: document.getElementById('countdown-hours'),
    countdownMinutes: document.getElementById('countdown-minutes'),
    countdownSeconds: document.getElementById('countdown-seconds'),
  };
  const liveDropTeasersGrid = document.getElementById('liveDropTeasers');
  let liveDropTargetDate = null;
  let liveDropCountdownTimer = null;

  const getLiveDropDefaults = () => (window.LiveDropStore && LiveDropStore.defaultSettings) || {};

  const getLiveDropSettingsSafe = () => {
    if (!window.LiveDropStore || typeof LiveDropStore.getSettings !== 'function') return null;
    try {
      return LiveDropStore.getSettings();
    } catch (_) {
      return null;
    }
  };

  const parseEventDate = (value) => {
    if (!value) return null;
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return null;
    return date;
  };

  const formatEventSubtitle = (settings) => {
    if (settings.eventSubtitle && settings.eventSubtitle.trim()) {
      return settings.eventSubtitle.trim();
    }
    const target = parseEventDate(settings.eventDateTime);
    if (!target) return 'Jadwal akan diumumkan';
    const formatted = target.toLocaleString('id-ID', {
      weekay: 'long',
      day: 'umeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
    return `${formatted} WIB`;
  };

  const setCountdownSlots = (values = {}) => {
    const normalized = {
      days: values.days ?? '--',
      hours: values.hours ?? '--',
      minutes: values.minutes ?? '--',
      seconds: values.seconds ?? '--',
    };
    const map = {
      days: liveDropElements.countdownDays,
      hours: liveDropElements.countdownHours,
      minutes: liveDropElements.countdownMinutes,
      seconds: liveDropElements.countdownSeconds,
    };
    Object.entries(map).forEach(([key, el]) => {
      if (!el) return;
      const raw = normalized[key];
      const value = typeof raw === 'number' ? raw : (raw || '--');
      el.textContent = value.toString().padStart(2, '0');
    });
  };

  const stopLiveDropCountdown = () => {
    if (liveDropCountdownTimer) {
      clearInterval(liveDropCountdownTimer);
      liveDropCountdownTimer = null;
    }
  };

  const updateLiveDropCountdown = () => {
    if (!liveDropTargetDate) {
      setCountdownSlots();
      return;
    }
    const now = new Date();
    const diff = liveDropTargetDate - now;
    if (diff <= 0) {
      setCountdownSlots({ days: '00', hours: '00', minutes: '00', seconds: '00' });
      stopLiveDropCountdown();
      return;
    }
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    setCountdownSlots({ days, hours, minutes, seconds });
  };

  const startLiveDropCountdown = () => {
    stopLiveDropCountdown();
    updateLiveDropCountdown();
    liveDropCountdownTimer = setInterval(updateLiveDropCountdown, 1000);
  };

  const applyLiveDropSettings = () => {
    const settings = getLiveDropSettingsSafe();
    if (!settings) return;
    const defaults = getLiveDropDefaults();
    if (liveDropElements.sectionTitle) {
      liveDropElements.sectionTitle.textContent = settings.heroTitle || defaults.heroTitle || 'Buka Bal Selanjutnya';
    }
    if (liveDropElements.sectionDesc) {
      liveDropElements.sectionDesc.textContent = settings.heroDescription || defaults.heroDescription || '';
    }
    if (liveDropElements.eventTitle) {
      liveDropElements.eventTitle.textContent = settings.eventTitle || defaults.eventTitle || '';
    }
    if (liveDropElements.eventSubtitle) {
      liveDropElements.eventSubtitle.textContent = formatEventSubtitle(settings);
    }
    if (liveDropElements.ctaLabel) {
      liveDropElements.ctaLabel.textContent = settings.ctaLabel || defaults.ctaLabel || 'Ingatkan Saya';
    }
    liveDropTargetDate = parseEventDate(settings.eventDateTime);
    if (!liveDropTargetDate) {
      stopLiveDropCountdown();
      setCountdownSlots();
    } else {
      startLiveDropCountdown();
    }
  };

  const loadCart = () => {
    try {
      const raw = localStorage.getItem(CART_KEY);
      const parsed = raw ? JSON.parse(raw) : [];
      return Array.isArray(parsed) ? parsed : [];
    } catch (_) {
      return [];
    }
  };
  const saveCart = () => localStorage.setItem(CART_KEY, JSON.stringify(cart));

  const prefersReducedMotion = () => {
    try {
      return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    } catch (_) {
      return false;
    }
  };

  const getCartTotalQty = (items) => {
    if (!Array.isArray(items) || !items.length) return 0;
    return items.reduce((sum, item) => sum + (Number(item?.qty) || 1), 0);
  };

  const restartAnimClass = (el, className) => {
    if (!el) return;
    el.classList.remove(className);
    // force reflow so animation restarts
    void el.offsetWidth; // eslint-disable-line no-unused-expressions
    el.classList.add(className);
  };

  const animateFlyToCart = (fromEl) => {
    if (!fromEl || prefersReducedMotion()) return;
    const cartBtnEl = document.getElementById('cartBtn');
    if (!cartBtnEl) return;

    const fromRect = fromEl.getBoundingClientRect();
    const toRect = cartBtnEl.getBoundingClientRect();
    if (!fromRect.width || !toRect.width) return;

    const startX = fromRect.left + fromRect.width / 2;
    const startY = fromRect.top + fromRect.height / 2;
    const endX = toRect.left + toRect.width / 2;
    const endY = toRect.top + toRect.height / 2;

    const dot = document.createElement('div');
    dot.className = 'wc-fly-dot';
    dot.style.left = `${startX - 6}px`;
    dot.style.top = `${startY - 6}px`;
    document.body.appendChild(dot);

    // Fallback removal in case transitionend doesn't fire.
    const fallbackRemoveId = window.setTimeout(() => {
      dot.remove();
    }, 900);

    requestAnimationFrame(() => {
      const dx = endX - startX;
      const dy = endY - startY;
      dot.style.transform = `translate3d(${dx}px, ${dy}px, 0) scale(0.35)`;
      dot.style.opacity = '0.12';
    });

    dot.addEventListener(
      'transitionend',
      () => {
        window.clearTimeout(fallbackRemoveId);
        dot.remove();
      },
      { once: true },
    );
  };

  const createRipple = (btn, evt) => {
    if (!btn || prefersReducedMotion()) return;
    btn.classList.add('wc-ripple-host');

    // Prevent buildup if animation events don't fire.
    try {
      const existing = btn.querySelectorAll('.wc-ripple');
      if (existing && existing.length > 2) {
        existing.forEach((node, idx) => {
          if (idx < existing.length - 2) node.remove();
        });
      }
    } catch (_) {}

    const rect = btn.getBoundingClientRect();
    const x = evt?.clientX ? evt.clientX - rect.left : rect.width / 2;
    const y = evt?.clientY ? evt.clientY - rect.top : rect.height / 2;

    const ripple = document.createElement('span');
    ripple.className = 'wc-ripple';
    ripple.style.left = `${x}px`;
    ripple.style.top = `${y}px`;
    btn.appendChild(ripple);

    const fallbackRemoveId = window.setTimeout(() => {
      ripple.remove();
    }, 800);

    ripple.addEventListener(
      'animationend',
      () => {
        window.clearTimeout(fallbackRemoveId);
        ripple.remove();
      },
      { once: true },
    );
  };

  const flashButtonLabel = (btn, label, durationMs = 2000) => {
    if (!btn) return;

    const hasRichContent = !!(btn.querySelector && btn.querySelector('i,svg,img,span'));
    const originalHtml = btn.innerHTML;
    const originalText = btn.textContent;

    const prevTimer = Number(btn.dataset.wcLabelTimer || 0);
    if (prevTimer) window.clearTimeout(prevTimer);

    btn.dataset.wcLabelRestoreHtml = originalHtml;
    btn.dataset.wcLabelRestoreText = originalText;

    // Use textContent for the temporary label to avoid HTML injection.
    btn.textContent = String(label ?? '');

    const timerId = window.setTimeout(() => {
      const restoreHtml = btn.dataset.wcLabelRestoreHtml;
      const restoreText = btn.dataset.wcLabelRestoreText;

      if (hasRichContent && typeof restoreHtml === 'string') btn.innerHTML = restoreHtml;
      else if (typeof restoreText === 'string') btn.textContent = restoreText;

      delete btn.dataset.wcLabelTimer;
    }, Math.max(200, Number(durationMs) || 2000));

    btn.dataset.wcLabelTimer = String(timerId);
  };

  let lastCartTotalQty = 0;

  const updateCartBadge = (opts = {}) => {
    const cc = document.getElementById('cartCount');
    if (!cc) return;

    const prevQty = lastCartTotalQty;
    const nextQty = getCartTotalQty(cart);
    lastCartTotalQty = nextQty;

    if (!nextQty) {
      cc.classList.add('hidden');
      cc.textContent = '0';
      return;
    }

    cc.textContent = String(nextQty);
    const wasHidden = cc.classList.contains('hidden');
    cc.classList.remove('hidden');

    const sourceEl = opts?.sourceEl;
    const shouldAnimate = !!sourceEl && nextQty > prevQty;
    if (!shouldAnimate) return;

    const cartBtnEl = document.getElementById('cartBtn');
    restartAnimClass(cartBtnEl, 'wc-cart-pulse');

    if (wasHidden || prevQty === 0) restartAnimClass(cc, 'wc-badge-in');
    else restartAnimClass(cc, 'wc-badge-pop');

    animateFlyToCart(sourceEl);
  };

  const addItemToCart = (item, sourceEl) => {
    cart.push({ ...item, qty: item.qty || 1 });
    saveCart();
    updateCartBadge({ sourceEl });
  };
  const isLoggedIn = () => localStorage.getItem('wc_logged_in') === '1';

  const deriveInitials = (name = '') => {
    const parts = String(name)
      .trim()
      .split(/\s+/)
      .filter(Boolean);
    if (!parts.length) return 'WC';
    const first = parts[0][0] || '';
    const second = (parts[1] && parts[1][0]) || '';
    return (first + second).toUpperCase() || 'WC';
  };

  const updateHeaderUser = () => {
    if (!headerUserFullName && !headerUserInitials) return;

    if (!isLoggedIn() || !window.ProfileStore || typeof ProfileStore.getProfileData !== 'function') {
      if (headerUserFullName) headerUserFullName.classList.add('hidden');
      if (headerUserInitials) headerUserInitials.textContent = 'WC';
      return;
    }

    let profile = null;
    try {
      profile = ProfileStore.getProfileData();
    } catch (_) {
      profile = null;
    }
    const fullName = String(profile?.name || '').trim();

    if (headerUserInitials) {
      headerUserInitials.textContent = deriveInitials(fullName);
    }

    if (!headerUserFullName) return;
    if (!fullName) {
      headerUserFullName.classList.add('hidden');
      return;
    }
    headerUserFullName.textContent = fullName;
    headerUserFullName.classList.remove('hidden');
  };

  const getWishlistItems = () => {
    if (!window.ProfileStore) return [];
    try {
      const wishlist = ProfileStore.getWishlist();
      return Array.isArray(wishlist) ? wishlist : [];
    } catch (_) {
      return [];
    }
  };

  const updateWishlistBadge = () => {
    if (!wishlistBadge) return;
    const wishlist = getWishlistItems();
    if (!wishlist.length) {
      wishlistBadge.classList.add('hidden');
      wishlistBadge.textContent = '0';
    } else {
      wishlistBadge.textContent = wishlist.length;
      wishlistBadge.classList.remove('hidden');
    }
  };

  let reviewSummaryCache = {};

  const getReviewSummaryMap = () => {
    if (!window.ReviewStore || typeof ReviewStore.getSummaryMap !== 'function') return {};
    try {
      return ReviewStore.getSummaryMap();
    } catch (_) {
      return {};
    }
  };

  const refreshReviewSummaryCache = () => {
    reviewSummaryCache = getReviewSummaryMap();
  };

  const applyReviewSummaries = (products) => {
    const summaryMap = getReviewSummaryMap();
    reviewSummaryCache = summaryMap;
    if (!summaryMap || !Object.keys(summaryMap).length) return products;
    return products.map((product) => {
      const summary = summaryMap[String(product.id)];
      if (!summary || !summary.count) return product;
      return {
        ...product,
        rating: {
          rate: summary.avg || 0,
          count: summary.count,
        },
      };
    });
  };

  refreshReviewSummaryCache();
  applyLiveDropSettings();

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

  const CLOTHING_KEYWORDS_RX = /\b(dress|skirt|crop|t[- ]?shirt|tee|shirt|jean|jeans|pant|pants|trouser|jacket|coat|hoodie|sweater|cardigan|tank)\b/i;

  const SUBCATEGORY_RULES = [
    { type: 'Crop Top', group: 'Womens Clothing', patterns: [/\bcrop\s*top\b/i, /\bcrop\b/i] },
    { type: 'Dresses', group: 'Womens Clothing', patterns: [/\bdress(es)?\b/i, /\bgown(s)?\b/i] },
    { type: 'Skirts', group: 'Womens Clothing', patterns: [/\bskirt(s)?\b/i] },
    { type: 'Jeans', patterns: [/\bjean(s)?\b/i] },
    { type: 'Jacket', patterns: [/\bjacket(s)?\b/i, /\bcoat(s)?\b/i, /\bhoodie(s)?\b/i] },
    { type: 'Long Sleeves', patterns: [/\blong\s*sleeve(s)?\b/i, /\blongsleeve(s)?\b/i] },
    { type: 'Sweaters', patterns: [/\bsweater(s)?\b/i, /\bcardigan(s)?\b/i, /\bknit\b/i] },
    { type: 'Tank Top', patterns: [/\btank\s*top(s)?\b/i, /\btank\b/i] },
    { type: 'T-Shirts', patterns: [/\bt[- ]?shirt(s)?\b/i, /\btee(s)?\b/i] },
    { type: 'Pants', patterns: [/\btrouser(s)?\b/i, /\bpant(s)?\b/i, /\bchino(s)?\b/i] },
  ];

  const getCustomProducts = () => {
    if (!window.CustomProductStore || typeof CustomProductStore.getAll !== 'function') return [];
    try {
      return CustomProductStore.getAll();
    } catch (_) {
      return [];
    }
  };

  function isSupportedClothingProduct(title = '', rawCategory = '') {
    const hay = `${title || ''} ${rawCategory || ''}`.toLowerCase();
    if (hay.includes('clothing')) return true;
    return CLOTHING_KEYWORDS_RX.test(hay);
  }

  function normalizeCategoryGroup(rawCategory = '', title = '') {
    const hay = `${rawCategory || ''} ${title || ''}`.toLowerCase();
    if (hay.includes('women') || hay.includes("women's") || hay.includes('woman')) return 'Womens Clothing';
    if (hay.includes('men') || hay.includes("men's") || hay.includes('man')) return 'Mens Clothing';
    if (/\b(dress|skirt|crop)\b/i.test(hay)) return 'Womens Clothing';
    return 'Mens Clothing';
  }

  function detectSubcategory(title = '', group = '') {
    const safeGroup = group === 'Womens Clothing' ? 'Womens Clothing' : 'Mens Clothing';
    for (const rule of SUBCATEGORY_RULES) {
      if (rule.group && rule.group !== safeGroup) continue;
      if (rule.patterns.some((rx) => rx.test(title))) return rule.type;
    }
    // Default fallback to a valid nested type.
    return safeGroup === 'Womens Clothing' ? 'T-Shirts' : 'T-Shirts';
  }

  function normalizeCatalogProduct(p) {
    const group = normalizeCategoryGroup(p.category, p.title);
    const sub = p.type || p._type || detectSubcategory(p.title, group);
    return {
      ...p,
      category: group,
      _type: sub,
    };
  }

  const mapCustomProducts = () => {
    return getCustomProducts().map((item) => {
      const normalized = normalizeCatalogProduct({
        ...item,
        category: item.category || '',
        type: item.type || '',
      });

      return {
        ...normalized,
        rating: item.rating || { rate: 0, count: 0 },
        price: typeof item.price === 'number' ? item.price : Number(item.price) || 0,
        image: item.image || 'https://via.placeholder.com/400x400?text=Custom+Product',
        description: item.description || 'Produk kustom dari admin.',
        isCustom: true,
      };
    });
  };

  function isFemalePreferred(p) {
    return String(p.category || '').toLowerCase().includes('women');
  }

  function formatIDR(usd) {
    const idr = Math.round(usd * RATE_IDR);
    return `Rp ${idr.toLocaleString('id-ID')}`;
  }

  function productCard(p, idx) {
    const label = p._type || (idx % 3 === 0 ? 'New' : (idx % 3 === 1 ? 'Hot' : 'Limited'));
    const variant = p._type ? 'accent' : (idx % 3 === 0 ? 'primary' : (idx % 3 === 1 ? 'secondary' : 'muted'));
    const summary = reviewSummaryCache[String(p.id)];
    let ratingDisplay = '-';
    let stock = '-';
    if (summary && summary.count) {
      ratingDisplay = Number(summary.avg || 0).toFixed(1);
      stock = summary.count;
    } else if (p.rating && p.rating.count) {
      ratingDisplay = Number(p.rating.rate || 0).toFixed(1);
      stock = p.rating.count;
    }
      const detailUrl = `product-detail.html?id=${encodeURIComponent(p.id)}`;
    const priceRaw = Math.round(p.price * RATE_IDR);
    return `
      <article class="product-card card-hover" data-id="${p.id}" data-detail-url="${detailUrl}" data-image="${p.image}" data-price-raw="${priceRaw}">
        <div class="product-card__media">
          <img src="${p.image}" alt="${p.title}" loading="lazy" referrerpolicy="no-referrer" />
          <span class="product-card__badge product-card__badge--${variant}">${label}</span>
        </div>
        <div class="product-card__body">
          <span class="product-card__category">${p.category}</span>
          <h3 class="product-card__title line-clamp-2" title="${p.title}">${p.title}</h3>
          <div class="product-card__price">${formatIDR(p.price)}</div>
          <dl class="product-card__meta">
            <div class="product-card__metaItem">
              <dt>Rating</dt>
              <dd>${ratingDisplay}</dd>
            </div>
            <div class="product-card__metaItem">
              <dt>Stok</dt>
              <dd>${stock}</dd>
            </div>
          </dl>
        </div>
        <div class="product-card__actions">
          <button type="button" class="product-card__btn add-to-cart" data-name="${p.title}" data-price="${formatIDR(p.price)}" data-price-raw="${priceRaw}">Tambah Keranjang</button>
            <a href="${detailUrl}" class="product-card__actionsIcon detail-link" title="Lihat detail">
              <i class="fas fa-eye"></i>
            </a>
        </div>
      </article>`;
  }

  async function initProducts() {
    try {
      if (!productsGrid || !productsLoading) return;
      productsLoading.classList.remove('hidden');
      const res = await fetch(API_URL);
      if (!res.ok) throw new Error('Gagal mengambil data produk');
      const all = await res.json();
      const filtered = all
        .filter((p) => isSupportedClothingProduct(p.title, p.category))
        .map((p) => normalizeCatalogProduct(p));

      const combined = filtered.concat(mapCustomProducts());
      combined.sort((a, b) => (isFemalePreferred(b) - isFemalePreferred(a)));
      dataCache = applyReviewSummaries(combined);
    
      updateTeasersFromProducts();
      productsLoading.classList.add('hidden');
      renderPage();
    } catch (err) {
      dataCache = applyReviewSummaries(mapCustomProducts());
      if (!dataCache.length) {
        if (productsLoading) productsLoading.textContent = 'Gagal memuat produk. Periksa koneksi internet Anda.';
      } else {
        productsLoading.classList.add('hidden');
        renderPage();
      }
    }
  }

  function updateTeasersFromProducts() {
    try {
      const teaserGrid = liveDropTeasersGrid;
      if (!teaserGrid) return;
      const imgs = teaserGrid.querySelectorAll('img');
      if (!imgs || !imgs.length) return;
      const picks = dataCache.slice(0, 4);
      picks.forEach((p, i) => {
        if (imgs[i]) {
          imgs[i].src = p.image;
          imgs[i].alt = p.title;
          imgs[i].loading = 'lazy';
          imgs[i].referrerPolicy = 'no-referrer';
        }
      });
    } catch (_) {
    }
  }

  function renderPage() {
    if (!productsGrid) return;
    const start = (page - 1) * pageSize;
    const slice = dataCache.slice(start, start + pageSize);
    if (slice.length === 0) {
      if (loadMoreBtn) loadMoreBtn.classList.add('hidden');
      return;
    }
    const cards = slice.map((p, i) => productCard(p, i)).join('');
    productsGrid.insertAdjacentHTML('beforeend', cards);
    attachCartListeners();
    attachDetailListeners();
    page++;
    if (start + pageSize >= dataCache.length && loadMoreBtn) {
      loadMoreBtn.classList.add('hidden');
    }
  }

  function openProductDetailById(id) {
    const modal = document.getElementById('productDetailModal');
    const wrap = document.getElementById('productDetailContent');
    if (!modal || !wrap) return;
    const p = dataCache.find(x => String(x.id) === String(id));
    if (!p) return;
    const rate = p.rating && p.rating.rate ? p.rating.rate : '-';
    const count = p.rating && p.rating.count ? p.rating.count : '-';
    const desc = p.description || 'Tidak ada deskripsi.';
    const truncated = desc.length > 350;
    const short = truncated ? desc.slice(0, 350) + '…' : desc;
    const stars = (r) => {
      if (!r || isNaN(r)) r = 0;
      const full = Math.floor(r);
      const half = r - full >= 0.5 ? 1 : 0;
      const empty = 5 - full - half;
      return `${'★'.repeat(full)}${half ? '☆' : ''}${'✩'.repeat(empty)}`;
    };
    wrap.innerHTML = `
      <div class="bg-gray-50 rounded-xl p-4 grid place-items-center">
        <img src="${p.image}" alt="${p.title}" class="w-full h-80 object-contain" loading="lazy" referrerpolicy="no-referrer" />
      </div>
      <div>
        <h4 class="text-2xl md:text-3xl font-extrabold text-dark mb-2">${p.title}</h4>
        <div class="flex flex-wrap items-center gap-2 mb-3">
          <span class="px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium">${p.category}</span>
          ${p._type ? `<span class="px-2.5 py-1 rounded-full bg-primary/10 text-primary text-xs font-medium">${p._type}</span>` : ''}
          <span class="ml-auto text-sm text-gray-500 hidden md:inline">ID: ${p.id}</span>
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-600 mb-2">
          <span class="flex items-center gap-1"><span class="text-yellow-500">${stars(rate)}</span><span>${rate}</span></span>
          <span>Stock: ${count}</span>
        </div>
        <div class="text-3xl md:text-4xl font-bold text-primary mb-4">${formatIDR(p.price)}</div>
        <div class="text-gray-700 leading-relaxed mb-4" id="productDetailDescription">${short}</div>
        ${truncated ? `<button id="toggleDesc" class="text-primary hover:underline text-sm mb-6">Selengkapnya</button>` : ''}
        <div class="flex gap-3">
          <button id="detailAddToCart" class="flex-1 bg-secondary hover:bg-teal-500 text-white font-medium py-2 px-4 rounded transition duration-300">Tambah Keranjang</button>
          <button id="closeProductDetail2" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Tutup</button>
        </div>
      </div>
    `;
    modal.classList.remove('hidden');
    const c1 = document.getElementById('closeProductDetail');
    const c2 = document.getElementById('closeProductDetail2');
    [c1, c2].forEach(el => el && el.addEventListener('click', () => modal.classList.add('hidden')));
    const addBtn = document.getElementById('detailAddToCart');
    if (addBtn) {
      addBtn.addEventListener('click', (e) => {
        e.preventDefault();
        flashButtonLabel(addBtn, 'Telah Ditambahkan', 2000);
        createRipple(addBtn, e);
        addItemToCart(
          {
            id: String(p.id),
            name: p.title,
            priceDisplay: formatIDR(p.price),
            priceRaw: Math.round(p.price * RATE_IDR),
            image: p.image || '',
          },
          addBtn,
        );
      });
    }
    const toggle = document.getElementById('toggleDesc');
    if (toggle) {
      const descEl = document.getElementById('productDetailDescription');
      toggle.onclick = () => {
        if (descEl.dataset.expanded === '1') {
          descEl.textContent = short;
          descEl.dataset.expanded = '0';
          toggle.textContent = 'Selengkapnya';
        } else {
          descEl.textContent = desc;
          descEl.dataset.expanded = '1';
          toggle.textContent = 'Sembunyikan';
        }
      };
    }
   
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.add('hidden');
    }, { once: true });
  }

  function attachDetailListeners() {
    document.querySelectorAll('.detail-btn').forEach(btn => {
      if (btn.tagName === 'A') return;
      if (btn.dataset.bound === '1') return;
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const id = btn.getAttribute('data-id') || btn.closest('[data-id]')?.getAttribute('data-id');
        if (id) openProductDetailById(id);
      });
      btn.dataset.bound = '1';
    });

    document.querySelectorAll('.card-hover').forEach(card => {
      if (card.dataset.detailBound === '1') return;
      const img = card.querySelector('img');
      if (img) {
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => {
          const detailUrl = card.getAttribute('data-detail-url');
          if (detailUrl) {
            window.location.href = detailUrl;
            return;
          }
          const id = card.getAttribute('data-id');
          if (id) openProductDetailById(id);
        });
      }
      card.dataset.detailBound = '1';
    });
  }

  function attachCartListeners() {
    document.querySelectorAll('.add-to-cart').forEach(btn => {
      if (btn.dataset.bound === '1') return;
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        flashButtonLabel(btn, 'Telah Ditambahkan', 2000);
        createRipple(btn, e);
        const card = btn.closest('.card-hover');
        const image = card?.dataset.image || '';
        const priceRaw = Number(btn.getAttribute('data-price-raw') || card?.dataset.priceRaw || 0);
        addItemToCart({
          id: card?.dataset.id || Date.now().toString(),
          name: btn.getAttribute('data-name'),
          priceDisplay: btn.getAttribute('data-price'),
          priceRaw,
          image
        }, btn);
      });
      btn.dataset.bound = '1';
    });
  }

  function ensureLoadMoreButton() {
    if (!productsGrid) return;
    if (!loadMoreBtn) {
      const wrapper = document.createElement('div');
      wrapper.className = 'text-center mt-12';
      const btn = document.createElement('button');
      btn.id = 'loadMoreBtn';
      btn.className = 'inline-block bg-primary hover:bg-red-600 text-white font-medium py-3 px-8 rounded-full transition duration-300';
      btn.textContent = 'Muat Lebih Banyak';
      wrapper.appendChild(btn);
      productsGrid.insertAdjacentElement('afterend', wrapper);
      loadMoreBtn = btn;
    }
    if (loadMoreBtn && !loadMoreBtn.dataset.bound) {
      loadMoreBtn.addEventListener('click', (e) => {
        e.preventDefault();
        renderPage();
      });
      loadMoreBtn.dataset.bound = '1';
    }
  }

  if (productsGrid && productsLoading) {
    ensureLoadMoreButton();
    initProducts();
  }

  const testimonialSlides = document.querySelectorAll('.testimonial-slide');
  const testimonialDots = document.querySelectorAll('.testimonial-dot');
  let currentSlide = 0;
  function showSlide(n) {
    if (!testimonialSlides.length) return;
    testimonialSlides.forEach(slide => slide.classList.remove('active'));
    testimonialDots.forEach(dot => { dot.classList.remove('bg-primary'); dot.classList.add('bg-gray-300'); });
    testimonialSlides[n].classList.add('active');
    if (testimonialDots[n]) { testimonialDots[n].classList.remove('bg-gray-300'); testimonialDots[n].classList.add('bg-primary'); }
    currentSlide = n;
  }
  testimonialDots.forEach((dot, index) => {
    dot.addEventListener('click', () => showSlide(index));
  });
  if (testimonialSlides.length) {
    showSlide(0);
    setInterval(() => {
      let nextSlide = (currentSlide + 1) % testimonialSlides.length;
      showSlide(nextSlide);
    }, 5000);
  }

  // modal logic
  function toggleModal(modalId, show) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    if (show) modal.classList.remove('hidden'); else modal.classList.add('hidden');
  }
  const sb = document.getElementById('searchBtn');
  const cs = document.getElementById('closeSearch');
  const cb = document.getElementById('cartBtn');
  const ccbtn = document.getElementById('closeCart');
  const pb = document.getElementById('profileBtn');
  const wb = document.getElementById('wishlistBtn');
  const cp = document.getElementById('closeProfile');
  const searchModal = document.getElementById('searchModal');
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');

  const closeSearch = () => {
    toggleModal('searchModal', false);
    if (searchResults) searchResults.innerHTML = '';
  };

  const openSearch = () => {
    toggleModal('searchModal', true);
    requestAnimationFrame(() => {
      try {
        const panel = searchModal?.querySelector('.wc-search-panel');
        const btnRect = sb?.getBoundingClientRect?.();
        if (panel && btnRect && window.innerWidth > 640) {
          const panelWidth = panel.getBoundingClientRect().width || 420;
          const rightPadding = 18;
          const left = Math.max(16, Math.min(btnRect.right - panelWidth, window.innerWidth - panelWidth - rightPadding));
          const top = Math.max(12, btnRect.bottom + 12);
          panel.style.position = 'fixed';
          panel.style.left = `${left}px`;
          panel.style.top = `${top}px`;
        } else if (panel) {
          panel.style.position = '';
          panel.style.left = '';
          panel.style.top = '';
        }
      } catch (_) {}

      if (searchInput) {
        searchInput.focus();
        searchInput.select?.();
      }
    });
  };

  if (sb && cs) {
    sb.onclick = () => openSearch();
    cs.onclick = () => closeSearch();
  }

  if (searchModal) {
    searchModal.addEventListener('click', (e) => {
      if (e.target === searchModal) closeSearch();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (!searchModal || searchModal.classList.contains('hidden')) return;
    closeSearch();
  });

  const cartNavigates = cb && cb.dataset && cb.dataset.navTarget === 'cart';
  const profileNavigates = pb && pb.dataset && pb.dataset.navTarget === 'profile';
  const wishlistNavigates = wb && wb.dataset && wb.dataset.navTarget === 'wishlist';

  if (pb && profileNavigates && window.AuthStore && typeof AuthStore.isAdmin === 'function' && AuthStore.isLoggedIn?.() && AuthStore.isAdmin()) {
    pb.setAttribute('href', 'profile-admin.html');
  }

  if (!cartNavigates && cb && ccbtn) {
    cb.onclick = () => toggleModal('cartModal', true);
    ccbtn.onclick = () => toggleModal('cartModal', false);
  }
  if (!profileNavigates && pb) {
    pb.onclick = () => toggleModal('profileModal', true);
  }
  if (cartNavigates && cb && cb.dataset.requiresAuth) {
    cb.addEventListener('click', (e) => {
      if (!isLoggedIn()) {
        e.preventDefault();
        toggleModal('profileModal', true);
      }
    });
  }
  if (profileNavigates && pb && pb.dataset.requiresAuth) {
    pb.addEventListener('click', (e) => {
      if (!isLoggedIn()) {
        e.preventDefault();
        toggleModal('profileModal', true);
      }
    });
  }
  if (wishlistNavigates && wb && wb.dataset.requiresAuth) {
    wb.addEventListener('click', (e) => {
      if (!isLoggedIn()) {
        e.preventDefault();
        toggleModal('profileModal', true);
      }
    });
  }
  if (cp) {
    cp.onclick = () => toggleModal('profileModal', false);
  }
  const profileModalCTA = document.querySelector('#profileModal .btn-main');
  if (profileModalCTA) {
    profileModalCTA.addEventListener('click', () => {
      window.location.href = 'login.html';
    });
  }

  let cart = loadCart();
  updateCartBadge();
  updateWishlistBadge();
  updateHeaderUser();
  window.addEventListener('storage', (event) => {
    if (event.key === 'wc_profiles_v2') {
      updateWishlistBadge();
      updateHeaderUser();
    }
    if (event.key === 'wc_logged_in' || event.key === 'wc_active_account') {
      updateHeaderUser();
    }
    if (window.CustomProductStore && event.key === CustomProductStore.STORAGE_KEY) {
      window.location.reload();
    }
    if (window.ReviewStore && event.key === ReviewStore.STORAGE_KEY) {
      window.location.reload();
    }
    if (window.LiveDropStore && event.key === LiveDropStore.STORAGE_KEY) {
      applyLiveDropSettings();
    }
  });

  window.addEventListener('wc-live-drop-updated', () => {
    applyLiveDropSettings();
  });

  window.addEventListener('wc-reviews-updated', () => {
    if (!dataCache.length) return;
    refreshReviewSummaryCache();
    dataCache = applyReviewSummaries(dataCache);
    if (productsGrid) {
      productsGrid.innerHTML = '';
      page = 1;
      if (loadMoreBtn) {
        loadMoreBtn.classList.remove('hidden');
      }
      renderPage();
    }
  });
  // Button fallback dihapus karena digantikan listener khusus
  // Tampilan isi keranjang
  const cartBtn = document.getElementById('cartBtn');
  if (cartBtn && cartBtn.dataset.navTarget !== 'cart') {
    cartBtn.addEventListener('click', function() {
      const cartItems = document.getElementById('cartItems');
      if (!cartItems) return;
      const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' });
      cartItems.innerHTML = cart.length === 0 ? '<li>Keranjang kosong.</li>' : cart.map(item => `<li class="mb-2 flex justify-between"><span>${item.name}</span><span class="font-bold text-primary">${item.priceDisplay || formatter.format(item.priceRaw || 0)}</span></li>`).join('');
    });
  }
  // Integrated search (uses loaded products + opens detail)
  const escapeHTML = (value = '') =>
    String(value ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');

  const getSearchSource = () => {
    if (Array.isArray(dataCache) && dataCache.length) {
      return dataCache.map((p) => ({
        id: p.id,
        title: p.title,
        category: p._type ? `${p.category} • ${p._type}` : p.category,
        image: p.image,
        price: p.price,
      }));
    }
    // Fallback from DOM if dataCache empty
    return Array.from(document.querySelectorAll('.card-hover')).map((card) => {
      const title = card.querySelector('.product-card__title')?.textContent || card.querySelector('h3')?.textContent || '';
      const category = card.querySelector('.product-card__category')?.textContent || '';
      const image = card.querySelector('img')?.getAttribute('src') || card.dataset.image || '';
      const priceRaw = Number(card.dataset.priceRaw || 0);
      return { id: card.dataset.id, title, category, image, priceRaw };
    });
  };

  const formatIDRFromSource = (item) => {
    if (typeof item.price === 'number') return formatIDR(item.price);
    const raw = Number(item.priceRaw || 0);
    if (!raw) return '';
    try {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(raw);
    } catch (_) {
      return `Rp ${raw.toLocaleString('id-ID')}`;
    }
  };

  let searchTimer = null;
  if (searchInput && searchResults) {
    searchInput.addEventListener('input', () => {
      const q = (searchInput.value || '').trim().toLowerCase();
      if (searchTimer) window.clearTimeout(searchTimer);
      if (!q) {
        searchResults.innerHTML = '';
        return;
      }

      searchTimer = window.setTimeout(() => {
        const source = getSearchSource();
        const matches = source
          .filter((p) => {
            const hay = `${p.title || ''} ${p.category || ''}`.toLowerCase();
            return hay.includes(q);
          })
          .slice(0, 6);

        if (!matches.length) {
          searchResults.innerHTML = '<div class="wc-search-empty">Tidak ditemukan.</div>';
          return;
        }

        searchResults.innerHTML = matches
          .map((p) => {
            const price = formatIDRFromSource(p);
            return `
              <button type="button" class="wc-search-item" data-id="${escapeHTML(String(p.id || ''))}">
                <span class="wc-search-thumb">${p.image ? `<img src="${escapeHTML(p.image)}" alt="${escapeHTML(p.title || 'Produk')}" loading="lazy" referrerpolicy="no-referrer" />` : ''}</span>
                <span class="wc-search-meta">
                  <span class="wc-search-name">${escapeHTML(p.title || 'Produk')}</span>
                  <span class="wc-search-sub">${escapeHTML(p.category || '')}</span>
                </span>
                <span class="wc-search-price">${escapeHTML(price)}</span>
              </button>
            `;
          })
          .join('');
      }, 120);
    });

    searchResults.addEventListener('click', (e) => {
      const btn = e.target.closest('button[data-id]');
      if (!btn) return;
      const id = btn.getAttribute('data-id');
      if (!id) return;
      closeSearch();
      // open integrated detail modal (uses same dataCache)
      openProductDetailById(id);
    });
  }

  // Checkout button redirect
  const checkoutBtn = document.getElementById('checkoutBtn');
  if (checkoutBtn) {
    checkoutBtn.onclick = function(e) {
      e.preventDefault();
      if (!isLoggedIn()) {
        toggleModal('profileModal', true);
        return;
      }
      window.location.href = 'checkout.html';
    };
  }

  // Contact form (body.html) -> admin inbox sync
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    const CONTACT_MESSAGES_KEY = 'wc_contact_messages_v1';
    const statusEl = document.getElementById('contactFormStatus');
    const nameEl = document.getElementById('name');
    const emailEl = document.getElementById('email');
    const subjectEl = document.getElementById('subject');
    const messageEl = document.getElementById('message');

    const setStatus = (message, type) => {
      if (!statusEl) return;
      statusEl.textContent = message || '';
      if (!message) {
        statusEl.classList.add('hidden');
        return;
      }
      statusEl.classList.remove('hidden');
      statusEl.classList.remove('text-gray-500', 'text-green-600', 'text-red-500');
      if (type === 'success') statusEl.classList.add('text-green-600');
      else if (type === 'error') statusEl.classList.add('text-red-500');
      else statusEl.classList.add('text-gray-500');
    };

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
      localStorage.setItem(CONTACT_MESSAGES_KEY, JSON.stringify(list));
      try {
        window.dispatchEvent(new CustomEvent('wc-contact-messages-updated'));
      } catch (_) {}
    };

    // Prefill from profile if logged in
    try {
      if (localStorage.getItem('wc_logged_in') === '1' && window.ProfileStore?.getProfileData) {
        const profile = ProfileStore.getProfileData();
        if (nameEl && !nameEl.value) nameEl.value = profile.name || '';
        if (emailEl && !emailEl.value) emailEl.value = profile.email || '';
      }
    } catch (_) {}

    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();
      setStatus('', 'info');

      const name = String(nameEl?.value || '').trim();
      const email = String(emailEl?.value || '').trim();
      const subject = String(subjectEl?.value || '').trim() || 'Pesan dari halaman kontak';
      const message = String(messageEl?.value || '').trim();

      if (!name) {
        setStatus('Nama wajib diisi.', 'error');
        nameEl?.focus?.();
        return;
      }
      if (!message) {
        setStatus('Pesan wajib diisi.', 'error');
        messageEl?.focus?.();
        return;
      }

      let userEmail = '';
      try {
        if (localStorage.getItem('wc_logged_in') === '1' && window.ProfileStore?.getProfileData) {
          userEmail = String(ProfileStore.getProfileData()?.email || '').trim();
        }
      } catch (_) {}

      const payload = {
        id: `msg-${Date.now().toString(36)}-${Math.random().toString(16).slice(2, 6)}`,
        name,
        email,
        subject,
        message,
        userEmail,
        createdAt: new Date().toISOString(),
        source: 'body.html#contact',
      };

      try {
        const list = readMessages();
        list.unshift(payload);
        writeMessages(list.slice(0, 200));
        setStatus('Pesan berhasil dikirim. Terima kasih!', 'success');
        if (subjectEl) subjectEl.value = '';
        if (messageEl) messageEl.value = '';
      } catch (err) {
        console.warn('Gagal menyimpan pesan kontak', err);
        setStatus('Gagal mengirim pesan. Coba ulangi.', 'error');
      }
    });
  }
});
