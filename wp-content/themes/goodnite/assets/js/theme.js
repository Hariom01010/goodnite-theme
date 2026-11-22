/**
 * Goodnite India — Production Interaction Script
 *
 * Architecture:
 * 1. Sticky Header
 * 2. Mega Menu Navigation
 * 3. Accessible Mobile Navigation Drawer
 * 4. Hero Background Gallery Slider & Touch Swipe
 * 5. Live Search Modal Dialog
 * 6. Interactive Sleep Finder (2-Preference Selection)
 * 7. Technology Pillars Indicators
 * 8. Product Carousel Controls & Indicators
 * 9. Animated Metric Counters
 * 10. Store Locator Pincode Validation & Dealer Preview
 * 11. Viewport Reveals
 */

(() => {
  'use strict';

  const doc = document;
  const root = doc.documentElement;
  root.classList.add('motion-ready');

  const syncResponsiveTypeScale = () => {
    const width = window.innerWidth;
    const scale = width >= 1440
      ? [76, 64, 56, 48, 36, 28, 22, 20, 13, 13, 64]
      : width >= 1024
        ? [68, 58, 52, 44, 34, 26, 22, 19, 13, 12, 56]
        : width >= 768
          ? [56, 48, 44, 38, 30, 24, 20, 18, 12, 12, 48]
          : [44, 40, 36, 32, 26, 22, 20, 18, 12, 11, 40];
    const names = ['display-xl', 'display-l', 'h1', 'h2', 'h3', 'h4', 'h5', 'body-large', 'caption', 'eyebrow', 'metric-xl'];
    names.forEach((name, index) => root.style.setProperty(`--type-${name}`, `${scale[index]}px`));
  };
  let typeScaleFrame = 0;
  const requestTypeScaleSync = () => {
    window.cancelAnimationFrame(typeScaleFrame);
    typeScaleFrame = window.requestAnimationFrame(syncResponsiveTypeScale);
  };
  syncResponsiveTypeScale();
  window.addEventListener('resize', requestTypeScaleSync, { passive: true });
  window.addEventListener('orientationchange', requestTypeScaleSync, { passive: true });
  window.visualViewport?.addEventListener('resize', requestTypeScaleSync, { passive: true });

  // ==========================================================================
  // 1. Sticky Header
  // ==========================================================================
  const initStickyHeader = () => {
    const header = document.querySelector('[data-site-header]');
    if (!header) return;

    const onScroll = () => {
      header.classList.toggle('is-compact', window.scrollY > 40);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  };

  // ==========================================================================
  // 2. Mega Menu Navigation
  // ==========================================================================
  const initMegaMenu = () => {
    const trigger = document.querySelector('[data-mega-trigger]');
    const menu = document.querySelector('[data-mega-menu]');
    if (!trigger || !menu) return;

    let hoverTimer = null;

    const setMegaState = (isOpen) => {
      trigger.setAttribute('aria-expanded', String(isOpen));
      menu.hidden = !isOpen;
    };

    trigger.addEventListener('click', () => {
      const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
      setMegaState(!isExpanded);
    });

    const isDesktop = () => window.matchMedia('(hover: hover) and (min-width: 1024px)').matches;

    trigger.addEventListener('mouseenter', () => {
      if (!isDesktop()) return;
      window.clearTimeout(hoverTimer);
      setMegaState(true);
    });

    menu.addEventListener('mouseenter', () => {
      if (!isDesktop()) return;
      window.clearTimeout(hoverTimer);
    });

    const scheduleClose = () => {
      if (!isDesktop()) return;
      window.clearTimeout(hoverTimer);
      hoverTimer = window.setTimeout(() => setMegaState(false), 140);
    };

    trigger.addEventListener('mouseleave', scheduleClose);
    menu.addEventListener('mouseleave', scheduleClose);

    document.addEventListener('click', (event) => {
      if (!trigger.contains(event.target) && !menu.contains(event.target)) {
        setMegaState(false);
      }
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !menu.hidden) {
        setMegaState(false);
        trigger.focus();
      }
    });
  };

  // ==========================================================================
  // 3. Accessible Mobile Navigation Drawer
  // ==========================================================================
  const initMobileDrawer = () => {
    const openBtn = document.querySelector('[data-mobile-open]');
    const overlay = document.querySelector('[data-mobile-overlay]');
    if (!openBtn || !overlay) return;

    const closeBtn = overlay.querySelector('[data-mobile-close]');
    const sheet = overlay.querySelector('.mobile-sheet');
    let previousActiveElement = null;

    const setDrawer = (isOpen) => {
      openBtn.setAttribute('aria-expanded', String(isOpen));
      overlay.hidden = !isOpen;
      document.body.classList.toggle('navigation-open', isOpen);

      if (isOpen) {
        previousActiveElement = document.activeElement;
        window.requestAnimationFrame(() => closeBtn?.focus());
      } else if (previousActiveElement instanceof HTMLElement) {
        previousActiveElement.focus();
      }
    };

    openBtn.addEventListener('click', () => setDrawer(true));
    closeBtn?.addEventListener('click', () => setDrawer(false));

    overlay.addEventListener('click', (event) => {
      if (event.target === overlay) setDrawer(false);
    });

    sheet?.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        setDrawer(false);
        return;
      }

      if (event.key === 'Tab') {
        const focusable = sheet.querySelectorAll('a[href], button:not([disabled]), summary, [tabindex]:not([tabindex="-1"])');
        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        if (event.shiftKey && document.activeElement === first) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      }
    });
  };

  // ==========================================================================
  // 4. Hero Background Gallery Slider & Touch Swipe
  // ==========================================================================
  const initHeroSlider = () => {
    const hero = document.querySelector('[data-hero-slider]');
    if (!hero) return;

    const slides = Array.from(hero.querySelectorAll('.hero-image-slider__slide'));
    const dots = Array.from(hero.querySelectorAll('.hero-slider-dot'));
    const prevBtn = hero.querySelector('[data-hero-prev]');
    const nextBtn = hero.querySelector('[data-hero-next]');

    if (slides.length <= 1) return;

    let currentIndex = 0;
    let timer = null;

    const goTo = (index) => {
      currentIndex = (index + slides.length) % slides.length;

      slides.forEach((slide, i) => {
        slide.classList.toggle('is-active', i === currentIndex);
      });

      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === currentIndex);
        dot.setAttribute('aria-selected', String(i === currentIndex));
      });
    };

    const startAutoplay = () => {
      stopAutoplay();
      timer = window.setInterval(() => goTo(currentIndex + 1), 6000);
    };

    const stopAutoplay = () => {
      if (timer) window.clearInterval(timer);
    };

    prevBtn?.addEventListener('click', () => {
      goTo(currentIndex - 1);
      startAutoplay();
    });

    nextBtn?.addEventListener('click', () => {
      goTo(currentIndex + 1);
      startAutoplay();
    });

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        goTo(index);
        startAutoplay();
      });
    });

    hero.addEventListener('mouseenter', stopAutoplay);
    hero.addEventListener('mouseleave', startAutoplay);

    // Touch Swipe
    let touchStartX = 0;
    let touchStartY = 0;

    hero.addEventListener('touchstart', (e) => {
      if (!e.touches.length) return;
      touchStartX = e.touches[0].clientX;
      touchStartY = e.touches[0].clientY;
    }, { passive: true });

    hero.addEventListener('touchend', (e) => {
      if (!e.changedTouches.length) return;
      const dx = e.changedTouches[0].clientX - touchStartX;
      const dy = e.changedTouches[0].clientY - touchStartY;

      if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
        goTo(dx < 0 ? currentIndex + 1 : currentIndex - 1);
        startAutoplay();
      }
    }, { passive: true });

    startAutoplay();
  };

  // ==========================================================================
  // 5. Live Search Modal Dialog
  // ==========================================================================
  const initSearchModal = () => {
    const panel = document.querySelector('[data-search-panel]');
    const openBtn = document.querySelector('[data-search-open]');
    const input = document.querySelector('[data-search-input]');
    if (!panel || !openBtn || !input) return;

    let searchIndex = [];
    try {
      const dataScript = document.getElementById('goodnite-search-index');
      if (dataScript) {
        searchIndex = JSON.parse(dataScript.textContent || '[]');
      }
    } catch {
      searchIndex = [];
    }

    const initialContainer = panel.querySelector('[data-search-initial]');
    const resultsContainer = panel.querySelector('[data-search-results]');
    const emptyContainer = panel.querySelector('[data-search-empty]');
    const clearBtn = panel.querySelector('[data-search-clear]');
    let lastActiveElement = null;

    const normalize = (val) => String(val || '').toLowerCase().replace(/[®™]/g, '').replace(/\s+/g, ' ').trim();

    const scoreItem = (item, query) => {
      const q = normalize(query);
      const title = normalize(item.title);
      const haystack = normalize([item.description || '', ...(item.keywords || []), ...(item.aliases || [])].join(' '));

      if (title === q) return 1000;
      if (title.startsWith(q)) return 850;
      if (title.includes(q)) return 700;
      if (haystack.includes(q)) return 450;

      const tokens = q.split(' ');
      let score = 0;
      tokens.forEach((token) => {
        if (token.length > 1 && (title.includes(token) || haystack.includes(token))) {
          score += 100;
        }
      });
      return score;
    };

    const performSearch = (query) => {
      const q = normalize(query);
      if (!q) {
        if (initialContainer) initialContainer.hidden = false;
        if (resultsContainer) {
          resultsContainer.hidden = true;
          resultsContainer.innerHTML = '';
        }
        if (emptyContainer) emptyContainer.hidden = true;
        if (clearBtn) clearBtn.hidden = true;
        return;
      }

      if (initialContainer) initialContainer.hidden = true;
      if (clearBtn) clearBtn.hidden = false;

      const scored = searchIndex
        .map((item) => ({ item, score: scoreItem(item, q) }))
        .filter((entry) => entry.score > 0)
        .sort((a, b) => b.score - a.score || a.item.title.localeCompare(b.item.title))
        .map((entry) => entry.item);

      if (!scored.length) {
        if (resultsContainer) {
          resultsContainer.hidden = true;
          resultsContainer.innerHTML = '';
        }
        if (emptyContainer) emptyContainer.hidden = false;
        return;
      }

      if (emptyContainer) emptyContainer.hidden = true;
      if (resultsContainer) {
        resultsContainer.hidden = false;
        resultsContainer.innerHTML = '';

        const groups = {
          product: 'Products',
          collection: 'Collections',
          technology: 'Technologies',
        };

        Object.keys(groups).forEach((type) => {
          const items = scored.filter((i) => i.type === type);
          if (!items.length) return;

          const groupEl = document.createElement('section');
          groupEl.className = 'gn-search-group';

          const titleEl = document.createElement('h3');
          titleEl.textContent = groups[type];
          groupEl.appendChild(titleEl);

          items.slice(0, 5).forEach((item) => {
            const card = document.createElement('a');
            card.className = 'gn-search-result focus-ring';
            card.href = item.url;
            card.innerHTML = `
              <span class="gn-search-result__body">
                <strong>${item.title}</strong>
                <small>${item.description}</small>
              </span>
              <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
            `;
            groupEl.appendChild(card);
          });

          resultsContainer.appendChild(groupEl);
        });

        if (scored.length > 10) {
          const moreLink = document.createElement('a');
          moreLink.className = 'gn-search-more';
          moreLink.href = `/search.php?q=${encodeURIComponent(query)}`;
          moreLink.textContent = `View All Results for "${query}" →`;
          resultsContainer.appendChild(moreLink);
        }
      }
    };

    const setModalState = (isOpen) => {
      panel.hidden = !isOpen;
      openBtn.setAttribute('aria-expanded', String(isOpen));
      document.body.classList.toggle('search-is-open', isOpen);

      if (isOpen) {
        lastActiveElement = document.activeElement;
        const header = document.querySelector('[data-site-header]');
        if (header) {
          panel.style.setProperty('--search-top', `${Math.round(header.getBoundingClientRect().bottom)}px`);
        }
        window.setTimeout(() => input.focus(), 30);
      } else {
        input.value = '';
        performSearch('');
        if (lastActiveElement instanceof HTMLElement) {
          lastActiveElement.focus();
        }
      }
    };

    openBtn.addEventListener('click', () => setModalState(true));
    panel.querySelectorAll('[data-search-close]').forEach((btn) => {
      btn.addEventListener('click', () => setModalState(false));
    });

    input.addEventListener('input', () => performSearch(input.value));

    clearBtn?.addEventListener('click', () => {
      input.value = '';
      performSearch('');
      input.focus();
    });

    panel.querySelectorAll('[data-search-suggestion]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const val = btn.dataset.searchSuggestion || '';
        input.value = val;
        performSearch(val);
        input.focus();
      });
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !panel.hidden) {
        setModalState(false);
      }
    });
  };

  // ==========================================================================
  // 6. Interactive Sleep Finder (2-Preference Selection with WooCommerce Matching)
  // ==========================================================================
  const initSleepFinder = () => {
    const finder = document.querySelector('[data-sleep-finder]');
    if (!finder) return;

    const buttons = Array.from(finder.querySelectorAll('.gn-sleep-finder__option'));
    const limitStatus = finder.querySelector('.gn-sleep-finder__limit');
    const cta = finder.querySelector('[data-finder-cta]');
    const defaultLabel = finder.dataset.defaultCta || 'Find My Sleep Match';
    let combinations = {};
    try {
      combinations = JSON.parse(finder.dataset.combinations || '{}');
    } catch (e) {
      combinations = {};
    }
    const selected = [];

    const updateCTA = () => {
      if (!cta) return;
      const base = cta.dataset.baseUrl || cta.getAttribute('href')?.split('?')[0] || '/find-your-mattress';

      if (selected.length === 2) {
        const sortedKey = [...selected].sort().join('+');
        if (combinations[sortedKey]) {
          cta.setAttribute('href', combinations[sortedKey].url);
          cta.innerHTML = `${combinations[sortedKey].label} <span aria-hidden="true">→</span>`;
          return;
        }
      }

      if (selected.length === 1) {
        const activeBtn = buttons.find((b) => b.dataset.preference === selected[0]);
        const prodUrl = activeBtn?.dataset.productUrl;
        if (prodUrl) {
          cta.setAttribute('href', prodUrl);
          cta.innerHTML = `${defaultLabel} <span aria-hidden="true">→</span>`;
          return;
        }
      }

      const url = new URL(base, window.location.origin);
      if (selected.length) {
        url.searchParams.set('preferences', selected.join(','));
      }
      cta.setAttribute('href', url.pathname + url.search);
      cta.innerHTML = `${defaultLabel} <span aria-hidden="true">→</span>`;
    };

    buttons.forEach((btn) => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.preference;
        if (!id) return;

        const idx = selected.indexOf(id);
        if (idx > -1) {
          selected.splice(idx, 1);
          btn.setAttribute('aria-pressed', 'false');
          if (limitStatus) limitStatus.textContent = '';
        } else if (selected.length < 2) {
          selected.push(id);
          btn.setAttribute('aria-pressed', 'true');
          if (limitStatus) limitStatus.textContent = '';
        } else {
          if (limitStatus) limitStatus.textContent = 'Choose up to two preferences.';
          return;
        }

        updateCTA();
      });
    });
  };

  // ==========================================================================
  // 7. Technology Pillars Indicators
  // ==========================================================================
  const initTechPillars = () => {
    const grid = document.querySelector('[data-tech-grid]');
    const indicatorsContainer = document.querySelector('[data-tech-indicators]');
    if (!grid || !indicatorsContainer) return;

    const cards = Array.from(grid.querySelectorAll('.gn-tech-pillars__card'));
    const dots = Array.from(indicatorsContainer.querySelectorAll('[data-tech-indicator]'));

    const onScroll = () => {
      let nearest = 0;
      let minDistance = Infinity;

      cards.forEach((card, i) => {
        const dist = Math.abs(card.offsetLeft - grid.scrollLeft);
        if (dist < minDistance) {
          minDistance = dist;
          nearest = i;
        }
      });

      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === nearest);
        dot.setAttribute('aria-selected', String(i === nearest));
      });
    };

    dots.forEach((dot) => {
      dot.addEventListener('click', () => {
        const idx = Number(dot.dataset.techIndicator);
        const card = cards[idx];
        if (card) {
          grid.scrollTo({ left: card.offsetLeft, behavior: 'smooth' });
        }
      });
    });

    grid.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  };

  // ==========================================================================
  // 8. Product Carousel Controls & Indicators
  // ==========================================================================
  const initProductCarousel = () => {
    const container = document.querySelector('[data-products-carousel]');
    if (!container) return;

    const grid = container.querySelector('[data-product-grid]');
    const prevBtn = container.querySelector('[data-product-prev]');
    const nextBtn = container.querySelector('[data-product-next]');
    const dots = Array.from(container.querySelectorAll('[data-product-dot]'));
    if (!grid) return;

    const cards = Array.from(grid.children);

    const onScroll = () => {
      let nearest = 0;
      let minDistance = Infinity;

      cards.forEach((card, i) => {
        const dist = Math.abs(card.offsetLeft - grid.scrollLeft);
        if (dist < minDistance) {
          minDistance = dist;
          nearest = i;
        }
      });

      dots.forEach((dot, i) => {
        dot.classList.toggle('is-active', i === nearest);
      });
    };

    const scrollDirection = (step) => {
      const current = cards.findIndex(
        (card) => Math.abs(card.offsetLeft - grid.scrollLeft) < card.offsetWidth * 0.5
      );
      const targetIndex = Math.max(0, Math.min(cards.length - 1, current + step));
      grid.scrollTo({ left: cards[targetIndex].offsetLeft, behavior: 'smooth' });
    };

    prevBtn?.addEventListener('click', () => scrollDirection(-1));
    nextBtn?.addEventListener('click', () => scrollDirection(1));

    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => {
        if (cards[i]) {
          grid.scrollTo({ left: cards[i].offsetLeft, behavior: 'smooth' });
        }
      });
    });

    grid.addEventListener('scroll', onScroll, { passive: true });
  };

  // ==========================================================================
  // 9. Animated Metric Counters
  // ==========================================================================
  const initMetricCounters = () => {
    const targets = Array.from(document.querySelectorAll('[data-counter-target]'));
    if (!targets.length) return;

    let hasRun = false;

    const runCountUp = () => {
      if (hasRun) return;
      hasRun = true;

      targets.forEach((el) => {
        const targetNum = Number(el.dataset.counterTarget) || 0;
        const duration = 1600;
        const startTime = performance.now();

        const tick = (currentTime) => {
          const progress = Math.min((currentTime - startTime) / duration, 1);
          const currentVal = Math.floor(progress * targetNum);
          el.textContent = currentVal.toLocaleString('en-IN');

          if (progress < 1) {
            requestAnimationFrame(tick);
          } else {
            el.textContent = targetNum.toLocaleString('en-IN');
          }
        };

        requestAnimationFrame(tick);
      });
    };

    const metricsBlock = document.querySelector('[data-metrics-block]');
    if (metricsBlock && 'IntersectionObserver' in window) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              runCountUp();
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.3 }
      );
      observer.observe(metricsBlock);
    } else {
      runCountUp();
    }
  };

  // ==========================================================================
  // 10. Store Locator Pincode Validation & Dealer Preview
  // ==========================================================================
  const initStoreLocator = () => {
    const form = document.querySelector('[data-store-form]');
    if (!form) return;

    const input = form.querySelector('#store-query');
    const errorMsg = form.querySelector('#store-error');
    const searchState = form.closest('.locator-copy')?.querySelector('[data-store-search-state]');
    const resultsPanel = form.closest('.locator-copy')?.querySelector('[data-store-results]');
    const resultsGrid = resultsPanel?.querySelector('[data-store-results-grid]');
    const pincodeLabel = resultsPanel?.querySelector('[data-store-pincode]');
    const resetBtn = resultsPanel?.querySelector('[data-store-reset]');

    const cityFor = (pin) => {
      const prefix = pin.slice(0, 2);
      const cities = {
        '11': 'Delhi',
        '40': 'Mumbai',
        '50': 'Hyderabad',
        '56': 'Bengaluru',
        '60': 'Chennai',
        '70': 'Kolkata',
      };
      return cities[prefix] || 'your area';
    };

    input?.addEventListener('input', () => {
      input.value = input.value.replace(/\D/g, '').slice(0, 6);
      if (errorMsg) errorMsg.hidden = true;
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const pin = input?.value.trim() || '';

      if (!/^\d{6}$/.test(pin)) {
        if (errorMsg) errorMsg.hidden = false;
        input?.focus();
        return;
      }

      if (pincodeLabel) pincodeLabel.textContent = pin;
      if (searchState) searchState.hidden = true;
      if (resultsPanel) resultsPanel.hidden = false;

      if (resultsGrid) {
        resultsGrid.innerHTML = `
          <div class="locator-loading" style="padding: 24px; text-align: center; color: var(--color-ink-muted, #666);">
            <span>Searching authorized dealers for ${pin}...</span>
          </div>
        `;

        try {
          // Query the official Goodnite Store Locator REST API endpoint
          const apiUrl = `/wp-json/goodnite/v1/stores/search?pincode=${encodeURIComponent(pin)}`;
          const response = await fetch(apiUrl);
          const data = await response.json();

          if (data && data.success && data.dealers && data.dealers.length > 0) {
            resultsGrid.innerHTML = data.dealers.map((dealer) => `
              <article class="locator-dealer-card" style="border: 1px solid rgba(0,0,0,0.08); padding: 20px; border-radius: 8px; margin-bottom: 16px; background: #fff;">
                <h4 style="margin: 0 0 10px; font-size: 1.15rem; color: var(--color-ink, #111);">${dealer.name}</h4>
                <ul style="list-style: none; padding: 0; margin: 0 0 14px; font-size: 0.95rem; line-height: 1.6;">
                  <li style="display: flex; gap: 8px; margin-bottom: 6px;">
                    <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                    <span>${dealer.address}, ${dealer.city} - ${dealer.pincode}</span>
                  </li>
                  ${dealer.phone ? `
                    <li style="display: flex; gap: 8px; margin-bottom: 6px;">
                      <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"/></svg>
                      <a href="${dealer.phone_url}" style="color: inherit; text-decoration: underline;">${dealer.phone}</a>
                    </li>
                  ` : ''}
                  ${dealer.timings ? `
                    <li style="display: flex; gap: 8px; color: #666;">
                      <svg viewBox="0 0 24 24" aria-hidden="true" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                      <span>${dealer.timings}</span>
                    </li>
                  ` : ''}
                </ul>
                ${dealer.google_maps_link ? `
                  <a href="${dealer.google_maps_link}" target="_blank" rel="noopener noreferrer" class="button-link text focus-ring" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.9rem; font-weight: 600;">
                    Get Directions →
                  </a>
                ` : ''}
              </article>
            `).join('');
          } else {
            resultsGrid.innerHTML = `
              <div class="locator-empty" style="padding: 24px; text-align: center; border: 1px dashed rgba(0,0,0,0.15); border-radius: 8px; background: #fafafa;">
                <p style="margin: 0 0 8px; font-weight: 500;">No authorized store found directly in pincode <strong>${pin}</strong>.</p>
                <p style="margin: 0; font-size: 0.9rem; color: #666;">Try searching your city's central pincode or contact our concierge.</p>
              </div>
            `;
          }
        } catch (err) {
          // Fallback if plugin API is not active or returns network error
          const cityName = cityFor(pin);
          resultsGrid.innerHTML = `
            <article class="locator-dealer-card">
              <h4>Goodnite Experience Partner</h4>
              <ul>
                <li><span>${cityName} Region, ${pin}</span></li>
                <li><span>Call Store Concierge: +91 1800-GOODNITE</span></li>
              </ul>
            </article>
          `;
        }
      }
    });

    resetBtn?.addEventListener('click', () => {
      if (resultsPanel) resultsPanel.hidden = true;
      if (searchState) searchState.hidden = false;
      form.reset();
      window.requestAnimationFrame(() => input?.focus());
    });
  };

  // ==========================================================================
  // 11. Viewport Reveals
  // ==========================================================================
  const initReveals = () => {
    const nodes = document.querySelectorAll('.reveal');
    if (!nodes.length) return;

    if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.15 }
      );
      nodes.forEach((node) => observer.observe(node));
    } else {
      nodes.forEach((node) => node.classList.add('is-visible'));
    }
  };

  // ==========================================================================
  // DOM Ready Orchestrator
  // ==========================================================================
  const onReady = () => {
    initStickyHeader();
    initMegaMenu();
    initMobileDrawer();
    initHeroSlider();
    initSearchModal();
    initSleepFinder();
    initTechPillars();
    initProductCarousel();
    initMetricCounters();
    initStoreLocator();
    initReveals();
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', onReady);
  } else {
    onReady();
  }
})();
