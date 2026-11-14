(function () {
  'use strict';

  function initCollectionListing() {
    var root = document.querySelector('[data-collection-listing]');
    var dataNode = document.getElementById('gn-collection-data');
    if (!root || !dataNode || root.dataset.ready === 'true') return;
    root.dataset.ready = 'true';

    var data;
    try { data = JSON.parse(dataNode.textContent || '{}'); } catch (error) { return; }

    var allProducts = Array.isArray(data.products) ? data.products.slice() : [];
    var banners = Array.isArray(data.banners) ? data.banners.slice() : [];
    var pageSize = Math.max(1, Number(data.page_size) || 8);
    var visibleLimit = pageSize;
    var filters = { collection: [], comfort: [], material: [], size: [], price: '' };
    var grid = root.querySelector('[data-product-grid]');
    var empty = root.querySelector('[data-product-empty]');
    var loadMoreWrap = root.querySelector('[data-load-more-wrap]');
    var loadMore = root.querySelector('[data-load-more]');
    var panel = document.querySelector('[data-filter-panel]');
    var openButton = document.querySelector('[data-filter-open]');
    var form = panel ? panel.querySelector('[data-filter-form]') : null;
    var applyButton = panel ? panel.querySelector('[data-filter-apply]') : null;
    var previousFocus = null;
    var mobileLayout = window.matchMedia('(max-width: 767px)').matches;

    function initFilterAccordions() {
      if (!form) return;
      form.querySelectorAll('fieldset').forEach(function (fieldset, index) {
        var legend = fieldset.querySelector('legend');
        if (!legend || legend.dataset.accordionReady === 'true') return;
        legend.dataset.accordionReady = 'true';
        legend.setAttribute('role', 'button');
        legend.setAttribute('tabindex', '0');
        legend.setAttribute('aria-expanded', index === 0 ? 'true' : 'false');
        if (index !== 0) fieldset.classList.add('is-collapsed');
        function toggle() {
          var collapsed = fieldset.classList.toggle('is-collapsed');
          legend.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        }
        legend.addEventListener('click', toggle);
        legend.addEventListener('keydown', function (event) {
          if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); toggle(); }
        });
      });
    }

    initFilterAccordions();

    function esc(value) {
      return String(value == null ? '' : value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function bagIcon() {
      return '<svg aria-hidden="true" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M16 10a4 4 0 0 1-8 0"/><path d="M3.103 6.034h17.794"/><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"/></svg>';
    }

    function arrowIcon() {
      return '<svg aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>';
    }

    function productMarkup(product) {
      return '<article class="product-card" data-product-card data-product-id="' + esc(product.id) + '">' +
        '<a class="focus-ring" href="' + esc(product.url) + '"><div class="product-image"><img src="' + esc(product.image_url) + '" alt="' + esc(product.alt) + '" width="1100" height="1375" loading="lazy" decoding="async"><span class="product-collection-overlay">' + esc(product.collection) + '</span></div></a>' +
        '<div class="product-info"><h3><a href="' + esc(product.url) + '">' + esc(product.name) + '</a></h3><span class="product-technology">' + esc(product.descriptor) + '</span><span class="product-price">' + esc(product.price_label) + '</span><button class="buy-now-link" type="button" disabled aria-label="Shopping action for ' + esc(product.name) + ' is not yet available" title="Shopping action coming soon">' + bagIcon() + '</button></div>' +
        '</article>';
    }

    function bannerMarkup(banner) {
      return '<article class="gn-plp-banner" data-editorial-banner><img src="' + esc(banner.image_url) + '" alt="' + esc(banner.alt) + '" width="1200" height="1500" loading="lazy" decoding="async"><div class="gn-plp-banner__overlay"></div><div class="gn-plp-banner__content"><h2>' + esc(banner.title) + '</h2></div></article>';
    }

    function inPriceRange(product) {
      if (!filters.price) return true;
      var price = Number(product.price);
      if (filters.price === 'under-50000') return price < 50000;
      if (filters.price === '50000-69999') return price >= 50000 && price < 70000;
      return price >= 70000;
    }

    function filteredProducts() {
      var products = allProducts.filter(function (product) {
        return (!filters.collection.length || filters.collection.indexOf(product.collection_slug) !== -1) &&
          (!filters.comfort.length || filters.comfort.indexOf(product.comfort) !== -1) &&
          (!filters.material.length || filters.material.indexOf(product.material) !== -1) &&
          (!filters.size.length || filters.size.some(function (size) { return (product.sizes || []).indexOf(size) !== -1; })) &&
          inPriceRange(product);
      });
      var selectedSort = form ? form.querySelector('input[name="sort"]:checked') : null;
      var mode = selectedSort ? selectedSort.value : 'featured';
      products.sort(function (a, b) {
        if (mode === 'best-selling') return Number(a.sales_rank) - Number(b.sales_rank);
        if (mode === 'newest') return Number(a.newest_order) - Number(b.newest_order);
        if (mode === 'price-asc') return Number(a.price) - Number(b.price);
        if (mode === 'price-desc') return Number(b.price) - Number(a.price);
        if (mode === 'name-asc') return String(a.name).localeCompare(String(b.name));
        return Number(a.featured_order) - Number(b.featured_order);
      });
      return products;
    }

    function activeFilterCount() {
      return filters.collection.length + filters.comfort.length + filters.material.length + filters.size.length + (filters.price ? 1 : 0);
    }

    function render() {
      var products = filteredProducts();
      var visible = products.slice(0, visibleLimit);
      var allowBanners = activeFilterCount() === 0 && products.length >= 4;
      var html = '';
      visible.forEach(function (product, index) {
        html += productMarkup(product);
        if (!allowBanners) return;
        banners.forEach(function (banner) {
          var position = mobileLayout ? Number(banner.after_mobile || banner.after) : Number(banner.after);
          if (position === index + 1) html += bannerMarkup(banner);
        });
      });
      grid.innerHTML = html;
      empty.hidden = products.length !== 0;
      loadMoreWrap.hidden = visible.length >= products.length;
      if (openButton) {
        var active = activeFilterCount();
        openButton.firstChild.nodeValue = active ? 'Sort & Filter (' + active + ') ' : 'Sort & Filter ';
      }
    }

    function collectFilters() {
      ['collection', 'comfort', 'material', 'size'].forEach(function (name) {
        filters[name] = Array.prototype.slice.call(form.querySelectorAll('input[name="' + name + '"]:checked')).map(function (input) { return input.value; });
      });
      var selectedPrice = form.querySelector('input[name="price"]:checked');
      filters.price = selectedPrice ? selectedPrice.value : '';
    }

    function setPanel(open) {
      if (!panel || !openButton) return;
      panel.hidden = !open;
      openButton.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.classList.toggle('gn-filter-open', open);
      if (open) {
        previousFocus = document.activeElement;
        window.requestAnimationFrame(function () { var first = panel.querySelector('input, button'); if (first) first.focus(); });
      } else if (previousFocus && previousFocus.focus) previousFocus.focus();
    }

    if (form) form.querySelectorAll('input[name="sort"]').forEach(function (input) {
      input.addEventListener('change', function () { visibleLimit = pageSize; render(); });
    });
    if (loadMore) loadMore.addEventListener('click', function () { visibleLimit += pageSize; render(); });

    if (panel && openButton && form) {
      openButton.addEventListener('click', function () { setPanel(true); });
      panel.querySelectorAll('[data-filter-close]').forEach(function (button) { button.addEventListener('click', function () { setPanel(false); }); });
      if (applyButton) applyButton.addEventListener('click', function () { collectFilters(); visibleLimit = pageSize; render(); setPanel(false); });
      form.addEventListener('reset', function () { window.setTimeout(function () { filters = { collection: [], comfort: [], material: [], size: [], price: '' }; visibleLimit = pageSize; render(); }, 0); });
      panel.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { setPanel(false); return; }
        if (event.key !== 'Tab') return;
        var focusable = Array.prototype.slice.call(panel.querySelectorAll('button:not([disabled]), input:not([disabled]), select:not([disabled]), a[href]'));
        if (!focusable.length) return;
        var first = focusable[0], last = focusable[focusable.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
      });
    }

    window.addEventListener('resize', function () {
      var nextMobile = window.matchMedia('(max-width: 767px)').matches;
      if (nextMobile !== mobileLayout) { mobileLayout = nextMobile; render(); }
    });
    render();
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initCollectionListing);
  else initCollectionListing();
})();
