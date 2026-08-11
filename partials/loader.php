<?php /** PARTIAL: preloader — homepage only, see layouts/main-header.php */ ?>
<div class="loader" id="loader" aria-hidden="true">
  <div class="loader__inner">
    <svg class="loader__logo" viewBox="0 0 360 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Maurice">
      <defs>
        <linearGradient id="emberGrad" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0" stop-color="#ffffff"/>
          <stop offset="0.55" stop-color="#FF6A3D"/>
          <stop offset="1" stop-color="#E01E26"/>
        </linearGradient>
      </defs>
      <text x="0" y="58" class="loader__word">maurice</text>
      <line class="loader__rule" x1="4" y1="72" x2="356" y2="72" stroke="url(#emberGrad)"/>
    </svg>
    <div class="loader__count"><span id="loaderCount">0</span><i>%</i></div>
  </div>
</div>
<script>
(function () {
  var loader   = document.getElementById('loader');
  var countEl  = document.getElementById('loaderCount');
  if (!loader) return;

  // Already played this session — hide immediately and bail.
  try {
    if (sessionStorage.getItem('mauriceLoaderPlayed')) {
      loader.classList.add('skip');
      return;
    }
  } catch (e) { /* private / restricted mode — just play it */ }

  // Detect reduced-motion preference.
  var reduceMotion = window.matchMedia
    ? window.matchMedia('(prefers-reduced-motion: reduce)').matches
    : false;

  // Add .run to trigger the CSS rule-draw animation.
  loader.classList.add('run');
  document.body.style.overflow = 'hidden';

  // Hard safety cap: dismiss no later than 4 s regardless.
  var failsafe = setTimeout(dismiss, 4000);

  var total = reduceMotion ? 150 : 1100;
  var start = (typeof performance !== 'undefined') ? performance.now() : Date.now();

  function tick(now) {
    var p = Math.min(1, (now - start) / total);
    if (countEl) countEl.textContent = String(Math.round(p * 100));
    if (p < 1) {
      requestAnimationFrame(tick);
    } else {
      clearTimeout(failsafe);
      setTimeout(dismiss, 400); // brief pause at 100% before fade-out
    }
  }

  function dismiss() {
    loader.classList.add('done');
    document.body.style.overflow = '';
    try { sessionStorage.setItem('mauriceLoaderPlayed', '1'); } catch (e) {}
    // Signal app.js that the loader is already finished.
    loader.dispatchEvent(new CustomEvent('loader:done'));
  }

  requestAnimationFrame(tick);
}());
</script>
