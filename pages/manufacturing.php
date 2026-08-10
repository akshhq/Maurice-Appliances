<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'Manufacturing &amp; Quality — Maurice Appliances',
  'desc'  => 'Two manufacturing units in Delhi and Himachal Pradesh, BIS (ISI) certification since 2017 and ISO 9001:2015 quality management — how Maurice builds appliances.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Manufacturing','url'=>url('pages/manufacturing.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-manufacturing';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Company','url'=>url('pages/about.php')],
      ['name'=>'Manufacturing &amp; Quality'],
    ]]); ?>
    <h1>Manufacturing &amp; quality.</h1>
    <p class="phero__lead">We manufacture in our own units rather than outsourcing, which gives us direct control over materials, process and final testing.</p>
    <div class="phero__meta">
      <div class="phero__meta-item"><b>2</b><span>Units</span></div>
      <div class="phero__meta-item"><b>2017</b><span>BIS certified</span></div>
      <div class="phero__meta-item"><b>9001:2015</b><span>ISO standard</span></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap ctwo">
    <div>
      <span class="eyebrow">Our units</span>
      <h2 style="margin:var(--s-4) 0">Two facilities, one standard.</h2>
      <div class="prose">
        <p>Our manufacturing is split across two locations established in 2015: <strong>Bawana, Delhi</strong> and <strong>Jia, Kullu (Himachal Pradesh)</strong>. Between them they cover fabrication, assembly, finishing and testing for our full range.</p>
        <p>Producing in-house means material selection, tolerances and finish are decided by us rather than a third party — and when a specification changes, it changes at source.</p>
      </div>
    </div>
    <div>
      <span class="eyebrow">Certification</span>
      <h2 style="margin:var(--s-4) 0">Tested to recognised standards.</h2>
      <div class="prose">
        <p>We obtained <strong>BIS (ISI) certification in 2017</strong> and upgraded our testing laboratories in the same year. Products carrying the ISI mark meet the Bureau of Indian Standards requirements for their category.</p>
        <p>We are also an <strong>ISO 9001:2015</strong> certified company, the international standard for quality-management systems.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--paper)">
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Quality process</span>
      <h2>What every appliance goes through.</h2>
      <div class="ember-rule"></div>
    </div>
    <div class="igrid">
      <?php
      $steps = [
        ['Material selection','Components and raw materials are specified and inspected before they enter production — from FR-grade plastics to copper heating elements.'],
        ['Precision fabrication','Bodies, elements and assemblies are manufactured to controlled tolerances in our own units.'],
        ['Electrical safety testing','Insulation, earthing continuity and load behaviour are verified against BIS requirements.'],
        ['Thermal &amp; performance testing','Heating output, temperature cut-offs and efficiency are measured in our upgraded labs.'],
        ['Durability checks','Switches, thermostats and moving assemblies are cycled to confirm working life.'],
        ['Final inspection','Fit, finish, labelling and packaging are checked before dispatch, with ISI marking applied where certified.'],
      ];
      foreach ($steps as $i => $s): ?>
      <div class="icard reveal reveal-d<?= $i % 3 ?>">
        <span class="icard__ic" aria-hidden="true" style="font-family:var(--font-display);font-weight:700;font-size:.9rem"><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span>
        <h3><?= $s[0] ?></h3>
        <p><?= $s[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php section('cta-dealer'); ?>
<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
