<?php
/**
 * MAURICE APPLIANCES — Product catalogue (all categories)
 */
require_once __DIR__ . '/app/bootstrap/app.php';

$cats      = get_categories();
$activeCat = isset($_GET['cat'])      ? clean_input((string)$_GET['cat'])  : '';
$q         = isset($_GET['q'])        ? clean_input((string)$_GET['q'])    : '';
$sort      = isset($_GET['sort'])     ? clean_input((string)$_GET['sort']) : 'featured';
$bandsSel  = isset($_GET['band'])     ? array_map('strval', (array)$_GET['band'])     : [];
$warrSel   = isset($_GET['warranty']) ? array_map('strval', (array)$_GET['warranty']) : [];

$results = query_products([
  'cat'        => $activeCat,
  'q'          => $q,
  'sort'       => $sort,
  'bands'      => $bandsSel,
  'warranties' => $warrSel,
]);

$activeCatName = $activeCat ? (get_category($activeCat)['name'] ?? 'Products') : 'All products';

$seo = [
  'title'  => ($activeCat ? $activeCatName . ' — ' : '') . 'Products — ' . SITE_NAME,
  'desc'   => 'Browse ' . total_products() . ' ISI-certified Maurice home appliances across eleven categories. Filter by price and warranty, compare models and find the right fit for your home.',
  'schema' => breadcrumb_schema([
    ['name' => 'Home',     'url' => url('index.php')],
    ['name' => 'Products', 'url' => url('products.php')],
  ]),
];
$pageCss   = ['products.css'];
$pageJs    = ['products.js'];
$bodyClass = 'page-products';

require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs' => [
      ['name' => 'Home', 'url' => url('index.php')],
      ['name' => 'Products'],
    ]]); ?>
    <h1>Appliances for every corner of the home.</h1>
    <p class="phero__lead">From heating and cooling to the heart of the kitchen — <?= total_products() ?> ISI-certified models, each engineered for safety, efficiency and honest everyday value.</p>
    <div class="phero__meta">
      <div class="phero__meta-item"><b class="tabular"><?= total_products() ?></b><span>Products</span></div>
      <div class="phero__meta-item"><b><?= count($cats) ?></b><span>Categories</span></div>
      <div class="phero__meta-item"><b>ISI · ISO</b><span>Certified</span></div>
    </div>
  </div>
</section>

<section class="wrap plist">
  <div class="pcats" aria-label="Filter by category">
    <a href="<?= e(url('products.php')) ?>" class="pchip <?= $activeCat === '' ? 'active' : '' ?>">All <span><?= total_products() ?></span></a>
    <?php foreach ($cats as $c): ?>
    <?php if (is_coming_soon($c['id'])): ?>
    <span class="pchip pchip--soon" aria-disabled="true"><?= e($c['name']) ?> <span class="badge badge--ember">Soon</span></span>
    <?php else: ?>
    <a href="<?= e(url('products.php?cat=' . urlencode($c['id']))) ?>" class="pchip <?= $activeCat === $c['id'] ? 'active' : '' ?>">
      <?= e($c['name']) ?> <span><?= category_count($c['id']) ?></span>
    </a>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <div class="plist__inner">
    <?php component('filters', [
      'items'     => $activeCat ? get_products($activeCat) : all_products(),
      'bandsSel'  => $bandsSel,
      'warrSel'   => $warrSel,
      'hiddenCat' => $activeCat ?: null,
    ]); ?>

    <div class="pmain">
      <?php component('product-toolbar', ['q' => $q, 'sort' => $sort]); ?>

      <p class="pcount">
        <b id="resultCount"><?= count($results) ?></b>
        <span id="resultWord"><?= count($results) === 1 ? 'product' : 'products' ?></span><?= $activeCat ? ' in ' . e($activeCatName) : '' ?>
      </p>

      <div class="pgrid" id="productGrid">
        <?php foreach ($results as $p) component('product-card', ['p' => $p, 'compare' => true]); ?>
      </div>

      <div class="pempty <?= count($results) ? 'is-hidden' : '' ?>" id="emptyState">
        <svg viewBox="0 0 48 48" fill="none" aria-hidden="true"><circle cx="21" cy="21" r="14" stroke="currentColor" stroke-width="2"/><path d="M31 31l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        <h3>No products match your filters</h3>
        <p>Try adjusting the price or warranty filters, or clearing your search.</p>
        <button class="btn btn--ghost btn--sm" id="resetEmpty" type="button" style="margin-top:1.25rem">Clear filters</button>
      </div>
    </div>
  </div>
</section>

<?php
component('compare', ['results' => $results]);
require LAYOUTS_PATH . '/main-footer.php';
