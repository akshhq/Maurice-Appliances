<?php
/**
 * API v1 index — lists endpoints.
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
  'version'   => 'v1',
  'endpoints' => [
    'GET  /api/v1/products.php'  => 'Filterable product list (cat, q, band[], warranty[], sort, limit, offset)',
    'GET  /api/v1/categories.php'=> 'Category list with product counts',
    'POST /api/v1/contact.php'   => 'Contact / product enquiry (CSRF required)',
    'POST /api/v1/dealer.php'    => 'Dealer application (CSRF required)',
    'POST /api/v1/newsletter.php'=> 'Newsletter subscribe (CSRF required)',
  ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
