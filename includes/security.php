<?php
/**
 * mcar Security — CSRF tokens, reCAPTCHA, simple rate limiting, input sanitizing.
 */

require_once __DIR__ . '/env.php';

// Ensure a session exists (used for CSRF + admin login)
function start_session_once() {
    if (session_status() === PHP_SESSION_NONE) {
        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

// --------- CSRF ---------
function csrf_token() {
    start_session_once();
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
}

function csrf_check() {
    start_session_once();
    $given = $_POST['_csrf'] ?? '';
    return isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $given);
}

// --------- reCAPTCHA v3 ---------
function recaptcha_site_key() {
    return env('RECAPTCHA_SITE_KEY', '');
}

function recaptcha_enabled() {
    return !empty(env('RECAPTCHA_SITE_KEY')) && !empty(env('RECAPTCHA_SECRET_KEY'));
}

function recaptcha_script() {
    if (!recaptcha_enabled()) return '';
    $key = recaptcha_site_key();
    return "<script src=\"https://www.google.com/recaptcha/api.js?render={$key}\"></script>";
}

function recaptcha_verify($action = 'submit') {
    if (!recaptcha_enabled()) return true; // don't block if not configured

    $token = $_POST['g-recaptcha-response'] ?? '';
    if (!$token) return false;

    $secret = env('RECAPTCHA_SECRET_KEY');
    $response = @file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify',
        false,
        stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query(['secret' => $secret, 'response' => $token, 'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '']),
                'timeout' => 5,
            ],
        ])
    );
    if ($response === false) return false;

    $data = json_decode($response, true);
    $min = (float)env('RECAPTCHA_MIN_SCORE', '0.5');
    return !empty($data['success']) && isset($data['score']) && $data['score'] >= $min;
}

// --------- Rate limiting (in-memory via session) ---------
function rate_limit_ok($key, $max = 5, $window = 60) {
    start_session_once();
    $now = time();
    $bucket = $_SESSION['_rl'][$key] ?? [];
    $bucket = array_filter($bucket, fn($t) => $t > $now - $window);
    if (count($bucket) >= $max) return false;
    $bucket[] = $now;
    $_SESSION['_rl'][$key] = $bucket;
    return true;
}

// --------- Input sanitizing ---------
function clean_str($v, $max = 300) {
    $v = is_string($v) ? $v : '';
    $v = trim($v);
    $v = substr($v, 0, $max);
    return $v;
}
function clean_phone($v) {
    $v = clean_str($v, 40);
    return preg_replace('/[^0-9+\-\s()]/u', '', $v);
}
function clean_email($v) {
    $v = filter_var(clean_str($v, 160), FILTER_VALIDATE_EMAIL);
    return $v ?: null;
}
