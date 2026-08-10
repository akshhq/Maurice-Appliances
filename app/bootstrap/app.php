<?php
/**
 * MAURICE APPLIANCES — Application bootstrap
 * The single entry point for every page. Include this first:
 *
 *   require_once __DIR__ . '/app/bootstrap/app.php';        // from a root page
 *   require_once dirname(__DIR__) . '/app/bootstrap/app.php'; // from a page in /pages
 *
 * Boot order: env -> constants -> config -> helpers -> functions -> session.
 */

declare(strict_types=1);

/* ---------------- 1. Environment loader ---------------- */
require_once __DIR__ . '/env.php';
env_load(dirname(__DIR__, 2) . '/.env');   // app/bootstrap -> app -> web root

/* ---------------- 2. Constants (defines BASE_PATH etc.) ---------------- */
require_once dirname(__DIR__, 2) . '/config/constants.php';

/* ---------------- 3. Core config ---------------- */
require_once CONFIG_PATH . '/app.php';

/** Lazily load and cache an array-returning config file. */
function config(string $file, ?string $key = null, $default = null) {
  static $loaded = [];
  if (!isset($loaded[$file])) {
    $path = CONFIG_PATH . '/' . $file . '.php';
    $loaded[$file] = is_readable($path) ? (require $path) : [];
  }
  if ($key === null) return $loaded[$file];
  return $loaded[$file][$key] ?? $default;
}

/* ---------------- 4. Helpers & functions ---------------- */
require_once APP_PATH . '/helpers/format.php';
require_once APP_PATH . '/helpers/url.php';
require_once APP_PATH . '/helpers/security.php';
require_once APP_PATH . '/helpers/view.php';
require_once APP_PATH . '/functions/products.php';
require_once APP_PATH . '/functions/icons.php';

/* SEO helpers must load here, not in the layout: pages build their $seo
   array (calling breadcrumb_schema() etc.) BEFORE the layout is included. */
require_once APP_PATH . '/services/SeoService.php';

/* ---------------- 5. Session ---------------- */
if (PHP_SAPI !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
  $s = config('security', 'session', []);
  if (!empty($s['path']) && is_dir($s['path']) && is_writable($s['path'])) {
    session_save_path($s['path']);
  }
  session_set_cookie_params([
    'lifetime' => $s['lifetime'] ?? 7200,
    'path'     => '/',
    'secure'   => (($_SERVER['HTTPS'] ?? '') === 'on') && ($s['secure'] ?? true),
    'httponly' => $s['httponly'] ?? true,
    'samesite' => $s['samesite'] ?? 'Lax',
  ]);
  session_name($s['name'] ?? 'MAURICE_SESSION');
  @session_start();
}

/* ---------------- 6. Security headers ---------------- */
if (PHP_SAPI !== 'cli' && !headers_sent()) {
  foreach (config('security', 'headers', []) as $h => $v) {
    header("$h: $v");
  }
}
