<?php
/**
 * MAURICE — Payment configuration
 * Reserved. The current site is a brochure/catalogue site with no checkout.
 * Keys must come from .env if e-commerce is enabled later.
 */

declare(strict_types=1);

return [
  'enabled'  => false,
  'currency' => 'INR',
  'gateways' => [
    'razorpay' => [
      'key_id'     => env('RAZORPAY_KEY_ID', ''),
      'key_secret' => env('RAZORPAY_KEY_SECRET', ''),
    ],
    'stripe' => [
      'key'    => env('STRIPE_KEY', ''),
      'secret' => env('STRIPE_SECRET', ''),
    ],
  ],
];
