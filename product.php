<?php
/**
 * MAURICE APPLIANCES — Product detail
 * One template serving every SKU in the catalogue.
 */
require_once __DIR__ . '/app/bootstrap/app.php';

$catSlug  = isset($_GET['cat']) ? clean_input((string)$_GET['cat']) : '';
$slug     = isset($_GET['id'])  ? clean_input((string)$_GET['id'])  : '';
$category = get_category($catSlug);
$product  = $category ? get_product($catSlug, $slug) : null;

if (!$product) {
  http_response_code(404);
  require ERRORS_PATH . '/404.php';
  exit;
}

$related = related_products($catSlug, $slug, 3);
$dims    = parse_dim($product['dim'] ?? '');
$mrp     = (int)($product['mrp'] ?? 0);
$wShort  = warranty_short($product);

$featIcons = [
  '<path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
  '<circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
  '<path d="M13 2L4.5 12.5h6L11 22l8.5-11.5h-6z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
  '<path d="M12 3l2.5 7.5H22l-6 4.5 2.3 7.5-6.3-4.7L5.7 22.5 8 15l-6-4.5h7.5z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
  '<rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
  '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
];

$seo = [
  'title'   => trim($product['model'] . ' ' . $product['title']) . ' — ' . SITE_NAME,
  'desc'    => str_excerpt($product['title'] . ' by Maurice. ' . implode('. ', array_slice($product['specs'] ?? [], 0, 2)) . '. ISI certified, ' . ($product['warranty'] ?? '') . '.', 155),
  'og_type' => 'product',
  'schema'  => [
    '@context'    => 'https://schema.org',
    '@type'       => 'Product',
    'name'        => trim($product['model'] . ' ' . $product['title']),
    'category'    => $category['name'],
    'brand'       => ['@type' => 'Brand', 'name' => 'Maurice'],
    'sku'         => $product['model'],
    'description' => implode('. ', $product['specs'] ?? []),
    'offers'      => [
      '@type'         => 'Offer',
      'price'         => $mrp,
      'priceCurrency' => SITE_CURRENCY,
      'availability'  => 'https://schema.org/InStock',
      'url'           => url(product_url($product)),
    ],
  ],
];
$pageCss   = ['products.css'];
$pageJs    = ['product.js'];
$bodyClass = 'page-product';

require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero" style="padding-bottom:0;border-bottom:none">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs' => [
      ['name' => 'Home',            'url' => url('index.php')],
      ['name' => 'Products',        'url' => url('products.php')],
      ['name' => $category['name'], 'url' => url(category_url($catSlug))],
      ['name' => $product['title']],
    ]]); ?>
  </div>
</section>

