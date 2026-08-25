<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
$hasVendor = file_exists($autoloadPath);

if ($hasVendor) {
    require_once $autoloadPath;
}

$config = require __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| Response Helpers
|--------------------------------------------------------------------------
*/
function jsonResponse(bool $success, string $message, int $status = 200): never {
    http_response_code($status);
    echo json_encode([
        'success' => $success,
        'message' => $message,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function cleanValue(mixed $value): string {
    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }
    return trim((string) $value);
}

function labelFromKey(string $key): string {
    return ucwords(str_replace(['_', '-'], ' ', $key));
}

/*
|--------------------------------------------------------------------------
| Request Method Validation
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed. Please submit the form via POST.', 405);
}

/*
|--------------------------------------------------------------------------
| Honeypot Spam Check
|--------------------------------------------------------------------------
| The frontend has an invisible "website" input. Bots fill it; real users do not.
*/
if (!empty($_POST['website'])) {
    // Return a fake success so bots do not learn they were caught
    jsonResponse(true, 'Submitted successfully.');
}

/*
|--------------------------------------------------------------------------
| Form Type & Subject Mapping
|--------------------------------------------------------------------------
*/
$formType = cleanValue($_POST['formType'] ?? 'general_submission');

$subjects = [
    'contact_message'        => 'New Contact Form Submission',
    'dealer_application'     => 'New Dealer Application',
    'job_application'        => 'New Job Application',
    'warranty_registration'  => 'New Warranty Registration',
    'service_ticket'         => 'New Service Request',
    'express_dealer_callback'=> 'New Express Dealer Callback',
    'product_inquiry'        => 'New Product Inquiry',
    'newsletter_subscription'=> 'New Newsletter Subscription',
    'general_submission'     => 'New Website Form Submission',
];

$subject = $subjects[$formType] ?? 'New Website Form Submission';

/*
|--------------------------------------------------------------------------
| Build Email Body (HTML & Plain Text)
|--------------------------------------------------------------------------
*/
$body = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 8px; background: #ffffff;">';
$body .= '<div style="border-bottom: 2px solid #e01e26; padding-bottom: 12px; margin-bottom: 20px;">';
$body .= '<h2 style="color: #131416; margin: 0 0 4px 0; font-size: 20px;">' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</h2>';
$body .= '<p style="color: #6b7280; margin: 0; font-size: 13px;">Received from Maurice Appliances Website (' . htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'mauriceappliances.in', ENT_QUOTES, 'UTF-8') . ')</p>';
$body .= '</div>';

$body .= '<table style="width: 100%; border-collapse: collapse; font-size: 14px; color: #374151;">';

foreach ($_POST as $key => $value) {
    if (in_array($key, ['website', 'formType'], true)) {
        continue;
    }

    $clean = cleanValue($value);
    if ($clean === '') continue;

    $label = labelFromKey($key);

    $body .= '<tr>';
    $body .= '<td style="padding: 10px 12px; font-weight: bold; width: 35%; border-bottom: 1px solid #f3f4f6; color: #111827; background: #f9fafb;">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</td>';
    $body .= '<td style="padding: 10px 12px; border-bottom: 1px solid #f3f4f6; color: #1f2937;">' . nl2br(htmlspecialchars($clean, ENT_QUOTES, 'UTF-8')) . '</td>';
    $body .= '</tr>';
}

$body .= '<tr>';
$body .= '<td style="padding: 10px 12px; font-weight: bold; border-bottom: 1px solid #f3f4f6; color: #111827; background: #f9fafb;">Form Type</td>';
$body .= '<td style="padding: 10px 12px; border-bottom: 1px solid #f3f4f6; color: #1f2937;">' . htmlspecialchars($formType, ENT_QUOTES, 'UTF-8') . '</td>';
$body .= '</tr>';

$body .= '<tr>';
$body .= '<td style="padding: 10px 12px; font-weight: bold; color: #111827; background: #f9fafb;">Submitted At</td>';
$body .= '<td style="padding: 10px 12px; color: #1f2937;">' . htmlspecialchars(date('d M Y, h:i A (T)'), ENT_QUOTES, 'UTF-8') . '</td>';
$body .= '</tr>';

$body .= '</table>';
$body .= '<div style="margin-top: 24px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; text-align: center;">';
$body .= 'Maurice Appliances Automated System &middot; <a href="https://mauriceappliances.in" style="color: #e01e26; text-decoration: none;">mauriceappliances.in</a>';
$body .= '</div></div>';

/*
|--------------------------------------------------------------------------
| Reply-To Detection
|--------------------------------------------------------------------------
*/
$replyToEmail = cleanValue($_POST['email'] ?? $_POST['customer_email'] ?? '');
$replyToName  = cleanValue($_POST['name'] ?? $_POST['customer_name'] ?? $_POST['contact_person'] ?? '');

/*
|--------------------------------------------------------------------------
| Send via PHPMailer (or fallback)
|--------------------------------------------------------------------------
*/
if ($hasVendor && class_exists(PHPMailer::class)) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtp_username'];
        $mail->Password   = $config['smtp_password'];

        if (($config['smtp_security'] ?? 'ssl') === 'ssl' || ($config['smtp_port'] ?? 465) === 465) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $mail->Port       = (int) ($config['smtp_port'] ?? 465);

        // Sender & Recipient
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($config['to_email'], $config['to_name']);

        // Reply-To header
        if ($replyToEmail !== '' && filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
            $mail->addReplyTo($replyToEmail, $replyToName);
        }

        // Email Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags(preg_replace('/<br\s*\/?>/i', "\n", $body));

        $mail->send();

        jsonResponse(true, 'Your request has been submitted successfully.');

    } catch (Exception $e) {
        error_log('Maurice form mail error: ' . $e->getMessage());
        jsonResponse(false, 'Unable to submit your request right now. Please try again later.', 500);
    }
} else {
    // Fallback if vendor autoload is not yet present on server: Log and inform
    error_log("Maurice Form: PHPMailer vendor library not found. Please run 'composer install'.");
    
    // Attempt native mail fallback if PHP mail() is configured
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$config['from_name']} <{$config['from_email']}>\r\n";
    if ($replyToEmail !== '' && filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
        $headers .= "Reply-To: {$replyToName} <{$replyToEmail}>\r\n";
    }

    $sent = @mail($config['to_email'], $subject, $body, $headers);

    if ($sent) {
        jsonResponse(true, 'Your request has been submitted successfully.');
    } else {
        jsonResponse(false, 'PHPMailer vendor library not installed. Please run composer install.', 500);
    }
}
