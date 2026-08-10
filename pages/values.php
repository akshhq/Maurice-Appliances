<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$x = company_extra();
$values = $x['coreValues'] ?? [];

$seo = [
  'title' => 'Core Values — Maurice Appliances',
  'desc'  => 'Innovation, quality, reliability, affordability, service excellence, integrity, excellence, energy efficiency and global ambition — the nine core values that guide how Maurice Appliances designs, manufactures and serves.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Core Values','url'=>url('pages/values.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-values';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Company','url'=>url('pages/about.php')],
      ['name'=>'Core Values'],
    ]]); ?>
    <h1>Core values</h1>
    <p class="phero__lead">Five principles that decide what we make, how we make it, and how we treat the people who buy it.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="vgrid">
      <?php foreach ($values as $i => $v): ?>
      <div class="vcard reveal reveal-d<?= $i % 3 ?>">
        <span class="vcard__n"><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span>
        <h3><?= e($v['name'] ?? '') ?></h3>
        <p><?= e($v['body'] ?? '') ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php section('cta-dealer'); ?>
<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
