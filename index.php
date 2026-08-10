<?php
/**
 * MAURICE APPLIANCES — Homepage
 */
require_once __DIR__ . '/app/bootstrap/app.php';

$seo = [
  'title'  => SITE_NAME . ' — ' . SITE_TAGLINE,
  'desc'   => SITE_DESC,
  'schema' => [
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    'name'     => SITE_NAME,
    'url'      => BASE_URL,
    'potentialAction' => [
      '@type'       => 'SearchAction',
      'target'      => BASE_URL . '/products.php?q={query}',
      'query-input' => 'required name=query',
    ],
  ],
];
$pageCss   = ['home.css'];
$pageJs    = ['home.js'];
$bodyClass = 'page-home';

require LAYOUTS_PATH . '/main-header.php';

section('home-hero');
section('home-marquee');
section('home-categories');
section('home-differentiators');
section('home-stats');
section('home-featured');
?>
<div class="section-divider" aria-hidden="true"><span class="section-divider__glyph"></span></div>
<?php
section('home-journey');
section('cta-dealer');

require LAYOUTS_PATH . '/main-footer.php';
