<?php
/**
 * MAURICE APPLIANCES — Application constants
 *
 * DEPLOYMENT NOTE
 * This build is packaged to run entirely inside public_html. BASE_PATH is
 * therefore the web root itself, and the application folders (app, config,
 * database, storage, …) sit alongside the public pages. Each of those folders
 * ships a deny-all .htaccess so they cannot be requested over HTTP.
 *
 * If you later move the application folders above the web root (the more
 * secure layout), change only the BASE_PATH line below to:
 *     define('BASE_PATH', dirname(__DIR__, 2));
 * Everything else derives from it.
 */

declare(strict_types=1);

/* ---------------- Paths ---------------- */
define('BASE_PATH',     dirname(__DIR__));   // = public_html
define('PUBLIC_PATH',   BASE_PATH);          // same folder in this layout
define('APP_PATH',      BASE_PATH . '/app');
define('CONFIG_PATH',   BASE_PATH . '/config');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('DATABASE_PATH', BASE_PATH . '/database');
define('STORAGE_PATH',  BASE_PATH . '/storage');
define('LOGS_PATH',     BASE_PATH . '/logs');

/* Public sub-paths */
define('COMPONENTS_PATH', PUBLIC_PATH . '/components');
define('SECTIONS_PATH',   PUBLIC_PATH . '/sections');
define('LAYOUTS_PATH',    PUBLIC_PATH . '/layouts');
define('PARTIALS_PATH',   PUBLIC_PATH . '/partials');
define('TEMPLATES_PATH',  PUBLIC_PATH . '/templates');
define('ERRORS_PATH',     PUBLIC_PATH . '/errors');

/* ---------------- Site meta ---------------- */
define('SITE_NAME',    'Maurice Appliances');
define('SITE_TAGLINE', 'Bright Ideas For Beautiful Homes');
define('SITE_DESC',    'Maurice Appliances — ISI & ISO-certified Indian home appliances. Room heaters, water heaters, fans, mixer grinders, gas stoves, induction, chimneys and more. Making every home better tomorrow.');
define('SITE_LOCALE',  'en_IN');
define('SITE_CURRENCY','INR');

/* ---------------- Asset versioning ----------------
   .htaccess caches every .css/.js file for a full year as "immutable", so
   the only way a browser ever re-fetches a changed file is if its ?v=
   query string changes. ASSET_VERSION drives that query string for every
   asset link (see layouts/head.php, layouts/main-footer.php). Deriving it
   from the newest mtime under assets/css and assets/js — rather than a
   hand-maintained string — means it bumps itself automatically whenever
   any stylesheet or script changes, so a stale one-year cache can never
   silently outlive an edit. */
function _asset_version(): string {
  $newest = 0;
  foreach ([PUBLIC_PATH . '/assets/css', PUBLIC_PATH . '/assets/js'] as $dir) {
    if (!is_dir($dir)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
      $mtime = $file->getMTime();
      if ($mtime > $newest) $newest = $mtime;
    }
  }
  return $newest ? (string)$newest : '1.0.0';
}
define('ASSET_VERSION', _asset_version());
