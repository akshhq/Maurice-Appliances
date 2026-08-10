<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'Careers — Maurice Appliances',
  'desc'  => 'Work with Maurice Appliances across manufacturing, quality, sales and service at our units in Delhi and Himachal Pradesh.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Careers','url'=>url('pages/careers.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-careers';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Company','url'=>url('pages/about.php')],
      ['name'=>'Careers'],
    ]]); ?>
    <h1>Build things that last.</h1>
    <p class="phero__lead">We hire across manufacturing, quality, sales and service at our units in Bawana, Delhi and Jia, Kullu.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Where we hire</span>
      <h2>Areas we recruit for.</h2>
      <div class="ember-rule"></div>
    </div>
    <div class="igrid">
      <?php
      $areas = [
        ['Manufacturing &amp; production','Assembly, fabrication and production supervision at our Delhi and Himachal units.'],
        ['Quality assurance','Inspection, laboratory testing and BIS compliance across the product range.'],
        ['Sales &amp; distribution','Territory sales, dealer development and institutional supply.'],
        ['Service &amp; support','Field service engineers and customer support for our expanding network.'],
        ['Design &amp; development','Product engineering, tooling and new model development.'],
        ['Operations &amp; supply chain','Procurement, inventory, logistics and vendor management.'],
      ];
      foreach ($areas as $i => $a): ?>
      <div class="icard reveal reveal-d<?= $i % 3 ?>">
        <span class="icard__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-4V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M10 7V5h4v2"/></svg></span>
        <h3><?= $a[0] ?></h3>
        <p><?= $a[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="statement" style="margin-top:clamp(2.5rem,2rem+3vw,4rem)">
      <div class="statement__glow" aria-hidden="true"></div>
      <blockquote style="font-size:clamp(1.15rem,1rem+.9vw,1.5rem)">No open role that fits? Send your CV anyway — we keep good applications on file.</blockquote>
      <figcaption>
        Email <a href="mailto:<?= e($co['email'] ?? '') ?>" style="color:var(--ember);text-decoration:underline"><?= e($co['email'] ?? '') ?></a>
        with the role you are interested in
      </figcaption>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
