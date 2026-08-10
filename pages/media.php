<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'Media &amp; Press — Maurice Appliances',
  'desc'  => 'Press enquiries, brand assets and company facts for journalists and partners covering Maurice Appliances.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Media','url'=>url('pages/media.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-media';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Company','url'=>url('pages/about.php')],
      ['name'=>'Media'],
    ]]); ?>
    <h1>Media &amp; press.</h1>
    <p class="phero__lead">Company facts and contact details for journalists, partners and publications.</p>
  </div>
</section>

<section class="section">
  <div class="wrap ctwo">
    <div>
      <span class="eyebrow">Company facts</span>
      <h2 style="margin:var(--s-4) 0 var(--s-5)">At a glance</h2>
      <table class="spectable">
        <tr><td>Company</td><td><?= e($co['name'] ?? '') ?></td></tr>
        <tr><td>Established</td><td><?= (int)($co['established'] ?? 2010) ?></td></tr>
        <tr><td>Brand registered</td><td><?= (int)(company_extra()['brandRegistered'] ?? 2012) ?></td></tr>
        <tr><td>Headquarters</td><td>Kullu, Himachal Pradesh, India</td></tr>
        <tr><td>Manufacturing</td><td>Bawana (Delhi) &middot; Jia, Kullu (H.P.)</td></tr>
        <tr><td>Certification</td><td>BIS (ISI) since 2017 &middot; ISO 9001:2015</td></tr>
        <tr><td>Product range</td><td><?= total_products() ?> models across <?= count(get_categories()) ?> categories</td></tr>
        <tr><td>Sector</td><td>Home appliances manufacturing</td></tr>
      </table>
    </div>
    <div>
      <span class="eyebrow">Press enquiries</span>
      <h2 style="margin:var(--s-4) 0 var(--s-5)">Get in touch</h2>
      <div class="cdetails">
        <div class="cdetail">
          <span class="cdetail__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg></span>
          <div>
            <p class="cdetail__l">Email</p>
            <p class="cdetail__v"><a href="mailto:<?= e($co['email'] ?? '') ?>"><?= e($co['email'] ?? '') ?></a></p>
          </div>
        </div>
        <div class="cdetail">
          <span class="cdetail__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.4 2.1L8.1 9.9a16 16 0 006 6l1.3-1.3a2 2 0 012.1-.4c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg></span>
          <div>
            <p class="cdetail__l">Phone</p>
            <p class="cdetail__v"><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['phones'][0] ?? '')) ?>"><?= e($co['phones'][0] ?? '') ?></a></p>
          </div>
        </div>
      </div>
      <div class="prose" style="margin-top:var(--s-6)">
        <p>For brand assets, product photography or interview requests, email us with your publication, deadline and what you need.</p>
      </div>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
