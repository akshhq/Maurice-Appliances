<?php
/**
 * MAURICE — Auth configuration
 * Reserved for the future dealer/admin portal (public_html/auth, /admin, /portal).
 * No authentication is active in the current public brochure site.
 */

declare(strict_types=1);

return [
  'enabled'         => false,
  'guard'           => 'session',
  'login_route'     => '/auth/login.php',
  'redirect_after'  => '/dashboard/',
  'password_algo'   => PASSWORD_DEFAULT,
  'min_password_len'=> 10,
  'max_attempts'    => 5,
  'lockout_seconds' => 900,
];
