<?php
/**
 * MAURICE — Data source configuration
 *
 * NOTE: This build is intentionally database-free (per the brief: no SQL).
 * Product data is served from a versioned JSON store in /database/.
 * The DB block below is left configured-but-disabled so the project can
 * adopt MySQL later without restructuring.
 */

declare(strict_types=1);

return [
  'default' => env('DB_CONNECTION', 'json'),

  'connections' => [
    'json' => [
      'driver' => 'json',
      'path'   => BASE_PATH . '/database/schema',
      'files'  => [
        'products' => 'products.json',
      ],
    ],

    'mysql' => [
      'driver'    => 'mysql',
      'host'      => env('DB_HOST', 'localhost'),
      'port'      => env('DB_PORT', '3306'),
      'database'  => env('DB_DATABASE', ''),
      'username'  => env('DB_USERNAME', ''),
      'password'  => env('DB_PASSWORD', ''),
      'charset'   => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci',
    ],
  ],
];
