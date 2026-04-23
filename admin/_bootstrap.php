<?php
/**
 * mcar Admin — bootstrap (auth + headers).
 * All admin pages require this at the top.
 */
require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/security.php';

start_session_once();

// Pages that don't require auth
$publicPages = ['login.php'];
$current = basename($_SERVER['PHP_SELF']);

if (!in_array($current, $publicPages, true)) {
    if (empty($_SESSION['admin_user'])) {
        header('Location: login.php');
        exit;
    }
    // Idle timeout (30 min)
    if (isset($_SESSION['admin_last']) && (time() - $_SESSION['admin_last']) > 1800) {
        session_destroy();
        header('Location: login.php?timeout=1');
        exit;
    }
    $_SESSION['admin_last'] = time();
}

// Security headers
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
