// Page-load reveal helper (all pages)
// - Auto tags relevant blocks (cards/images/sections/forms)
// - Adds wc-reveal-ready after first paint
// - Animates elements inserted later (MutationObserver)

const WC_REVEAL_CLASS = 'wc-reveal';
const WC_REVEAL_READY_CLASS = 'wc-reveal-ready';
const WC_REVEAL_TEXT_CLASS = 'wc-reveal-text';
const WC_REVEAL_DONE_CLASS = 'wc-reveal-done';

const prefersReducedMotion = () =>
  typeof window !== 'undefined' &&
  typeof window.matchMedia === 'function' &&
  window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const getInlineDelayMs = (el) => {
  if (!el || !el.style || typeof el.style.getPropertyValue !== 'function') return 0;
  const raw = el.style.getPropertyValue('--reveal-delay') || '';
  const match = String(raw).match(/(-?\d+)\s*ms/i);
  if (!match) return 0;
  const num = Number(match[1]);
  return Number.isFinite(num) ? Math.max(0, num) : 0;
};

const scheduleRevealDone = (el, delayMsOverride) => {
  if (!el || !el.classList) return;
  if (!el.classList.contains(WC_REVEAL_CLASS)) return;
  if (el.classList.contains(WC_REVEAL_DONE_CLASS)) return;

  // Avoid re-binding
  if (el.dataset && el.dataset.wcRevealDoneBound === '1') return;
  if (el.dataset) el.dataset.wcRevealDoneBound = '1';

  if (prefersReducedMotion()) {
    el.classList.add(WC_REVEAL_DONE_CLASS);
    return;
  }

  const delay = typeof delayMsOverride === 'number' && !Number.isNaN(delayMsOverride) ? Math.max(0, delayMsOverride) : getInlineDelayMs(el);
  // Reveal duration is 780ms; add buffer so we don't cut late elements short.
  window.setTimeout(() => {
    el.classList.add(WC_REVEAL_DONE_CLASS);
  }, delay + 900);

  el.addEventListener(
    'animationend',
    (event) => {
      if (event.target !== el) return;
      el.classList.add(WC_REVEAL_DONE_CLASS);
    },
    { once: true }
  );
};

const isTextLikeTag = (tagName = '') =>
  ['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P', 'SPAN', 'LI', 'DT', 'DD'].includes(tagName);

const isInteractiveTag = (tagName = '') =>
  ['A', 'BUTTON', 'INPUT', 'SELECT', 'TEXTAREA', 'OPTION', 'LABEL'].includes(tagName);

const shouldSkipReveal = (el) => {
  if (!el || el.nodeType !== 1) return true;
  const tag = el.tagName;
  if (!tag) return true;

  if (
    tag === 'SCRIPT' ||
    tag === 'STYLE' ||
    tag === 'LINK' ||
    tag === 'META' ||
    tag === 'HEAD' ||
    tag === 'TITLE' ||
    tag === 'TEMPLATE'
  ) {
    return true;
  }

  if (tag === 'CANVAS') return true;

  if (el.hasAttribute('hidden') || el.getAttribute('aria-hidden') === 'true') return true;
  if (el.classList && (el.classList.contains('hidden') || el.classList.contains('sr-only'))) return true;

  // Avoid animating modal containers / overlays
  if (
    el.closest(
      '.modal-bg, .modal, #searchModal, #cartModal, #profileModal, #productDetailModal, #reviewModal'
    )
  ) {
    return true;
  }

  // Allow opting out
  if (el.hasAttribute('data-no-reveal')) return true;

  return false;
};

const ensureTextVariant = (el) => {
  if (!el || !el.classList) return;
  if (!el.classList.contains(WC_REVEAL_CLASS)) return;
  if (el.classList.contains(WC_REVEAL_TEXT_CLASS)) return;
  if (!isTextLikeTag(el.tagName)) return;
  if (isInteractiveTag(el.tagName)) return;
  el.classList.add(WC_REVEAL_TEXT_CLASS);
};

const applyReveal = (el, delayMs) => {
  if (shouldSkipReveal(el)) return;
  if (!el.classList) return;
  if (el.classList.contains(WC_REVEAL_CLASS)) {
    ensureTextVariant(el);
    scheduleRevealDone(el);
    return;
  }

  // Avoid wrapping a whole container if it already contains reveal elements
  // (prevents nested opacity 0 on parents hiding already-staggered children)
  if (el.querySelector && el.querySelector(`.${WC_REVEAL_CLASS}`)) return;

  el.classList.add(WC_REVEAL_CLASS);
  if (typeof delayMs === 'number' && !Number.isNaN(delayMs)) {
    if (!el.style.getPropertyValue('--reveal-delay')) {
      el.style.setProperty('--reveal-delay', `${Math.max(0, delayMs)}ms`);
    }
  }
  ensureTextVariant(el);
  scheduleRevealDone(el, delayMs);
};

const getAutoCandidates = () => {
  const selectors = [
    // Common cards / blocks
    '.product-card',
    '.form-card',
    'main > *',
    'section > .container > *',
    'article',
    'aside',
    'form',
    // Card-like blocks
    '.rounded-2xl.shadow, .rounded-2xl.shadow-lg, .rounded-2xl.shadow-xl',
    '.rounded-3xl.shadow, .rounded-3xl.shadow-lg, .rounded-3xl.shadow-xl',
    // Media
    'img',
  ];

  const seen = new Set();
  const out = [];

  for (const selector of selectors) {
    document.querySelectorAll(selector).forEach((el) => {
      if (!el || seen.has(el)) return;
      seen.add(el);
      out.push(el);
    });
  }

  return out;
};

const autoTagInitial = () => {
  // Add text variant to manually tagged elements
  document.querySelectorAll(`.${WC_REVEAL_CLASS}`).forEach((el) => {
    ensureTextVariant(el);
    scheduleRevealDone(el);
  });

  const candidates = getAutoCandidates();

  // Apply stagger in document order (simple + predictable)
  let i = 0;
  for (const el of candidates) {
    const delay = Math.min(420, 60 + i * 55);
    applyReveal(el, delay);
    i += 1;
  }
};

const setupMutationReveal = () => {
  if (!('MutationObserver' in window)) return;
  const observer = new MutationObserver((mutations) => {
    for (const mutation of mutations) {
      for (const node of mutation.addedNodes || []) {
        if (!node || node.nodeType !== 1) continue;
        const el = /** @type {Element} */ (node);

        // If the node itself is a good candidate, tag it.
        applyReveal(el, 0);

        // Also tag any matching descendants.
        el.querySelectorAll(
          '.product-card, article, aside, form, .rounded-2xl.shadow, .rounded-2xl.shadow-lg, .rounded-2xl.shadow-xl, .rounded-3xl.shadow, .rounded-3xl.shadow-lg, .rounded-3xl.shadow-xl, img'
        ).forEach((child) => applyReveal(child, 0));

        // Ensure text variant for any manually added reveal elements.
        el.querySelectorAll(`.${WC_REVEAL_CLASS}`).forEach(ensureTextVariant);
      }
    }
  });

  observer.observe(document.body, { childList: true, subtree: true });
};

document.addEventListener('DOMContentLoaded', () => {
  if (!document.body) return;

  // Tag candidates before enabling reveal-ready
  autoTagInitial();
  setupMutationReveal();

  requestAnimationFrame(() => {
    document.body.classList.add(WC_REVEAL_READY_CLASS);
  });
});
