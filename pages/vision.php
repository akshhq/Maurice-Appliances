<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$x = company_extra();

$seo = [
  'title' => 'Vision &amp; Mission — Maurice Appliances',
  'desc'  => str_excerpt($x['vision'] ?? 'Our vision and mission at Maurice Appliances.', 155),
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Vision & Mission','url'=>url('pages/vision.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-vision';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Company','url'=>url('pages/about.php')],
      ['name'=>'Vision &amp; Mission'],
    ]]); ?>
    <h1>Vision &amp; Mission</h1>
    <p class="phero__lead">Where we are going, and how we intend to get there.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <figure class="statement" style="margin-bottom:clamp(2.5rem,2rem+3vw,4rem)">
      <div class="statement__glow" aria-hidden="true"></div>
      <blockquote><?= e($x['vision'] ?? '') ?></blockquote>
      <figcaption>Our vision</figcaption>
    </figure>

    <div class="section-head">
      <span class="eyebrow">Our mission</span>
      <h2>Five commitments we hold ourselves to.</h2>
      <div class="ember-rule"></div>
    </div>
    <ol class="nlist">
      <?php foreach (($x['missions'] ?? []) as $m): ?>
      <li class="reveal"><span><?= e($m) ?></span></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section" style="background:var(--paper)">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Our philosophy</span>
      <h2><?= e($x['philosophy']['quote'] ?? '') ?></h2>
      <p class="lead">These principles shape what we build and how we build it.</p>
      <div class="ember-rule"></div>
    </div>
    <div class="prose">
      <ul>
        <?php foreach (($x['philosophy']['principles'] ?? []) as $p): ?>
        <li><?= e($p) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>

<?php section('cta-dealer'); ?>
<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
