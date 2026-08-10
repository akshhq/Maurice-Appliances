<?php
/**
 * MAURICE — Shared POST form handling for API endpoints.
 * Enforces method, honeypot, CSRF, rate limit and required fields,
 * then returns a clean payload. Always responds JSON.
 */

declare(strict_types=1);

function json_response(bool $ok, string $message, int $status = 200, array $extra = []): void {
  http_response_code($status);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(array_merge(['ok' => $ok, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
  exit;
}

/**
 * Validate a form POST. Exits with a JSON error on failure.
 * @param array $required  field => label
 * @return array cleaned field values
 */
function handle_form_post(array $required, string $rateKey): array {
  if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(false, 'Method not allowed.', 405);
  }

  // Honeypot — bots fill hidden fields; respond 200 so they learn nothing.
  if (!empty($_POST['website'])) {
    json_response(true, 'Thank you.');
  }

  $tokenName = config('security', 'csrf', [])['token_name'] ?? 'csrf_token';
  if (!csrf_verify($_POST[$tokenName] ?? ($_POST['csrf'] ?? null))) {
    json_response(false, 'Your session expired. Please refresh the page and try again.', 419);
  }

  if (!rate_limit($rateKey)) {
    json_response(false, 'Too many submissions. Please try again later.', 429);
  }

  $clean  = [];
  $errors = [];
  foreach ($required as $field => $label) {
    $raw = trim((string)($_POST[$field] ?? ''));
    if ($raw === '') { $errors[] = $label; continue; }
    if ($field === 'email') {
      $email = clean_email($raw);
      if (!$email) { $errors[] = $label; continue; }
      $clean[$field] = $email;
    } else {
      $clean[$field] = clean_input($raw);
    }
  }
  if ($errors) {
    json_response(false, 'Please complete: ' . implode(', ', $errors) . '.', 422);
  }

  // Carry through any additional optional fields, sanitised.
  foreach ($_POST as $k => $v) {
    if ($k === $tokenName || $k === 'csrf' || $k === 'website' || isset($clean[$k])) continue;
    $clean[$k] = is_array($v) ? array_map(fn($x) => clean_input((string)$x), $v) : clean_input((string)$v);
  }

  return $clean;
}
