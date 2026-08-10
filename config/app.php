<?php
/**
 * MAURICE — Core application config
 * Environment, error handling, base URL resolution.
 */

declare(strict_types=1);

/* ---------------- Environment ----------------
   Set APP_ENV=production in .env (or the host panel) for live sites. */
$env = env('APP_ENV', 'production');
define('APP_ENV', $env);
define('IS_PROD', $env === 'production');
define('APP_DEBUG', filter_var(env('APP_DEBUG', IS_PROD ? 'false' : 'true'), FILTER_VALIDATE_BOOL));

if (APP_DEBUG) {
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
} else {
  error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
  ini_set('display_errors', '0');
  ini_set('log_errors', '1');
  // Only redirect the log if the directory is actually writable. On shared
  // hosting an unwritable path silently disables logging altogether, which
  // turns every fatal error into a blank 500 with no diagnostics.
  $logDir = LOGS_PATH . '/php';
  if (is_dir($logDir) && is_writable($logDir)) {
    ini_set('error_log', $logDir . '/error.log');
  }
}

/* ---------------- Timezone ---------------- */
date_default_timezone_set(env('APP_TIMEZONE', 'Asia/Kolkata'));

/* ---------------- Base URL ---------------- */
$scheme = (($_SERVER['HTTPS'] ?? '') === 'on' || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443
           || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'] ?? env('APP_HOST', 'www.mauriceappliances.in');
define('BASE_URL', env('APP_URL', $scheme . '://' . $host));

/* ---------------- Company profile ---------------- */
$GLOBALS['COMPANY'] = [
  'name'            => 'Maurice Appliances',
  'tagline'         => SITE_TAGLINE,
  'established'     => 2010,
  'registered'      => 2012,
  'address'         => 'VPO, Jia Teh. Bhunter, Distt Kullu, Himachal Pradesh – 175 125',
  'corporateOffice' => '6487 C-6, Vatika Complex, Vasant Kunj, New Delhi – 110 070',
  'phones'          => ['+91 98165-91699', '+91 90154-88584'],
  'tollFree'        => '1800 547 2505',
  'email'           => 'mauriceappliances@gmail.com',
  'infoEmail'       => 'info@mauriceappliances.com',
  'web'             => 'www.mauriceappliances.in',
  'certs'           => ['BIS (ISI) Certified', 'ISO 9001:2015'],
  'units'           => ['Bawana, Delhi', 'Jia, Kullu (H.P.)'],
];
