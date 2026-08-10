<?php
/**
 * MAURICE — Mail configuration
 * Credentials come from .env only. Never hard-code them here.
 */

declare(strict_types=1);

return [
  'driver'     => env('MAIL_DRIVER', 'mail'),   // 'mail' (PHP mail) or 'smtp'
  'host'       => env('MAIL_HOST', ''),
  'port'       => (int) env('MAIL_PORT', '587'),
  'username'   => env('MAIL_USERNAME', ''),
  'password'   => env('MAIL_PASSWORD', ''),
  'encryption' => env('MAIL_ENCRYPTION', 'tls'),
  'from'       => [
    'address' => env('MAIL_FROM_ADDRESS', 'noreply@mauriceappliances.in'),
    'name'    => env('MAIL_FROM_NAME', 'Maurice Appliances'),
  ],
  'to' => [
    'enquiries' => env('MAIL_TO_ENQUIRIES', 'mauriceappliances@gmail.com'),
    'dealers'   => env('MAIL_TO_DEALERS', 'mauriceappliances@gmail.com'),
    'careers'   => env('MAIL_TO_CAREERS', 'mauriceappliances@gmail.com'),
  ],
];
