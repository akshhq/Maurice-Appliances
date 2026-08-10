<?php
/**
 * MAURICE — Installation self-test
 *
 * Visit https://yourdomain.com/check.php after uploading.
 * It reports what is working and what is not, WITHOUT exposing
 * error details to the public (it shows status only).
 *
 * DELETE THIS FILE once the site is confirmed working.
 */

// Show errors on this page only, so a fatal here is visible rather than blank.
error_reporting(E_ALL);
ini_set('display_errors', '1');

$checks = [];
$fatal  = null;

function check(string $label, callable $test, string $fixHint = ''): array {
    try {
        $result = $test();
        if ($result === true)  return ['label'=>$label, 'ok'=>true,  'detail'=>'OK',   'hint'=>''];
        if ($result === false) return ['label'=>$label, 'ok'=>false, 'detail'=>'FAILED','hint'=>$fixHint];
        return ['label'=>$label, 'ok'=>true, 'detail'=>(string)$result, 'hint'=>''];
    } catch (\Throwable $e) {
        return ['label'=>$label, 'ok'=>false, 'detail'=>get_class($e).': '.$e->getMessage(), 'hint'=>$fixHint];
    }
}

/* ---- 1. PHP version ---- */
$checks[] = check('PHP version', function () {
    return version_compare(PHP_VERSION, '8.0.0', '>=') ? PHP_VERSION : false;
}, 'Set PHP to 8.0 or higher in hPanel > Advanced > PHP Configuration.');

/* ---- 2. Required extensions ---- */
$checks[] = check('JSON extension', fn() => extension_loaded('json'), 'Enable the json extension in PHP settings.');
$checks[] = check('mbstring extension', fn() => extension_loaded('mbstring'), 'Enable the mbstring extension in PHP settings.');

/* ---- 3. Bootstrap ---- */
$bootPath = __DIR__ . '/app/bootstrap/app.php';
$checks[] = check('Bootstrap file present', fn() => is_readable($bootPath),
    'The app/ folder did not upload correctly. Re-extract the zip.');

if (is_readable($bootPath)) {
    try {
        require_once $bootPath;
        $checks[] = ['label'=>'Bootstrap loads', 'ok'=>true, 'detail'=>'OK', 'hint'=>''];
    } catch (\Throwable $e) {
        $fatal = $e;
        $checks[] = ['label'=>'Bootstrap loads', 'ok'=>false,
                     'detail'=>get_class($e).': '.$e->getMessage().' (line '.$e->getLine().' of '.basename($e->getFile()).')',
                     'hint'=>'This is the cause of your 500 error.'];
    }
}

if (!$fatal && defined('BASE_PATH')) {
    /* ---- 4. Core functions defined ---- */
    foreach ([
        'e' => 'format helper',
        'url' => 'url helper',
        'csrf_token' => 'security helper',
        'component' => 'view helper',
        'get_categories' => 'product functions',
        'category_icon' => 'icon functions',
        'render_seo' => 'SEO service',
        'breadcrumb_schema' => 'SEO service (schema)',
    ] as $fn => $where) {
        $checks[] = check("Function {$fn}() [{$where}]", fn() => function_exists($fn),
            'A file failed to load in app/bootstrap/app.php.');
    }

    /* ---- 5. Data ---- */
    $checks[] = check('Product data readable', function () {
        $n = total_products();
        return $n > 0 ? "{$n} products loaded" : false;
    }, 'database/schema/products.json is missing or unreadable.');

    $checks[] = check('Categories readable', function () {
        $n = count(get_categories());
        return $n > 0 ? "{$n} categories" : false;
    }, 'database/schema/products.json is malformed.');

    /* ---- 6. Writable folders ---- */
    foreach (['storage/cache','storage/sessions','storage/exports','logs/php'] as $dir) {
        $checks[] = check("Writable: {$dir}", function () use ($dir) {
            $p = BASE_PATH . '/' . $dir;
            if (!is_dir($p)) return false;
            return is_writable($p) ? true : false;
        }, "Set folder permissions to 755 (or 775) on {$dir}.");
    }

    /* ---- 7. Key pages present ---- */
    foreach (['index.php','products.php','category.php','product.php',
              'pages/about.php','pages/contact.php','layouts/head.php',
              'partials/navbar.php','components/product-card.php'] as $f) {
        $checks[] = check("File: {$f}", fn() => is_readable(BASE_PATH . '/' . $f),
            'Re-extract the zip — some files did not upload.');
    }

    /* ---- 8. Detected site address ---- */
    $checks[] = check('Detected site URL', fn() => BASE_URL, '');
}

