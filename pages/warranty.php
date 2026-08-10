<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

/* Warranty terms grouped by what the catalogue actually states */
$groups = [];
foreach (get_categories() as $c) {
  $years = [];
  foreach (get_products($c['id']) as $p) {
    $w = trim($p['warranty'] ?? '');
    if ($w !== '') $years[$w] = true;
  }
  if ($years) $groups[$c['name']] = array_keys($years);
}

$seo = [
  'title' => 'Warranty — Maurice Appliances',
  'desc'  => 'Warranty cover for Maurice appliances runs from one to five years depending on category. See the terms that apply to your product.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Warranty','url'=>url('pages/warranty.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-warranty';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Support','url'=>url('pages/service.php')],
      ['name'=>'Warranty'],
    ]]); ?>
    <h1>Warranty.</h1>
    <p class="phero__lead">Cover runs from one to five years depending on the product category. The exact term for your appliance is printed on its packaging and listed on its product page.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">By category</span>
      <h2>Warranty terms across the range.</h2>
      <div class="ember-rule"></div>
    </div>

    <table class="spectable" style="max-width:900px">
      <?php foreach ($groups as $cat => $terms): ?>
      <tr>
        <td style="width:34%"><?= e($cat) ?></td>
        <td><?= e(implode(' · ', $terms)) ?></td>
      </tr>
      <?php endforeach; ?>
    </table>

    <div class="prose" style="margin-top:clamp(2.5rem,2rem+3vw,3.5rem)">
      <h2>What is covered</h2>
      <ul>
        <li>Manufacturing defects in materials and workmanship under normal domestic use.</li>
        <li>Repair or replacement of defective parts, at our discretion, during the warranty period.</li>
        <li>Where stated, extended cover on specific components — for example the inner container on storage water heaters.</li>
      </ul>

      <h2>What is not covered</h2>
      <ul>
        <li>Damage from misuse, accident, neglect, or use outside the stated rating.</li>
        <li>Damage from incorrect installation, voltage fluctuation, or connection to an unsuitable supply.</li>
        <li>Normal wear on consumable parts, and cosmetic damage that does not affect function.</li>
        <li>Repairs or modifications carried out by anyone not authorised by Maurice Appliances.</li>
        <li>Products where the model or serial marking has been removed or altered.</li>
        <li>Damage from scale, corrosive water or hard-water deposits where descaling was not maintained.</li>
      </ul>

      <h2>Making a claim</h2>
      <p>Call our customer support team on <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['phones'][0] ?? '')) ?>"><?= e($co['phones'][0] ?? '') ?></a> with your model number, date of purchase and proof of purchase. Keep your invoice — warranty claims cannot be processed without it.</p>
      <p>Full terms are supplied with the product. Where the printed warranty card differs from this page, the printed card applies.</p>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
