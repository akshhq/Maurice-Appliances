<?php
http_response_code(401);
$errCode = 401;
$errTitle = 'Authentication required';
$errMessage = 'You need to sign in to view this page.';
$errActionUrl = (function_exists('url') ? url('index.php') : '/index.php');
$errActionLabel = 'Back to home';
require __DIR__ . '/error-template.php';
