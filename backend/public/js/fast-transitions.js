/**
 * Wida Collection - Fast Page Transitions
 * Same elegant transition as login/register pages
 */

(function () {
    'use strict';

    // ========== Preload Pages on Hover ==========
    const preloadedUrls = new Set();

    function preloadPage(url) {
        if (preloadedUrls.has(url)) return;
        preloadedUrls.add(url);

        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.href = url;
        document.head.appendChild(link);
    }

    // Preload on hover (100ms delay to avoid false positives)
    document.addEventListener('mouseover', (e) => {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('http')) return;

        setTimeout(() => {
            if (link.matches(':hover')) {
                preloadPage(href);
            }
        }, 100);
    });

    // ========== Create Transition Overlay (Same as Login/Register) ==========
    let pageTransition = null;
    let transitionLogo = null;
    let stylesInjected = false;

    function injectStyles() {
        if (stylesInjected) return;
        stylesInjected = true;

        const style = document.createElement('style');
        style.id = 'wc-transition-styles';
        style.textContent = `
            /* ========== FAST PAGE TRANSITION ========== */
            .page-transition {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                pointer-events: none;
                display: flex;
                overflow: hidden;
            }

            .transition-panel {
                flex: 1;
                background: linear-gradient(180deg, #1a1a2e 0%, #0f3460 50%, #16213e 100%);
                transform: translateY(100%);
                will-change: transform;
                margin-left: -1px;
            }

            .transition-panel:first-child {
                margin-left: 0;
            }

            /* Staggered delays - fast and smooth */
            .transition-panel:nth-child(1) { transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 0ms; }
            .transition-panel:nth-child(2) { transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 30ms; }
            .transition-panel:nth-child(3) { transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 60ms; }
            .transition-panel:nth-child(4) { transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 90ms; }
            .transition-panel:nth-child(5) { transition: transform 0.4s cubic-bezier(0.86, 0, 0.07, 1) 120ms; }

            .page-transition.entering .transition-panel {
                transform: translateY(0);
            }

            .page-transition.exiting .transition-panel {
                transform: translateY(-100%);
            }

            /* Center Logo During Transition */
            .transition-logo {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(0);
                z-index: 10000;
                opacity: 0;
                pointer-events: none;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
            }

            .transition-logo.show {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }

            .transition-logo .logo-icon {
                width: 70px;
                height: 70px;
                background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%);
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                font-weight: 700;
                color: white;
                font-family: 'Poppins', sans-serif;
                box-shadow: 0 15px 50px rgba(255, 107, 107, 0.5);
                animation: logoPulse 0.6s ease-in-out infinite alternate;
            }

            @keyframes logoPulse {
                from { transform: scale(1); }
                to { transform: scale(1.08); }
            }
        `;
        document.head.appendChild(style);
    }

    function createTransitionElements() {
        // Check if elements already exist
        if (document.getElementById('wcPageTransition')) {
            pageTransition = document.getElementById('wcPageTransition');
            transitionLogo = document.getElementById('wcTransitionLogo');
            return;
        }

        injectStyles();

        // Create transition panels
        pageTransition = document.createElement('div');
        pageTransition.className = 'page-transition';
        pageTransition.id = 'wcPageTransition';
        pageTransition.innerHTML = `
            <div class="transition-panel"></div>
            <div class="transition-panel"></div>
            <div class="transition-panel"></div>
            <div class="transition-panel"></div>
            <div class="transition-panel"></div>
        `;
        document.body.appendChild(pageTransition);

        // Create logo
        transitionLogo = document.createElement('div');
        transitionLogo.className = 'transition-logo';
        transitionLogo.id = 'wcTransitionLogo';
        transitionLogo.innerHTML = `
            <div class="logo-icon">WC</div>
        `;
        document.body.appendChild(transitionLogo);
    }

    // ========== Navigate with Transition ==========
    let isNavigating = false;

    window.wcNavigate = function (url, options = {}) {
        if (isNavigating) return;
        isNavigating = true;

        const { transition = true } = options;

        if (!transition) {
            window.location.href = url;
            return;
        }

        // Create elements if not exists
        createTransitionElements();

        // Start preloading the target page immediately
        preloadPage(url);

        // Show logo immediately
        transitionLogo.classList.add('show');

        // Start panels entering
        pageTransition.classList.add('entering');

        // Navigate DURING the animation (not after) - feels instant
        setTimeout(() => {
            window.location.href = url;
        }, 200); // Navigate at 200ms - panels are mid-animation
    };

    // ==========    // Intercept clicks on links for smooth transition
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');

        // Ignore if no link, target blank, or non-http protocol
        if (!link || link.target === '_blank' || (link.href && !link.href.startsWith(window.location.origin))) {
            return;
        }

        // Ignore hash links on same page
        if (link.getAttribute('href').startsWith('#')) {
            return;
        }

        // Check for specific ignored paths or extensions
        const href = link.href;
        if (href.match(/\.(jpg|jpeg|png|gif|pdf|zip)$/i)) {
            return;
        }

        e.preventDefault();

        // Check if authentication is required
        const requiresAuth = link.getAttribute('data-requires-auth');
        if (requiresAuth && window.AuthStore && typeof window.AuthStore.me === 'function') {
            // Check auth status
            window.AuthStore.me().then(user => {
                if (user) {
                    // Logged in, proceed with transition
                    wcNavigate(href);
                } else {
                    // Not logged in, show warning modal
                    const profileModal = document.getElementById('profileModal');
                    if (profileModal) {
                        profileModal.classList.remove('hidden');

                        // Setup close handlers if not already set
                        if (!profileModal.hasAttribute('data-listeners-set')) {
                            const closeBtn = document.getElementById('closeProfile');
                            if (closeBtn) {
                                closeBtn.addEventListener('click', () => {
                                    profileModal.classList.add('hidden');
                                });
                            }
                            // Close when clicking outside
                            profileModal.addEventListener('click', (ev) => {
                                if (ev.target === profileModal || ev.target.querySelector('.modal-content-custom') === ev.target) {
                                    profileModal.classList.add('hidden');
                                }
                            });
                            profileModal.setAttribute('data-listeners-set', 'true');
                        }
                    } else {
                        // Fallback if modal missing
                        wcNavigate('login');
                    }
                }
            }).catch(() => {
                // Error checking auth, safe default is login
                wcNavigate('login');
            });
            return;
        }

        // Standard navigation
        wcNavigate(href);
    });


    // ========== Faster Response on Mousedown ==========
    document.addEventListener('mousedown', (e) => {
        if (e.button !== 0) return; // Only left click

        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('http')) return;
        if (link.target === '_blank' || link.hasAttribute('download')) return;

        // Preload immediately on mousedown for faster navigation
        preloadPage(href);
    });

    // ========== Handle Back/Forward Navigation ==========
    window.addEventListener('pageshow', (e) => {
        if (e.persisted) {
            // Page was restored from bfcache
            isNavigating = false;
            if (pageTransition) {
                pageTransition.classList.remove('entering', 'exiting');
            }
            if (transitionLogo) {
                transitionLogo.classList.remove('show');
            }
        }
    });

    // ========== Reset on Page Hide ==========
    window.addEventListener('pagehide', () => {
        isNavigating = false;
    });

})();
