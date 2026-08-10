<?php
/**
 * CRON: single entry point that dispatches all scheduled tasks.
 * Use this if the host allows only one cron entry:
 *   0 * * * * /usr/bin/php /home/USER/cron/scheduler.php
 */
require_once dirname(__DIR__) . '/app/bootstrap/app.php';

$hour = (int)date('G');
$dow  = (int)date('w');

$tasks = [];
if ($hour === 3)                 $tasks[] = 'cleanup.php';
if ($hour === 2 && $dow === 0)   $tasks[] = 'backups.php';
if ($hour === 4)                 $tasks[] = 'sitemap.php';

foreach ($tasks as $t) {
  $file = __DIR__ . '/' . $t;
  if (is_readable($file)) {
    echo "--- running {$t}\n";
    include $file;
  }
}
if (!$tasks) echo date('c') . " scheduler: nothing due\n";
