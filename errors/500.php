<?php
http_response_code(500);
$errCode = 500;
$errTitle = 'Something went wrong';
$errMessage = 'We hit an unexpected error on our end. Please try again in a moment.';
$errActionUrl = (function_exists('url') ? url('index.php') : '/index.php');
$errActionLabel = 'Back to home';
require __DIR__ . '/error-template.php';
