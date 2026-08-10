<?php
/**
 * GET /api/v1/products.php — filterable product list.
 * Params: cat, q, band[], warranty[], sort, limit (max 100), offset
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=600');

$limit  = min(100, max(1, (int)($_GET['limit'] ?? 24)));
$offset = max(0, (int)($_GET['offset'] ?? 0));

$results = query_products([
  'cat'        => isset($_GET['cat'])      ? clean_input((string)$_GET['cat'])  : '',
  'q'          => isset($_GET['q'])        ? clean_input((string)$_GET['q'])    : '',
  'sort'       => isset($_GET['sort'])     ? clean_input((string)$_GET['sort']) : 'featured',
  'bands'      => isset($_GET['band'])     ? array_map('strval', (array)$_GET['band'])     : [],
  'warranties' => isset($_GET['warranty']) ? array_map('strval', (array)$_GET['warranty']) : [],
]);

$total = count($results);
$page  = array_slice($results, $offset, $limit);

$data = array_map(fn($p) => [
  'model'    => $p['model'] ?? '',
  'title'    => $p['title'] ?? '',
  'category' => $p['cat'],
  'slug'     => $p['slug'],
  'mrp'      => (int)($p['mrp'] ?? 0),
  'mrpFmt'   => inr((int)($p['mrp'] ?? 0)),
  'warranty' => $p['warranty'] ?? '',
  'specs'    => array_values($p['specs'] ?? []),
  'dim'      => $p['dim'] ?? '',
  'weight'   => $p['weight'] ?? '',
  'url'      => url(product_url($p)),
], $page);

echo json_encode([
  'data' => $data,
  'meta' => ['total' => $total, 'limit' => $limit, 'offset' => $offset, 'returned' => count($data)],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
