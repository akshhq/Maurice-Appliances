<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'Dealer Network — Maurice Appliances',
  'desc'  => 'Maurice Appliances supplies dealers, distributors and government departments across India, including rate contracts in Himachal Pradesh. Find or become a dealer.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Dealers','url'=>url('pages/dealers.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-dealers';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Dealers'],
    ]]); ?>
    <h1>Our dealer network.</h1>
    <p class="phero__lead">Maurice products reach homes through retail dealers, distributors and institutional supply contracts across India.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Institutional supply</span>
      <h2>Trusted by government departments.</h2>
      <p class="lead">Alongside our retail network, we hold rate contracts and have completed large institutional orders.</p>
      <div class="ember-rule"></div>
    </div>
    <div class="igrid">
      <?php
      $items = [
        ['95,000 heat pillars','Rado heat pillars supplied with one-year warranty to government offices across Himachal Pradesh.'],
        ['45,000 induction cooktops','MIC-21 induction units supplied to Sericulture offices throughout Himachal Pradesh.'],
        ['9,600 unit order','A further order of Rado heat pillars received from the Himachal Pradesh government in 2019.'],
        ['HPBOCW L - 1 vendor','Rate contract L-1 vendor status with the Himachal Pradesh Building &amp; Other Construction Workers Board.'],
        ['Controller of Stores','Rate contract with the Controller of Stores, Udhyog Bhawan, Shimla.'],
        ['Retail dealers','An expanding network of retail dealers and distributors across northern India.'],
      ];
      foreach ($items as $i => $it): ?>
      <div class="icard reveal reveal-d<?= $i % 3 ?>">
        <span class="icard__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg></span>
        <h3><?= $it[0] ?></h3>
        <p><?= $it[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Where we operate</span>
      <h2>A growing footprint across India.</h2>
      <div class="ember-rule"></div>
    </div>
    <?php partial('india-map', ['size' => 'compact']); ?>
  </div>
</section>

<section class="section" style="background:var(--paper)">
  <div class="wrap ctwo">
    <div>
      <span class="eyebrow">Looking for a dealer?</span>
      <h2 style="margin:var(--s-4) 0">Find Maurice products near you.</h2>
      <div class="prose">
        <p>We are building a searchable dealer locator. In the meantime, call our customer support team and we will point you to your nearest stockist.</p>
      </div>
      <div style="display:flex;gap:var(--s-4);flex-wrap:wrap;margin-top:var(--s-6)">
        <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['phones'][0] ?? '')) ?>" class="btn"><span><?= e($co['phones'][0] ?? '') ?></span></a>
        <a href="<?= e(url('pages/contact.php')) ?>" class="btn btn--ghost">Contact us</a>
      </div>
    </div>
    <div>
      <span class="eyebrow">Want to stock Maurice?</span>
      <h2 style="margin:var(--s-4) 0">Join the network.</h2>
      <div class="prose">
        <p>We are actively appointing dealers and distributors across India. An eleven-category portfolio, ISI-certified products, competitive margins and OEM capability.</p>
      </div>
      <a href="<?= e(url('pages/become-dealer.php')) ?>" class="btn" style="margin-top:var(--s-6)">
        <span>Apply to become a dealer</span>
        <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
