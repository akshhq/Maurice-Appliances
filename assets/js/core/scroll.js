/* Smooth scroll (Lenis) synced to GSAP ScrollTrigger,
   plus IntersectionObserver-driven reveals for .reveal / .section-head. */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
let lenis = null;

export function getLenis() { return lenis; }

export function initScroll() {
  if (reduceMotion || typeof Lenis === 'undefined') return;

  lenis = new Lenis({
    duration: 1.1,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smoothWheel: true,
    smoothTouch: false,
  });

  function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
  requestAnimationFrame(raf);

  // Sync with ScrollTrigger if present
  if (window.gsap && window.ScrollTrigger) {
    gsap.registerPlugin(ScrollTrigger);
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((t) => lenis.raf(t * 1000));
    gsap.ticker.lagSmoothing(0);
  }

  // Anchor links go through Lenis
  document.querySelectorAll('a[href^="#"]').forEach((a) => {
    a.addEventListener('click', (e) => {
      const id = a.getAttribute('href');
      if (id.length > 1) {
        const target = document.querySelector(id);
        if (target) { e.preventDefault(); lenis.scrollTo(target, { offset: -90 }); }
      }
    });
  });
}

export function initReveals() {
  const items = document.querySelectorAll('.reveal, .section-head');
  if (!items.length) return;

  if (reduceMotion || !('IntersectionObserver' in window)) {
    items.forEach((el) => el.classList.add('in-view'));
    return;
  }

  const io = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

  items.forEach((el) => io.observe(el));
}
