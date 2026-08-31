<?php
/**
 * MAURICE APPLIANCES — Form & Email Configuration
 * Centralized settings for Hostinger SMTP and email routing.
 */

// Helper to parse .env file if present in the project root
if (!function_exists('loadMauriceEnv')) {
    function loadMauriceEnv(string $path): array {
        $vars = [];
        if (file_exists($path) && is_readable($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#')) continue;
                if (strpos($line, '=') !== false) {
                    [$key, $val] = explode('=', $line, 2);
                    $key = trim($key);
                    $val = trim(trim($val), "\"'");
                    $vars[$key] = $val;
                }
            }
        }
        return $vars;
    }
}

$env = loadMauriceEnv(__DIR__ . '/../.env');

return [
    /*
    |--------------------------------------------------------------------------
    | SMTP Server Settings
    |--------------------------------------------------------------------------
    */
    'smtp_host'     => $env['SMTP_HOST'] ?? 'smtp.hostinger.com',
    'smtp_port'     => (int) ($env['SMTP_PORT'] ?? 465),
    'smtp_security' => $env['SMTP_SECURITY'] ?? 'ssl', // 'ssl' (465) or 'tls' (587)
    'smtp_username' => $env['SMTP_USERNAME'] ?? 'customer.care@mauriceappliances.in',
    'smtp_password' => $env['SMTP_PASSWORD'] ?? 'PUT_YOUR_MAILBOX_PASSWORD_HERE',

    /*
    |--------------------------------------------------------------------------
    | Website Sender (From Header)
    |--------------------------------------------------------------------------
    */
    'from_email'    => $env['FROM_EMAIL'] ?? 'customer.care@mauriceappliances.in',
    'from_name'     => $env['FROM_NAME'] ?? 'Maurice Appliances Website',

    /*
    |--------------------------------------------------------------------------
    | Destination Mailbox (To Header)
    |--------------------------------------------------------------------------
    */
    'to_email'      => $env['TO_EMAIL'] ?? 'customer.care@mauriceappliances.in',
    'to_name'       => $env['TO_NAME'] ?? 'Maurice Appliances',

    /*
    |--------------------------------------------------------------------------
    | Google Sheets Webhook Integration
    |--------------------------------------------------------------------------
    | Automatically uploads every form submission to the configured Google Sheet
    | into its dedicated sheet tab alongside sending the notification email.
    */
    'google_sheet_id'           => $env['GOOGLE_SHEET_ID'] ?? '',
    'google_script_webapp_url'  => $env['GOOGLE_SCRIPT_WEBAPP_URL'] ?? '',
];
