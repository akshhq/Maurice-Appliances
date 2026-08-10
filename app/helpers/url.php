<?php
/**
 * MAURICE — URL & routing helpers
 */

declare(strict_types=1);

/** Absolute URL for a public path. */
function url(string $path = ''): string {
  return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

/** Versioned asset URL (cache-busting via ?v=). */
function asset(string $path): string {
  return url('assets/' . ltrim($path, '/')) . '?v=' . ASSET_VERSION;
}

/** Canonical URL for the current request. */
function current_url(): string {
  return rtrim(BASE_URL, '/') . ($_SERVER['REQUEST_URI'] ?? '/');
}

/** Product detail URL. */
function product_url(array $p): string {
  return 'product.php?cat=' . urlencode($p['cat']) . '&id=' . urlencode($p['slug']);
}

/** Category listing URL. */
function category_url(string $slug): string {
  return 'category.php?cat=' . urlencode($slug);
}

/** Current script basename, for active-nav detection. */
function current_page(): string {
  return basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
}

/** Emits aria-current when the given file is the active page. */
function is_active(string $file): string {
  return current_page() === $file ? ' aria-current="page"' : '';
}
