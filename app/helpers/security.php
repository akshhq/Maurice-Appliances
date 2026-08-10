<?php
/**
 * MAURICE — Security helpers
 * CSRF tokens, input sanitisation, simple rate limiting.
 */

declare(strict_types=1);

/** Get (or create) the CSRF token for this session. */
function csrf_token(): string {
  if (session_status() !== PHP_SESSION_ACTIVE) @session_start();
  $name = config('security', 'csrf', [])['token_name'] ?? 'csrf_token';
  if (empty($_SESSION[$name])) {
    $_SESSION[$name] = bin2hex(random_bytes(32));
  }
  return $_SESSION[$name];
}

/** Hidden input carrying the CSRF token. */
function csrf_field(): string {
  $name = config('security', 'csrf', [])['token_name'] ?? 'csrf_token';
  return '<input type="hidden" name="' . e($name) . '" value="' . e(csrf_token()) . '">';
}

/** Constant-time CSRF verification. */
function csrf_verify(?string $token): bool {
  if (session_status() !== PHP_SESSION_ACTIVE) @session_start();
  $name = config('security', 'csrf', [])['token_name'] ?? 'csrf_token';
  return !empty($token)
      && !empty($_SESSION[$name])
      && hash_equals($_SESSION[$name], $token);
}

/** Trim + strip low/control characters from user input. */
function clean_input(string $s): string {
  return trim(filter_var($s, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW));
}

/** Validate an email address, returning null when invalid. */
function clean_email(string $s): ?string {
  $s = trim($s);
  return filter_var($s, FILTER_VALIDATE_EMAIL) ? $s : null;
}

/** Very small session-based rate limiter for form endpoints. */
function rate_limit(string $key, ?int $max = null, ?int $window = null): bool {
  if (session_status() !== PHP_SESSION_ACTIVE) @session_start();
  $cfg    = config('security', 'rate_limit', []);
  $max    = $max    ?? ($cfg['form_submissions'] ?? 5);
  $window = $window ?? ($cfg['window'] ?? 600);
  $now    = time();
  $bucket = $_SESSION['_rl'][$key] ?? ['count' => 0, 'start' => $now];
  if ($now - $bucket['start'] > $window) {
    $bucket = ['count' => 0, 'start' => $now];
  }
  $bucket['count']++;
  $_SESSION['_rl'][$key] = $bucket;
  return $bucket['count'] <= $max;
}
