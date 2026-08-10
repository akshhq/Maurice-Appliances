<?php
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
$co = get_company();

$seo = [
  'title' => 'Terms of Use — Maurice Appliances',
  'desc'  => 'Terms governing use of the Maurice Appliances website, including product information, pricing and intellectual property.',
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
      ['name'=>'Terms of Use'],
    ]]); ?>
    <h1>Terms of use</h1>
    <p class="phero__lead">The terms on which we make this website available.</p>
  </div>
</section>

<section class="section">
  <div class="wrap prose">
    <p class="muted">Last updated <?= date('F Y') ?></p>

    <h2>About this website</h2>
    <p>This website is operated by <?= e($co['name'] ?? '') ?>, <?= e($co['address'] ?? '') ?>. By using it you accept these terms.</p>

    <h2>Product information</h2>
    <p>We aim to keep specifications, dimensions, weights and warranty terms accurate and current. However:</p>
    <ul>
      <li>Specifications may change without notice as we improve our products.</li>
      <li>Product illustrations on this site are representative and may differ from the item supplied.</li>
      <li>Where information on this website differs from the documentation supplied with a product, the supplied documentation applies.</li>
    </ul>

    <h2>Pricing</h2>
    <p>Prices shown are the maximum retail price (MRP) inclusive of all taxes, and are indicative. Actual retail prices are set by individual dealers and may differ. Dealer and bulk pricing is available on request and is not published here. Prices may change without notice.</p>

    <h2>No sale through this website</h2>
    <p>This website is an informational catalogue. It does not sell products, take payment or accept orders. Purchases are made through our authorised dealers and distributors.</p>

    <h2>Warranty</h2>
    <p>Warranty terms are summarised on our <a href="<?= e(url('pages/warranty.php')) ?>">warranty page</a> and on individual product pages. The warranty documentation supplied with your product is the governing version.</p>

    <h2>Intellectual property</h2>
    <p>The <strong>maurice</strong> name and logo are registered trade marks. All content on this website — text, design, graphics, illustrations and code — is the property of <?= e($co['name'] ?? '') ?> and may not be reproduced, distributed or used to create derivative works without our prior written permission.</p>

    <h2>Acceptable use</h2>
    <ul>
      <li>Do not use this website in any way that is unlawful or could damage or impair it.</li>
      <li>Do not attempt to gain unauthorised access to any part of the site, its server, or connected systems.</li>
      <li>Do not use automated systems to scrape or harvest content or contact details.</li>
      <li>Do not submit false, misleading or unlawful information through our forms.</li>
    </ul>

    <h2>External links</h2>
    <p>Where we link to third-party websites, we do so for convenience. We do not control those sites and are not responsible for their content or practices.</p>

    <h2>Availability</h2>
    <p>We aim to keep this website available at all times, but we do not guarantee uninterrupted access. We may suspend, withdraw or change any part of it without notice.</p>

    <h2>Liability</h2>
    <p>This website is provided on an "as is" basis. To the extent permitted by law, we exclude liability for any loss arising from reliance on information published here. Nothing in these terms limits liability that cannot lawfully be limited, including liability for death or personal injury caused by negligence, or your statutory rights as a consumer.</p>

    <h2>Governing law</h2>
    <p>These terms are governed by the laws of India, and the courts of Himachal Pradesh have exclusive jurisdiction over any dispute arising from them.</p>

    <h2>Contact</h2>
    <p><?= e($co['email'] ?? '') ?> &middot; <?= e($co['phones'][0] ?? '') ?></p>
  </div>
</section>

<?php require LAYOUTS_PATH . '/main-footer.php'; ?>
