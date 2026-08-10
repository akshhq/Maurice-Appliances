<?php
http_response_code(503);
header('Retry-After: 3600');
if (!defined('BASE_PATH')) {
  $boot = dirname(__DIR__) . '/app/bootstrap/app.php';
  if (is_readable($boot)) { require_once $boot; }
}
$phone = function_exists('get_company') ? (get_company()['phones'][0] ?? '') : '';
$errCode = 503;
$errTitle = 'Down for scheduled maintenance';
$errMessage = 'We are making improvements and will be back shortly. Thank you for your patience.';
$errActionUrl = 'tel:' . preg_replace('/[^0-9+]/', '', $phone);
$errActionLabel = 'Call ' . $phone;
require __DIR__ . '/error-template.php';
