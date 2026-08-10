<?php /** SECTION: homepage hero */ ?>
<section class="hero" id="hero">
  <canvas class="hero__canvas" id="emberCanvas" aria-hidden="true"></canvas>
  <div class="wrap hero__grid">
    <div class="hero__copy">
      <span class="eyebrow hero__eyebrow">Est. 2010 · Kullu, Himachal Pradesh</span>
      <h1 class="hero__title" id="heroTitle">
        <span class="line"><span>Making every</span></span>
        <span class="line"><span class="accent">home better</span></span>
        <span class="line"><span>tomorrow.</span></span>
      </h1>
      <p class="hero__lead">
        ISI &amp; ISO-certified home appliances engineered in India — heating, cooling,
        water heating and the kitchen — built on quality, reliability and honest value.
      </p>
      <div class="hero__cta">
        <a href="<?= e(url('products.php')) ?>" class="btn" data-magnetic data-cursor="Explore">
          <span>Explore products</span>
          <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="<?= e(url('pages/become-dealer.php')) ?>" class="btn btn--ghost" data-cursor="Apply">Become a dealer</a>
      </div>

      <div class="hero__trust">
        <div class="hero__trust-item"><b class="tabular" data-count="<?= total_products() ?>">0</b><span>Products</span></div>
        <div class="hero__trust-sep"></div>
        <div class="hero__trust-item"><b class="tabular" data-count="<?= count(get_categories()) ?>">0</b><span>Categories</span></div>
        <div class="hero__trust-sep"></div>
        <div class="hero__trust-item"><b>ISI · ISO</b><span>Certified</span></div>
        <div class="hero__trust-sep"></div>
        <div class="hero__trust-item"><b class="tabular" data-count="13" data-suffix="+">0</b><span>Years building</span></div>
      </div>
    </div>

    <div class="hero__stage" id="heroStage">
      <div class="hero__ring" data-parallax="0.04"></div>
      <div class="hero__product" data-parallax="0.08">
        <?= category_product_visual('room-heaters', 'hero__product-image', true) ?>
      </div>
      <div class="hero__float hero__float--1" data-parallax="0.16">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 2l2.4 7.4H22l-6 4.6 2.3 7.4-6.3-4.6L5.7 21.4 8 14 2 9.4h7.6z"/></svg>
        Premium quality
      </div>
      <div class="hero__float hero__float--2" data-parallax="0.12">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M13 2L4.5 12.5h6L11 22l8.5-11.5h-6z"/></svg>
        Energy efficient
      </div>
      <div class="hero__float hero__float--3" data-parallax="0.2">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
        1–5 yr warranty
      </div>
    </div>
  </div>

  <div class="hero__scroll" aria-hidden="true">
    <span>Scroll</span>
    <span class="hero__scroll-line"></span>
  </div>
</section>
