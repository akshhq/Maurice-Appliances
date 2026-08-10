<?php
/**
 * Dynamic XML sitemap — covers static pages, all categories and all products.
 * A static sitemap.xml can be generated from this via:
 *   php scripts/utilities/generate-sitemap.php
 */
require_once __DIR__ . '/app/bootstrap/app.php';
header('Content-Type: application/xml; charset=utf-8');

$today = date('Y-m-d');
$urls  = [];

/* Static pages */
$static = [
  ['index.php',                    '1.0', 'weekly'],
  ['products.php',                 '0.9', 'weekly'],
  ['pages/about.php',              '0.7', 'monthly'],
  ['pages/journey.php',            '0.6', 'monthly'],
  ['pages/vision.php',             '0.5', 'monthly'],
  ['pages/values.php',             '0.5', 'monthly'],
  ['pages/manufacturing.php',      '0.6', 'monthly'],
  ['pages/dealers.php',            '0.7', 'monthly'],
  ['pages/become-dealer.php',      '0.8', 'monthly'],
  ['pages/service.php',            '0.6', 'monthly'],
  ['pages/warranty.php',           '0.5', 'monthly'],
  ['pages/faq.php',                '0.5', 'monthly'],
  ['pages/downloads.php',          '0.5', 'monthly'],
  ['pages/contact.php',            '0.7', 'monthly'],
  ['pages/privacy.php',            '0.3', 'yearly'],
  ['pages/terms.php',              '0.3', 'yearly'],
];
foreach ($static as [$p, $pri, $freq]) {
  $urls[] = ['loc' => url($p), 'priority' => $pri, 'changefreq' => $freq];
}

/* Categories */
foreach (get_categories() as $c) {
  $urls[] = ['loc' => url(category_url($c['id'])), 'priority' => '0.8', 'changefreq' => 'weekly'];
}

/* Products */
foreach (all_products() as $p) {
  $urls[] = ['loc' => url(product_url($p)), 'priority' => '0.7', 'changefreq' => 'monthly'];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $u): ?>
  <url>
    <loc><?= e($u['loc']) ?></loc>
    <lastmod><?= $today ?></lastmod>
    <changefreq><?= $u['changefreq'] ?></changefreq>
    <priority><?= $u['priority'] ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
