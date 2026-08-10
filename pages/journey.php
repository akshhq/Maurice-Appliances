<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';

$x = company_extra();
$journey = $x['journey'] ?? [];

$seo = [
  'title' => 'Our Journey — Maurice Appliances since 2012',
  'desc'  => 'From our first heat pillar in 2012 to BIS certification, government supply contracts and a full home portfolio — the Maurice Appliances story year by year.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Our Journey','url'=>url('pages/journey.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-journey';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero phero--journey">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Company','url'=>url('pages/about.php')],
      ['name'=>'Our Journey'],
    ]]); ?>
    <h1>Innovation, quality and trust — year by year.</h1>
    <p class="phero__lead">Every milestone below is a product launched, a standard met or a partnership earned. This is how a single heat pillar became a complete home portfolio.</p>
    <div class="phero__meta">
      <div class="phero__meta-item"><b><?= count($journey) ?></b><span>Milestone years</span></div>
      <div class="phero__meta-item"><b><?= total_products() ?></b><span>Products today</span></div>
    </div>
  </div>
  <svg class="phero__art" viewBox="0 0 220 200" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
    <path d="M20 170V95l35-24 35 24v10l35-24 35 24v75z" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/>
    <path d="M55 170v-40h30v40M125 170v-30h30v30" fill="none" stroke="var(--red)" stroke-width="2"/>
    <path d="M55 71V50h14v21M125 71V56h14v15" fill="#fff" stroke="var(--ink)" stroke-width="2"/>
    <path d="M12 170h196" stroke="var(--ink)" stroke-width="2.5" stroke-linecap="round"/>
    <path d="M30 60l14 16 20-26 22 20 30-34" fill="none" stroke="var(--ember)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
    <path d="M96 20h20v20" stroke="var(--ember)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</section>

<section class="section">
  <div class="wrap">
    <div class="tl">
      <?php foreach ($journey as $entry): ?>
      <div class="tl__item reveal">
        <p class="tl__year"><?= e((string)($entry['year'] ?? '')) ?></p>
        <ul class="tl__events">
          <?php foreach (($entry['events'] ?? []) as $ev): ?>
          <li>
            <?= milestone_icon_svg($ev) ?>
            <span><?= e($ev) ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php section('cta-dealer'); ?>
<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
