<?php
/**
 * POST /api/v1/contact.php — contact & product enquiry.
 */
require_once dirname(__DIR__, 2) . '/app/bootstrap/app.php';
require_once APP_PATH . '/validation/FormRequest.php';
require_once APP_PATH . '/services/MailService.php';

$data = handle_form_post([
  'name'    => 'name',
  'email'   => 'email address',
  'message' => 'message',
], 'contact');

$to   = config('mail', 'to', [])['enquiries'] ?? 'mauriceappliances@gmail.com';
$body = format_enquiry($data, 'New enquiry from mauriceappliances.in');
$sent = send_mail($to, 'Website enquiry — ' . ($data['name'] ?? ''), $body, ['reply_to' => $data['email'] ?? '']);

json_response($sent,
  $sent ? 'Thank you — we have received your message and will be in touch shortly.'
        : 'We could not send your message right now. Please call ' . (get_company()['phones'][0] ?? 'customer support') . ' or email us directly.',
  $sent ? 200 : 500);
