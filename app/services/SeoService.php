<?php
/**
 * MAURICE — SEO service
 * Renders <title>, meta, Open Graph, Twitter cards and JSON-LD schema.
 */

declare(strict_types=1);

function render_seo(array $seo = []): void {
  $title = $seo['title']     ?? SITE_NAME . ' — ' . SITE_TAGLINE;
  $desc  = $seo['desc']      ?? SITE_DESC;
  $canon = $seo['canonical'] ?? current_url();
  $ogImg = $seo['og_image']  ?? url('assets/images/misc/og-default.jpg');
  $type  = $seo['og_type']   ?? 'website';
  $robots= $seo['robots']    ?? 'index,follow,max-image-preview:large';
  ?>
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= attr($desc) ?>">
  <link rel="canonical" href="<?= attr($canon) ?>">
  <meta name="robots" content="<?= attr($robots) ?>">
  <meta name="theme-color" content="#E01E26">
  <meta name="author" content="<?= attr(SITE_NAME) ?>">

  <meta property="og:site_name" content="<?= attr(SITE_NAME) ?>">
  <meta property="og:type" content="<?= attr($type) ?>">
  <meta property="og:title" content="<?= attr($title) ?>">
  <meta property="og:description" content="<?= attr($desc) ?>">
  <meta property="og:url" content="<?= attr($canon) ?>">
  <meta property="og:image" content="<?= attr($ogImg) ?>">
  <meta property="og:locale" content="<?= attr(SITE_LOCALE) ?>">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= attr($title) ?>">
  <meta name="twitter:description" content="<?= attr($desc) ?>">
  <meta name="twitter:image" content="<?= attr($ogImg) ?>">

  <script type="application/ld+json">
  <?= json_encode(organization_schema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
  </script>

  <?php if (!empty($seo['schema'])): ?>
  <script type="application/ld+json">
  <?= json_encode($seo['schema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
  </script>
  <?php endif;
}

/** Site-wide Organization schema. */
function organization_schema(): array {
  $co = get_company();
  return [
    '@context'     => 'https://schema.org',
    '@type'        => 'Organization',
    'name'         => $co['name'] ?? SITE_NAME,
    'url'          => BASE_URL,
    'slogan'       => $co['tagline'] ?? SITE_TAGLINE,
    'foundingDate' => (string)($co['established'] ?? '2010'),
    'logo'         => url('assets/images/logos/maurice-logo.png'),
    'address'      => [
      '@type'           => 'PostalAddress',
      'streetAddress'   => 'VPO Jia, Teh. Bhunter',
      'addressLocality' => 'Kullu',
      'addressRegion'   => 'Himachal Pradesh',
      'postalCode'      => '175125',
      'addressCountry'  => 'IN',
    ],
    'contactPoint' => [
      '@type'       => 'ContactPoint',
      'telephone'   => $co['phones'][0] ?? '',
      'contactType' => 'customer service',
      'areaServed'  => 'IN',
    ],
  ];
}

/** Breadcrumb schema from [['name'=>..,'url'=>..], ...] */
function breadcrumb_schema(array $crumbs): array {
  $items = [];
  foreach (array_values($crumbs) as $i => $c) {
    $items[] = [
      '@type'    => 'ListItem',
      'position' => $i + 1,
      'name'     => $c['name'],
      'item'     => $c['url'],
    ];
  }
  return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items];
}
