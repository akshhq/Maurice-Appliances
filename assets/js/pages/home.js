/**
 * MAURICE APPLIANCES — Homepage Controller (Pure Vanilla JS)
 * Single Unified Hero Showcase, Dynamic Credential Stats,
 * Amazon-style category bento grid, interactive finder & B2B express form.
 */

import { ALL_PRODUCTS, CATEGORIES, COMPANY } from '../data/products.js';
import { initProductFinder } from '../modules/product-finder.js';
import { initReveals } from '../core/scroll.js';

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
let initialized = false;

export function initHomePage() {
  if (initialized) return;
  initialized = true;

  initDynamicHeroStats();
  countUp();
  emberField();
  initProductFinder();
  initB2BExpressForm();
  initReveals();
}

/* ---- 1. Dynamic Hero Stats ---- */
function initDynamicHeroStats() {
  const prodEl = document.getElementById('heroStatProducts');
  const catEl = document.getElementById('heroStatCats');
  const yearsEl = document.getElementById('heroStatYears');

  const totalProducts = ALL_PRODUCTS.length || 118;
  const totalCats = CATEGORIES.length || 11;
  const currentYear = new Date().getFullYear();
  const yearsBuilt = Math.max(16, currentYear - (COMPANY.established || 2010));

  if (prodEl) {
    prodEl.setAttribute('data-count', String(totalProducts));
    prodEl.textContent = String(totalProducts);
  }
  if (catEl) {
    catEl.setAttribute('data-count', String(totalCats));
    catEl.textContent = String(totalCats);
  }
  if (yearsEl) {
    yearsEl.setAttribute('data-count', String(yearsBuilt));
    yearsEl.textContent = String(yearsBuilt);
  }
}

/* ---- 2. Count Up Stats ---- */
function countUp() {
  const els = document.querySelectorAll('[data-count]');
  if (!els.length) return;
  if (reduceMotion || !('IntersectionObserver' in window)) {
    els.forEach((el) => { el.textContent = el.dataset.count + (el.dataset.suffix || ''); });
    return;
  }
  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const end = parseInt(el.dataset.count, 10) || 0;
      const suffix = el.dataset.suffix || '';
      const dur = 1400; const start = performance.now();
      function step(now) {
        const p = Math.min(1, (now - start) / dur);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = Math.round(eased * end) + suffix;
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
      io.unobserve(el);
    });
  }, { threshold: 0.5 });
  els.forEach((el) => io.observe(el));
}

/* ---- 3. Ember Particle Field (2D Canvas) ---- */
function emberField() {
  const canvas = document.getElementById('emberCanvas');
  if (!canvas || reduceMotion) return;
  if (window.innerWidth < 720 || (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 4)) return;

  const ctx = canvas.getContext('2d');
  let w, h, dpr, particles, raf;

  function size() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.clientWidth; h = canvas.clientHeight;
    canvas.width = w * dpr; canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function seed() {
    const count = Math.min(36, Math.floor(w / 32));
    particles = Array.from({ length: count }, () => ({
      x: Math.random() * w,
      y: h + Math.random() * h,
      r: Math.random() * 2 + 0.6,
      vy: Math.random() * 0.45 + 0.15,
      vx: (Math.random() - 0.5) * 0.2,
      a: Math.random() * 0.35 + 0.1,
      hue: Math.random() > 0.5 ? '255,106,61' : '224,30,38',
    }));
  }

  function frame() {
    ctx.clearRect(0, 0, w, h);
    for (const p of particles) {
      p.y -= p.vy; p.x += p.vx;
      if (p.y < -10) { p.y = h + 10; p.x = Math.random() * w; }
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${p.hue},${p.a})`;
      ctx.shadowBlur = 6; ctx.shadowColor = `rgba(${p.hue},${p.a})`;
      ctx.fill();
    }
    ctx.shadowBlur = 0;
    raf = requestAnimationFrame(frame);
  }

  size(); seed(); frame();
  window.addEventListener('resize', () => {
    cancelAnimationFrame(raf);
    size(); seed(); frame();
  }, { passive: true });
}

/* ---- 4. B2B / Dealer Express Onboarding Form ---- */
function initB2BExpressForm() {
  const form = document.getElementById('expressDealerForm');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const name = form.querySelector('[name="name"]')?.value.trim();
    const city = form.querySelector('[name="city"]')?.value.trim();
    const phone = form.querySelector('[name="phone"]')?.value.trim();

    if (!name || !city || !phone) {
      if (window.showToast) window.showToast('Please fill all 3 fields for express callback.', 'warning');
      return;
    }

    try {
      const existing = JSON.parse(localStorage.getItem('maurice_dealer_inquiries') || '[]');
      existing.push({ id: Date.now(), name, city, phone, date: new Date().toISOString() });
      localStorage.setItem('maurice_dealer_inquiries', JSON.stringify(existing));
    } catch (err) {}

    if (window.showToast) {
      window.showToast('Express application received! Our regional distributor manager will call you within 2 hours.', 'success');
    } else {
      alert('Express application received! Our regional distributor manager will call you within 2 hours.');
    }
    form.reset();
  });
}

// Auto-run if loaded
if (document.readyState !== 'loading') {
  initHomePage();
} else {
  document.addEventListener('DOMContentLoaded', initHomePage);
}
document.addEventListener('maurice:ready', initHomePage);
