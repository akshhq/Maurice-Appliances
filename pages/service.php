<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'After-Sales Service — Maurice Appliances',
  'desc'  => 'Service support for Maurice appliances. Call our customer support team with your model number and purchase details, or raise a request online.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Service','url'=>url('pages/service.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-service';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Support','url'=>url('pages/service.php')],
      ['name'=>'After-Sales Service'],
    ]]); ?>
    <h1>After-sales service.</h1>
    <p class="phero__lead">Our relationship does not end at the sale. Here is how to get your appliance looked at.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">How it works</span>
      <h2>Raising a service request.</h2>
      <div class="ember-rule"></div>
    </div>
    <div class="igrid">
      <?php
      $steps = [
        ['Find your model number','It is printed on the rating label on the body of the appliance, and on your invoice. Have it ready before calling.'],
        ['Call customer support','Ring ' . ($co['phones'][0] ?? '') . ' with your model number, date of purchase and a description of the fault.'],
        ['Keep your invoice','Warranty claims need proof of purchase. A photo of the invoice is fine.'],
        ['We assess the fault','Many issues are resolved over the phone. If not, we arrange inspection or replacement per your warranty terms.'],
        ['In-warranty repair','If the fault is covered, repair or replacement of the defective part is carried out at no charge.'],
        ['Out-of-warranty support','Past warranty, we still help — you will be quoted for parts and labour before any work begins.'],
      ];
      foreach ($steps as $i => $s): ?>
      <div class="icard reveal reveal-d<?= $i % 3 ?>">
        <span class="icard__ic" aria-hidden="true" style="font-family:var(--font-display);font-weight:700;font-size:.9rem"><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span>
        <h3><?= e($s[0]) ?></h3>
        <p><?= e($s[1]) ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="statement" style="margin-top:clamp(2.5rem,2rem+3vw,4rem)">
      <div class="statement__glow" aria-hidden="true"></div>
      <blockquote style="font-size:clamp(1.2rem,1rem+1vw,1.6rem)">Service line: <?= e($co['phones'][0] ?? '') ?></blockquote>
      <figcaption>Customer Support &middot; or email <?= e($co['email'] ?? '') ?></figcaption>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
