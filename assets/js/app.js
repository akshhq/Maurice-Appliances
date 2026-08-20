/**
 * MAURICE APPLIANCES — Main Application Entry (Pure Vanilla JS / ES Modules)
 * Initializes design system, custom cursor, navigation, inquiry modal, and compare drawer.
 */

import { CONFIG } from './core/config.js?v=3.0';
import { runLoader } from './core/loader.js?v=3.0';
import { initScroll, initReveals } from './core/scroll.js?v=3.0';
import { initCursor } from './modules/cursor.js?v=3.0';
import { initNav } from './modules/nav.js?v=3.0';
import { initForms } from './modules/forms.js?v=3.0';
import { initInquiryModal } from './modules/inquiry-modal.js?v=3.0';
import { initCompareDrawer } from './modules/compare-drawer.js?v=3.0';
import { initGlobalSearch } from './modules/global-search.js?v=3.0';
import { initFloatingWidgets } from './modules/floating-widgets.js?v=3.0';

function boot() {
  const basePath = window.location.pathname.includes('/pages/') ? '..' : '.';
  initCursor();             // Custom magnetic cursor (auto-disabled on touch)
  initNav();                // Glass sticky nav + 4-column mega menu + mobile drawer
  initScroll();             // Smooth scroll + ScrollTrigger
  initReveals();            // IntersectionObserver reveal animations
  initForms();              // Universal form validation + localStorage lead capture
  initInquiryModal();       // Global prefilled product inquiry modal & WhatsApp connect
  initCompareDrawer(basePath); // LG-style sticky compare drawer + side-by-side modal
  initGlobalSearch(basePath);  // Instant Global Search Command Palette (CTRL+K)
  initFloatingWidgets();       // WhatsApp direct connect + Sticky back-to-top button

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
