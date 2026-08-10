<?php
/**
 * MAURICE — Minimal .env loader (no Composer dependency).
 * Parses KEY=VALUE lines, ignores # comments, strips surrounding quotes.
 * Values are exposed via env() and $_ENV — never echoed to the browser.
 */

declare(strict_types=1);

function env_load(string $path): void {
  if (!is_readable($path)) return;
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) continue;
    if (!str_contains($line, '=')) continue;
    [$key, $value] = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);
    // Strip matching quotes
    if (strlen($value) > 1 &&
        ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
         (str_starts_with($value, "'") && str_ends_with($value, "'")))) {
      $value = substr($value, 1, -1);
    }
    $_ENV[$key] = $value;
    putenv("$key=$value");
  }
}

function env(string $key, ?string $default = null): ?string {
  $v = $_ENV[$key] ?? getenv($key);
  if ($v === false || $v === null || $v === '') return $default;
  return $v;
}
