<?php
/**
 * MAURICE — Formatting helpers
 * Escaping, slugs, currency, text utilities.
 */

declare(strict_types=1);

/** HTML-escape for output. Use on every dynamic value printed to a page. */
function e(?string $s): string {
  return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Alias for attribute contexts (same escaping, clearer intent). */
function attr(?string $s): string { return e($s); }

/** URL-safe slug from arbitrary text. */
function slugify(string $s): string {
  $s = strtolower(trim($s));
  $s = preg_replace('/[^a-z0-9]+/', '-', $s);
  return trim((string)$s, '-');
}

/** Indian digit grouping: 24990 -> ₹24,990 · 124990 -> ₹1,24,990 */
function inr(int $n): string {
  $s = (string)$n;
  if (strlen($s) <= 3) return '₹' . $s;
  $last3 = substr($s, -3);
  $rest  = substr($s, 0, -3);
  $rest  = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
  return '₹' . $rest . ',' . $last3;
}

/** Truncate to a word boundary with an ellipsis. */
function str_excerpt(string $s, int $len = 155): string {
  $s = trim(preg_replace('/\s+/', ' ', $s));
  if (mb_strlen($s) <= $len) return $s;
  $cut = mb_substr($s, 0, $len);
  $sp  = mb_strrpos($cut, ' ');
  return rtrim($sp ? mb_substr($cut, 0, $sp) : $cut, ',.;: ') . '…';
}

/** Parse a "W × D × H" dimension string into labelled parts (mm). */
function parse_dim(string $dim): array {
  $parts  = preg_split('/\s*[\x{00D7}xX]\s*/u', trim($dim));
  $labels = ['Width', 'Depth', 'Height'];
  $out = [];
  foreach ($parts as $i => $v) {
    $v = trim($v);
    if ($v === '') continue;
    $out[$labels[$i] ?? 'Dim ' . ($i + 1)] = $v . (is_numeric($v) ? ' mm' : '');
  }
  return $out;
}