/* ---- 9. .htaccess protection (informational) ---- */
$checks[] = check('.htaccess present', fn() => is_readable(__DIR__ . '/.htaccess'),
    'Without .htaccess your app folders are publicly readable. Re-upload it.');

$failed = array_values(array_filter($checks, fn($c) => !$c['ok']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Installation check — Maurice Appliances</title>
<style>
  :root{--red:#E01E26;--ink:#141518;--graphite:#3A3D42;--paper:#FBFBFA;--line:rgba(20,21,24,.1)}
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:system-ui,-apple-system,'Segoe UI',Roboto,sans-serif;background:var(--paper);
       color:var(--ink);padding:2rem 1.25rem;line-height:1.6}
  .wrap{max-width:820px;margin:0 auto}
  h1{font-size:1.75rem;letter-spacing:-.02em;margin-bottom:.35rem}
  .sub{color:var(--graphite);margin-bottom:2rem}
  .banner{padding:1rem 1.25rem;border-radius:12px;margin-bottom:2rem;font-weight:600}
  .banner.good{background:#e7f7ec;color:#14663a;border:1px solid #bce5cd}
  .banner.bad{background:#fdeceb;color:#8c1218;border:1px solid #f5c4c2}
  table{width:100%;border-collapse:collapse;background:#fff;border:1px solid var(--line);border-radius:12px;overflow:hidden}
  td{padding:.7rem .9rem;border-bottom:1px solid var(--line);font-size:.9rem;vertical-align:top}
  tr:last-child td{border-bottom:none}
  td.s{width:76px;font-weight:700}
  .ok{color:#14663a}.no{color:var(--red)}
  .d{color:var(--graphite);font-size:.85rem}
  .hint{display:block;margin-top:.3rem;color:var(--red);font-size:.82rem}
  .note{margin-top:2rem;padding:1rem 1.25rem;background:#fff;border:1px solid var(--line);border-radius:12px;font-size:.88rem;color:var(--graphite)}
  code{background:#eef0f2;padding:.15em .4em;border-radius:4px;font-size:.85em}
</style>
</head>
<body>
<div class="wrap">
  <h1>Installation check</h1>
  <p class="sub">Maurice Appliances &mdash; server self-test</p>

  <?php if ($failed): ?>
    <div class="banner bad">
      <?= count($failed) ?> problem<?= count($failed) === 1 ? '' : 's' ?> found. See the red rows below.
    </div>
  <?php else: ?>
    <div class="banner good">
      Everything passed. Your site should be working &mdash; now delete <code>check.php</code>.
    </div>
  <?php endif; ?>

  <table>
    <?php foreach ($checks as $c): ?>
    <tr>
      <td class="s <?= $c['ok'] ? 'ok' : 'no' ?>"><?= $c['ok'] ? 'PASS' : 'FAIL' ?></td>
      <td>
        <?= htmlspecialchars($c['label'], ENT_QUOTES, 'UTF-8') ?>
        <span class="d">&mdash; <?= htmlspecialchars($c['detail'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php if (!$c['ok'] && $c['hint']): ?>
          <span class="hint"><?= htmlspecialchars($c['hint'], ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

  <div class="note">
    <strong>Security:</strong> delete <code>check.php</code> once the site is confirmed working.
    It reports server status and should not stay on a live site.
  </div>
</div>
</body>
</html>
