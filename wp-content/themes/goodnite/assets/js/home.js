(() => {
  "use strict";

  const doc = document;
  const root = doc.documentElement;
  root.classList.add("motion-ready");

  const syncResponsiveTypeScale = () => {
    const width = window.innerWidth;
    const scale = width >= 1440
      ? [64, 56, 48, 40, 30, 24, 20, 18, 12, 12, 52]
      : width >= 1024
        ? [58, 50, 44, 38, 28, 23, 20, 18, 12, 12, 48]
        : width >= 768
          ? [48, 42, 38, 32, 26, 22, 18, 17, 12, 11, 42]
          : [38, 34, 32, 28, 24, 20, 18, 16, 12, 11, 36];
    const names = ["display-xl", "display-l", "h1", "h2", "h3", "h4", "h5", "body-large", "caption", "metric-xl"];
    names.forEach((name, index) => root.style.setProperty(`--type-${name}`, `${scale[index]}px`));
  };
  let typeScaleFrame = 0;
  const requestTypeScaleSync = () => {
    window.cancelAnimationFrame(typeScaleFrame);
    typeScaleFrame = window.requestAnimationFrame(syncResponsiveTypeScale);
  };
  syncResponsiveTypeScale();
  window.addEventListener("resize", requestTypeScaleSync, { passive: true });
  window.addEventListener("orientationchange", requestTypeScaleSync, { passive: true });
  window.visualViewport?.addEventListener("resize", requestTypeScaleSync, { passive: true });

  const header = doc.querySelector("[data-site-header]");
  if (header) {
    const syncHeader = () => header.classList.toggle("is-compact", window.scrollY > 56);
    syncHeader();
    window.addEventListener("scroll", syncHeader, { passive: true });
  }

  const megaTrigger = doc.querySelector("[data-mega-trigger]");
  const megaMenu = doc.querySelector("[data-mega-menu]");
  const setMega = (open) => {
    if (!megaTrigger || !megaMenu) return;
    megaTrigger.setAttribute("aria-expanded", String(open));
    megaMenu.hidden = !open;
  };
  megaTrigger?.addEventListener("click", () => setMega(megaTrigger.getAttribute("aria-expanded") !== "true"));
  // Reveal the mattress menu on pointer hover, while retaining click and
  // keyboard support for touch devices and keyboard users.
  let megaHoverTimer;
  const scheduleMegaClose = () => {
    window.clearTimeout(megaHoverTimer);
    megaHoverTimer = window.setTimeout(() => setMega(false), 120);
  };
  const cancelMegaClose = () => window.clearTimeout(megaHoverTimer);
  megaTrigger?.addEventListener("mouseenter", () => {
    if (window.matchMedia("(hover: hover) and (min-width: 1024px)").matches) {
      cancelMegaClose();
      setMega(true);
    }
  });
  megaMenu?.addEventListener("mouseenter", cancelMegaClose);
  megaTrigger?.addEventListener("mouseleave", scheduleMegaClose);
  megaMenu?.addEventListener("mouseleave", scheduleMegaClose);
  doc.addEventListener("click", (event) => {
    if (header && !header.contains(event.target)) setMega(false);
  });

  const mobileOpen = doc.querySelector("[data-mobile-open]");
  const mobileOverlay = doc.querySelector("[data-mobile-overlay]");
  const mobileSheet = mobileOverlay?.querySelector(".mobile-sheet");
  const mobileClose = doc.querySelector("[data-mobile-close]");
  let returnFocus = null;

  const setMobile = (open) => {
    if (!mobileOpen || !mobileOverlay) return;
    mobileOpen.setAttribute("aria-expanded", String(open));
    mobileOverlay.hidden = !open;
    doc.body.classList.toggle("navigation-open", open);
    if (open) {
      returnFocus = doc.activeElement;
      window.requestAnimationFrame(() => mobileClose?.focus());
    } else if (returnFocus instanceof HTMLElement) {
      returnFocus.focus();
    }
  };
  mobileOpen?.addEventListener("click", () => setMobile(true));
  mobileClose?.addEventListener("click", () => setMobile(false));
  mobileOverlay?.addEventListener("mousedown", (event) => {
    if (event.target === mobileOverlay) setMobile(false);
  });
  mobileOverlay?.querySelectorAll("a").forEach((link) => link.addEventListener("click", () => setMobile(false)));
  mobileSheet?.addEventListener("keydown", (event) => {
    if (event.key !== "Tab") return;
    const focusable = [...mobileSheet.querySelectorAll('a[href], button:not([disabled]), summary, [tabindex]:not([tabindex="-1"])')];
    if (!focusable.length) return;
    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    if (event.shiftKey && doc.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && doc.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  });

  doc.addEventListener("keydown", (event) => {
    if (event.key !== "Escape") return;
    setMega(false);
    if (mobileOverlay && !mobileOverlay.hidden) setMobile(false);
  });

  const revealNodes = doc.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window && !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.16 });
    revealNodes.forEach((node) => revealObserver.observe(node));
  } else {
    revealNodes.forEach((node) => node.classList.add("is-visible"));
  }

  const finderLink = doc.querySelector("[data-finder-link]");
  doc.querySelectorAll("[data-preference-grid] button").forEach((button) => {
    button.addEventListener("click", () => {
      const wasSelected = button.getAttribute("aria-pressed") === "true";
      doc.querySelectorAll("[data-preference-grid] button").forEach((item) => item.setAttribute("aria-pressed", "false"));
      button.setAttribute("aria-pressed", String(!wasSelected));
      if (!finderLink) return;
      const base = finderLink.dataset.baseUrl || finderLink.getAttribute("href") || "/";
      const url = new URL(base, window.location.origin);
      if (!wasSelected) url.searchParams.set("preference", button.dataset.preference || "");
      else url.searchParams.delete("preference");
      finderLink.setAttribute("href", url.toString());
    });
  });

  doc.querySelectorAll("[data-technology-tabs]").forEach((tabs) => {
    const buttons = [...tabs.querySelectorAll('[role="tab"]')];
    const panel = tabs.querySelector('[role="tabpanel"]');
    const heading = panel?.querySelector("h3");
    const copy = panel?.querySelector("p");
    const activate = (button) => {
      buttons.forEach((item) => {
        const active = item === button;
        item.setAttribute("aria-selected", String(active));
        item.setAttribute("tabindex", active ? "0" : "-1");
      });
      if (heading) heading.textContent = button.dataset.title || "";
      if (copy) copy.textContent = button.dataset.copy || "";
      if (panel) panel.setAttribute("aria-labelledby", button.id);
    };
    buttons.forEach((button, index) => {
      button.addEventListener("click", () => activate(button));
      button.addEventListener("keydown", (event) => {
        if (!["ArrowRight", "ArrowDown", "ArrowLeft", "ArrowUp", "Home", "End"].includes(event.key)) return;
        event.preventDefault();
        let target = index;
        if (["ArrowRight", "ArrowDown"].includes(event.key)) target = (index + 1) % buttons.length;
        if (["ArrowLeft", "ArrowUp"].includes(event.key)) target = (index - 1 + buttons.length) % buttons.length;
        if (event.key === "Home") target = 0;
        if (event.key === "End") target = buttons.length - 1;
        activate(buttons[target]);
        buttons[target].focus();
      });
    });
  });

  doc.querySelectorAll("[data-store-form]").forEach((form) => {
    const input = form.querySelector("input[name=q]");
    const error = form.querySelector("[role=alert]");
    const copy = form.closest(".locator-copy");
    const searchState = copy?.querySelector("[data-store-search-state]");
    const results = copy?.querySelector("[data-store-results]");
    const resultsGrid = results?.querySelector("[data-store-results-grid]");
    const pincodeOutput = results?.querySelector("[data-store-pincode]");
    const reset = results?.querySelector("[data-store-reset]");
    const icon = (type) => type === "location"
      ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>'
      : type === "phone"
        ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L8 9.73a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 22 16.92Z"/></svg>'
        : '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>';
    const cityFor = (pincode) => ({ "11": "Delhi", "40": "Mumbai", "50": "Hyderabad", "56": "Bengaluru", "60": "Chennai", "70": "Kolkata" }[pincode.slice(0, 2)] || "your area");
    input?.addEventListener("input", () => {
      input.value = input.value.replace(/\D/g, "").slice(0, 6);
      input.removeAttribute("aria-invalid");
      if (error) error.hidden = true;
    });
    form.addEventListener("submit", (event) => {
      event.preventDefault();
      const pincode = input?.value.trim() || "";
      if (!/^\d{6}$/.test(pincode)) {
        input?.setAttribute("aria-invalid", "true");
        if (error) error.hidden = false;
        input?.focus();
        return;
      }
      const city = cityFor(pincode);
      const dealers = [
        { name: "Goodnite Sleep Studio", location: `Central ${city}, ${pincode}`, phone: "Contact number pending verification", email: "studio@goodniteindia.example" }
      ];
      if (resultsGrid) resultsGrid.innerHTML = dealers.map((dealer) => `<article class="locator-dealer-card"><h4>${dealer.name}</h4><ul><li>${icon("location")}<span>${dealer.location}</span></li><li>${icon("phone")}<span>${dealer.phone}</span></li><li>${icon("email")}<span>${dealer.email}</span></li></ul></article>`).join("");
      if (pincodeOutput) pincodeOutput.textContent = pincode;
      if (searchState) searchState.hidden = true;
      else form.hidden = true;
      if (results) results.hidden = false;
    });
    reset?.addEventListener("click", () => {
      if (results) results.hidden = true;
      if (searchState) searchState.hidden = false;
      else form.hidden = false;
      form.reset();
      input?.removeAttribute("aria-invalid");
      if (error) error.hidden = true;
      window.requestAnimationFrame(() => input?.focus());
    });
  });
})();
/* Hero background image slider */
(function(){
  function initHeroSlider(){
    var hero=document.querySelector('.hero');
    if(!hero||hero.dataset.heroSliderReady)return;
    hero.dataset.heroSliderReady='true';
    var root=hero.querySelector('.hero-content')||hero.firstElementChild;
    var images=['assets/images/goodnite-hero-desktop.webp','assets/images/goodnite-collection-dreamer.webp','assets/images/goodnite-collection-love.webp'];
    var slider=document.createElement('div');slider.className='hero-image-slider';slider.setAttribute('aria-label','Hero images');
    images.forEach(function(src,i){var s=document.createElement('div');s.className='hero-image-slider__slide'+(i===0?' is-active':'');s.style.backgroundImage='url("'+src+'")';slider.appendChild(s)});
    hero.insertBefore(slider,hero.firstChild);
    var controls=document.createElement('div');controls.className='hero-slider-controls';
    controls.innerHTML='<button class="hero-slider-arrow hero-slider-prev" type="button" aria-label="Previous image"><svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg></button><div class="hero-slider-dots" role="tablist" aria-label="Choose hero image"></div><button class="hero-slider-arrow hero-slider-next" type="button" aria-label="Next image"><svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg></button>';
    hero.appendChild(controls);
    var slides=[].slice.call(slider.children),dots=controls.querySelector('.hero-slider-dots'),index=0,timer;
    slides.forEach(function(_,i){var d=document.createElement('button');d.className='hero-slider-dot'+(i===0?' is-active':'');d.type='button';d.setAttribute('role','tab');d.setAttribute('aria-label','Show image '+(i+1));d.setAttribute('aria-selected',i===0);d.addEventListener('click',function(){show(i);restart()});dots.appendChild(d)});
    function show(next){index=(next+slides.length)%slides.length;slides.forEach(function(s,i){s.classList.toggle('is-active',i===index)});[].slice.call(dots.children).forEach(function(d,i){d.classList.toggle('is-active',i===index);d.setAttribute('aria-selected',i===index)})}
    function restart(){clearInterval(timer);timer=setInterval(function(){show(index+1)},6000)}
    controls.querySelector('.hero-slider-prev').addEventListener('click',function(){show(index-1);restart()});controls.querySelector('.hero-slider-next').addEventListener('click',function(){show(index+1);restart()});
    hero.addEventListener('mouseenter',function(){clearInterval(timer)});hero.addEventListener('mouseleave',restart);restart();
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initHeroSlider);else initHeroSlider();
})();
/* Content cleanup requested for the hero and collection metadata */
(function(){
  function removeRequestedCopy(){
    document.querySelectorAll('.hero-note, small, .collection-meta .number, .collection-meta .collection-number, .collection-meta [data-number]').forEach(function(el){el.remove()});
    document.querySelectorAll('.collection-meta > *').forEach(function(el){if(/^\d+$/.test(el.textContent.trim()))el.remove()});
    document.querySelectorAll('span').forEach(function(el){
      if(el.textContent.trim()==='Material study · provisional')el.remove();
    });
    document.querySelectorAll('body *').forEach(function(el){
      if(el.children.length===0 && el.textContent.trim().includes('Final wording must be checked against approved technical documentation.'))el.remove();
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',removeRequestedCopy);else removeRequestedCopy();
})();
/* Mobile-only swipe interaction for hero images */
(function(){
  function initHeroSwipe(){
    if(!window.matchMedia('(max-width: 767px)').matches)return;
    var hero=document.querySelector('.hero');
    if(!hero||hero.dataset.heroSwipeReady)return;
    hero.dataset.heroSwipeReady='true';
    var startX=0,startY=0;
    hero.addEventListener('touchstart',function(e){
      if(!e.touches.length)return;
      startX=e.touches[0].clientX;startY=e.touches[0].clientY;
    },{passive:true});
    hero.addEventListener('touchend',function(e){
      if(!e.changedTouches.length)return;
      var end=e.changedTouches[0],dx=end.clientX-startX,dy=end.clientY-startY;
      if(Math.abs(dx)<45||Math.abs(dx)<=Math.abs(dy))return;
      var button=hero.querySelector(dx<0?'.hero-slider-next':'.hero-slider-prev');
      if(button)button.click();
    },{passive:true});
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initHeroSwipe);else initHeroSwipe();
})();
/* Desktop-only hover behavior for the mattress navigation dropdown */
(function(){
  function setupMattressHover(){
    var desktop=window.matchMedia('(min-width: 1024px)').matches;
    document.querySelectorAll('.desktop-mattress-dropdown').forEach(function(el){el.classList.remove('desktop-mattress-dropdown','is-hover-open')});
    if(!desktop)return;
    document.querySelectorAll('nav a, nav button, header a, header button').forEach(function(trigger){
      if(!/mattress/i.test(trigger.textContent||''))return;
      var parent=trigger.closest('li')||trigger.parentElement;
      if(!parent||!parent.querySelector('.dropdown, .dropdown-menu, [class*="dropdown"]'))return;
      parent.classList.add('desktop-mattress-dropdown');
      parent.addEventListener('mouseenter',function(){parent.classList.add('is-hover-open')});
      parent.addEventListener('mouseleave',function(){parent.classList.remove('is-hover-open')});
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',setupMattressHover);else setupMattressHover();
  window.addEventListener('resize',setupMattressHover);
})();
/* Updated Legacy section copy */
(function(){
  function updateLegacyCopy(){
    var section=document.querySelector('section[class*="legacy"], .legacy, [data-section="legacy"]');
    if(!section)return;
    var heading=section.querySelector('h1,h2,h3');
    var text=section.querySelector('p');
    if(heading)heading.textContent='A Legacy of Better Sleep, Since 1989';
    if(text)text.innerHTML='For decades, Goodnite has brought together sleep expertise, material innovation and manufacturing craftsmanship to create comfort for people around the world.<br><br>Today, that global heritage begins a new chapter in India bringing thoughtfully engineered sleep solutions to a market that deserves more than one definition of perfect sleep.';
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',updateLegacyCopy);else updateLegacyCopy();
})();
/* Legacy section flow: copy, metrics, then CTA */
(function(){
  function arrangeLegacySection(){
    var section=document.querySelector('section[class*="legacy"], .legacy, [data-section="legacy"]');
    if(!section)return;
    var copy=section.querySelector('p');
    var metrics=section.querySelector('.container-shell.metrics, .metrics');
    if(!copy||!metrics)return;
    copy.parentNode.insertBefore(metrics,copy.nextSibling);
    var cta=section.querySelector('.legacy-cta, .cta, a.button, a.btn, button');
    if(cta&&cta!==metrics)metrics.parentNode.insertBefore(cta,metrics.nextSibling);
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',arrangeLegacySection);else arrangeLegacySection();
})();
/* Legacy metric: 1,500 mattresses per day with viewport-triggered count-up */
(function(){
  function initLegacyMetric(){
    var section=document.querySelector('section[class*="legacy"], .legacy, [data-section="legacy"]');
    if(!section)return;
    section.querySelectorAll('a,button').forEach(function(el){if(/discover our story/i.test(el.textContent)){el.classList.add('legacy-cta')}});
    var metrics=section.querySelector('.container-shell.metrics, .metrics');
    if(!metrics)return;
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initLegacyMetric);else initLegacyMetric();
})();
/* Abbreviate million in displayed copy */
(function(){
  function abbreviateMillion(){
    var walker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT);
    var node;while(node=walker.nextNode()){if(/million/i.test(node.nodeValue))node.nodeValue=node.nodeValue.replace(/million/gi,'M')}
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',abbreviateMillion);else abbreviateMillion();
})();
/* Ensure metric pointer values and labels share one row below 1024px */
(function(){
  function alignMetricPointers(){
    if(!window.matchMedia('(max-width:767px)').matches)return;
    document.querySelectorAll('.container-shell.metrics.is-visible, .metrics.is-visible').forEach(function(metrics){
      metrics.querySelectorAll('*').forEach(function(el){
        var hasStrong=el.children.length&&el.querySelector(':scope>strong');
        var hasSpan=el.children.length&&el.querySelector(':scope>span');
        if(hasStrong&&hasSpan)el.classList.add('metric-pointer-inline');
      });
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',alignMetricPointers);else alignMetricPointers();
  window.addEventListener('resize',alignMetricPointers);
})();
/* Style the Collections Explore CTA */
(function(){
  function markExploreCta(){
    var matches=[].slice.call(document.querySelectorAll('.collections-section a,.collections-section button')).filter(function(el){return /^explore/i.test(el.textContent.trim())});
    matches.forEach(function(el){el.classList.remove('explore-cta')});
    if(matches.length)matches[matches.length-1].classList.add('explore-cta');
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',markExploreCta);else markExploreCta();
})();
/* Keep the Legacy CTA directly below the section subtext */
(function(){
  function placeLegacyCta(){
    var section=document.querySelector('section[class*="legacy"], .legacy, [data-section="legacy"]');
    if(!section)return;
    var copy=section.querySelector('p');
    var cta=section.querySelector('.button-link.legacy-cta, .legacy-cta');
    if(copy&&cta)copy.parentNode.insertBefore(cta,copy.nextSibling);
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',placeLegacyCta);else placeLegacyCta();
})();
/* Normalize page typography to 4px increments across breakpoints */
(function(){
  function normalizeFontSizes(){
    document.querySelectorAll('body *').forEach(function(el){
      // Header navigation owns its responsive type scale in CSS. Do not
      // replace those breakpoint values with inline font-size declarations.
      if(el.closest('[data-site-header], .mobile-overlay, [data-mega-menu]'))return;
      if(!el.textContent.trim()||el.children.length&&![].slice.call(el.childNodes).some(function(n){return n.nodeType===3&&n.nodeValue.trim()}))return;
      var size=parseFloat(getComputedStyle(el).fontSize);
      if(!size)return;
      el.style.fontSize=(Math.max(8,Math.round(size/4)*4))+'px';
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',normalizeFontSizes);else normalizeFontSizes();
  var resizeTimer;window.addEventListener('resize',function(){clearTimeout(resizeTimer);resizeTimer=setTimeout(normalizeFontSizes,150)});
})();
/* Remove collection descriptor copy */
(function(){
  function removeCollectionDescriptors(){
    var phrases=['Essential Engineered Comfort','Advanced Sleep Technology','Engineered Beyond Comfort'];
    document.querySelectorAll('.collections-section *').forEach(function(el){
      if(el.children.length)return;
      var text=el.textContent.replace(/\s+/g,' ').trim();
      if(phrases.indexOf(text)!==-1)el.remove();
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',removeCollectionDescriptors);else removeCollectionDescriptors();
})();
/* Interactive Your Sleep. Your Way. preference discovery */
(function(){
  var allowed=['cooling','support','pressure_relief','motion_isolation'];
  function findSection(){return [].slice.call(document.querySelectorAll('section')).find(function(section){return /your sleep\. your way/i.test(section.textContent)})||document.querySelector('[class*="sleep-finder"]')}
  function render(){
    var section=findSection();if(!section||section.dataset.gnSleepFinderReady)return;
    section.dataset.gnSleepFinderReady='true';section.className='gn-sleep-finder';
    section.innerHTML='<div class="gn-sleep-finder__inner"><div class="gn-sleep-finder__content"><h2 id="gn-sleep-finder-title">Your Sleep. <span class="gn-sleep-finder__way">Your Way.</span></h2><p class="gn-sleep-finder__intro">Some prefer a cooler night. Others seek firmer support, softer comfort or more natural materials. Tell us what matters to you, and discover a better place to begin.</p><div class="gn-sleep-finder__question"><h3>Choose Two Options :</h3><div class="gn-sleep-finder__options" role="group" aria-label="Sleep preferences"></div><p class="gn-sleep-finder__limit" role="status" aria-live="polite"></p><div class="gn-sleep-finder__actions"><a class="gn-sleep-finder__cta" href="/find-your-mattress">Find My Sleep Match <span aria-hidden="true">→</span></a><a class="gn-sleep-finder__secondary" href="/mattresses">Explore All Mattresses</a></div></div></div><div class="gn-sleep-finder__visual"><picture><source media="(max-width:767px)" srcset="assets/images/goodnite-sleep-finder.webp"><img src="assets/images/goodnite-sleep-finder.webp" width="1448" height="1086" loading="lazy" decoding="async" alt="Softly creased white bedding in a calm bedroom at morning"></picture></div></div>';
    var data=[['cooling','Stay Cooler','Cooling surfaces and breathable comfort.','✧'],['support','Feel Supported','A steadier, more supportive sleep feel.','⌁'],['pressure_relief','Ease Pressure','Contouring and cushioning comfort.','∿'],['motion_isolation','Sleep Undisturbed','Less disturbance from movement.','☾']];
    var options=section.querySelector('.gn-sleep-finder__options'),selected=[];
    data.forEach(function(item){var button=document.createElement('button');button.type='button';button.className='gn-sleep-finder__option';button.dataset.preference=item[0];button.setAttribute('aria-pressed','false');button.innerHTML='<span class="gn-sleep-finder__icon" aria-hidden="true">'+item[3]+'</span><span><strong>'+item[1]+'</strong></span>';options.appendChild(button)});
    var status=section.querySelector('.gn-sleep-finder__limit'),cta=section.querySelector('.gn-sleep-finder__cta');
    options.addEventListener('click',function(e){var button=e.target.closest('button');if(!button)return;var id=button.dataset.preference,index=selected.indexOf(id);if(index>-1){selected.splice(index,1);button.setAttribute('aria-pressed','false');status.textContent=''}else if(selected.length<2){selected.push(id);button.setAttribute('aria-pressed','true');status.textContent=''}else{status.textContent='Choose up to two preferences.';return} });
    cta.addEventListener('click',function(e){var url=new URL(cta.href,window.location.origin);if(selected.length)url.searchParams.set('preferences',selected.join(','));window.location.href=url.toString();});
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',render);else render();
})();
/* Use the approved Goodnite logo asset in header and footer */
(function(){
  function replaceBrandLogos(){
    document.querySelectorAll('.temp-logo').forEach(function(link){
      link.classList.add('brand-logo-link');
      link.innerHTML='<img class="brand-logo-image" src="assets/images/brand-logo.png" alt="Goodnite 1989">';
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',replaceBrandLogos);else replaceBrandLogos();
})();
/* Tablet/mobile finder order: heading, intro, image, then question */
(function(){
  function reorderSleepFinder(){
    document.querySelectorAll('.gn-sleep-finder').forEach(function(section){
      var content=section.querySelector('.gn-sleep-finder__content'),visual=section.querySelector('.gn-sleep-finder__visual'),intro=section.querySelector('.gn-sleep-finder__intro'),question=section.querySelector('.gn-sleep-finder__question');
      if(!content||!visual||!intro||!question)return;
      if(window.matchMedia('(max-width:1023px)').matches){if(visual.parentNode!==content)content.insertBefore(visual,question)}
      else if(visual.parentNode===content)section.querySelector('.gn-sleep-finder__inner').appendChild(visual);
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',reorderSleepFinder);else reorderSleepFinder();
  window.addEventListener('resize',reorderSleepFinder);
})();
/* Footer navigation cleanup */
(function(){
  function tidyFooter(){
    var footer=document.querySelector('.site-footer');if(!footer)return;
    footer.querySelectorAll('a').forEach(function(link){
      if(/^(find your mattress|faqs)$/i.test(link.textContent.trim()))link.closest('li')?.remove()||link.remove();
    });
    var policyTerms=/privacy|terms|conditions|returns|refunds|warranty|shipping|delivery/i;
    var policyLinks=[].slice.call(footer.querySelectorAll('a')).filter(function(link){return policyTerms.test(link.textContent)});
    if(policyLinks.length>1){
      var bottom=footer.querySelector('.footer-bottom');if(!bottom)return;
      var group=bottom.querySelector('.footer-policies');if(!group){group=document.createElement('div');group.className='footer-policies';bottom.appendChild(group)}
      policyLinks.forEach(function(link){if(!group.contains(link))group.appendChild(link)});
    }
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',tidyFooter);else tidyFooter();
})();
/* Consolidate footer policies under the renamed Policies column */
(function(){
  function movePolicies(){
    var footer=document.querySelector('.site-footer');if(!footer)return;
    var heading=[].slice.call(footer.querySelectorAll('h2')).find(function(el){return /customer care/i.test(el.textContent)});
    if(!heading)return;
    heading.textContent='Policies';
    var list=heading.parentElement.querySelector('ul');if(!list)return;
    var pattern=/privacy|terms|conditions|returns|refunds|warranty|shipping|delivery/i;
    footer.querySelectorAll('a').forEach(function(link){
      if(pattern.test(link.textContent)&&!list.contains(link)){
        var item=link.closest('li')||link;list.appendChild(item);
      }
    });
    footer.querySelectorAll('.footer-policies').forEach(function(el){el.remove()});
  }
  function schedule(){setTimeout(movePolicies,0)}
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',schedule);else schedule();
})();
/* Remove empty footer menu items */
(function(){
  function removeEmptyFooterItems(){
    document.querySelectorAll('.site-footer .menu-item').forEach(function(item){
      if(!item.textContent.trim()&&!item.querySelector('a'))item.remove();
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',removeEmptyFooterItems);else removeEmptyFooterItems();
})();
/* Use the approved PNG favicon */
(function(){
  function setFavicon(){
    var link=document.querySelector('link[rel~="icon"]')||document.createElement('link');
    link.rel='icon';link.type='image/png';link.href='assets/images/favicon.png';
    if(!link.parentNode)document.head.appendChild(link);
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',setFavicon);else setFavicon();
})();
/* Products horizontal carousel with controls, indicators, and reveal states */
(function(){
  function initProductsCarousel(){
    document.querySelectorAll('.products .product-grid').forEach(function(grid){
      if(grid.dataset.carouselReady)return;grid.dataset.carouselReady='true';
      var cards=[].slice.call(grid.children);if(!cards.length)return;
      cards.forEach(function(card){card.classList.add('reveal','is-visible')});
      var controls=document.createElement('div');controls.className='products-carousel-controls';controls.innerHTML='<button class="products-carousel-arrow products-carousel-prev" type="button" aria-label="Previous product"><svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg></button><div class="products-carousel-dots" role="tablist" aria-label="Choose product"></div><button class="products-carousel-arrow products-carousel-next" type="button" aria-label="Next product"><svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg></button>';
      var shopLink=grid.parentNode.querySelector('.mobile-shop-link');if(shopLink)grid.parentNode.insertBefore(controls,shopLink);else grid.parentNode.appendChild(controls);var dots=controls.querySelector('.products-carousel-dots');
      cards.forEach(function(card,i){var dot=document.createElement('button');dot.className='products-carousel-dot'+(i===0?' is-active':'');dot.type='button';dot.setAttribute('aria-label','Show product '+(i+1));dot.addEventListener('click',function(){grid.scrollTo({left:card.offsetLeft,behavior:'smooth'})});dots.appendChild(dot)});
      function active(){var nearest=0,min=Infinity;cards.forEach(function(card,i){var distance=Math.abs(card.offsetLeft-grid.scrollLeft);if(distance<min){min=distance;nearest=i}});[].slice.call(dots.children).forEach(function(dot,i){dot.classList.toggle('is-active',i===nearest)})}
      function move(direction){var current=cards.findIndex(function(card){return Math.abs(card.offsetLeft-grid.scrollLeft)<card.offsetWidth*.5});var next=Math.max(0,Math.min(cards.length-1,current+direction));grid.scrollTo({left:cards[next].offsetLeft,behavior:'smooth'})}
      controls.querySelector('.products-carousel-prev').addEventListener('click',function(){move(-1)});controls.querySelector('.products-carousel-next').addEventListener('click',function(){move(1)});grid.addEventListener('scroll',active,{passive:true});active();
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initProductsCarousel);else initProductsCarousel();
})();
/* Product card information layout */
(function(){
  function buildProductInfo(){
    document.querySelectorAll('.products .product-card').forEach(function(card){
      if(card.querySelector('.product-info'))return;
      var image=card.querySelector('.product-image'),collection=card.querySelector('.product-collection'),name=card.querySelector('h3'),status=card.querySelector('.product-status'),link=card.querySelector('.card-link');
      if(!image||!name)return;
      var info=document.createElement('div');info.className='product-info';
      if(collection){var overlay=document.createElement('span');overlay.className='product-collection-overlay';overlay.textContent=collection.textContent;image.appendChild(overlay);collection.remove()}
      info.appendChild(name);
      var technologies={'Kayler':'SupportCore Foam','Dream Maker':'Zoned Comfort','Dream Catcher':'Memory Foam Relief','Orion Care LX':'IceSleep Surface'};var technology=document.createElement('span');technology.className='product-technology';technology.textContent=technologies[name.textContent.trim()]||'Advanced Comfort';info.appendChild(technology);
      var prices={'Kayler':'₹42,990','Dream Maker':'₹54,990','Dream Catcher':'₹48,990','Orion Care LX':'₹64,990'};var price=document.createElement('span');price.className='product-price';price.textContent='Starting from '+(prices[name.textContent.trim()]||'₹49,990');info.appendChild(price);
      card.appendChild(info);if(status)info.appendChild(status);if(link)link.remove();
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',buildProductInfo);else buildProductInfo();
})();
/* Remove Products section heading subtext */
(function(){
  function removeProductsSubtext(){
    document.querySelectorAll('.products .products-head p').forEach(function(el){el.remove()});
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',removeProductsSubtext);else removeProductsSubtext();
})();
/* Header search overlay and shared public index */
(function(){
  function initSearch(){
    var panel=document.querySelector('[data-search-panel]'),trigger=document.querySelector('[data-search-open]'),input=document.querySelector('[data-search-input]');
    if(!panel||!trigger||!input)return;
    var data=document.getElementById('goodnite-search-index'),index=[];try{index=JSON.parse(data.textContent||'[]')}catch(e){}
    var initial=panel.querySelector('[data-search-initial]'),results=panel.querySelector('[data-search-results]'),empty=panel.querySelector('[data-search-empty]'),clear=panel.querySelector('[data-search-clear]'),form=panel.querySelector('[data-search-form]'),previous=document.activeElement;
    function norm(v){return String(v||'').toLowerCase().replace(/[®™]/g,'').replace(/\s+/g,' ').trim()}
    function score(item,q){var n=norm(q),title=norm(item.title),body=norm([item.description||'',...(item.keywords||[]),...(item.aliases||[])].join(' '));if(title===n)return 1000;if(title.indexOf(n)===0)return 850;if(title.indexOf(n)>-1)return 700;if((item.aliases||[]).some(function(a){return norm(a)===n}))return 650;if(body.indexOf(n)>-1)return 450;return n.split(' ').reduce(function(s,t){return t.length>1&&(title.indexOf(t)>-1||body.indexOf(t)>-1)?s+100:s},0)}
    function search(q){return index.map(function(i){return {item:i,score:score(i,q)}}).filter(function(x){return x.score>0}).sort(function(a,b){return b.score-a.score||a.item.title.localeCompare(b.item.title)}).map(function(x){return x.item})}
    function icon(){return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>'}
    function render(q){var clean=norm(q);initial.hidden=!!clean;clear.hidden=!clean;results.hidden=!clean;empty.hidden=true;results.innerHTML='';if(!clean)return;var found=search(clean);if(!found.length){results.hidden=true;empty.hidden=false;empty.textContent='No results found. Try a mattress name, collection, sleep technology or article topic.';return}var groups={product:'Products',collection:'Collections',technology:'Technologies',article:'Articles'};['product','collection','technology','article'].forEach(function(type){var list=found.filter(function(i){return i.type===type});if(!list.length)return;var section=document.createElement('section');section.className='gn-search-group';var h=document.createElement('h3');h.textContent=groups[type];section.appendChild(h);list.slice(0,5).forEach(function(item){var a=document.createElement('a');a.className='gn-search-result';a.href=item.url;var body=document.createElement('span');body.className='gn-search-result__body';var strong=document.createElement('strong');strong.textContent=item.title;var small=document.createElement('small');small.textContent=item.description;body.append(strong,small);a.append(body);a.insertAdjacentHTML('beforeend',icon());section.appendChild(a)});results.appendChild(section)});if(found.length>15){var more=document.createElement('a');more.className='gn-search-more';more.href='/search.php?q='+encodeURIComponent(q);more.textContent='View All Results for “'+q+'” '+String.fromCharCode(8594);results.appendChild(more)}}
    function setOpen(open){panel.hidden=!open;trigger.setAttribute('aria-expanded',open?'true':'false');document.body.classList.toggle('search-is-open',open);if(open){previous=document.activeElement;var header=document.querySelector('[data-site-header]');if(header)panel.style.setProperty('--search-top',Math.round(header.getBoundingClientRect().bottom)+'px');setTimeout(function(){input.focus()},20)}else{input.value='';render('');if(previous&&previous.focus)previous.focus()}}
    trigger.addEventListener('click',function(){setOpen(panel.hidden)});panel.querySelectorAll('[data-search-close]').forEach(function(el){el.addEventListener('click',function(){setOpen(false)})});input.addEventListener('input',function(){render(input.value)});clear.addEventListener('click',function(){input.value='';render('');input.focus()});panel.querySelectorAll('[data-search-suggestion]').forEach(function(button){button.addEventListener('click',function(){input.value=button.dataset.searchSuggestion||'';render(input.value);input.focus()})});form.addEventListener('submit',function(e){if(!norm(input.value)){e.preventDefault();return}});document.addEventListener('keydown',function(e){if(e.key==='Escape'&&!panel.hidden)setOpen(false)});document.addEventListener('click',function(e){if(panel.hidden||panel.contains(e.target)||trigger.contains(e.target))return;setOpen(false)});
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initSearch);else initSearch();
})();
/* Technology pillar slider indicators */
(function(){
  function initTechnologyIndicators(){document.querySelectorAll('[data-tech-grid]').forEach(function(grid){var dots=grid.parentElement.querySelector('[data-tech-indicators]'),cards=[].slice.call(grid.querySelectorAll('.gn-tech-pillars__card'));if(!dots||cards.length<2)return;function active(){var nearest=0,min=Infinity;cards.forEach(function(card,i){var distance=Math.abs(card.offsetLeft-grid.scrollLeft);if(distance<min){min=distance;nearest=i}});dots.querySelectorAll('[data-tech-indicator]').forEach(function(dot,i){dot.classList.toggle('is-active',i===nearest);dot.setAttribute('aria-selected',i===nearest?'true':'false')})}dots.querySelectorAll('[data-tech-indicator]').forEach(function(dot){dot.addEventListener('click',function(){var card=cards[Number(dot.dataset.techIndicator)];if(card)grid.scrollTo({left:card.offsetLeft,behavior:'smooth'})})});grid.addEventListener('scroll',active,{passive:true});active();function visibility(){dots.hidden=grid.scrollWidth<=grid.clientWidth+2}window.addEventListener('resize',visibility);visibility()})}
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initTechnologyIndicators);else initTechnologyIndicators();
})();
/* Collection carousel dots, matching the products carousel indicator */
(function(){
  function initCollectionIndicators(){
    document.querySelectorAll('.collections-section .collection-grid').forEach(function(grid){
      var indicator=grid.parentElement.querySelector('.collection-carousel-dots'),cards=[].slice.call(grid.children);
      if(!indicator||cards.length<2)return;
      cards.forEach(function(_,i){var dot=document.createElement('button');dot.className='products-carousel-dot'+(i===0?' is-active':'');dot.type='button';dot.setAttribute('role','tab');dot.setAttribute('aria-label','Show collection '+(i+1));dot.setAttribute('aria-selected',i===0?'true':'false');dot.addEventListener('click',function(){grid.scrollTo({left:cards[i].offsetLeft,behavior:'smooth'})});indicator.appendChild(dot)});
      var dots=[].slice.call(indicator.children);
      function update(){
        var nearest=0,min=Infinity;
        cards.forEach(function(card,i){var distance=Math.abs(card.offsetLeft-grid.scrollLeft);if(distance<min){min=distance;nearest=i}});
        dots.forEach(function(dot,i){dot.classList.toggle('is-active',i===nearest);dot.setAttribute('aria-selected',i===nearest?'true':'false')});
        indicator.hidden=grid.scrollWidth<=grid.clientWidth+2;
      }
      grid.addEventListener('scroll',update,{passive:true});
      window.addEventListener('resize',update);
      update();
    });
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initCollectionIndicators);else initCollectionIndicators();
})();
