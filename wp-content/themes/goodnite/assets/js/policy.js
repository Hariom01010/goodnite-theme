/**
 * Goodnite India — Policy Page Scripts
 *
 * Provides client-side usability enhancements for legal and policy documents:
 * - Smooth scroll behavior for internal anchor links with sticky header offset
 * - External link security enforcement (rel="noopener noreferrer")
 * - Document readiness state
 *
 * @package Goodnite
 */

(() => {
  'use strict';

  const doc = document;

  const initPolicyAnchorScroll = () => {
    const siteHeader = doc.querySelector('[data-site-header]');
    const getHeaderOffset = () => (siteHeader ? siteHeader.getBoundingClientRect().height + 24 : 96);

    doc.addEventListener('click', (event) => {
      const anchor = event.target.closest('a[href^="#"]');
      if (!anchor) return;

      const targetId = anchor.getAttribute('href').slice(1);
      if (!targetId) return;

      const targetEl = doc.getElementById(targetId);
      if (!targetEl) return;

      event.preventDefault();
      const targetPos = targetEl.getBoundingClientRect().top + window.scrollY - getHeaderOffset();

      window.scrollTo({
        top: Math.max(0, targetPos),
        behavior: 'smooth',
      });

      if (history.pushState) {
        history.pushState(null, '', '#' + targetId);
      }
    });
  };

  const initExternalLinks = () => {
    const policyContainer = doc.querySelector('.policy-copy');
    if (!policyContainer) return;

    const links = policyContainer.querySelectorAll('a[href^="http"]');
    links.forEach((link) => {
      if (!link.hostname || link.hostname === window.location.hostname) return;
      link.setAttribute('target', '_blank');
      link.setAttribute('rel', 'noopener noreferrer');
    });
  };

  const init = () => {
    doc.documentElement.classList.add('policy-page-loaded');
    initPolicyAnchorScroll();
    initExternalLinks();
  };

  if (doc.readyState === 'loading') {
    doc.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();
