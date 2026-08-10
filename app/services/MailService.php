<?php
/**
 * MAURICE — Mail service
 * Thin wrapper over PHP mail() with headers built from config/mail.php.
 * Swap send_mail() internals for SMTP/PHPMailer without touching callers.
 */

declare(strict_types=1);

function send_mail(string $to, string $subject, string $body, array $opts = []): bool {
  $cfg  = config('mail');
  $from = $opts['from'] ?? ($cfg['from']['address'] ?? 'noreply@mauriceappliances.in');
  $name = $opts['from_name'] ?? ($cfg['from']['name'] ?? SITE_NAME);

  $headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: ' . sprintf('%s <%s>', $name, $from),
    'X-Mailer: Maurice/1.0',
  ];
  if (!empty($opts['reply_to'])) {
    $headers[] = 'Reply-To: ' . $opts['reply_to'];
  }

  // Guard against header injection via the subject line.
  $subject = str_replace(["\r", "\n"], ' ', $subject);

  return @mail($to, $subject, $body, implode("\r\n", $headers));
}

/** Format an associative array of form fields into a readable plain-text body. */
function format_enquiry(array $fields, string $heading = 'New website enquiry'): string {
  $lines = [$heading, str_repeat('=', strlen($heading)), ''];
  foreach ($fields as $k => $v) {
    $label = ucwords(str_replace(['_', '-'], ' ', (string)$k));
    $lines[] = $label . ': ' . (is_array($v) ? implode(', ', $v) : (string)$v);
  }
  $lines[] = '';
  $lines[] = 'Submitted: ' . date('d M Y, H:i') . ' IST';
  $lines[] = 'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
  return implode("\n", $lines);
}
