<?php
/**
 * MAURICE — Inline SVG icon + product illustration library.
 * Inline (not sprite files) so icons inherit currentColor and cost no extra request.
 */

declare(strict_types=1);

/** Category glyph paths (24×24 viewBox, stroked). */
function category_icon(string $slug): string {
  $icons = [
    'room-heaters'       => '<path d="M7 3v6M12 3v6M17 3v6"/><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 15h8"/>',
    'water-heaters'      => '<rect x="6" y="3" width="12" height="14" rx="6"/><path d="M9 21h2M13 21h2"/><circle cx="12" cy="10" r="2"/>',
    'fans'               => '<circle cx="12" cy="12" r="2"/><path d="M12 10c0-4 1-7 3-7s2 4-1 6M14 12c4 0 7 1 7 3s-4 2-6-1M12 14c0 4-1 7-3 7s-2-4 1-6M10 12c-4 0-7-1-7-3s4-2 6 1"/>',
    'mixer-grinders'     => '<path d="M9 3h6l-1 5h-4z"/><rect x="8" y="8" width="8" height="8" rx="1"/><path d="M10 16v3h4v-3"/>',
    'gas-stoves'         => '<rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="8" cy="12" r="2"/><circle cx="16" cy="12" r="2"/>',
    'induction'          => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
    'irons'              => '<path d="M4 15c0-4 4-7 9-7h7l-2 7z"/><path d="M4 15h13"/><circle cx="9" cy="11" r=".6"/>',
    'chimneys'           => '<path d="M4 20V10l8-5 8 5v10"/><path d="M8 20v-6h8v6"/>',
    'kitchen-appliances' => '<rect x="5" y="4" width="14" height="16" rx="2"/><path d="M9 8h6M9 12h6"/>',
    'madhani'            => '<rect x="8" y="3" width="8" height="7" rx="1"/><path d="M12 10v9M9 19h6M10 13h4"/>',
    'coolers-ac'         => '<rect x="3" y="5" width="18" height="9" rx="2"/><path d="M7 18v1.4M11 18v2M15 18v1.4M19 18v2"/><path d="M6 9.5h.01M9.5 9.5h5"/>',
  ];
  return $icons[$slug] ?? '<circle cx="12" cy="12" r="8"/>';
}

/** Complete <svg> for a category glyph. */
function category_icon_svg(string $slug, float $stroke = 1.6): string {
  return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="' . $stroke .
         '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' .
         category_icon($slug) . '</svg>';
}

/**
 * milestone_icon() — themed glyph for a Our Journey timeline event, chosen
 * by keyword match against the event text (certification, government supply,
 * manufacturing, launch, or a general growth marker as fallback).
 */
function milestone_icon(string $eventText): string {
  $t = strtolower($eventText);
  if (preg_match('/certif|bis|isi|iso|lab/', $t)) {
    // Award / certification badge
    return '<path d="M12 15.5l-4.9 2.9 1.2-5.6-4.2-3.8 5.6-.5L12 3l2.3 5.5 5.6.5-4.2 3.8 1.2 5.6z"/>';
  }
  if (preg_match('/government|contract|vendor|supplied|rate contract|stores/', $t)) {
    // Institutional building
    return '<path d="M4 21h16M5 21V10l7-6 7 6v11M9 21v-6h6v6"/>';
  }
  if (preg_match('/manufactur|unit|bawana|kullu|labs/', $t)) {
    // Factory
    return '<path d="M4 21V12l4.5 3V12l4.5 3V9l5 3.5V21z"/><path d="M3 21h18"/>';
  }
  if (preg_match('/launch|introduc|started|first product|registered/', $t)) {
    // Rocket / new launch
    return '<path d="M12 2.5c2.4 2.6 3.8 5.8 3.8 9 0 2-1 4-3.8 8-2.8-4-3.8-6-3.8-8 0-3.2 1.4-6.4 3.8-9z"/><circle cx="12" cy="10" r="1.5"/><path d="M9 17l-2 3M15 17l2 3"/>';
  }
  // Default: growth arrow
  return '<path d="M4 17l5.5-6 4 4L21 6"/><path d="M15 6h6v6"/>';
}

/** Complete <svg> for a milestone glyph. */
function milestone_icon_svg(string $eventText, float $stroke = 2): string {
  return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="' . $stroke .
         '" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' .
         milestone_icon($eventText) . '</svg>';
}

/**
 * Shared inner-path lookup for the product_frame() illustrations (120×128
 * viewBox), used both by product_frame() itself (single inline <svg>, for
 * one-off placements) and product_frame_use() (an SVG <use> reference into
 * product_frame_defs()'s <symbol> set, for placements that repeat the same
 * illustration many times — e.g. the product showcase marquee — without
 * duplicating the full path data in the DOM for every instance).
 */
