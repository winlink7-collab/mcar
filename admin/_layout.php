<?php
/**
 * Admin layout — right-side fixed sidebar nav (RTL-first)
 */

function admin_header($title) {
    $cur = basename($_SERVER['PHP_SELF'], '.php');
    ?>
<!doctype html>
<html lang="he" dir="rtl">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($title); ?> · admin · mcar</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<style>
    * { box-sizing: border-box; }
    :root {
        --side-w: 240px;
        --bg: #f4f6fb;
        --surface: #fff;
        --ink: #0a1740;
        --ink-2: #1a2a5e;
        --ink-3: #5a6892;
        --ink-4: #8891b3;
        --border: rgba(0,35,102,.08);
        --border-strong: rgba(0,35,102,.14);
        --accent: #0f766e;
        --accent-2: #14b8a6;
        --accent-soft: rgba(15,118,110,.08);
        --shadow: 0 1px 3px rgba(10,23,64,.04);
        --shadow-2: 0 4px 14px rgba(10,23,64,.08);
    }
    body { font-family: 'Rubik', 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--ink); margin: 0; min-height: 100vh; }

    /* === Sidebar === */
    .sidebar {
        position: fixed; top: 0; right: 0; bottom: 0; width: var(--side-w);
        background: linear-gradient(180deg, #0a1740 0%, #060d2a 100%);
        color: #eaf0ff;
        display: flex; flex-direction: column;
        z-index: 100;
        box-shadow: -2px 0 12px rgba(0,0,0,.06);
    }
    .sidebar-brand {
        padding: 24px 20px 18px;
        display: flex; align-items: center; gap: 12px;
        border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .sidebar-brand .mark {
        width: 38px; height: 38px;
        background: linear-gradient(135deg, var(--accent-2), var(--accent));
        border-radius: 11px; display: grid; place-items: center;
        font-weight: 900; font-size: 18px; color: #fff;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.3), 0 4px 12px -2px var(--accent);
    }
    .sidebar-brand .name { font-weight: 800; font-size: 16px; line-height: 1.1; }
    .sidebar-brand .name small { display: block; font-size: 11px; color: rgba(200,210,240,.65); font-weight: 500; margin-top: 3px; font-family: 'JetBrains Mono', monospace; }

    .sidebar-nav { flex: 1; overflow-y: auto; padding: 16px 12px; display: flex; flex-direction: column; gap: 2px; }
    .sidebar-nav .group-label {
        font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        color: rgba(200,210,240,.45); padding: 14px 12px 6px;
    }
    .sidebar-nav a {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; border-radius: 9px;
        color: #c8d2f0; text-decoration: none;
        font-size: 14px; font-weight: 600;
        transition: all .15s;
    }
    .sidebar-nav a:hover { background: rgba(255,255,255,.06); color: #fff; }
    .sidebar-nav a.on {
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #fff;
        box-shadow: 0 4px 12px -4px var(--accent);
    }
    .sidebar-nav .icon { font-size: 16px; opacity: .9; flex-shrink: 0; }

    .sidebar-foot {
        padding: 14px 12px;
        border-top: 1px solid rgba(255,255,255,.06);
        display: flex; flex-direction: column; gap: 2px;
    }
    .sidebar-foot a {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; border-radius: 9px;
        color: rgba(200,210,240,.7); text-decoration: none;
        font-size: 13px; font-weight: 500;
        transition: all .15s;
    }
    .sidebar-foot a:hover { background: rgba(255,255,255,.06); color: #fff; }
    .sidebar-foot .logout-link:hover { background: rgba(225,29,72,.18); color: #fda4af; }

    /* === Main area === */
    .main { margin-right: var(--side-w); min-height: 100vh; }
    .topbar {
        background: var(--surface);
        border-bottom: 1px solid var(--border);
        padding: 14px 32px;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0; z-index: 10;
    }
    .topbar h2 { margin: 0; font-size: 17px; font-weight: 700; color: var(--ink); }
    .topbar .crumb { font-size: 13px; color: var(--ink-3); font-family: 'JetBrains Mono', monospace; }
    .topbar .user-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 6px 12px 6px 6px;
        background: var(--bg); border: 1px solid var(--border);
        border-radius: 999px; font-size: 13px; font-weight: 600;
    }
    .topbar .user-pill .avatar {
        width: 26px; height: 26px; border-radius: 50%;
        background: linear-gradient(135deg, var(--accent), var(--accent-2));
        color: #fff; display: grid; place-items: center; font-weight: 800; font-size: 12px;
    }

    .container { padding: 28px 32px; max-width: 1280px; }
    h1 { font-size: 26px; margin: 0 0 24px; font-weight: 800; }

    .card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 24px; box-shadow: var(--shadow); }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th, td { padding: 12px 14px; text-align: right; border-bottom: 1px solid rgba(0,35,102,.06); }
    th { background: var(--bg); font-weight: 700; font-size: 12px; color: var(--ink-3); text-transform: uppercase; letter-spacing: .06em; }
    tr:hover td { background: #f9fafe; }

    .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; font-family: 'JetBrains Mono', monospace; }
    .badge-new { background: #dbeafe; color: #1e40af; }
    .badge-contacted { background: #fef3c7; color: #92400e; }
    .badge-qualified { background: #d1fae5; color: #065f46; }
    .badge-closed { background: #e9d5ff; color: #5b21b6; }
    .badge-lost { background: #fee2e2; color: #991b1b; }

    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; border: 1px solid var(--border-strong); background: var(--surface); color: var(--ink); transition: all .15s; }
    .btn:hover { border-color: var(--accent); color: var(--accent); }
    .btn-primary { background: linear-gradient(180deg, var(--accent-2), var(--accent)); color: #fff; border-color: transparent; box-shadow: 0 4px 12px -4px var(--accent); }
    .btn-primary:hover { transform: translateY(-1px); color: #fff; }

    .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
    .alert-warn { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .alert-info { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }

    .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .stat { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 20px; transition: all .2s; }
    .stat:hover { transform: translateY(-2px); box-shadow: var(--shadow-2); border-color: var(--accent); }
    .stat .k { font-size: 28px; font-weight: 900; color: var(--ink); letter-spacing: -0.03em; }
    .stat .l { font-size: 12px; color: var(--ink-3); margin-top: 4px; font-family: 'JetBrains Mono', monospace; }

    /* === Mobile === */
    .mobile-toggle {
        display: none;
        background: var(--surface); border: 1px solid var(--border-strong);
        width: 38px; height: 38px; border-radius: 9px;
        align-items: center; justify-content: center; cursor: pointer;
        font-size: 18px;
    }
    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(100%); /* hidden off-screen right */
            transition: transform .25s;
            width: 280px;
        }
        .sidebar.open { transform: translateX(0); }
        .main { margin-right: 0; }
        .topbar { padding: 12px 16px; }
        .container { padding: 20px 16px; }
        .mobile-toggle { display: inline-flex; }
        .sidebar-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(10,23,64,.5); z-index: 99;
        }
        .sidebar.open ~ .sidebar-backdrop { display: block; }
        .stat-row { grid-template-columns: repeat(2, 1fr); }
    }
</style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="mark">m</div>
        <div class="name">mcar admin<small>ניהול האתר</small></div>
    </div>

    <nav class="sidebar-nav">
        <div class="group-label">ראשי</div>
        <a href="index.php" class="<?php echo $cur==='index'?'on':''; ?>"><span class="icon">📊</span> דשבורד</a>
        <a href="leads.php" class="<?php echo $cur==='leads'?'on':''; ?>"><span class="icon">📥</span> לידים</a>

        <div class="group-label">תוכן</div>
        <a href="pages.php"    class="<?php echo in_array($cur,['pages','page_edit'])?'on':''; ?>"><span class="icon">📄</span> עמודים</a>
        <a href="cars.php"     class="<?php echo in_array($cur,['cars','car_edit'])?'on':''; ?>"><span class="icon">🚗</span> רכבים</a>
        <a href="packages.php" class="<?php echo $cur==='packages'?'on':''; ?>"><span class="icon">📦</span> חבילות</a>
        <a href="faq.php"      class="<?php echo $cur==='faq'?'on':''; ?>"><span class="icon">❓</span> שאלות נפוצות</a>

        <div class="group-label">ניהול</div>
        <a href="menu.php"     class="<?php echo $cur==='menu'?'on':''; ?>"><span class="icon">🧭</span> תפריט / פוטר</a>
        <a href="media.php"    class="<?php echo $cur==='media'?'on':''; ?>"><span class="icon">🖼️</span> מדיה</a>
        <a href="settings.php" class="<?php echo $cur==='settings'?'on':''; ?>"><span class="icon">⚙️</span> הגדרות אתר</a>
    </nav>

    <div class="sidebar-foot">
        <a href="../" target="_blank">↗ צפה באתר</a>
        <a href="logout.php" class="logout-link">⏻ יציאה</a>
    </div>
</aside>
<div class="sidebar-backdrop" onclick="document.getElementById('sidebar').classList.remove('open')"></div>

<div class="main">
    <div class="topbar">
        <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">☰</button>
        <h2><?php echo htmlspecialchars($title); ?></h2>
        <span class="user-pill">
            <span class="avatar"><?php echo strtoupper(mb_substr($_SESSION['admin_user'] ?? 'A', 0, 1)); ?></span>
            <?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'admin'); ?>
        </span>
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
