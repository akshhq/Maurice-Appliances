/* Preloader — ember-gradient logo draw + progress count.
   Resolves once the intro has finished so the app can boot. */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function runLoader() {
  const loader = document.getElementById('loader');
  const countEl = document.getElementById('loaderCount');
  // No loader on this page (non-homepage — see layouts/main-header.php), or
  // it already played once this session (partials/loader.php's inline
  // script already marked it .skip before paint) — boot straight away.
  if (!loader || loader.classList.contains('skip')) return Promise.resolve();

  // Inject the ember gradient used by the logo rule
  const svg = loader.querySelector('.loader__logo');
  if (svg) {
    const ns = 'http://www.w3.org/2000/svg';
    const defs = document.createElementNS(ns, 'defs');
    defs.innerHTML =
      '<linearGradient id="emberGrad" x1="0" y1="0" x2="1" y2="0">' +
      '<stop offset="0" stop-color="#ffffff"/>' +
      '<stop offset="0.55" stop-color="#FF6A3D"/>' +
      '<stop offset="1" stop-color="#E01E26"/></linearGradient>';
    svg.prepend(defs);
    const rule = svg.querySelector('.loader__rule');
    if (rule) rule.setAttribute('stroke', 'url(#emberGrad)');
  }

  loader.classList.add('run');
  document.body.style.overflow = 'hidden';

  return new Promise((resolve) => {
    // Hard failsafe: never freeze the page longer than 4 s no matter what.
    const failsafe = setTimeout(() => {
      loader.classList.add('done');
      document.body.style.overflow = '';
      resolve();
    }, 4000);

    const total = reduceMotion ? 200 : 1100;
    const start = typeof performance !== 'undefined' ? performance.now() : Date.now();
    function tick(now) {
      const p = Math.min(1, (now - start) / total);
      if (countEl) countEl.textContent = String(Math.round(p * 100));
      if (p < 1) {
        requestAnimationFrame(tick);
      } else {
        clearTimeout(failsafe);
        loader.classList.add('done');
        document.body.style.overflow = '';
        try { sessionStorage.setItem('mauriceLoaderPlayed', '1'); } catch { /* private mode, etc. */ }
        setTimeout(resolve, 500);
      }
    }
    requestAnimationFrame(tick);
  });
}
