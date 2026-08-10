<?php
/**
 * CRON: backup the product catalogue and subscriber exports.
 * Suggested schedule: weekly, Sunday 02:00
 *   0 2 * * 0 /usr/bin/php /home/USER/cron/backups.php
 */
require_once dirname(__DIR__) . '/app/bootstrap/app.php';

$stamp  = date('Y-m-d_His');
$destination = BASE_PATH . '/backups/database';
@mkdir($destination, 0775, true);

$sources = [
  DATABASE_PATH . '/schema/products.json',
  STORAGE_PATH  . '/exports/newsletter-subscribers.csv',
];

$copied = 0;
foreach ($sources as $src) {
  if (!is_readable($src)) continue;
  $dst = $destination . '/' . $stamp . '_' . basename($src);
  if (@copy($src, $dst)) $copied++;
}

/* Retain the 12 most recent backups. */
$all = glob($destination . '/*') ?: [];
usort($all, fn($a, $b) => filemtime($b) <=> filemtime($a));
foreach (array_slice($all, 12) as $old) @unlink($old);

echo date('c') . " backup: {$copied} file(s) copied\n";
