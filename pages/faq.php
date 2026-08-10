<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();
$phone = $co['phones'][0] ?? '';

$faqs = [
  ['Are Maurice appliances ISI certified?',
   'Yes. Maurice has been BIS (ISI) certified since 2017, and we are an ISO 9001:2015 certified company. Products carrying the ISI mark meet the Bureau of Indian Standards requirements for their category.'],
  ['Where are Maurice products manufactured?',
   'In our own two units — Bawana in Delhi and Jia in Kullu, Himachal Pradesh. We manufacture in-house rather than outsourcing, which gives us direct control over materials, tolerances and final testing.'],
  ['What warranty do I get?',
   'Between one and five years depending on the category. Storage water heaters, for example, carry two years complete cover plus five years on the inner container. The exact term is shown on each product page and printed on the packaging.'],
  ['How do I claim warranty?',
   'Call our customer support team on ' . $phone . ' with your model number, date of purchase and proof of purchase. Keep your invoice — claims cannot be processed without it.'],
  ['Where can I buy Maurice products?',
   'Through our dealer and distributor network. Call ' . $phone . ' and we will direct you to your nearest stockist. A searchable dealer locator is in development.'],
  ['Do you supply to government departments?',
   'Yes. We hold a rate contract with the Controller of Stores at Udhyog Bhawan, Shimla, and HPBOCW L-1 vendor status. We have supplied 95,000 Rado heat pillars and 45,000 MIC-21 induction cooktops to Himachal Pradesh government offices.'],
  ['Can I become a Maurice dealer?',
   'We are actively appointing dealers and distributors across India. Submit the form on our Become a Dealer page, or call ' . $phone . ' to discuss terms.'],
  ['Do you offer OEM or private-label manufacturing?',
   'Yes. Because we manufacture in our own units, we can produce under your brand where volumes justify it. Get in touch with your requirements and expected quantities.'],
  ['What is the minimum order quantity?',
   'MOQ varies by product and is listed on each product page — typically 1 piece for larger items such as chimneys and storage geysers, and 6 to 12 pieces for smaller appliances.'],
  ['My water heater is not heating properly. What should I check?',
   'First check the power supply and thermostat setting. In hard-water areas, scale build-up on the heating element is the most common cause of reduced performance and is prevented by periodic descaling. If the problem persists, call ' . $phone . '.'],
  ['Are spare parts available?',
   'Yes. Contact our service line with your model number and we will advise on availability and pricing for the part you need.'],
  ['Do prices on this site include GST?',
   'The prices shown are MRP inclusive of all taxes. Dealer and bulk pricing differs — contact us for a trade price list.'],
];

$seo = [
  'title' => 'Frequently Asked Questions — Maurice Appliances',
  'desc'  => 'Answers on certification, warranty, service, dealership, OEM manufacturing and minimum order quantities for Maurice Appliances.',
  'schema'=> [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
      '@type' => 'Question',
      'name' => $f[0],
      'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f[1]],
    ], $faqs),
  ],
];
$pageCss = ['content.css'];
$pageJs  = ['faq.js'];
$bodyClass = 'page-faq';
require LAYOUTS_PATH . '/main-header.php';
?>

<section class="phero">
  <div class="wrap">
    <?php partial('breadcrumbs', ['crumbs'=>[
      ['name'=>'Home','url'=>url('index.php')],
      ['name'=>'Support','url'=>url('pages/service.php')],
      ['name'=>'FAQ'],
    ]]); ?>
    <h1>Frequently asked questions.</h1>
    <p class="phero__lead">Certification, warranty, service, dealership and ordering — answered.</p>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="faq">
      <?php foreach ($faqs as $i => $f): ?>
      <div class="faq__item">
        <button class="faq__q" type="button" aria-expanded="false" aria-controls="faq-a-<?= $i ?>" id="faq-q-<?= $i ?>">
          <span><?= e($f[0]) ?></span>
          <span class="faq__icon" aria-hidden="true"></span>
        </button>
        <div class="faq__a" id="faq-a-<?= $i ?>" role="region" aria-labelledby="faq-q-<?= $i ?>">
          <div class="faq__a-inner"><p><?= e($f[1]) ?></p></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="margin-top:clamp(2.5rem,2rem+3vw,4rem);text-align:center">
      <p class="lead" style="margin:0 auto var(--s-5)">Still need help?</p>
      <a href="<?= e(url('pages/contact.php')) ?>" class="btn">
        <span>Contact us</span>
        <svg class="arrow" width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
