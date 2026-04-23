<?php
/**
 * mcar Mail — send lead notifications
 * Uses PHP mail() by default; swap to SMTP by configuring env.
 * Safe to fail silently — the user sees the success message regardless.
 */

require_once __DIR__ . '/env.php';

/**
 * Send a lead notification email to the sales team.
 * @param array $data  name, phone, email, source, car_id, pkg_id, deal_type, message
 * @return bool
 */
function send_lead_email($data) {
    $to     = env('MAIL_TO', 'vip@mcar.co.il');
    $from   = env('MAIL_FROM', 'noreply@mcar.co.il');
    $fromName = env('MAIL_FROM_NAME', 'mcar');

    if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) return false;

    $name    = htmlspecialchars($data['name'] ?? '', ENT_QUOTES, 'UTF-8');
    $phone   = htmlspecialchars($data['phone'] ?? '', ENT_QUOTES, 'UTF-8');
    $email   = htmlspecialchars($data['email'] ?? '', ENT_QUOTES, 'UTF-8');
    $source  = htmlspecialchars($data['source'] ?? 'web', ENT_QUOTES, 'UTF-8');
    $carId   = htmlspecialchars($data['car_id'] ?? '—', ENT_QUOTES, 'UTF-8');
    $pkgId   = htmlspecialchars($data['pkg_id'] ?? '—', ENT_QUOTES, 'UTF-8');
    $deal    = htmlspecialchars($data['deal_type'] ?? '—', ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($data['message'] ?? '', ENT_QUOTES, 'UTF-8');

    $subject = "ליד חדש מ-mcar · {$name}";

    $body = "<!doctype html><html lang=he dir=rtl><body style=\"font-family:system-ui,sans-serif;max-width:600px;margin:20px auto;padding:20px;color:#0a1740\">
<h2 style=\"color:#0f766e\">ליד חדש · mcar</h2>
<table style=\"width:100%;border-collapse:collapse;font-size:15px\">
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700;width:140px\">שם מלא</td><td style=\"padding:10px 14px;background:#fff\">{$name}</td></tr>
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700\">טלפון</td><td style=\"padding:10px 14px;background:#fff\"><a href=\"tel:{$phone}\">{$phone}</a></td></tr>
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700\">אימייל</td><td style=\"padding:10px 14px;background:#fff\">{$email}</td></tr>
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700\">רכב</td><td style=\"padding:10px 14px;background:#fff\">{$carId}</td></tr>
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700\">חבילה</td><td style=\"padding:10px 14px;background:#fff\">{$pkgId}</td></tr>
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700\">סוג עסקה</td><td style=\"padding:10px 14px;background:#fff\">{$deal}</td></tr>
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700\">מקור</td><td style=\"padding:10px 14px;background:#fff\">{$source}</td></tr>
  <tr><td style=\"padding:10px 14px;background:#f4f6fb;font-weight:700;vertical-align:top\">הודעה</td><td style=\"padding:10px 14px;background:#fff\">{$message}</td></tr>
</table>
<p style=\"margin-top:20px;font-size:12px;color:#8891b3\">נשלח אוטומטית מ-mcar.co.il · IP: " . ($_SERVER['REMOTE_ADDR'] ?? '—') . "</p>
</body></html>";

    $headers = [
        "MIME-Version: 1.0",
        "Content-type: text/html; charset=utf-8",
        "From: {$fromName} <{$from}>",
        "Reply-To: " . ($email ?: $from),
        "X-Mailer: mcar-web",
    ];

    // If SMTP is configured, try PHPMailer (if vendored); otherwise fall back to mail()
    $smtpHost = env('SMTP_HOST');
    if ($smtpHost && file_exists(__DIR__ . '/lib/PHPMailer/PHPMailer.php')) {
        return send_via_smtp($to, $subject, $body, $from, $fromName, $email);
    }

    return @mail($to, $subject, $body, implode("\r\n", $headers));
}

/**
 * SMTP sender. Only runs if PHPMailer is vendored at includes/lib/PHPMailer/.
 * Drop PHPMailer.php + SMTP.php + Exception.php there to enable.
 */
function send_via_smtp($to, $subject, $body, $from, $fromName, $replyTo = null) {
    $lib = __DIR__ . '/lib/PHPMailer/';
    if (!file_exists($lib . 'PHPMailer.php')) return false;

    require_once $lib . 'PHPMailer.php';
    require_once $lib . 'SMTP.php';
    require_once $lib . 'Exception.php';

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = env('SMTP_HOST');
        $mail->Port       = (int)env('SMTP_PORT', 587);
        $mail->SMTPAuth   = true;
        $mail->Username   = env('SMTP_USER');
        $mail->Password   = env('SMTP_PASS');
        $mail->SMTPSecure = env('SMTP_SECURE', 'tls');
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        if ($replyTo) $mail->addReplyTo($replyTo);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        return $mail->send();
    } catch (Exception $e) {
        error_log('SMTP failed: ' . $e->getMessage());
        return false;
    }
}
