<?php
/**
 * MAURICE APPLIANCES — Single category listing
 */
require_once __DIR__ . '/app/bootstrap/app.php';

$catSlug  = isset($_GET['cat']) ? clean_input((string)$_GET['cat']) : '';
$category = get_category($catSlug);

if (!$category) {
  http_response_code(404);
  require ERRORS_PATH . '/404.php';
  exit;
}

$q        = isset($_GET['q'])        ? clean_input((string)$_GET['q'])    : '';
$sort     = isset($_GET['sort'])     ? clean_input((string)$_GET['sort']) : 'featured';
$bandsSel = isset($_GET['band'])     ? array_map('strval', (array)$_GET['band'])     : [];
$warrSel  = isset($_GET['warranty']) ? array_map('strval', (array)$_GET['warranty']) : [];

$results  = query_products([
  'cat' => $catSlug, 'q' => $q, 'sort' => $sort,
  'bands' => $bandsSel, 'warranties' => $warrSel,
]);

$allInCat   = get_products($catSlug);
$priceMin   = $allInCat ? min(array_map(fn($p) => (int)($p['mrp'] ?? 0), $allInCat)) : 0;
$comingSoon = is_coming_soon($catSlug);

/* Category intro copy */
$catIntros = [
  'room-heaters'       => 'From quartz and halogen to convection, PTC and our signature heat pillars — safe, efficient warmth engineered for Indian winters and BIS-certified for peace of mind.',
  'water-heaters'      => 'Instant and storage geysers with long-life enameled heating elements, superior insulation and multi-layer safety — hot water that lasts, built to a 5-year inner-container standard.',
  'fans'               => 'Ventilation, exhaust and fresh-air fans with computerised-balanced blades and power-saver motors — quiet, durable air movement for every room of the home.',
  'mixer-grinders'     => 'Powerful, long-rated motors with stainless-steel jars and blades — dependable kitchen workhorses built for daily Indian cooking.',
  'gas-stoves'         => 'ISI-marked stainless-steel gas stoves with high-efficiency brass burners and toughened-glass options — reliable, easy-to-clean cooking at the heart of the kitchen.',
  'induction'          => 'Ceramic-glass induction and infrared cooktops with push-button and touch controls, multiple presets and smart safety — fast, precise, energy-efficient heat.',
  'irons'              => 'Dry and steam irons with Teflon-coated soleplates, adjustable thermostats and safety indicators — crisp results, built to last.',
  'chimneys'           => 'Auto-clean and motion-sensor kitchen chimneys with powerful suction and stainless-steel finishes — a clear, smoke-free kitchen, elegantly done.',
  'kitchen-appliances' => 'Kettles, toasters, sandwich makers, atta chakki and more — thoughtfully engineered small appliances that make everyday kitchen life easier.',
  'madhani'            => 'Electric madhani and churners with powerful motors and sharp aluminium blades — traditional preparation, modernised for the contemporary kitchen.',
];
$intro = $catIntros[$catSlug] ?? ($category['name'] . ' — engineered by Maurice for quality, safety and honest value.');

$seo = [
  'title'  => $category['name'] . ' — ' . SITE_NAME,
  'desc'   => str_excerpt($intro, 155),
  'schema' => breadcrumb_schema([
    ['name' => 'Home',              'url' => url('index.php')],
    ['name' => 'Products',          'url' => url('products.php')],
    ['name' => $category['name'],   'url' => url(category_url($catSlug))],
  ]),
];
$pageCss   = ['products.css'];
$pageJs    = ['products.js'];
$bodyClass = 'page-category';

require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs' => [
      ['name' => 'Home',     'url' => url('index.php')],
      ['name' => 'Products', 'url' => url('products.php')],
      ['name' => $category['name']],
    ]]); ?>
    <h1><?= e($category['name']) ?></h1>
    <p class="phero__lead"><?= e($intro) ?></p>
    <div class="phero__meta">
      <?php if ($comingSoon): ?>
      <div class="phero__meta-item"><b class="tabular">&mdash;</b><span>Launching soon</span></div>
      <?php else: ?>
      <div class="phero__meta-item"><b class="tabular"><?= count($allInCat) ?></b><span>Models</span></div>
      <?php if ($priceMin): ?>
      <div class="phero__meta-item"><b><?= e(inr($priceMin)) ?></b><span>Starting from</span></div>
      <?php endif; ?>
      <?php endif; ?>
      <div class="phero__meta-item"><b>ISI</b><span>Certified</span></div>
    </div>
  </div>
</section>

<?php if ($comingSoon): ?>
<section class="wrap plist">
  <div class="csoon">
    <span class="csoon__frame" aria-hidden="true"><?= product_frame($catSlug) ?></span>
    <span class="badge badge--ember">Coming Soon</span>
    <h2><?= e($category['name']) ?> are on the way.</h2>
    <p>We're finalising this range for launch. In the meantime, browse our <?= count(get_categories()) - 1 ?> other categories or get in touch and we'll notify you when it's available.</p>
    <div class="csoon__actions">
      <a href="<?= e(url('products.php')) ?>" class="btn">
        <span>Browse all products</span>
        <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <a href="<?= e(url('pages/contact.php?enquiry=' . urlencode($category['name']))) ?>" class="btn btn--ghost">Notify me</a>
    </div>
  </div>
</section>
<?php else: ?>
<section class="wrap plist">
  <div class="pcats" aria-label="Other categories">
    <a href="<?= e(url('products.php')) ?>" class="pchip">All <span><?= total_products() ?></span></a>
    <?php foreach (get_categories() as $c): ?>
    <?php if (is_coming_soon($c['id'])): ?>
    <span class="pchip pchip--soon" aria-disabled="true"><?= e($c['name']) ?> <span class="badge badge--ember">Soon</span></span>
    <?php else: ?>
    <a href="<?= e(url(category_url($c['id']))) ?>" class="pchip <?= $c['id'] === $catSlug ? 'active' : '' ?>">
      <?= e($c['name']) ?> <span><?= category_count($c['id']) ?></span>
    </a>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <div class="plist__inner">
    <?php component('filters', [
      'items'     => $allInCat,
      'bandsSel'  => $bandsSel,
      'warrSel'   => $warrSel,
      'hiddenCat' => $catSlug,
    ]); ?>

    <div class="pmain">
      <?php component('product-toolbar', [
        'q' => $q, 'sort' => $sort,
        'placeholder' => 'Search in ' . strtolower($category['name']) . '…',
      ]); ?>

      <p class="pcount">
        <b id="resultCount"><?= count($results) ?></b>
        <span id="resultWord"><?= count($results) === 1 ? 'product' : 'products' ?></span>
      </p>

      <div class="pgrid" id="productGrid">
        <?php foreach ($results as $p) component('product-card', ['p' => $p, 'compare' => true]); ?>
      </div>

      <div class="pempty <?= count($results) ? 'is-hidden' : '' ?>" id="emptyState">
        <svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="21" cy="21" r="14" stroke="currentColor" stroke-width="2"/><path d="M31 31l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <h3>No products match your filters</h3>
        <p>Try adjusting or clearing your filters.</p>
        <button class="btn btn--ghost btn--sm" id="resetEmpty" type="button" style="margin-top:1.25rem">Clear filters</button>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
component('compare', ['results' => $results, 'catNameFallback' => $category['name']]);
require LAYOUTS_PATH . '/main-footer.php';
