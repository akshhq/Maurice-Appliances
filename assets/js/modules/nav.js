/* Navigation — scroll-aware glass nav, hide-on-scroll-down,
   mobile menu toggle, escape/outside-click handling. */

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
    // Hide when scrolling down past hero, show when scrolling up
    if (y > 320 && y > lastY + 6) nav.classList.add('hidden');
    else if (y < lastY - 6) nav.classList.remove('hidden');
    lastY = y;
    ticking = false;
  }
  window.addEventListener('scroll', () => {
    if (!ticking) { requestAnimationFrame(onScroll); ticking = true; }
  }, { passive: true });
  onScroll();

  // Mobile menu
  function toggleMobile(open) {
    const isOpen = open ?? !mobile.classList.contains('open');
    mobile.classList.toggle('open', isOpen);
    burger.classList.toggle('open', isOpen);
    burger.setAttribute('aria-expanded', String(isOpen));
    mobile.setAttribute('aria-hidden', String(!isOpen));
    document.body.style.overflow = isOpen ? 'hidden' : '';
  }

  if (burger && mobile) {
    burger.addEventListener('click', () => toggleMobile());
    mobile.querySelectorAll('a').forEach((a) =>
      a.addEventListener('click', () => toggleMobile(false)));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobile.classList.contains('open')) toggleMobile(false);
    });
  }
}
