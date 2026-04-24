<?php
/**
 * Admin layout — clean, modern, light theme with SVG icons
 */

require_once __DIR__ . '/../includes/functions.php';

function admin_svg($name) {
    $icons = [
        'dashboard' => '<path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>',
        'leads'     => '<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>',
        'pages'     => '<path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>',
        'cars'      => '<path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>',
        'packages'  => '<path d="M12 2L3 7v10l9 5 9-5V7l-9-5zm0 2.31L18.5 8 12 11.69 5.5 8 12 4.31zM5 16.2V9.31l6 3.46v6.89l-6-3.46zm8 3.49v-6.89l6-3.46v6.89l-6 3.46z"/>',
        'faq'       => '<path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/>',
        'menu'      => '<path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>',
        'media'     => '<path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>',
        'settings'  => '<path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>',
        'logout'    => '<path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>',
        'external'  => '<path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/>',
        'bell'      => '<path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>',
    ];
    $path = $icons[$name] ?? $icons['dashboard'];
    return '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">' . $path . '</svg>';
}

function admin_header($title) {
    $cur = basename($_SERVER['PHP_SELF'], '.php');
    ?>
<!doctype html>
<html lang="he" dir="rtl">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($title); ?> · mcar admin</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; }
    :root {
        --side-w: 240px;
        --bg: #fafbfc;
        --surface: #ffffff;
        --ink: #0a1740;
        --ink-2: #394971;
        --ink-3: #6b7a99;
        --ink-4: #98a1b8;
        --border: #eef0f4;
        --border-strong: #dde1e9;
        --hover: #f5f7fa;
        --accent: #0f766e;
        --accent-2: #14b8a6;
        --accent-soft: #ecfdf5;
        --danger: #dc2626;
        --danger-soft: #fef2f2;
        --warn: #d97706;
        --ok: #059669;
        --shadow-xs: 0 1px 2px rgba(16,24,40,.04);
        --shadow-sm: 0 1px 3px rgba(16,24,40,.06), 0 1px 2px rgba(16,24,40,.04);
        --shadow-md: 0 4px 8px -2px rgba(16,24,40,.08), 0 2px 4px -2px rgba(16,24,40,.04);
    }
    html, body { margin: 0; padding: 0; }
    body {
        font-family: 'Rubik', -apple-system, 'Segoe UI', system-ui, sans-serif;
        background: var(--bg); color: var(--ink);
        font-size: 14px; line-height: 1.5;
        -webkit-font-smoothing: antialiased;
        min-height: 100vh;
    }

    /* ════════ SIDEBAR ════════ */
    .sidebar {
        position: fixed; top: 0; right: 0; bottom: 0; width: var(--side-w);
        background: var(--surface);
        border-left: 1px solid var(--border);
        display: flex; flex-direction: column;
        z-index: 100;
    }

    .brand {
        padding: 20px 20px 18px;
        display: flex; align-items: center; gap: 11px;
    }
    .brand-mark {
        width: 34px; height: 34px;
        background: linear-gradient(135deg, var(--accent-2), var(--accent));
        border-radius: 9px; display: grid; place-items: center;
        color: #fff; font-weight: 800; font-size: 17px; letter-spacing: -0.04em;
        box-shadow: 0 2px 6px -1px rgba(15,118,110,.3);
    }
    .brand-text { font-weight: 700; font-size: 15px; letter-spacing: -0.01em; line-height: 1.2; }
    .brand-text small {
        display: block; font-size: 11px; color: var(--ink-4);
        font-weight: 500; margin-top: 2px; letter-spacing: 0;
    }

    .nav { flex: 1; overflow-y: auto; padding: 6px 12px 12px; }
    .nav-group { margin-top: 14px; }
    .nav-group:first-child { margin-top: 0; }
    .nav-label {
        font-size: 11px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase;
        color: var(--ink-4); padding: 10px 12px 6px;
    }
    .nav a {
        display: flex; align-items: center; gap: 11px;
        padding: 8px 12px; border-radius: 7px;
        color: var(--ink-2); text-decoration: none;
        font-size: 14px; font-weight: 500;
        margin-bottom: 1px;
        transition: background 0.12s, color 0.12s;
    }
    .nav a:hover { background: var(--hover); color: var(--ink); }
    .nav a.on {
        background: var(--accent-soft);
        color: var(--accent);
        font-weight: 600;
    }
    .nav a svg { flex-shrink: 0; opacity: 0.85; }
    .nav a.on svg { opacity: 1; }

    .side-foot {
        padding: 10px 12px 14px;
        border-top: 1px solid var(--border);
    }
    .side-foot a {
        display: flex; align-items: center; gap: 11px;
        padding: 8px 12px; border-radius: 7px;
        color: var(--ink-3); text-decoration: none;
        font-size: 13px; font-weight: 500;
        transition: background 0.12s, color 0.12s;
    }
    .side-foot a:hover { background: var(--hover); color: var(--ink); }
    .side-foot a.danger:hover { background: var(--danger-soft); color: var(--danger); }

    /* ════════ MAIN ════════ */
    .main { margin-right: var(--side-w); min-height: 100vh; }

    .topbar {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 0 32px;
        height: 60px;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 10;
    }
    .topbar-title { font-size: 15px; font-weight: 600; color: var(--ink); }
    .topbar-actions { display: flex; align-items: center; gap: 10px; }

    .user-chip {
        display: inline-flex; align-items: center; gap: 9px;
        padding: 5px 14px 5px 5px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 999px; font-size: 13px; font-weight: 500;
        color: var(--ink);
    }
    .user-avatar {
        width: 26px; height: 26px; border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-2), var(--accent));
        color: #fff; display: grid; place-items: center;
        font-weight: 700; font-size: 12px;
    }

    .icon-btn {
        width: 34px; height: 34px; border-radius: 8px;
        display: grid; place-items: center;
        background: transparent; border: none; cursor: pointer;
        color: var(--ink-3); transition: background 0.12s, color 0.12s;
    }
    .icon-btn:hover { background: var(--hover); color: var(--ink); }

    /* ════════ CONTENT ════════ */
    .container { padding: 28px 32px; max-width: 1320px; }

    h1 {
        font-size: 24px; font-weight: 700; letter-spacing: -0.01em;
        margin: 0 0 6px; color: var(--ink);
    }
    .page-sub {
        font-size: 14px; color: var(--ink-3); margin: 0 0 24px;
    }

    h2 { font-size: 17px; font-weight: 600; margin: 0 0 12px; color: var(--ink); }
    h3 { font-size: 14px; font-weight: 600; margin: 0 0 10px; color: var(--ink); }
    label { display: block; font-size: 13px; font-weight: 500; color: var(--ink-2); margin-bottom: 6px; }

    .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 22px;
        box-shadow: var(--shadow-xs);
    }

    /* Inputs */
    input[type="text"], input[type="email"], input[type="number"], input[type="tel"],
    input[type="password"], input[type="url"], textarea, select {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid var(--border-strong);
        border-radius: 7px;
        font-family: inherit;
        font-size: 14px;
        background: var(--surface);
        color: var(--ink);
        transition: border-color 0.12s, box-shadow 0.12s;
    }
    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(15,118,110,0.1);
    }
    textarea { resize: vertical; min-height: 80px; }

    /* Tables */
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    thead th {
        background: var(--bg);
        font-weight: 600; font-size: 12px; color: var(--ink-3);
        text-transform: uppercase; letter-spacing: 0.04em;
        padding: 10px 16px; text-align: right;
        border-bottom: 1px solid var(--border);
    }
    tbody td { padding: 12px 16px; text-align: right; border-bottom: 1px solid var(--border); }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: var(--bg); }
    .card > table { margin: -22px; width: calc(100% + 44px); }
    .card > table thead th:first-child { padding-right: 22px; }
    .card > table tbody td:first-child { padding-right: 22px; }
    .card > table thead th:last-child { padding-left: 22px; }
    .card > table tbody td:last-child { padding-left: 22px; }

    /* Badges */
    .badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; font-family: 'JetBrains Mono', monospace; letter-spacing: 0.02em; }
    .badge-new { background: #dbeafe; color: #1e40af; }
    .badge-contacted { background: #fef3c7; color: #92400e; }
    .badge-qualified { background: #d1fae5; color: #065f46; }
    .badge-closed { background: #e9d5ff; color: #5b21b6; }
    .badge-lost { background: #fee2e2; color: #991b1b; }

    /* Buttons */
    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px; border-radius: 7px;
        font-family: inherit; font-size: 13px; font-weight: 600;
        text-decoration: none; cursor: pointer;
        border: 1px solid var(--border-strong);
        background: var(--surface); color: var(--ink);
        transition: all 0.12s;
    }
    .btn:hover { background: var(--hover); border-color: var(--ink-4); }
    .btn-primary {
        background: var(--accent);
        color: #fff; border-color: var(--accent);
    }
    .btn-primary:hover { background: #0b5a55; border-color: #0b5a55; color: #fff; }
    .btn-sm { padding: 5px 10px; font-size: 12px; }
    .btn-danger { color: var(--danger); border-color: var(--border-strong); }
    .btn-danger:hover { background: var(--danger-soft); border-color: var(--danger); }

    /* Alerts */
    .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid transparent; }
    .alert-warn { background: #fef3c7; color: #92400e; border-color: #fde68a; }
    .alert-info { background: var(--accent-soft); color: var(--accent); border-color: #a7f3d0; }
    .alert-err { background: var(--danger-soft); color: var(--danger); border-color: #fecaca; }

    /* Stat cards */
    .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
    .stat {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 10px; padding: 18px 20px;
        transition: all 0.15s;
    }
    .stat:hover { border-color: var(--border-strong); box-shadow: var(--shadow-sm); }
    .stat .stat-label { font-size: 12px; color: var(--ink-3); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }
    .stat .stat-value { font-size: 26px; font-weight: 700; color: var(--ink); letter-spacing: -0.02em; margin-top: 4px; }
    .stat .stat-delta { font-size: 12px; color: var(--ok); font-weight: 500; margin-top: 4px; font-family: 'JetBrains Mono', monospace; }

    /* Mobile */
    .mobile-toggle {
        display: none; width: 36px; height: 36px;
        background: transparent; border: 1px solid var(--border-strong);
        border-radius: 8px; cursor: pointer; padding: 0;
        align-items: center; justify-content: center;
        color: var(--ink-2);
    }
    .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(10,23,64,0.4); z-index: 99; }

    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(100%);
            transition: transform 0.25s ease-out;
            width: 280px; box-shadow: -10px 0 30px rgba(0,0,0,0.08);
        }
        .sidebar.open { transform: translateX(0); }
        .sidebar.open ~ .sidebar-backdrop { display: block; }
        .main { margin-right: 0; }
        .topbar { padding: 0 16px; }
        .container { padding: 20px 16px; }
        .mobile-toggle { display: inline-flex; }
        .stat-row { grid-template-columns: repeat(2, 1fr); }
    }
</style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-mark">m</div>
        <div class="brand-text">mcar<small>admin panel</small></div>
    </div>

    <nav class="nav">
        <div class="nav-group">
            <div class="nav-label">ראשי</div>
            <a href="index.php" class="<?php echo $cur==='index'?'on':''; ?>"><?php echo admin_svg('dashboard'); ?> דשבורד</a>
            <a href="leads.php" class="<?php echo $cur==='leads'?'on':''; ?>"><?php echo admin_svg('leads'); ?> לידים</a>
        </div>

        <div class="nav-group">
            <div class="nav-label">תוכן</div>
            <a href="pages.php"    class="<?php echo in_array($cur,['pages','page_edit'])?'on':''; ?>"><?php echo admin_svg('pages'); ?> עמודים</a>
            <a href="cars.php"     class="<?php echo in_array($cur,['cars','car_edit'])?'on':''; ?>"><?php echo admin_svg('cars'); ?> רכבים</a>
            <a href="packages.php" class="<?php echo $cur==='packages'?'on':''; ?>"><?php echo admin_svg('packages'); ?> חבילות</a>
            <a href="faq.php"      class="<?php echo $cur==='faq'?'on':''; ?>"><?php echo admin_svg('faq'); ?> שאלות נפוצות</a>
        </div>

        <div class="nav-group">
            <div class="nav-label">הגדרות</div>
            <a href="menu.php"     class="<?php echo $cur==='menu'?'on':''; ?>"><?php echo admin_svg('menu'); ?> תפריט / פוטר</a>
            <a href="media.php"    class="<?php echo $cur==='media'?'on':''; ?>"><?php echo admin_svg('media'); ?> מדיה</a>
            <a href="settings.php" class="<?php echo $cur==='settings'?'on':''; ?>"><?php echo admin_svg('settings'); ?> הגדרות אתר</a>
        </div>
    </nav>

    <div class="side-foot">
        <a href="../" target="_blank"><?php echo admin_svg('external'); ?> צפה באתר</a>
        <a href="logout.php" class="danger"><?php echo admin_svg('logout'); ?> יציאה</a>
    </div>
</aside>
<div class="sidebar-backdrop" onclick="document.getElementById('sidebar').classList.remove('open')"></div>

<div class="main">
    <div class="topbar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')" aria-label="תפריט">
            <?php echo admin_svg('menu'); ?>
        </button>
        <div class="topbar-title"><?php echo htmlspecialchars($title); ?></div>
        <div class="topbar-actions">
            <span class="user-chip">
                <span class="user-avatar"><?php echo strtoupper(mb_substr($_SESSION['admin_user'] ?? 'A', 0, 1)); ?></span>
                <?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'admin'); ?>
            </span>
        </div>
    </div>

    <main class="container">
<?php
}

function admin_footer() {
    ?>
    </main>
</div>

</body>
</html>
<?php
}
