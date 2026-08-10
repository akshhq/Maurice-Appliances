<?php /** PARTIAL: preloader — homepage only, see layouts/main-header.php */ ?>
<div class="loader" id="loader" aria-hidden="true">
  <div class="loader__inner">
    <svg class="loader__logo" viewBox="0 0 360 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Maurice">
      <text x="0" y="58" class="loader__word">maurice</text>
      <line class="loader__rule" x1="4" y1="72" x2="356" y2="72"/>
    </svg>
    <div class="loader__count"><span id="loaderCount">0</span><i>%</i></div>
  </div>
</div>
<script>
  // Runs synchronously before paint so a repeat homepage visit this session
  // never flashes the opaque loader screen. runLoader() (core/loader.js)
  // checks the same class and skips straight to boot() when present.
  if (sessionStorage.getItem('mauriceLoaderPlayed')) {
    document.getElementById('loader').classList.add('skip');
  }
</script>