<section class="wrap pdp">
  <div class="pdp__grid">
    <div class="pdp__visual">
      <div class="pdp__stage">
        <div class="pdp__badges">
          <span class="badge badge--red">ISI Certified</span>
          <?php if ($wShort): ?><span class="badge badge--ember"><?= e($wShort) ?></span><?php endif; ?>
        </div>
        <?= product_visual($product, 'pdp__image') ?>
      </div>
      <div class="pdp__thumbs" aria-hidden="true">
        <div class="pdp__thumb active"><?= product_visual($product, 'pdp__thumb-image', true) ?></div>
        <div class="pdp__thumb" style="opacity:.5"><?= product_visual($product, 'pdp__thumb-image', true) ?></div>
        <div class="pdp__thumb" style="opacity:.5"><?= product_visual($product, 'pdp__thumb-image', true) ?></div>
      </div>
    </div>

    <div class="pdp__info">
      <p class="pdp__model"><?= e($product['model']) ?></p>
      <h1><?= e($product['title']) ?></h1>

      <div class="pdp__price-row">
        <span class="pdp__price tabular"><?= e(inr($mrp)) ?></span>
        <span class="pdp__price-note">MRP incl. of all taxes</span>
      </div>

      <div class="pdp__tags">
        <span class="badge"><?= e($category['name']) ?></span>
        <?php if (!empty($product['warranty'])): ?><span class="badge badge--ember"><?= e($product['warranty']) ?></span><?php endif; ?>
        <?php if (!empty($product['moq'])): ?><span class="badge">MOQ: <?= e($product['moq']) ?></span><?php endif; ?>
      </div>

      <div class="pdp__highlights">
        <?php foreach (array_slice($product['specs'] ?? [], 0, 4) as $s): ?>
        <div class="pdp__hl">
          <svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M3 10.5l4 4L17 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span><?= e($s) ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="pdp__cta">
        <a href="<?= e(url('pages/contact.php?enquiry=' . urlencode(trim($product['model'] . ' ' . $product['title'])))) ?>" class="btn" data-magnetic data-cursor="Enquire">
          <span>Enquire about this product</span>
          <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="<?= e(url('pages/dealers.php')) ?>" class="btn btn--ghost" data-cursor="Find">Find a dealer</a>
      </div>

      <div class="pdp__quick">
        <?php if (!empty($product['warranty'])): ?>
        <div class="pdp__quick-item"><span class="l">Warranty</span><span class="v"><?= e($wShort ?: $product['warranty']) ?></span></div>
        <?php endif; ?>
        <?php if (!empty($product['weight'])): ?>
        <div class="pdp__quick-item"><span class="l">Weight</span><span class="v"><?= e($product['weight']) ?></span></div>
        <?php endif; ?>
        <?php if (!empty($product['dim'])): ?>
        <div class="pdp__quick-item"><span class="l">Dimensions</span><span class="v"><?= e($product['dim']) ?> mm</span></div>
        <?php endif; ?>
        <div class="pdp__quick-item"><span class="l">Certification</span><span class="v">BIS / ISI</span></div>
        <?php if (!empty($product['moq'])): ?>
        <div class="pdp__quick-item"><span class="l">Min. order</span><span class="v"><?= e($product['moq']) ?></span></div>
        <?php endif; ?>
        <div class="pdp__quick-item"><span class="l">Brand</span><span class="v">Maurice</span></div>
      </div>
    </div>
  </div>

  <div class="pdp__specs">
    <h2>Full specifications</h2>
    <div class="specgrid">
      <table class="spectable">
        <tr><td>Model</td><td><?= e($product['model']) ?></td></tr>
        <tr><td>Product</td><td><?= e($product['title']) ?></td></tr>
        <tr><td>Category</td><td><?= e($category['name']) ?></td></tr>
        <?php if (!empty($product['warranty'])): ?><tr><td>Warranty</td><td><?= e($product['warranty']) ?></td></tr><?php endif; ?>
        <?php if (!empty($product['moq'])): ?><tr><td>Minimum order</td><td><?= e($product['moq']) ?></td></tr><?php endif; ?>
      </table>
      <table class="spectable">
        <?php foreach ($dims as $label => $val): ?>
        <tr><td><?= e($label) ?></td><td><?= e($val) ?></td></tr>
        <?php endforeach; ?>
        <?php if (!empty($product['weight'])): ?><tr><td>Weight</td><td><?= e($product['weight']) ?></td></tr><?php endif; ?>
        <tr><td>Certification</td><td>BIS (ISI) certified</td></tr>
        <tr><td>Country of origin</td><td>India</td></tr>
      </table>
    </div>
  </div>

  <?php if (!empty($product['specs'])): ?>
  <div class="pdp__features">
    <h2>Features &amp; benefits</h2>
    <div class="featgrid">
      <?php foreach ($product['specs'] as $i => $s): ?>
      <div class="featitem reveal reveal-d<?= $i % 3 ?>">
        <span class="featitem__ic"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><?= $featIcons[$i % count($featIcons)] ?></svg></span>
        <p><?= e($s) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</section>

<?php if ($related): ?>
<section class="section related">
  <div class="wrap">
    <div class="featured__head">
      <div class="section-head" style="margin-bottom:0">
        <span class="eyebrow">More in <?= e($category['name']) ?></span>
        <h2>You might also consider</h2>
        <div class="ember-rule"></div>
      </div>
      <a href="<?= e(url(category_url($catSlug))) ?>" class="btn btn--ghost btn--sm" data-cursor="All">View all <?= e($category['name']) ?></a>
    </div>
    <div class="related__grid">
      <?php foreach ($related as $rp) component('product-card', ['p' => $rp]); ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
