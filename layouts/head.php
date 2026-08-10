<?php
/**
 * LAYOUT: document head + opening body.
 * Expects (optional): $seo, $pageCss[], $bodyClass
 * Pages include this via layouts/main-header.php.
 */
require_once APP_PATH . '/services/SeoService.php';
$seo       = $seo       ?? [];
$pageCss   = $pageCss   ?? [];
$bodyClass = $bodyClass ?? '';

// Prefer the minified production bundle when it has been built.
$minified = PUBLIC_PATH . '/assets/css/style.min.css';
$mainCss  = is_readable($minified) ? 'assets/css/style.min.css' : 'assets/css/style.css';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

  <?php render_seo($seo); ?>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://api.fontshare.com" crossorigin>
  <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=clash-display@600,700&display=swap">

  <!-- Design system -->
  <link rel="stylesheet" href="<?= e(url($mainCss)) ?>?v=<?= e(ASSET_VERSION) ?>">
  <?php foreach ((array)$pageCss as $css): ?>
  <link rel="stylesheet" href="<?= e(url('assets/css/pages/' . $css)) ?>?v=<?= e(ASSET_VERSION) ?>">
  <?php endforeach; ?>

  <!-- Icons & manifest -->
  <link rel="icon" href="<?= e(url('assets/images/icons/favicon.svg')) ?>" type="image/svg+xml">
  <link rel="icon" href="<?= e(url('favicon.ico')) ?>" sizes="any">
  <link rel="apple-touch-icon" href="<?= e(url('assets/images/icons/apple-touch-icon.png')) ?>">
  <link rel="manifest" href="<?= e(url('manifest.json')) ?>">
  <meta name="msapplication-config" content="<?= e(url('browserconfig.xml')) ?>">
</head>
<body class="has-cursor <?= e($bodyClass) ?>">

<a class="skip-link" href="#main">Skip to content</a>
