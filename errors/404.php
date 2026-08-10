<?php
http_response_code(404);
$errCode = 404;
$errTitle = 'Page not found';
$errMessage = 'The page you are looking for may have moved or no longer exists. Browse our full product range instead.';
$errActionUrl = (function_exists('url') ? url('products.php') : '/products.php');
$errActionLabel = 'Browse all products';
require __DIR__ . '/error-template.php';
