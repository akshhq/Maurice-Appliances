<?php
/**
 * MAURICE — Security configuration
 */

declare(strict_types=1);

return [
  'csrf' => [
    'enabled'    => true,
    'token_name' => 'csrf_token',
    'lifetime'   => 7200,        // seconds
  ],
  'session' => [
    'name'      => 'MAURICE_SESSION',
    'lifetime'  => 7200,
    'path'      => BASE_PATH . '/storage/sessions',
    'secure'    => true,          // requires HTTPS in production
    'httponly'  => true,
    'samesite'  => 'Lax',
  ],
  'rate_limit' => [
    'form_submissions' => 5,      // per window
    'window'           => 600,    // seconds
  ],
  'headers' => [
    'X-Content-Type-Options' => 'nosniff',
    'X-Frame-Options'        => 'SAMEORIGIN',
    'Referrer-Policy'        => 'strict-origin-when-cross-origin',
  ],
  'allowed_upload_types' => ['jpg','jpeg','png','webp','pdf'],
  'max_upload_size'      => 5 * 1024 * 1024,
];
