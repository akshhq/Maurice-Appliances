<?php
/**
 * MAURICE — Cache configuration
 */

declare(strict_types=1);

return [
  'enabled' => filter_var(env('CACHE_ENABLED', 'true'), FILTER_VALIDATE_BOOL),
  'driver'  => env('CACHE_DRIVER', 'file'),
  'path'    => BASE_PATH . '/storage/cache',
  'ttl'     => (int) env('CACHE_TTL', '3600'),
  'prefix'  => 'maurice_',
];
