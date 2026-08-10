<?php
/**
 * API root — advertises available versions. No data exposed here.
 */
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'name'     => SITE_NAME . ' API',
  'versions' => ['v1' => url('api/v1/')],
  'status'   => 'ok',
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
