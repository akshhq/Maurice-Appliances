/**
 * MAURICE APPLIANCES — Navigation Controller
 * Handles glass sticky navbar, scroll awareness, mobile drawer toggle,
 * search trigger injection, and accessibility keyboard navigation.
 */

export function initNav() {
  const nav = document.getElementById('nav');
  const burger = document.getElementById('burger');
  const mobile = document.getElementById('mobileMenu');
  if (!nav) return;

  // 1. Dropdown Grace Period / Hover Delay Timer (0.7s)
  const navItems = nav.querySelectorAll('.nav__item--mega, .nav__item--drop');
  const closeTimers = new Map();

  function closeAllDropdowns(exceptItem = null) {
    navItems.forEach((item) => {
      if (item !== exceptItem) {
        if (closeTimers.has(item)) {
          clearTimeout(closeTimers.get(item));
          closeTimers.delete(item);
        }
        item.classList.remove('is-open');
        const link = item.querySelector('.nav__link');
        if (link) link.setAttribute('aria-expanded', 'false');
      }
    });
  }

  navItems.forEach((item) => {
    const link = item.querySelector('.nav__link');
    const menu = item.querySelector('.mega, .drop');
    if (!menu) return;

    function openItem() {
      // Clear any pending close timer for this item
      if (closeTimers.has(item)) {
        clearTimeout(closeTimers.get(item));
        closeTimers.delete(item);
      }
      // Switch immediately away from other dropdowns without delay
      closeAllDropdowns(item);
      item.classList.add('is-open');
      if (link) link.setAttribute('aria-expanded', 'true');
    }

    function scheduleClose() {
      if (closeTimers.has(item)) {
        clearTimeout(closeTimers.get(item));
      }
      // 0.7s grace period: dropdown stays open for 0.7s after cursor leaves
      const timer = setTimeout(() => {
        item.classList.remove('is-open');
        if (link) link.setAttribute('aria-expanded', 'false');
        closeTimers.delete(item);
      }, 700);
      closeTimers.set(item, timer);
    }

    item.addEventListener('mouseenter', openItem);
    item.addEventListener('mouseleave', scheduleClose);

    // Keyboard accessibility
    item.addEventListener('focusin', openItem);
    item.addEventListener('focusout', (e) => {
      if (!item.contains(e.relatedTarget)) {
        item.classList.remove('is-open');
        if (link) link.setAttribute('aria-expanded', 'false');
        if (closeTimers.has(item)) {
          clearTimeout(closeTimers.get(item));
          closeTimers.delete(item);
        }
      }
    });
  });

  // Close dropdowns when clicking outside or pressing Escape
  document.addEventListener('click', (e) => {
    if (!nav.contains(e.target)) {
      closeAllDropdowns();
    }
  });

  let lastY = window.scrollY;
  let ticking = false;

  function onScroll() {
    const y = window.scrollY;
    nav.classList.toggle('scrolled', y > 20);

    // Don't auto-hide nav if user is hovering over a dropdown or mega menu or menu is currently open
    const isHoveringMenu = nav.matches(':hover') || nav.querySelector('.is-open') || nav.querySelector(':focus-within');
    if (!isHoveringMenu) {
      if (y > 320 && y > lastY + 8) {
        nav.classList.add('hidden');
      } else if (y < lastY - 8) {
        nav.classList.remove('hidden');
      }
    } else {
      nav.classList.remove('hidden');
    }

    lastY = y;
    ticking = false;
  }

  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(onScroll);
      ticking = true;
    }
  }, { passive: true });
  onScroll();

  // Mobile menu drawer
  function toggleMobile(open) {
    if (!mobile || !burger) return;
    const isOpen = open !== undefined ? open : !mobile.classList.contains('open');
    mobile.classList.toggle('open', isOpen);
    burger.classList.toggle('open', isOpen);
    burger.setAttribute('aria-expanded', String(isOpen));
    mobile.setAttribute('aria-hidden', String(!isOpen));
    document.body.style.overflow = isOpen ? 'hidden' : '';
  }

  if (burger && mobile) {
    burger.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleMobile();
    });

    mobile.querySelectorAll('a').forEach((a) => {
      a.addEventListener('click', () => toggleMobile(false));
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeAllDropdowns();
        if (mobile && mobile.classList.contains('open')) toggleMobile(false);
      }
    });

    // Close when clicking outside on backdrop
    document.addEventListener('click', (e) => {
      if (mobile.classList.contains('open') && !mobile.contains(e.target) && !burger.contains(e.target)) {
        toggleMobile(false);
      }
    });
  }

  // Auto-inject Search Trigger in Nav Actions
  const navActions = nav.querySelector('.nav__actions');
  if (navActions && !navActions.querySelector('[data-open-search]')) {
    const searchBtn = document.createElement('button');
    searchBtn.type = 'button';
    searchBtn.className = 'nav__search-btn';
    searchBtn.setAttribute('data-open-search', '');
    searchBtn.setAttribute('aria-label', 'Search appliances (Ctrl+K)');
    searchBtn.title = 'Search appliances (Ctrl+K)';
    searchBtn.innerHTML = `
      <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
      </svg>
    `;
    navActions.insertBefore(searchBtn, navActions.firstChild);
  }
}
