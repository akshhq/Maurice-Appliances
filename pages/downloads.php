<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';

/* Files are served from /assets/downloads/. Missing files render as
   "coming soon" rather than a broken link. */
$docs = [
  ['Product catalogue', 'Complete Maurice range across all eleven categories.', 'maurice-catalogue.pdf'],
  ['Company profile',   'Who we are, our journey, certifications and capability.', 'maurice-company-profile.pdf'],
  ['Price list ' . date('Y'), 'Current MRP list across the full range.', 'maurice-price-list.pdf'],
  ['Dealer information pack', 'Margins, terms and support for prospective partners.', 'maurice-dealer-pack.pdf'],
];

$seo = [
  'title' => 'Downloads — Maurice Appliances',
  'desc'  => 'Download the Maurice Appliances product catalogue, company profile, price list and dealer information pack.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Downloads','url'=>url('pages/downloads.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-downloads';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Support','url'=>url('pages/service.php')],
      ['name'=>'Downloads'],
    ]]); ?>
    <h1>Downloads.</h1>
    <p class="phero__lead">Catalogues, specifications and dealer material.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div style="display:grid;gap:var(--s-4);max-width:820px">
      <?php foreach ($docs as $d):
        $path = PUBLIC_PATH . '/assets/downloads/' . $d[2];
        $exists = is_readable($path);
        $size = $exists ? number_format(filesize($path) / 1048576, 1) . ' MB' : null;
        $tag = $exists ? 'a' : 'div';
      ?>
      <<?= $tag ?> class="drow"<?= $exists ? ' href="' . e(url('assets/downloads/' . $d[2])) . '" download' : ' style="opacity:.62"' ?>>
        <span class="drow__ic" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        </span>
        <div>
          <p class="drow__t"><?= e($d[0]) ?></p>
          <p class="drow__m"><?= e($d[1]) ?><?= $size ? ' · PDF · ' . $size : '' ?></p>
        </div>
        <span class="drow__go"><?= $exists ? 'Download &darr;' : 'Coming soon' ?></span>
      </<?= $tag ?>>
      <?php endforeach; ?>
    </div>

    <div class="prose" style="margin-top:clamp(2.5rem,2rem+3vw,3.5rem)">
      <p>Need a specification sheet for a particular model? Every product page lists full specifications, dimensions and warranty terms. If you need something in another format, <a href="<?= e(url('pages/contact.php')) ?>">contact us</a>.</p>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