function product_frame_paths(): array {
  $s = 'var(--ink)';
  $a = 'var(--red)';
  return [
    'room-heaters' =>
      '<rect x="34" y="20" width="52" height="90" rx="8" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<rect x="44" y="34" width="32" height="58" rx="4" fill="none" stroke="'.$a.'" stroke-width="2"/>'.
      '<line x1="52" y1="34" x2="52" y2="92" stroke="'.$a.'" stroke-width="2"/>'.
      '<line x1="60" y1="34" x2="60" y2="92" stroke="'.$a.'" stroke-width="2"/>'.
      '<line x1="68" y1="34" x2="68" y2="92" stroke="'.$a.'" stroke-width="2"/>'.
      '<rect x="40" y="110" width="40" height="8" rx="3" fill="'.$s.'"/>',
    'water-heaters' =>
      '<rect x="30" y="24" width="60" height="72" rx="26" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<circle cx="60" cy="58" r="9" fill="none" stroke="'.$a.'" stroke-width="2.5"/>'.
      '<path d="M60 53v10" stroke="'.$a.'" stroke-width="2"/>'.
      '<path d="M48 100l-3 8M72 100l3 8" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/>'.
      '<path d="M50 100h20" stroke="'.$s.'" stroke-width="2"/>',
    'fans' =>
      '<circle cx="60" cy="52" r="34" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<circle cx="60" cy="52" r="6" fill="'.$a.'"/>'.
      '<path d="M60 46c0-14 4-22 10-22s6 12-4 18" fill="none" stroke="'.$s.'" stroke-width="2"/>'.
      '<path d="M66 52c14 0 22 4 22 10s-12 6-18-4" fill="none" stroke="'.$s.'" stroke-width="2"/>'.
      '<path d="M60 58c0 14-4 22-10 22s-6-12 4-18" fill="none" stroke="'.$s.'" stroke-width="2"/>'.
      '<path d="M54 52c-14 0-22-4-22-10s12-6 18 4" fill="none" stroke="'.$s.'" stroke-width="2"/>'.
      '<path d="M60 86v22M48 108h24" stroke="'.$s.'" stroke-width="2.5"/>',
    'mixer-grinders' =>
      '<path d="M46 22h28l-4 22H50z" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<path d="M52 22l3-8h10l3 8" fill="none" stroke="'.$a.'" stroke-width="2"/>'.
      '<rect x="42" y="46" width="36" height="44" rx="6" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<circle cx="60" cy="70" r="7" fill="none" stroke="'.$a.'" stroke-width="2.5"/>'.
      '<rect x="46" y="90" width="28" height="12" rx="3" fill="'.$s.'"/>',
    'gas-stoves' =>
      '<rect x="18" y="46" width="84" height="36" rx="6" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<circle cx="42" cy="64" r="9" fill="none" stroke="'.$a.'" stroke-width="2.5"/>'.
      '<circle cx="78" cy="64" r="9" fill="none" stroke="'.$a.'" stroke-width="2.5"/>'.
      '<circle cx="42" cy="64" r="3" fill="'.$a.'"/><circle cx="78" cy="64" r="3" fill="'.$a.'"/>'.
      '<circle cx="30" cy="88" r="3" fill="'.$s.'"/><circle cx="60" cy="88" r="3" fill="'.$s.'"/><circle cx="90" cy="88" r="3" fill="'.$s.'"/>',
    'induction' =>
      '<rect x="20" y="40" width="80" height="48" rx="8" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<circle cx="60" cy="60" r="18" fill="none" stroke="'.$a.'" stroke-width="2.5"/>'.
      '<circle cx="60" cy="60" r="10" fill="none" stroke="'.$a.'" stroke-width="1.5" opacity=".6"/>'.
      '<rect x="34" y="80" width="8" height="3" rx="1.5" fill="'.$s.'"/>'.
      '<rect x="46" y="80" width="8" height="3" rx="1.5" fill="'.$s.'"/>'.
      '<rect x="58" y="80" width="8" height="3" rx="1.5" fill="'.$a.'"/>',
    'irons' =>
      '<path d="M22 74c0-20 22-34 46-34h30l-8 34z" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<path d="M22 74h68" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<path d="M60 40c6-10 18-12 26-6" fill="none" stroke="'.$a.'" stroke-width="2.5" stroke-linecap="round"/>'.
      '<circle cx="44" cy="58" r="3" fill="'.$a.'"/>',
    'chimneys' =>
      '<path d="M30 96V60l30-22 30 22v36" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<path d="M44 96V74h32v22" fill="none" stroke="'.$a.'" stroke-width="2"/>'.
      '<rect x="54" y="20" width="12" height="20" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<line x1="50" y1="84" x2="70" y2="84" stroke="'.$a.'" stroke-width="2"/>',
    'kitchen-appliances' =>
      '<rect x="34" y="24" width="52" height="80" rx="8" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<path d="M46 40h28M46 54h28" stroke="'.$a.'" stroke-width="2"/>'.
      '<rect x="46" y="70" width="28" height="20" rx="3" fill="none" stroke="'.$s.'" stroke-width="2"/>'.
      '<circle cx="60" cy="80" r="4" fill="'.$a.'"/>',
    'madhani' =>
      '<rect x="44" y="20" width="32" height="30" rx="4" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<rect x="50" y="14" width="20" height="8" rx="4" fill="'.$a.'"/>'.
      '<path d="M60 50v42" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<path d="M50 92h20M52 84h16" stroke="'.$s.'" stroke-width="2.5" stroke-linecap="round"/>'.
      '<ellipse cx="60" cy="60" rx="18" ry="4" fill="none" stroke="'.$s.'" stroke-width="2"/>',
    'coolers-ac' =>
      '<rect x="20" y="34" width="80" height="38" rx="8" fill="#fff" stroke="'.$s.'" stroke-width="2.5"/>'.
      '<path d="M34 78v6M50 78v9M66 78v6M82 78v9" stroke="'.$a.'" stroke-width="2.5" stroke-linecap="round"/>'.
      '<circle cx="34" cy="50" r="2.5" fill="'.$a.'"/>'.
      '<path d="M46 50h30" stroke="'.$s.'" stroke-width="2"/>'.
      '<path d="M46 58h20" stroke="'.$s.'" stroke-width="2" opacity=".6"/>',
  ];
}

