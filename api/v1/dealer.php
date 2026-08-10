<?php
/**
 * POST /api/v1/dealer.php — dealer / distributor application.
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';
require_once APP_PATH . '/validation/FormRequest.php';
require_once APP_PATH . '/services/MailService.php';

$data = handle_form_post([
  'name'    => 'contact name',
  'firm'    => 'firm name',
  'email'   => 'email address',
  'phone'   => 'phone number',
  'city'    => 'city',
  'state'   => 'state',
], 'dealer');

$to   = config('mail', 'to', [])['dealers'] ?? 'mauriceappliances@gmail.com';
$body = format_enquiry($data, 'New dealer application');
$sent = send_mail($to, 'Dealer application — ' . ($data['firm'] ?? ''), $body, ['reply_to' => $data['email'] ?? '']);

json_response($sent,
  $sent ? 'Thank you — our team will review your application and contact you soon.'
        : 'We could not submit your application right now. Please call ' . (get_company()['phones'][0] ?? 'customer support') . '.',
  $sent ? 200 : 500);
