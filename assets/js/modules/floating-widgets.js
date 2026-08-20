/**
 * MAURICE APPLIANCES — Floating UX Widgets
 * 1. WhatsApp Quick-Connect Button with live support tooltip.
 * 2. Sticky "Back to Top" Floating Pill (auto-reveals at scroll > 400px).
 * Perfectly coordinated with the bottom compare tray to avoid overlapping.
 */

export function initFloatingWidgets() {
  // 1. Back to Top Button
  let topBtn = document.getElementById('backToTopBtn');
  if (!topBtn) {
    topBtn = document.createElement('button');
    topBtn.id = 'backToTopBtn';
    topBtn.className = 'floating-btn floating-btn--top';
    topBtn.setAttribute('aria-label', 'Back to top of page');
    topBtn.title = 'Back to top';
    topBtn.innerHTML = `
      <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 15l-6-6-6 6"/>
      </svg>
    `;
    document.body.appendChild(topBtn);
  }

  // 2. WhatsApp Direct Connect Button
  let waBtn = document.getElementById('floatingWhatsAppBtn');
  if (!waBtn) {
    waBtn = document.createElement('a');
    waBtn.id = 'floatingWhatsAppBtn';
    waBtn.className = 'floating-btn floating-btn--whatsapp';
    waBtn.href = 'https://wa.me/919816591699?text=Hello%20Maurice%20Appliances%2C%20I%20have%20an%20inquiry%20regarding%20products%2Fdealership.';
    waBtn.target = '_blank';
    waBtn.rel = 'noopener noreferrer';
    waBtn.setAttribute('aria-label', 'Chat with Maurice Support on WhatsApp (+91 98165-91699)');
    waBtn.innerHTML = `
      <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.196 8.196 0 01-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 012.41 5.83c.02 4.54-3.68 8.23-8.22 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.7-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.39-1.72-.14-.25-.02-.38.11-.5.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43s-.56-1.34-.76-1.84c-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.84-.86 2.05s.88 2.38 1 2.55c.12.17 1.73 2.65 4.2 3.71.59.25 1.05.4 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.23-.17-.48-.3z"/>
      </svg>
      <span class="floating-tooltip">Chat with Maurice Support</span>
    `;
    document.body.appendChild(waBtn);
  }

  // Scroll visibility handler for Back-to-Top button
  let ticking = false;
  function onScroll() {
    if (!ticking) {
      window.requestAnimationFrame(() => {
        const scrollY = window.scrollY || window.pageYOffset;
        if (scrollY > 400) {
          topBtn.classList.add('is-visible');
        } else {
          topBtn.classList.remove('is-visible');
        }
        ticking = false;
      });
      ticking = true;
    }
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  topBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  // Watch for Compare Tray visibility to shift floating buttons upward
  function adjustForCompareTray() {
    const tray = document.getElementById('compareTray');
    const isTrayOpen = tray && tray.classList.contains('is-visible');
    
    if (isTrayOpen) {
      waBtn.classList.add('has-tray-open');
      topBtn.classList.add('has-tray-open');
    } else {
      waBtn.classList.remove('has-tray-open');
      topBtn.classList.remove('has-tray-open');
    }
  }

  document.addEventListener('maurice:compare-updated', adjustForCompareTray);
  const observer = new MutationObserver(adjustForCompareTray);
  const trayEl = document.getElementById('compareTray');
  if (trayEl) {
    observer.observe(trayEl, { attributes: true, attributeFilter: ['class'] });
  }
}
