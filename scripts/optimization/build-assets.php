<?php
/**
 * Concatenates and minifies the CSS import graph into style.min.css,
 * removing the @import waterfall for production.
 *
 * Usage:  php scripts/optimization/build-assets.php
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';

$cssDir = PUBLIC_PATH . '/assets/css';
$entry  = $cssDir . '/style.css';
$out    = $cssDir . '/style.min.css';

if (!is_readable($entry)) { fwrite(STDERR, "style.css not found\n"); exit(1); }

/** Recursively inline @import url("...") statements. */
function inline_imports(string $file, string $baseDir, array &$seen = []): string {
  $real = realpath($file);
  if (!$real || isset($seen[$real])) return '';
  $seen[$real] = true;

  $css = (string) file_get_contents($real);
  return preg_replace_callback(
    '/@import\s+url\((["\']?)([^"\')]+)\1\)\s*;/',
    function ($m) use ($baseDir, &$seen) {
      $path = $baseDir . '/' . $m[2];
      return is_readable($path)
        ? inline_imports($path, dirname($path), $seen)
        : '';
    },
    $css
  );
}

$css = inline_imports($entry, $cssDir);

/* Conservative minification — safe for hand-written CSS. */
$css = preg_replace('!/\*(?!.*?\bimportant\b).*?\*/!s', '', $css); // strip comments
$css = preg_replace('/\s+/', ' ', $css);                            // collapse whitespace
$css = preg_replace('/\s*([{}:;,>~])\s*/', '$1', $css);             // trim around delimiters
$css = str_replace(';}', '}', $css);
$css = trim($css);

file_put_contents($out, $css);

printf("Built %s — %s KB (from %s KB)\n",
  basename($out),
  number_format(strlen($css) / 1024, 1),
  number_format(filesize($entry) / 1024, 1)
);
echo "The layout links style.min.css automatically when present.\n";
