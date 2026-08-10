<?php
/**
 * CRON: housekeeping — clears expired cache, temp files and old sessions.
 * Suggested schedule: daily at 03:00
 *   0 3 * * * /usr/bin/php /home/USER/cron/cleanup.php
 */
require_once dirname(__DIR__) . '/app/bootstrap/app.php';

$removed = 0;
$targets = [
  STORAGE_PATH . '/cache'   => 86400 * 7,
  STORAGE_PATH . '/temp'    => 86400,
  STORAGE_PATH . '/sessions'=> 86400 * 2,
  PUBLIC_PATH  . '/uploads/temp' => 86400,
];

foreach ($targets as $dir => $maxAge) {
  if (!is_dir($dir)) continue;
  foreach (glob($dir . '/*') ?: [] as $file) {
    if (!is_file($file) || basename($file) === '.gitkeep') continue;
    if ((time() - filemtime($file)) > $maxAge) {
      @unlink($file);
      $removed++;
    }
  }
}

echo date('c') . " cleanup: removed {$removed} file(s)\n";
