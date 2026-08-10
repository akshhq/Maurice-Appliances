<?php
/** SECTION: featured products — real SKUs with graceful fallback */
$picks = [
  ['room-heaters',  'rado-1500-watts-heat-pillar'],
  ['water-heaters', 'storage-geyser-25-ltr-glass-line-lava-digital'],
  ['induction',     'mic-21-2000-watts-touch-button'],
];
$featured = [];
foreach ($picks as [$c, $s]) {
  if ($p = get_product($c, $s)) $featured[] = $p;
}
if (count($featured) < 3) {
  foreach (['chimneys', 'gas-stoves', 'fans'] as $c) {
    $list = get_products($c);
    if ($list) $featured[] = $list[0];
    if (count($featured) >= 3) break;
  }
}
if (!$featured) return;
?>
<section class="section featured">
  <div class="wrap">
    <div class="featured__head">
      <div class="section-head" style="margin-bottom:0">
        <span class="eyebrow">Featured</span>
        <h2>Popular across Indian homes.</h2>
        <div class="ember-rule"></div>
      </div>
      <a href="<?= e(url('products.php')) ?>" class="btn btn--ghost btn--sm" data-cursor="All">View all products</a>
    </div>
    <div class="featured__grid">
      <?php foreach ($featured as $p) component('product-card', ['p' => $p]); ?>
    </div>
  </div>
</section>
