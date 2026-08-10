<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';

$co = get_company();
$x  = company_extra();

$seo = [
  'title' => 'About Maurice Appliances — Making every home better tomorrow',
  'desc'  => 'Founded in 2010 in Kullu, Himachal Pradesh, Maurice Appliances manufactures ISI and ISO-certified home appliances across eleven categories from two units in Delhi and Himachal.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'About','url'=>url('pages/about.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-about';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Company','url'=>url('pages/about.php')],
      ['name'=>'About Maurice'],
    ]]); ?>
    <h1>Making every home better tomorrow.</h1>
    <p class="phero__lead">What began in 2010 with a single heat pillar has grown into a nationwide home-appliance brand — <?= total_products() ?> products across eleven categories, built in India and trusted by families, dealers and government buyers alike.</p>
    <div class="phero__meta">
      <div class="phero__meta-item"><b><?= (int)($co['established'] ?? 2010) ?></b><span>Established</span></div>
      <div class="phero__meta-item"><b>2</b><span>Manufacturing units</span></div>
      <div class="phero__meta-item"><b>ISI · ISO</b><span>Certified</span></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap ctwo">
    <div>
      <span class="eyebrow">Who we are</span>
      <h2 style="margin:var(--s-4) 0">An Indian manufacturer, building for Indian homes.</h2>
      <div class="prose">
        <p>Maurice Appliances was established in <?= (int)($co['established'] ?? 2010) ?> in Kullu, Himachal Pradesh, and registered the <strong>maurice</strong> brand in <?= (int)($x['brandRegistered'] ?? 2012) ?>. We design and manufacture home appliances across heating, cooling, water heating and the kitchen.</p>
        <p>We operate two manufacturing units — at Bawana in Delhi and Jia in Kullu — and have been <strong>BIS (ISI) certified since 2017</strong> and an <strong>ISO 9001:2015</strong> certified company. Every product is tested for safety, durability and consistent performance before it carries our name.</p>
        <p>Our work extends beyond retail. We hold rate contracts with the Controller of Stores at Udhyog Bhawan, Shimla, and have supplied <strong>95,000 Rado heat pillars</strong> and <strong>45,000 MIC-21 induction cooktops</strong> to government offices across Himachal Pradesh.</p>
      </div>
    </div>
    <figure class="statement">
      <div class="statement__glow" aria-hidden="true"></div>
      <blockquote>“<?= e($x['philosophy']['quote'] ?? 'Technology should simplify life rather than complicate it.') ?>”</blockquote>
      <figcaption>Our philosophy</figcaption>
    </figure>
  </div>
</section>

<section class="section" style="background:var(--paper)">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">What we stand for</span>
      <h2>Principles that guide every product.</h2>
      <div class="ember-rule"></div>
    </div>
    <div class="vgrid">
      <?php foreach (($x['philosophy']['principles'] ?? []) as $i => $p):
        $parts = explode(' ', $p, 2); ?>
      <div class="vcard reveal reveal-d<?= $i % 3 ?>">
        <span class="vcard__n"><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span>
        <h3><?= e($parts[0]) ?></h3>
        <p><?= e($parts[1] ?? '') ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Manufacturing &amp; quality</span>
      <h2>Made in India, tested to standard.</h2>
      <div class="ember-rule"></div>
    </div>
    <div class="igrid">
      <?php
      $facts = [
        ['Two manufacturing units', 'Bawana in Delhi and Jia in Kullu, Himachal Pradesh — giving us direct control over production and quality.', '<rect x="3" y="9" width="18" height="12" rx="2"/><path d="M7 9V5h10v4M9 21v-5h6v5"/>'],
        ['BIS (ISI) certified', 'Certified since 2017. Our labs were upgraded the same year to support in-house safety and performance testing.', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>'],
        ['ISO 9001:2015', 'An internationally recognised quality-management standard applied across our manufacturing processes.', '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>'],
        ['Government supply', 'Rate-contracted supplier to Himachal Pradesh government departments, including HPBOCW L-1 vendor status.', '<path d="M3 21h18M5 21V10l7-5 7 5v11M9 21v-6h6v6"/>'],
        ['In-house testing', 'Every appliance is checked for electrical safety, thermal performance, durability and energy efficiency.', '<path d="M9 3h6M10 3v6l-5 9a2 2 0 002 3h10a2 2 0 002-3l-5-9V3"/>'],
        ['1–5 year warranty', 'Warranty cover from one to five years depending on category, backed by an expanding service network.', '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'],
      ];
      foreach ($facts as $i => $f): ?>
      <div class="icard reveal reveal-d<?= $i % 3 ?>">
        <span class="icard__ic" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?= $f[2] ?></svg>
        </span>
        <h3><?= e($f[0]) ?></h3>
        <p><?= e($f[1]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php section('cta-dealer'); ?>
<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
