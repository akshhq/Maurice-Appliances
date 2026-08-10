<?php
/**
 * Shared error page renderer.
 * Expects: $errCode, $errTitle, $errMessage, $errActionUrl, $errActionLabel
 * Falls back to a dependency-free static page if the bootstrap is unavailable.
 */
if (!defined('BASE_PATH')) {
  $boot = dirname(__DIR__) . '/app/bootstrap/app.php';
  if (is_readable($boot)) { require_once $boot; }
}

$errCode        = $errCode        ?? 500;
$errTitle       = $errTitle       ?? 'Something went wrong';
$errMessage     = $errMessage     ?? 'An unexpected error occurred. Please try again.';
$errActionUrl   = $errActionUrl   ?? (function_exists('url') ? url('index.php') : '/');
$errActionLabel = $errActionLabel ?? 'Back to home';

if (function_exists('url') && defined('LAYOUTS_PATH')) {
  $seo = ['title' => $errCode . ' — ' . $errTitle . ' — ' . SITE_NAME, 'robots' => 'noindex,follow'];
  $bodyClass = 'page-error';
  require LAYOUTS_PATH . '/main-header.php';
  ?>
  <section class="section wrap" style="min-height:64vh;display:grid;place-items:center;text-align:center">
    <div>
      <span class="eyebrow" style="justify-content:center">Error <?= e((string)$errCode) ?></span>
      <h1 style="margin:1rem 0"><?= e($errTitle) ?></h1>
      <p class="lead" style="margin:0 auto 2rem"><?= e($errMessage) ?></p>
      <a href="<?= e($errActionUrl) ?>" class="btn"><?= e($errActionLabel) ?></a>
    </div>
  </section>
  <?php
  require LAYOUTS_PATH . '/main-footer.php';
} else {
  // Static fallback — no app dependencies.
  $esc = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
  ?><!DOCTYPE html>
  <html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $esc($errCode) ?> — <?= $esc($errTitle) ?></title>
  <style>
    body{font-family:system-ui,-apple-system,'Segoe UI',sans-serif;background:#FBFBFA;color:#141518;
         display:grid;place-items:center;min-height:100vh;margin:0;text-align:center;padding:2rem}
    h1{font-size:2rem;margin:.5rem 0}p{color:#3A3D42;max-width:46ch;margin:0 auto 1.5rem}
    .c{color:#E01E26;font-weight:700;letter-spacing:.12em;font-size:.8rem;text-transform:uppercase}
    a{display:inline-block;background:#E01E26;color:#fff;padding:.85em 1.6em;border-radius:999px;
      text-decoration:none;font-weight:600}
  </style></head><body><div>
  <p class="c">Error <?= $esc($errCode) ?></p><h1><?= $esc($errTitle) ?></h1>
  <p><?= $esc($errMessage) ?></p><a href="/"><?= $esc($errActionLabel) ?></a>
  </div></body></html><?php
}
