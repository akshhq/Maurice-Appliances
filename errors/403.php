<?php
http_response_code(403);
$errCode = 403;
$errTitle = 'Access denied';
$errMessage = 'You do not have permission to view this page.';
$errActionUrl = (function_exists('url') ? url('index.php') : '/index.php');
$errActionLabel = 'Back to home';
require __DIR__ . '/error-template.php';
