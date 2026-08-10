<?php
/**
 * CRON: newsletter queue processor.
 *
 * Placeholder — the current site collects subscribers to
 * storage/exports/newsletter-subscribers.csv and sends no bulk mail.
 * Wire this to an ESP (Mailchimp / Brevo / SES) when campaigns begin.
 */
require_once dirname(__DIR__) . '/app/bootstrap/app.php';

$file = STORAGE_PATH . '/exports/newsletter-subscribers.csv';
$count = is_readable($file) ? max(0, count(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))) : 0;

echo date('c') . " newsletter: {$count} subscriber(s) on file; no campaign configured\n";