/** Fallback inner markup for an unrecognised category slug. */
function product_frame_default_path(): string {
  return '<circle cx="60" cy="60" r="34" fill="#fff" stroke="var(--ink)" stroke-width="2.5"/>';
}

/**
 * product_frame() — line-art appliance illustration per category, as a
 * single self-contained inline <svg>. Placeholder-grade but on-brand; swap
 * for real PNG cut-outs when available. Use this for one-off placements
 * (hero, cards, category pages); use product_frame_use() instead when the
 * same illustration repeats many times on one page.
 */
function product_frame(string $cat, string $extraClass = ''): string {
  $inner = product_frame_paths()[$cat] ?? product_frame_default_path();
  return '<svg class="pframe ' . e($extraClass) . '" viewBox="0 0 120 128" fill="none" '.
         'xmlns="http://www.w3.org/2000/svg" aria-hidden="true">' . $inner . '</svg>';
}

/**
 * Hidden <svg><defs> block of <symbol>s, one per category, keyed by
 * "pframe-{slug}". Render this once per page (e.g. product_frame_use() is
 * used), then reference instances with product_frame_use().
 */
function product_frame_defs(): string {
  $out = '<svg aria-hidden="true" style="position:absolute;width:0;height:0;overflow:hidden"><defs>';
  foreach (product_frame_paths() as $cat => $inner) {
    $out .= '<symbol id="pframe-' . e($cat) . '" viewBox="0 0 120 128">' . $inner . '</symbol>';
  }
  $out .= '<symbol id="pframe-default" viewBox="0 0 120 128">' . product_frame_default_path() . '</symbol>';
  $out .= '</defs></svg>';
  return $out;
}

/** Lightweight <svg><use> reference into product_frame_defs() for a category. */
function product_frame_use(string $cat, string $extraClass = ''): string {
  $id = array_key_exists($cat, product_frame_paths()) ? $cat : 'default';
  return '<svg class="pframe ' . e($extraClass) . '" viewBox="0 0 120 128" fill="none" aria-hidden="true">' .
         '<use href="#pframe-' . e($id) . '"/></svg>';
}

