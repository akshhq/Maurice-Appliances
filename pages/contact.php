<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();
$enquiry = isset($_GET['enquiry']) ? clean_input((string)$_GET['enquiry']) : '';

$seo = [
  'title' => 'Contact — Maurice Appliances',
  'desc'  => 'Get in touch with Maurice Appliances customer support. Call, email mauriceappliances@gmail.com, or send us a message. Kullu, Himachal Pradesh.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Contact','url'=>url('pages/contact.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-contact';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Contact'],
    ]]); ?>
    <h1>Talk to us.</h1>
    <p class="phero__lead">Product questions, service requests, dealership enquiries or bulk and government orders — we are glad to help.</p>
  </div>
</section>

<section class="section">
  <div class="wrap ctwo">
    <div>
      <span class="eyebrow">Reach us directly</span>
      <h2 style="margin:var(--s-4) 0 var(--s-6)">Customer Support</h2>
      <div class="cdetails">
        <div class="cdetail">
          <span class="cdetail__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 01-2.2 2 19.8 19.8 0 01-8.6-3.1 19.5 19.5 0 01-6-6A19.8 19.8 0 012.1 4.2 2 2 0 014.1 2h3a2 2 0 012 1.7c.1 1 .4 1.9.7 2.8a2 2 0 01-.4 2.1L8.1 9.9a16 16 0 006 6l1.3-1.3a2 2 0 012.1-.4c.9.3 1.8.6 2.8.7a2 2 0 011.7 2z"/></svg></span>
          <div>
            <p class="cdetail__l">Phone</p>
            <?php foreach (($co['phones'] ?? []) as $ph): ?>
            <p class="cdetail__v"><a href="tel:<?= e(preg_replace('/[^0-9+]/','',$ph)) ?>"><?= e($ph) ?></a></p>
            <?php endforeach; ?>
            <?php if (!empty($co['tollFree'])): ?>
            <p class="cdetail__v"><a href="tel:<?= e(preg_replace('/[^0-9+]/','',$co['tollFree'])) ?>">Toll-Free: <?= e($co['tollFree']) ?></a></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="cdetail">
          <span class="cdetail__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6l-10 7L2 6"/></svg></span>
          <div>
            <p class="cdetail__l">Email</p>
            <p class="cdetail__v"><a href="mailto:<?= e($co['email'] ?? '') ?>"><?= e($co['email'] ?? '') ?></a></p>
            <?php if (!empty($co['infoEmail'])): ?>
            <p class="cdetail__v"><a href="mailto:<?= e($co['infoEmail']) ?>"><?= e($co['infoEmail']) ?></a></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="cdetail">
          <span class="cdetail__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
          <div>
            <p class="cdetail__l">Registered office</p>
            <p class="cdetail__v" style="font-weight:500"><?= e($co['address'] ?? '') ?></p>
          </div>
        </div>
        <div class="cdetail">
          <span class="cdetail__ic" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="9" width="18" height="12" rx="2"/><path d="M7 9V5h10v4"/></svg></span>
          <div>
            <p class="cdetail__l">Manufacturing units</p>
            <p class="cdetail__v" style="font-weight:500">Bawana, Delhi &middot; Jia, Kullu (H.P.)</p>
          </div>
        </div>
      </div>
    </div>

    <div>
      <div class="formcard">
        <h2 style="font-size:var(--fs-h3);margin-bottom:var(--s-5)">Send a message</h2>
        <form data-ajax-form method="post" action="<?= e(url('api/v1/contact.php')) ?>" novalidate>
          <?= csrf_field() ?>
          <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
          <div class="fieldgrid">
            <div class="field">
              <label for="c-name">Name <span class="req">*</span></label>
              <input id="c-name" name="name" type="text" required autocomplete="name">
            </div>
            <div class="field">
              <label for="c-phone">Phone</label>
              <input id="c-phone" name="phone" type="tel" autocomplete="tel">
            </div>
            <div class="field field--full">
              <label for="c-email">Email <span class="req">*</span></label>
              <input id="c-email" name="email" type="email" required autocomplete="email">
            </div>
            <div class="field field--full">
              <label for="c-subject">Subject</label>
              <input id="c-subject" name="subject" type="text" value="<?= attr($enquiry ? 'Enquiry: ' . $enquiry : '') ?>">
            </div>
            <div class="field field--full">
              <label for="c-message">Message <span class="req">*</span></label>
              <textarea id="c-message" name="message" required><?= $enquiry ? e('I would like more information about the ' . $enquiry . '.') : '' ?></textarea>
            </div>
          </div>
          <button type="submit" class="btn" style="margin-top:var(--s-5)">
            <span>Send message</span>
            <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <p class="form-msg" data-form-msg role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
