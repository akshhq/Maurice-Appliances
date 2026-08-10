/* ============================================================
   MAURICE APPLIANCES — app.js
   Application entry point (ES module).

   Boot order:
     1. Preloader intro          (core/loader.js)
     2. Core systems             (core/scroll.js)
     3. UI modules               (modules/*)
     4. Dispatch 'maurice:ready' — page modules (pages/*) listen for this
   ============================================================ */

import { CONFIG } from './core/config.js';
import { runLoader } from './core/loader.js';
import { initScroll, initReveals } from './core/scroll.js';
import { initCursor } from './modules/cursor.js';
import { initNav } from './modules/nav.js';
import { initForms } from './modules/forms.js';

function boot() {
  initCursor();       // custom cursor (auto-disables on touch)
  initNav();          // sticky glass nav + mobile drawer
  initScroll();       // Lenis smooth scroll synced to ScrollTrigger
  initReveals();      // IntersectionObserver reveal animations
  initForms();        // newsletter + AJAX forms

  // Page-specific modules boot from this event.
  document.dispatchEvent(new CustomEvent('maurice:ready', {
    detail: { reduceMotion: CONFIG.reduceMotion, config: CONFIG },
  }));
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => { runLoader().then(boot); });
} else {
  runLoader().then(boot);
}