function _product_media_key(string $value): string {
  $value = strtoupper($value);
  $value = str_replace(['&', '"', "'", '(', ')', ',', '.', '/', '\\', '-', '–', '—', ':', ';', '[', ']', '{', '}', '’', '“', '”'], ' ', $value);
  $value = preg_replace('/\bM\s*3\s*H\b/u', ' M3H ', $value);
  $value = preg_replace('/\bM\s*3\s*\/\s*H\b/u', ' M3H ', $value);
  $value = preg_replace('/\bWATT(S)?\b/u', 'WATTS', $value);
  $value = preg_replace('/\bL\s*TR\b/u', 'LTR', $value);
  $value = preg_replace('/\bLITRE(S)?\b/u', 'LTR', $value);
  $value = preg_replace('/\bMM\b/u', 'MM', $value);
  $value = preg_replace('/\bCW\b/u', 'CW', $value);
  $value = preg_replace('/\bAW\b/u', 'AW', $value);
  $value = preg_replace('/\bBR\b/u', 'BR', $value);
  $value = preg_replace('/\bSS\b/u', 'SS', $value);
  $value = preg_replace('/\bS\s+R\b/u', 'SR', $value);
  $value = preg_replace('/\bD\s+R\b/u', 'DR', $value);
  $value = preg_replace('/\bH\s*\/\s*Z\b/u', 'HZ', $value);
  $value = preg_replace('/\bM\s*\^?\s*3\b/u', 'M3', $value);
  $value = preg_replace('/[^A-Z0-9]+/', ' ', $value);
  return trim(preg_replace('/\s+/', ' ', $value));
}

function _product_media_tokens(string $value): array {
  $key = _product_media_key($value);
  return $key === '' ? [] : explode(' ', $key);
}

function _product_media_files(): array {
  static $files = null;
  if ($files !== null) return $files;

  $files = [];
  $base = PUBLIC_PATH . '/media';
  if (!is_dir($base)) return $files;

  // Recursive iterator: scan root + all subdirectories
  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($base, FilesystemIterator::SKIP_DOTS)
  );
  foreach ($it as $f) {
    if (!$f->isFile()) continue;
    if (!preg_match('/\.(webp|jpe?g|png|gif)$/i', $f->getFilename())) continue;
    // Store relative path from /media/ using forward slashes
    $rel = ltrim(str_replace(DIRECTORY_SEPARATOR, '/', substr($f->getPathname(), strlen($base))), '/');
    $files[] = $rel;
  }
  return $files;
}

function product_image_file(array $p): ?string {
  // 1. Explicit image field — highest priority (exact filename or relative path from /media/)
  if (!empty($p['image'])) {
    $explicit = ltrim(str_replace('\\', '/', $p['image']), '/');
    $full = PUBLIC_PATH . '/media/' . $explicit;
    if (is_file($full)) return $explicit;
  }

  // 2. Fuzzy token match across all files found under /media/ (recursive)
  $candidates = array_values(array_unique(array_filter([
    trim(($p['model'] ?? '') . ' ' . ($p['title'] ?? '')),
    trim(($p['title'] ?? '') . ' ' . ($p['model'] ?? '')),
    trim($p['title'] ?? ''),
    trim($p['model'] ?? ''),
  ])));

  $candidateTokens = [];
  foreach ($candidates as $candidate) {
    $candidateTokens = array_values(array_unique(array_merge($candidateTokens, _product_media_tokens($candidate))));
  }
  if (!$candidateTokens) return null;

  $bestFile = null;
  $bestScore = 0;
  foreach (_product_media_files() as $file) {
    // Use only the filename (not the directory) for matching
    $fileTokens = _product_media_tokens(pathinfo($file, PATHINFO_FILENAME));
    if (!$fileTokens) continue;

    $shared = count(array_intersect($candidateTokens, $fileTokens));
    if (!$shared) continue;

    $coverage = $shared / max(count($candidateTokens), count($fileTokens));
    $score = ($shared * 100) + (int)round($coverage * 100);
    if ($score > $bestScore) {
      $bestScore = $score;
      $bestFile = $file;
    }
  }

  return $bestScore >= 150 ? $bestFile : null;
}

function product_image_url(array $p): ?string {
  $file = product_image_file($p);
  if (!$file) return null;
  // For paths with subdirectories, encode each segment separately
  $parts = explode('/', $file);
  $encoded = implode('/', array_map('rawurlencode', $parts));
  return url('media/' . $encoded);
}

function product_visual(array $p, string $extraClass = '', bool $decorative = false): string {
  $src = product_image_url($p);
  if ($src) {
    $alt = $decorative ? '' : trim(($p['model'] ?? '') . ' ' . ($p['title'] ?? ''));
    return '<img class="pframe ' . e($extraClass) . '" src="' . e($src) . '" alt="' . attr($alt) . '" loading="lazy" decoding="async"' . ($decorative ? ' aria-hidden="true"' : '') . '>';
  }

  return product_frame($p['cat'] ?? '', $extraClass);
}

function category_product_visual(string $cat, string $extraClass = '', bool $decorative = false): string {
  $products = get_products($cat);
  if (!$products) return product_frame($cat, $extraClass);
  return product_visual($products[0], $extraClass, $decorative);
}
