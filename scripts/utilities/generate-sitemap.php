<?php
/**
 * Writes a static public_html/sitemap.xml from the dynamic generator.
 * Run after adding products:   php scripts/utilities/generate-sitemap.php
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';

$out = PUBLIC_PATH . '/sitemap.xml';
ob_start();
$_SERVER['HTTP_HOST'] = parse_url(BASE_URL, PHP_URL_HOST) ?: 'www.mauriceappliances.in';
include PUBLIC_PATH . '/sitemap.php';
$xml = ob_get_clean();

file_put_contents($out, $xml);
$count = substr_count($xml, '<url>');
echo "Sitemap written: $out ($count URLs)\n";
