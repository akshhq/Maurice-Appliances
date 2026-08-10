<?php
http_response_code(400);
$errCode = 400;
$errTitle = 'Bad request';
$errMessage = 'The request could not be understood. Please check the address and try again.';
$errActionUrl = (function_exists('url') ? url('index.php') : '/index.php');
$errActionLabel = 'Back to home';
require __DIR__ . '/error-template.php';
