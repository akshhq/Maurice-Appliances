<?php
/**
 * MAURICE — Product data access
 *
 * Reads the versioned JSON catalogue from /database/schema/products.json
 * (outside the web root, so it cannot be fetched directly over HTTP).
 * All results are cached per request.
 */

declare(strict_types=1);

/** Load and cache the full data file. */
function data_all(): array {
  static $cache = null;
  if ($cache !== null) return $cache;

  $conn = config('database', 'connections', [])['json'] ?? [];
  $file = ($conn['path'] ?? (DATABASE_PATH . '/schema')) . '/' . ($conn['files']['products'] ?? 'products.json');

  $json  = is_readable($file) ? file_get_contents($file) : '';
  if ($json !== '') {
    $json = preg_replace('/^\xEF\xBB\xBF/', '', $json);
  }
  $cache = $json ? (json_decode($json, true) ?: []) : [];
  return $cache;
}

function get_categories(): array { return data_all()['categories'] ?? []; }
function company_extra(): array  { return data_all()['company'] ?? []; }

function get_category(string $slug): ?array {
  foreach (get_categories() as $c) {
    if (($c['id'] ?? '') === $slug) return $c;
  }
  return null;
}

/** Products of a category, enriched with slug + category id. */
function get_products(string $catSlug): array {
  $raw = data_all()['products'][$catSlug] ?? [];
  $out = [];
  foreach ($raw as $i => $p) {
    $p['cat']  = $catSlug;
    $p['slug'] = slugify(($p['model'] ?? 'item') . '-' . ($p['title'] ?? (string)$i));
    $p['_i']   = $i;
    $out[] = $p;
  }
  return $out;
}

function category_count(string $catSlug): int {
  return count(data_all()['products'][$catSlug] ?? []);
}

/** True when a category has no products listed yet (e.g. an upcoming launch). */
function is_coming_soon(string $catSlug): bool {
  return category_count($catSlug) === 0;
}

function total_products(): int {
  $n = 0;
  foreach (data_all()['products'] ?? [] as $arr) { $n += count($arr); }
  return $n;
}

function get_product(string $catSlug, string $slug): ?array {
  foreach (get_products($catSlug) as $p) {
    if ($p['slug'] === $slug) return $p;
  }
  return null;
}

/** Every product, flattened across categories. */
function all_products(): array {
  $out = [];
  foreach (array_keys(data_all()['products'] ?? []) as $cat) {
    foreach (get_products($cat) as $p) { $out[] = $p; }
  }
  return $out;
}

function related_products(string $catSlug, string $excludeSlug, int $n = 3): array {
  $out = [];
  foreach (get_products($catSlug) as $p) {
    if ($p['slug'] === $excludeSlug) continue;
    $out[] = $p;
    if (count($out) >= $n) break;
  }
  return $out;
}

/* ---------------- Pricing & warranty ---------------- */

function price_band(int $mrp): string {
  if ($mrp < 1500) return 'under-1500';
  if ($mrp < 3000) return '1500-3000';
  if ($mrp < 6000) return '3000-6000';
  return '6000-plus';
}

function price_bands(): array {
  return ['under-1500', '1500-3000', '3000-6000', '6000-plus'];
}

function band_label(string $band): string {
  return [
    'under-1500' => 'Under ₹1,500',
    '1500-3000'  => '₹1,500 – ₹3,000',
    '3000-6000'  => '₹3,000 – ₹6,000',
    '6000-plus'  => '₹6,000 & above',
  ][$band] ?? $band;
}

/**
 * Primary warranty years — the headline complete-unit figure (first number).
 * Deliberately matches warranty_short() so filter results always agree with
 * the badge shown on the product card.
 */
function warranty_years(array $p): int {
  if (preg_match('/(\d+)\s*[Yy]ear/', $p['warranty'] ?? '', $m)) {
    return (int)$m[1];
  }
  return 0;
}

function warranty_short(array $p): string {
  $y = warranty_years($p);
  return $y ? $y . ' yr warranty' : '';
}

/* ---------------- Query ---------------- */

/**
 * Filter + sort products.
 * $opts: cat, q, bands[], warranties[], sort
 */
function query_products(array $opts = []): array {
  $cat   = $opts['cat'] ?? '';
  $q     = trim(strtolower($opts['q'] ?? ''));
  $bands = $opts['bands'] ?? [];
  $warrs = array_map('intval', $opts['warranties'] ?? []);
  $sort  = $opts['sort'] ?? 'featured';

  $list = $cat ? get_products($cat) : all_products();

  $list = array_values(array_filter($list, function ($p) use ($q, $bands, $warrs) {
    if ($q !== '') {
      $hay = strtolower(($p['model'] ?? '') . ' ' . ($p['title'] ?? '') . ' ' . implode(' ', $p['specs'] ?? []));
      if (!str_contains($hay, $q)) return false;
    }
    if ($bands && !in_array(price_band((int)($p['mrp'] ?? 0)), $bands, true)) return false;
    if ($warrs) {
      $y = warranty_years($p);
      $ok = false;
      foreach ($warrs as $w) { if ($y >= $w) { $ok = true; break; } }
      if (!$ok) return false;
    }
    return true;
  }));

  usort($list, function ($a, $b) use ($sort) {
    switch ($sort) {
      case 'price-asc':  return ($a['mrp'] ?? 0) <=> ($b['mrp'] ?? 0);
      case 'price-desc': return ($b['mrp'] ?? 0) <=> ($a['mrp'] ?? 0);
      case 'name':       return strcmp($a['title'] ?? '', $b['title'] ?? '');
      default:           return 0;
    }
  });

  return $list;
}
