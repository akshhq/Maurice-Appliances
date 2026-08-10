<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'Become a Dealer — Maurice Appliances',
  'desc'  => 'Partner with Maurice Appliances. An eleven-category portfolio, OEM and private-label capability, strong margins and dependable after-sales support. Apply to join our dealer network.',
  'schema'=> breadcrumb_schema([
    ['name'=>'Home','url'=>url('index.php')],
    ['name'=>'Become a Dealer','url'=>url('pages/become-dealer.php')],
  ]),
];
$pageCss = ['content.css'];
$bodyClass = 'page-dealer-apply';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Dealers','url'=>url('pages/dealers.php')],
      ['name'=>'Become a Dealer'],
    ]]); ?>
    <h1>Grow with a trusted Indian brand.</h1>
    <p class="phero__lead">We are expanding our dealer and distributor network across India. If you sell home appliances, we would like to hear from you.</p>
  </div>
</section>

<section class="section section--tight">
  <div class="wrap">
    <div class="section-head" style="text-align:center;margin-inline:auto">
      <span class="eyebrow">Where we build</span>
      <h2>Manufactured in India, sold across it.</h2>
      <div class="ember-rule" style="margin-inline:auto"></div>
    </div>
    <?php partial('india-map', ['size' => 'hero']); ?>
  </div>
</section>

<section class="section">
  <div class="wrap ctwo">
    <div>
      <span class="eyebrow">Why partner with us</span>
      <h2 style="margin:var(--s-4) 0 var(--s-6)">What you get.</h2>
      <ul class="blist">
        <?php
        $benefits = [
          ['A complete portfolio', total_products() . ' products across eleven categories — heating, cooling, water heating and the kitchen — so one supplier covers the whole home.'],
          ['ISI &amp; ISO certified', 'BIS (ISI) certified products from an ISO 9001:2015 company, which makes the sell-through conversation far easier.'],
          ['Healthy margins', 'Competitive dealer pricing with volume-based structures and clearly stated minimum order quantities.'],
          ['OEM &amp; private label', 'We manufacture in our own units and can produce under your brand where volumes justify it.'],
          ['Marketing support', 'Product catalogues, specification sheets and point-of-sale material to support your retail floor.'],
          ['After-sales backing', 'Warranty support from one to five years by category, with an expanding service network.'],
        ];
        foreach ($benefits as $b): ?>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>
          <div><b><?= $b[0] ?></b><span><?= $b[1] ?></span></div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div>
      <div class="formcard">
        <h2 style="font-size:var(--fs-h3);margin-bottom:var(--s-5)">Dealer application</h2>
        <form data-ajax-form method="post" action="<?= e(url('api/v1/dealer.php')) ?>" novalidate>
          <?= csrf_field() ?>
          <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
          <div class="fieldgrid">
            <div class="field">
              <label for="d-name">Contact name <span class="req">*</span></label>
              <input id="d-name" name="name" type="text" required autocomplete="name">
            </div>
            <div class="field">
              <label for="d-firm">Firm name <span class="req">*</span></label>
              <input id="d-firm" name="firm" type="text" required autocomplete="organization">
            </div>
            <div class="field">
              <label for="d-email">Email <span class="req">*</span></label>
              <input id="d-email" name="email" type="email" required autocomplete="email">
            </div>
            <div class="field">
              <label for="d-phone">Phone <span class="req">*</span></label>
              <input id="d-phone" name="phone" type="tel" required autocomplete="tel">
            </div>
            <div class="field">
              <label for="d-city">City <span class="req">*</span></label>
              <input id="d-city" name="city" type="text" required autocomplete="address-level2">
            </div>
            <div class="field">
              <label for="d-state">State <span class="req">*</span></label>
              <input id="d-state" name="state" type="text" required autocomplete="address-level1">
            </div>
            <div class="field field--full">
              <label for="d-type">Business type</label>
              <select id="d-type" name="business_type">
                <option value="">Select…</option>
                <option>Retailer</option>
                <option>Distributor</option>
                <option>Wholesaler</option>
                <option>Institutional / Government supplier</option>
                <option>Online seller</option>
                <option>Other</option>
              </select>
            </div>
            <div class="field field--full">
              <label for="d-cats">Categories of interest</label>
              <input id="d-cats" name="categories" type="text" placeholder="e.g. room heaters, water heaters, chimneys">
            </div>
            <div class="field field--full">
              <label for="d-msg">Anything else?</label>
              <textarea id="d-msg" name="message" placeholder="Years in business, existing brands carried, expected volumes…"></textarea>
            </div>
          </div>
          <button type="submit" class="btn" style="margin-top:var(--s-5)">
            <span>Submit application</span>
            <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <p class="form-msg" data-form-msg role="status" aria-live="polite"></p>
          <p class="field__hint" style="margin-top:var(--s-4)">Or call us directly on <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['phones'][0] ?? '')) ?>" style="color:var(--red);font-weight:600"><?= e($co['phones'][0] ?? '') ?></a>.</p>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
