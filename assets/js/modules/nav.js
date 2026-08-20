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

  let lastY = window.scrollY;
  let ticking = false;

  function onScroll() {
    const y = window.scrollY;
    nav.classList.toggle('scrolled', y > 20);

    // Don't auto-hide nav if user is hovering over a dropdown or mega menu
    const isHoveringMenu = nav.matches(':hover') || nav.querySelector(':focus-within');
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
      if (e.key === 'Escape' && mobile.classList.contains('open')) toggleMobile(false);
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
