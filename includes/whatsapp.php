<?php
/**
 * mcar WhatsApp notifications.
 *
 * Sends a message to the admin's WhatsApp when a new lead comes in.
 * Supports:
 *   1. CallMeBot (free, easiest)  — https://www.callmebot.com/blog/free-api-whatsapp-messages/
 *   2. Generic webhook URL — for any custom integration
 *
 * Setup (CallMeBot):
 *   1. Admin saves our bot number to their phone: +34 644 51 95 23
 *   2. Admin sends from WhatsApp: "I allow callmebot to send me messages"
 *   3. Bot replies with a personal API key
 *   4. Admin enters phone + api key in /admin/settings.php (group: whatsapp)
 */

require_once __DIR__ . '/cms.php';

/**
 * Send WhatsApp notification about a new lead.
 * Silent on failure — never blocks the form submission UX.
 *
 * @param array $lead  name, phone, email, source, car_id, pkg_id, deal_type, message
 * @return bool
 */
function send_lead_whatsapp($lead) {
    // Must be explicitly enabled
    if (setting('whatsapp_notify_enabled') !== '1') return false;

    $admin_wa = setting('admin_whatsapp');
    if (!$admin_wa) return false;

    $admin_wa = preg_replace('/[^0-9]/', '', $admin_wa);
    if (!$admin_wa) return false;

    $text = build_lead_message($lead);

    // Try providers in order
    $apikey = setting('callmebot_apikey');
    if ($apikey) return send_callmebot($admin_wa, $apikey, $text);

    $webhook = setting('whatsapp_webhook_url');
    if ($webhook) return send_generic_webhook($webhook, $admin_wa, $text, $lead);

    return false;
}

function build_lead_message($lead) {
    $name    = $lead['name']    ?? 'לא ידוע';
    $phone   = $lead['phone']   ?? '';
    $email   = $lead['email']   ?? '';
    $source  = $lead['source']  ?? 'web';
    $carId   = $lead['car_id']  ?? '';
    $pkgId   = $lead['pkg_id']  ?? '';
    $deal    = $lead['deal_type'] ?? '';
    $msg     = $lead['message'] ?? '';

    $lines = [];
    $lines[] = '*🔥 ליד חדש מ-mcar*';
    $lines[] = '';
    $lines[] = "👤 *שם:* {$name}";
    $lines[] = "📞 *טלפון:* {$phone}";
    if ($email)  $lines[] = "✉️ *אימייל:* {$email}";
    if ($carId)  $lines[] = "🚗 *רכב:* {$carId}";
    if ($pkgId)  $lines[] = "📦 *חבילה:* {$pkgId}";
    if ($deal)   $lines[] = "💼 *סוג:* {$deal}";
    $lines[] = "📍 *מקור:* {$source}";
    if ($msg) {
        $lines[] = '';
        $lines[] = "💬 {$msg}";
    }
    $lines[] = '';
    $lines[] = "👉 חזור ללקוח: https://wa.me/" . preg_replace('/[^0-9]/', '', $phone);

    return implode("\n", $lines);
}

function send_callmebot($phone, $apikey, $text) {
    $url = 'https://api.callmebot.com/whatsapp.php?' . http_build_query([
        'phone'  => $phone,
        'text'   => $text,
        'apikey' => $apikey,
    ]);

    $ctx = stream_context_create([
        'http' => ['timeout' => 8, 'ignore_errors' => true],
    ]);
    $resp = @file_get_contents($url, false, $ctx);
    return $resp !== false && stripos($resp, 'error') === false;
}

function send_generic_webhook($url, $phone, $text, $lead) {
    $payload = json_encode([
        'phone' => $phone,
        'text'  => $text,
        'lead'  => $lead,
    ]);
    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n",
            'content' => $payload,
            'timeout' => 8,
            'ignore_errors' => true,
        ],
    ]);
    $resp = @file_get_contents($url, false, $ctx);
    return $resp !== false;
}
