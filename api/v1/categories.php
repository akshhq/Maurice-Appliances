<?php
/**
 * GET /api/v1/categories.php — category list with counts.
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$out = [];
foreach (get_categories() as $c) {
  $out[] = [
    'id'    => $c['id'],
    'name'  => $c['name'],
    'count' => category_count($c['id']),
    'url'   => url(category_url($c['id'])),
  ];
}
echo json_encode(['data' => $out, 'total' => count($out)], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
