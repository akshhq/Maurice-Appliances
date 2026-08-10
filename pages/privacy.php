<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'Privacy Policy — Maurice Appliances',
  'desc'  => 'How Maurice Appliances collects, uses and protects personal information submitted through this website.',
  'robots'=> 'index,follow',
];
$pageCss = ['content.css'];
$bodyClass = 'page-legal';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Privacy Policy'],
    ]]); ?>
    <h1>Privacy policy</h1>
    <p class="phero__lead">How we handle information you share with us through this website.</p>
  </div>
</section>

<section class="section">
  <div class="wrap prose">
    <p class="muted">Last updated <?= date('F Y') ?></p>

    <h2>Who we are</h2>
    <p><?= e($co['name'] ?? '') ?>, <?= e($co['address'] ?? '') ?>. You can reach us on <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['phones'][0] ?? '')) ?>"><?= e($co['phones'][0] ?? '') ?></a> or at <a href="mailto:<?= e($co['email'] ?? '') ?>"><?= e($co['email'] ?? '') ?></a>.</p>

    <h2>What we collect</h2>
    <p>We only collect what you actively submit through a form on this website:</p>
    <ul>
      <li><strong>Contact and enquiry forms</strong> — your name, email address, phone number and message.</li>
      <li><strong>Dealer applications</strong> — your name, firm name, email, phone, city, state and any business details you provide.</li>
      <li><strong>Newsletter</strong> — your email address.</li>
    </ul>
    <p>Our web server also records standard technical data such as your IP address and browser type, which is normal for any website and is used for security and diagnostics.</p>

    <h2>Why we use it</h2>
    <ul>
      <li>To respond to your enquiry or service request.</li>
      <li>To assess and follow up on dealer applications.</li>
      <li>To send product updates if you have subscribed — and only until you ask us to stop.</li>
      <li>To keep the website secure and functioning.</li>
    </ul>

    <h2>What we do not do</h2>
    <ul>
      <li>We do not sell your personal information.</li>
      <li>We do not share it with third parties for their own marketing.</li>
      <li>We do not run advertising trackers or sell profiling data.</li>
      <li>We do not collect payment details on this website — there is no checkout.</li>
    </ul>

    <h2>Cookies</h2>
    <p>This website uses a single session cookie, required to keep forms secure against cross-site request forgery. It contains no personal information and expires when you close your browser. We do not use advertising or third-party tracking cookies.</p>
    <p>Some resources — web fonts and animation libraries — are loaded from third-party content delivery networks, which necessarily receive your IP address in order to serve those files.</p>

    <h2>How long we keep it</h2>
    <p>Enquiries and applications are retained for as long as needed to deal with your request and to meet our record-keeping obligations. Newsletter subscriptions are kept until you unsubscribe.</p>

    <h2>Your rights</h2>
    <p>You may ask us to provide a copy of the information we hold about you, correct it if it is wrong, or delete it. Email <a href="mailto:<?= e($co['email'] ?? '') ?>"><?= e($co['email'] ?? '') ?></a> and we will respond within a reasonable period.</p>

    <h2>Security</h2>
    <p>Form submissions are protected against cross-site request forgery and rate-abuse. Data is held on our hosting provider's servers. No transmission over the internet can be guaranteed completely secure, but we take reasonable technical measures to protect what you send us.</p>

    <h2>Changes</h2>
    <p>If this policy changes we will update the date at the top of this page.</p>

    <h2>Contact</h2>
    <p>Questions about this policy: <a href="mailto:<?= e($co['email'] ?? '') ?>"><?= e($co['email'] ?? '') ?></a> or <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['phones'][0] ?? '')) ?>"><?= e($co['phones'][0] ?? '') ?></a>.</p>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
