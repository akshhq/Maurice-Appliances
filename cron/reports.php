<?php
/**
 * CRON: weekly summary report to the team.
 * Suggested schedule: Monday 09:00
 */
require_once dirname(__DIR__) . '/app/bootstrap/app.php';
require_once APP_PATH . '/services/MailService.php';

$subs = STORAGE_PATH . '/exports/newsletter-subscribers.csv';
$body = format_enquiry([
  'products_live'        => total_products(),
  'categories'           => count(get_categories()),
  'newsletter_subscribers' => is_readable($subs) ? count(file($subs, FILE_SKIP_EMPTY_LINES)) : 0,
  'week_ending'          => date('d M Y'),
], 'Weekly website report');

$to = config('mail', 'to', [])['enquiries'] ?? '';
if ($to) send_mail($to, 'Maurice — weekly website report', $body);

echo date('c') . " reports: summary dispatched\n";
