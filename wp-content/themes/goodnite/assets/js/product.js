(() => {
  'use strict';
  const root = document.querySelector('[data-pdp]');
  const dataElement = document.getElementById('gn-pdp-data');
  if (!root || !dataElement) return;
  const data = JSON.parse(dataElement.textContent);
  const variants = data.variants || {};
  const state = { variant: data.defaultVariant, pillow: 'without', custom: null, image: 0 };
  const money = (value, decimals = 0) => new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: decimals, maximumFractionDigits: decimals }).format(value);
  const variantLabel = (variant) => `${variant.category} — ${variant.length_in} × ${variant.width_in} in`;
  const $ = (selector) => root.querySelector(selector);
  const priceBox = $('.gn-pdp-price');
  if (priceBox && !priceBox.querySelector('small')) {
    const taxNote = document.createElement('small');
    taxNote.textContent = 'Inclusive all taxes';
    priceBox.appendChild(taxNote);
  }

  // Keep PDP accordions mutually exclusive and animate both opening and closing.
  const accordionDetails = Array.from(root.querySelectorAll('.gn-pdp-panel__accordion, .gn-pdp-faq__item'));
  const animateAccordion = (detail, opening) => {
    const content = Array.from(detail.children).find((child) => child.tagName !== 'SUMMARY');
    if (!content) { detail.open = opening; return; }
    if (opening) {
      detail.open = true;
      content.style.overflow = 'hidden';
      content.style.height = '0px';
      const animation = content.animate(
        [{ height: '0px', opacity: 0 }, { height: `${content.scrollHeight}px`, opacity: 1 }],
        { duration: 260, easing: 'cubic-bezier(.2,.7,.2,1)' }
      );
      animation.onfinish = () => { content.style.height = ''; content.style.overflow = ''; };
      return;
    }
    const height = content.offsetHeight;
    content.style.overflow = 'hidden';
    const animation = content.animate(
      [{ height: `${height}px`, opacity: 1 }, { height: '0px', opacity: 0 }],
      { duration: 220, easing: 'cubic-bezier(.4,0,.2,1)' }
    );
    animation.onfinish = () => { detail.open = false; content.style.height = ''; content.style.overflow = ''; };
  };
  accordionDetails.forEach((detail) => detail.querySelector('summary')?.addEventListener('click', (event) => {
    event.preventDefault();
    if (detail.open) { animateAccordion(detail, false); return; }
    accordionDetails.forEach((other) => { if (other !== detail && other.open) animateAccordion(other, false); });
    animateAccordion(detail, true);
  }));

  function currentVariant() { return variants[state.variant] || null; }
  function refreshPrice() {
    const variant = currentVariant();
    const priceKey = `${state.variant}:${state.pillow}`;
    const amount = data.pricesApproved ? data.approvedPrices?.[priceKey] : variant && variant[state.pillow === 'with' ? 'reference_with_pt_inr' : 'reference_without_pt_inr'];
    const displayedAmount = state.custom?.amount ?? amount;
    const text = Number.isFinite(displayedAmount) && displayedAmount > 0 ? money(displayedAmount, state.custom ? 2 : 0) : 'Price unavailable';
    $('[data-price]').textContent = text;
    if (state.custom) $('[data-size-value]').textContent = `${state.custom.length} × ${state.custom.width} in · Custom`;
    else if (variant) $('[data-size-value]').textContent = variantLabel(variant);
    updateWhatsapp();
  }

  const trigger = $('[data-size-trigger]');
  const sizeDialog = $('[data-size-dialog]');
  const categoryButtons = Array.from(root.querySelectorAll('[data-size-category]'));
  const dimensionButtons = Array.from(root.querySelectorAll('[data-size-dimension]'));
  const pillowButtons = Array.from(root.querySelectorAll('[data-size-pillow-option]'));
  let draftVariant = state.variant;
  let draftPillow = state.pillow;
  function renderSizeDialog() {
    const variant = variants[draftVariant];
    const category = variant?.category || categoryButtons[0]?.dataset.sizeCategory;
    categoryButtons.forEach((button) => button.classList.toggle('is-selected', button.dataset.sizeCategory === category));
    dimensionButtons.forEach((button) => {
      const visible = button.dataset.sizeDimensionCategory === category;
      button.hidden = !visible;
      button.classList.toggle('is-selected', button.dataset.sizeDimension === draftVariant);
    });
    pillowButtons.forEach((button) => button.classList.toggle('is-selected', button.dataset.sizePillowOption === draftPillow));
  }
  function openSizeDialog() {
    draftVariant = state.variant;
    draftPillow = state.pillow;
    renderSizeDialog();
    sizeDialog.showModal();
  }
  function selectVariant(id, restoreFocus = false) {
    if (!variants[id]) return;
    state.variant = id;
    state.custom = null;
    $('[data-open-custom]')?.classList.remove('is-active');
    refreshPrice();
    if (restoreFocus) trigger.focus();
  }
  trigger.addEventListener('click', openSizeDialog);
  categoryButtons.forEach((button) => button.addEventListener('click', () => {
    const matching = Object.values(variants).find((variant) => variant.category === button.dataset.sizeCategory);
    if (matching) draftVariant = matching.id;
    renderSizeDialog();
  }));
  dimensionButtons.forEach((button) => button.addEventListener('click', () => {
    draftVariant = button.dataset.sizeDimension;
    renderSizeDialog();
  }));
  pillowButtons.forEach((button) => button.addEventListener('click', () => {
    draftPillow = button.dataset.sizePillowOption;
    renderSizeDialog();
  }));
  $('[data-size-confirm]').addEventListener('click', () => {
    state.pillow = draftPillow;
    selectVariant(draftVariant, true);
    sizeDialog.close();
  });
  root.querySelectorAll('input[name="pillow_top"]').forEach((input) => input.addEventListener('change', () => { if (input.checked) { state.pillow = input.value; refreshPrice(); } }));

  const dialogs = root.querySelectorAll('dialog');
  let dialogOpener = null;
  function openDialog(dialog, opener) {
    if (!dialog) return;
    dialogOpener = opener;
    dialog.showModal();
  }
  dialogs.forEach((dialog) => {
    dialog.querySelectorAll('[data-close-dialog]').forEach((button) => button.addEventListener('click', () => dialog.close()));
    dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('close', () => { if (dialogOpener && dialogOpener.isConnected) dialogOpener.focus(); dialogOpener = null; updateSticky(); });
  });
  $('[data-open-chart]')?.addEventListener('click', (event) => openDialog($('[data-chart-dialog]'), event.currentTarget));
  $('[data-open-custom]')?.addEventListener('click', (event) => openDialog($('[data-custom-dialog]'), event.currentTarget));
  $('[data-open-pillow]')?.addEventListener('click', (event) => openDialog($('[data-pillow-dialog]'), event.currentTarget));
  root.querySelectorAll('[data-open-store-locator]').forEach((button) => button.addEventListener('click', (event) => {
    const dialog = $('[data-store-locator-dialog]');
    const searchState = dialog?.querySelector('[data-store-search-state]');
    const results = dialog?.querySelector('[data-store-results]');
    const form = dialog?.querySelector('[data-store-form]');
    const input = form?.querySelector('input[name="q"]');
    if (!dialog || !searchState || !results) return;
    results.hidden = true;
    searchState.hidden = false;
    form?.reset();
    input?.removeAttribute('aria-invalid');
    dialog?.querySelector('[role="alert"]')?.setAttribute('hidden', '');
    openDialog(dialog, event.currentTarget);
  }));
  $('[data-open-help]')?.addEventListener('click', (event) => {
    const dialog = $('[data-help-dialog]');
    const form = dialog?.querySelector('[data-help-form]');
    form?.reset();
    dialog?.querySelector('[data-help-error]')?.setAttribute('hidden', '');
    dialog?.querySelector('[data-help-success]')?.setAttribute('hidden', '');
    dialog?.querySelectorAll('[data-help-form] input').forEach((input) => input.removeAttribute('aria-invalid'));
    dialog?.querySelector('[data-help-form] button[type="submit"]')?.removeAttribute('disabled');
    openDialog(dialog, event.currentTarget);
  });
  const helpForm = $('[data-help-form]');
  const helpName = helpForm?.elements.name;
  const helpMobile = helpForm?.elements.mobile;
  helpMobile?.addEventListener('input', () => {
    helpMobile.value = helpMobile.value.replace(/\D/g, '').slice(0, 10);
    helpMobile.removeAttribute('aria-invalid');
    helpForm.querySelector('[data-help-error]').hidden = true;
  });
  helpName?.addEventListener('input', () => {
    helpName.removeAttribute('aria-invalid');
    helpForm.querySelector('[data-help-error]').hidden = true;
  });
  helpForm?.addEventListener('submit', (event) => {
    event.preventDefault();
    const name = helpForm.elements.name.value.trim();
    const mobile = helpForm.elements.mobile.value.replace(/\D/g, '').slice(0, 10);
    helpForm.elements.mobile.value = mobile;
    const validName = name.length > 1;
    const validMobile = /^\d{10}$/.test(mobile);
    const valid = validName && validMobile;
    helpName.toggleAttribute('aria-invalid', !validName);
    helpMobile.toggleAttribute('aria-invalid', !validMobile);
    helpForm.querySelector('[data-help-error]').hidden = valid;
    if (!valid) return;
    helpForm.querySelector('[data-help-success]').hidden = false;
    helpForm.querySelector('button[type="submit"]').disabled = true;
  });
  root.querySelectorAll('[data-chart-size]').forEach((button) => button.addEventListener('click', () => {
    selectVariant(button.dataset.chartSize);
    if (button.dataset.chartPillow) state.pillow = button.dataset.chartPillow;
    refreshPrice();
    $('[data-chart-dialog]').close();
  }));

  const gallery = $('[data-gallery]');
  const galleryImage = $('[data-gallery-image]');
  const zoomImage = $('[data-zoom-image]');
  const thumbs = Array.from(root.querySelectorAll('[data-gallery-thumb]'));
  const indicatorDots = Array.from(root.querySelectorAll('[data-gallery-indicator-dot]'));
  function showImage(index) {
    if (!data.gallery.length) return;
    state.image = (index + data.gallery.length) % data.gallery.length;
    const image = data.gallery[state.image];
    galleryImage.src = `assets/${image.src}`;
    galleryImage.alt = image.alt;
    zoomImage.src = galleryImage.src;
    zoomImage.alt = image.alt;
    thumbs.forEach((thumb, i) => { thumb.classList.toggle('is-active', i === state.image); thumb.setAttribute('aria-pressed', String(i === state.image)); });
    indicatorDots.forEach((dot, i) => { dot.classList.toggle('is-active', i === state.image); dot.setAttribute('aria-selected', String(i === state.image)); });
  }
  thumbs.forEach((thumb) => thumb.addEventListener('click', () => showImage(Number(thumb.dataset.galleryThumb))));
  indicatorDots.forEach((dot) => dot.addEventListener('click', () => showImage(Number(dot.dataset.galleryIndicatorDot))));
  gallery.querySelector('[data-gallery-prev]')?.addEventListener('click', () => showImage(state.image - 1));
  gallery.querySelector('[data-gallery-next]')?.addEventListener('click', () => showImage(state.image + 1));
  $('[data-gallery-zoom]')?.addEventListener('click', (event) => openDialog($('[data-zoom-dialog]'), event.currentTarget));
  gallery.addEventListener('keydown', (event) => { if (event.key === 'ArrowLeft') showImage(state.image - 1); if (event.key === 'ArrowRight') showImage(state.image + 1); });
  let touchStart = null;
  gallery.addEventListener('touchstart', (event) => { touchStart = event.changedTouches[0]?.clientX ?? null; }, { passive: true });
  gallery.addEventListener('touchend', (event) => { if (touchStart === null) return; const delta = (event.changedTouches[0]?.clientX ?? touchStart) - touchStart; if (Math.abs(delta) > 35) showImage(state.image + (delta < 0 ? 1 : -1)); touchStart = null; }, { passive: true });

  function standardMessage() {
    const variant = currentVariant();
    return `Hello Goodnite, I'm interested in the ${data.name} mattress. My preferred size is ${variant ? `${variant.length_in} × ${variant.width_in} inches` : 'not yet selected'}, ${state.pillow === 'with' ? 'with' : 'without'} Pillow Top. Could you help me with the details?`;
  }
  function updateWhatsapp() {
    const link = $('[data-whatsapp]');
    if (link && data.whatsapp) link.href = `https://wa.me/${data.whatsapp}?text=${encodeURIComponent(standardMessage())}`;
  }

  const customForm = $('[data-custom-form]');
  const customDialog = $('[data-custom-dialog]');
  const customError = $('[data-custom-error]');
  const customLength = customForm.querySelector('input[name="length"]');
  const customWidth = customForm.querySelector('input[name="width"]');
  const customPrice = $('[data-custom-price]');
  const customSummary = $('[data-custom-summary]');
  const customSubmit = $('[data-custom-submit]');
  const customRates = data.custom || { rate_without_pt: 12.55, rate_with_pt: 13.26, preview_max_dimension_in: 240 };
  function customValues(showError = false) {
    const length = Number(customLength.value);
    const width = Number(customWidth.value);
    const selectedPillow = customForm.querySelector('input[name="custom_pillow"]:checked')?.value || 'without';
    const max = Number(customRates.preview_max_dimension_in) || 240;
    const rate = selectedPillow === 'with' ? Number(customRates.rate_with_pt) : Number(customRates.rate_without_pt);
    const valid = customLength.value.trim() !== '' && customWidth.value.trim() !== '' &&
      Number.isFinite(length) && Number.isFinite(width) && length > 0 && width > 0 && length <= max && width <= max;
    customError.hidden = true;
    const warning = (!customLength.value.trim() || !customWidth.value.trim())
      ? 'Enter dimensions to calculate'
      : `Enter positive dimensions no larger than ${max} inches for this preview.`;
    if (customSubmit) customSubmit.disabled = !valid;
    customPrice.classList.toggle('is-warning', !valid);
    customPrice.textContent = valid ? money(length * width * rate, 2) : warning;
    customSummary.textContent = valid ? `${length} × ${width} in · ${selectedPillow === 'with' ? 'With' : 'Without'} Pillow Top` : '';
    return valid ? { length, width, pillow: selectedPillow, amount: length * width * rate } : null;
  }
  ['input', 'change', 'keyup', 'blur'].forEach((eventName) => customForm.addEventListener(eventName, () => customValues()));
  customValues();
  // Keep the estimate in sync even when a browser autofill or number-stepper changes
  // the field value without emitting a normal input event.
  window.setInterval(() => { if (customDialog?.open) customValues(); }, 150);
  customForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const value = customValues(true);
    if (!value) return;
    state.custom = value;
    $('[data-open-custom]')?.classList.add('is-active');
    refreshPrice();
    customDialog.close();
  });

  const techGrid = $('.gn-pdp-tech__grid');
  const techControls = $('[data-tech-carousel-controls]');
  if (techGrid && techControls) {
    const techCards = Array.from(techGrid.children);
    techControls.innerHTML = '<button class="products-carousel-arrow products-carousel-prev" type="button" aria-label="Previous technology"><svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg></button><div class="products-carousel-dots" role="tablist" aria-label="Choose technology"></div><button class="products-carousel-arrow products-carousel-next" type="button" aria-label="Next technology"><svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg></button>';
    const dots = techControls.querySelector('.products-carousel-dots');
    techCards.forEach((card, index) => {
      const dot = document.createElement('button');
      dot.className = `products-carousel-dot${index === 0 ? ' is-active' : ''}`;
      dot.type = 'button';
      dot.setAttribute('aria-label', `Show technology ${index + 1}`);
      dot.addEventListener('click', () => techGrid.scrollTo({ left: card.offsetLeft, behavior: 'smooth' }));
      dots.appendChild(dot);
    });
    const updateTechDots = () => {
      let nearest = 0;
      let distance = Infinity;
      techCards.forEach((card, index) => {
        const nextDistance = Math.abs(card.offsetLeft - techGrid.scrollLeft);
        if (nextDistance < distance) { distance = nextDistance; nearest = index; }
      });
      Array.from(dots.children).forEach((dot, index) => dot.classList.toggle('is-active', index === nearest));
    };
    const moveTech = (direction) => {
      const current = techCards.findIndex((card) => Math.abs(card.offsetLeft - techGrid.scrollLeft) < card.offsetWidth * 0.5);
      const next = Math.max(0, Math.min(techCards.length - 1, current + direction));
      techGrid.scrollTo({ left: techCards[next].offsetLeft, behavior: 'smooth' });
    };
    techControls.querySelector('.products-carousel-prev').addEventListener('click', () => moveTech(-1));
    techControls.querySelector('.products-carousel-next').addEventListener('click', () => moveTech(1));
    techGrid.addEventListener('scroll', updateTechDots, { passive: true });
    updateTechDots();
  }

  const sticky = $('[data-sticky-bar]');
  let purchaseVisible = true;
  let footerVisible = false;
  function updateSticky() {
    sticky.hidden = window.innerWidth > 767 || purchaseVisible || footerVisible || !!root.querySelector('dialog[open]');
  }
  const purchaseObserver = new IntersectionObserver(([entry]) => { purchaseVisible = entry.isIntersecting; updateSticky(); });
  purchaseObserver.observe($('[data-purchase-area]'));
  const footer = document.querySelector('.site-footer');
  if (footer) new IntersectionObserver(([entry]) => { footerVisible = entry.isIntersecting; updateSticky(); }).observe(footer);
  window.addEventListener('resize', updateSticky);
  refreshPrice();
})();
