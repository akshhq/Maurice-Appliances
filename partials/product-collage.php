<?php
/**
 * PARTIAL: infinite-scroll product showcase.
 * Every product (shuffled, not grouped by category) as a labelled
 * illustration card in a seamlessly looping marquee. Not shown on the
 * homepage or product/category/listing pages (see layouts/main-footer.php),
 * which already show real product grids.
 *
 * The track is rendered twice (second copy aria-hidden + untabbable) so the
 * CSS animation can loop by translating exactly -50% with no visible seam.
 * Each card uses the matching product media asset from /media, with a
 * category illustration fallback when a file is missing.
 */
$showcaseProducts = all_products();
shuffle($showcaseProducts);
?>
<section class="section showcase-section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">The full range</span>
      <h2>Explore the complete Maurice collection.</h2>
      <div class="ember-rule"></div>
    </div>
  </div>

  <div class="showcase" role="region" aria-label="Product showcase — all models">
    <div class="showcase__fade showcase__fade--left" aria-hidden="true"></div>
    <div class="showcase__fade showcase__fade--right" aria-hidden="true"></div>
    <div class="showcase__track">
      <?php for ($copy = 0; $copy < 2; $copy++): ?>
      <div class="showcase__set"<?= $copy === 1 ? ' aria-hidden="true"' : '' ?>>
        <?php foreach ($showcaseProducts as $p): ?>
        <a class="showcase__card" href="<?= e(url(product_url($p))) ?>"<?= $copy === 1 ? ' tabindex="-1"' : '' ?>>
          <span class="showcase__frame"><?= product_visual($p, 'showcase__image') ?></span>
          <span class="showcase__name"><?= e(trim(($p['model'] ?? '') . ' ' . ($p['title'] ?? ''))) ?></span>
          <span class="showcase__price"><?= e(inr((int)($p['mrp'] ?? 0))) ?></span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
