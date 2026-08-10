<?php
/**
 * POST /api/v1/newsletter.php — newsletter subscribe.
 * Stores to a flat file outside the web root and notifies the team.
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';
require_once APP_PATH . '/validation/FormRequest.php';
require_once APP_PATH . '/services/MailService.php';

$data  = handle_form_post(['email' => 'email address'], 'newsletter');
$email = $data['email'];

// Append to the subscriber list (outside public_html).
$file = STORAGE_PATH . '/exports/newsletter-subscribers.csv';
@mkdir(dirname($file), 0775, true);
$isNew = true;
if (is_readable($file)) {
  foreach (file($file, FILE_IGNORE_NEW_LINES) as $line) {
    if (stripos($line, $email) === 0) { $isNew = false; break; }
  }
}
if ($isNew) {
  @file_put_contents($file, $email . ',' . date('c') . PHP_EOL, FILE_APPEND | LOCK_EX);
  send_mail(config('mail', 'to', [])['enquiries'] ?? '', 'New newsletter subscriber',
            format_enquiry(['email' => $email], 'New newsletter subscriber'));
}

json_response(true, $isNew ? 'Thank you — you are on the list.' : 'You are already subscribed.');
