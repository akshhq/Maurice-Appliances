/**
 * MAURICE APPLIANCES — Main Application Entry (Pure Vanilla JS / ES Modules)
 * Initializes design system, custom cursor, navigation, inquiry modal, and compare drawer.
 */

import { CONFIG } from './core/config.js';
import { runLoader } from './core/loader.js';
import { initScroll, initReveals } from './core/scroll.js';
import { initCursor } from './modules/cursor.js';
import { initNav } from './modules/nav.js';
import { initForms } from './modules/forms.js';
import { initInquiryModal } from './modules/inquiry-modal.js';
import { initCompareDrawer } from './modules/compare-drawer.js';

function boot() {
  const basePath = window.location.pathname.includes('/pages/') ? '..' : '.';
  initCursor();             // Custom magnetic cursor (auto-disabled on touch)
  initNav();                // Glass sticky nav + 4-column mega menu + mobile drawer
  initScroll();             // Smooth scroll + ScrollTrigger
  initReveals();            // IntersectionObserver reveal animations
  initForms();              // Universal form validation + localStorage lead capture
  initInquiryModal();       // Global prefilled product inquiry modal & WhatsApp connect
  initCompareDrawer(basePath); // LG-style sticky compare drawer + side-by-side modal

  window.__mauriceReady = true;
  window.__mauriceBasePath = basePath;

  // Dispatch event for any page listeners
  document.dispatchEvent(new CustomEvent('maurice:ready', {
    detail: { reduceMotion: CONFIG.reduceMotion, config: CONFIG, basePath: basePath },
  }));
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => { runLoader().then(boot); });
} else {
  runLoader().then(boot);
}
