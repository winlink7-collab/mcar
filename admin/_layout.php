<?php
/**
 * Reusable admin layout — call admin_header($title) at top of page,
 * admin_footer() at bottom.
 */

function admin_header($title) {
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
    body { font-family: 'Rubik', system-ui, sans-serif; background: #f4f6fb; color: #0a1740; margin: 0; min-height: 100vh; }
    .nav { background: #0a1740; color: #eaf0ff; padding: 14px 24px; display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .nav-brand { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 16px; }
    .nav-brand .mark { width: 32px; height: 32px; background: linear-gradient(135deg, #14b8a6, #0f766e); border-radius: 9px; display: grid; place-items: center; font-weight: 900; }
    .nav a { color: #c8d2f0; text-decoration: none; font-size: 14px; font-weight: 600; padding: 8px 14px; border-radius: 8px; transition: all .15s; }
    .nav a:hover, .nav a.on { background: rgba(255,255,255,.08); color: #fff; }
    .nav-links { display: flex; gap: 4px; align-items: center; flex: 1; justify-content: center; }
    .nav-actions a { background: #0f766e; color: #fff; }
    .container { max-width: 1280px; margin: 0 auto; padding: 28px 24px; }
    h1 { font-size: 26px; margin: 0 0 24px; }
    .card { background: #fff; border: 1px solid rgba(0,35,102,.08); border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(10,23,64,.04); }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th, td { padding: 12px 14px; text-align: right; border-bottom: 1px solid rgba(0,35,102,.06); }
    th { background: #f4f6fb; font-weight: 700; font-size: 12px; color: #5a6892; text-transform: uppercase; letter-spacing: .06em; }
    tr:hover td { background: #f9fafe; }
    .badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; font-family: 'JetBrains Mono', monospace; }
    .badge-new { background: #dbeafe; color: #1e40af; }
    .badge-contacted { background: #fef3c7; color: #92400e; }
    .badge-qualified { background: #d1fae5; color: #065f46; }
    .badge-closed { background: #e9d5ff; color: #5b21b6; }
    .badge-lost { background: #fee2e2; color: #991b1b; }
    .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; border: 1px solid rgba(0,35,102,.14); background: #fff; color: #0a1740; }
    .btn:hover { border-color: #0f766e; color: #0f766e; }
    .btn-primary { background: linear-gradient(180deg, #14b8a6, #0f766e); color: #fff; border-color: transparent; }
    .alert { padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
    .alert-warn { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .stat { background: #fff; border: 1px solid rgba(0,35,102,.08); border-radius: 14px; padding: 20px; }
    .stat .k { font-size: 28px; font-weight: 900; color: #0a1740; }
    .stat .l { font-size: 12px; color: #5a6892; margin-top: 4px; font-family: 'JetBrains Mono', monospace; }
</style>
</head>
<body>
<nav class="nav">
    <div class="nav-brand">
        <div class="mark">m</div>
        <span>mcar admin</span>
    </div>
    <div class="nav-links">
        <?php $cur = basename($_SERVER['PHP_SELF'], '.php'); ?>
        <a href="index.php"    class="<?php echo $cur==='index'?'on':''; ?>">דשבורד</a>
        <a href="leads.php"    class="<?php echo $cur==='leads'?'on':''; ?>">לידים</a>
        <a href="pages.php"    class="<?php echo in_array($cur,['pages','page_edit'])?'on':''; ?>">עמודים</a>
        <a href="cars.php"     class="<?php echo in_array($cur,['cars','car_edit'])?'on':''; ?>">רכבים</a>
        <a href="packages.php" class="<?php echo $cur==='packages'?'on':''; ?>">חבילות</a>
        <a href="faq.php"      class="<?php echo $cur==='faq'?'on':''; ?>">שאלות נפוצות</a>
        <a href="menu.php"     class="<?php echo $cur==='menu'?'on':''; ?>">תפריט/פוטר</a>
        <a href="media.php"    class="<?php echo $cur==='media'?'on':''; ?>">מדיה</a>
        <a href="settings.php" class="<?php echo $cur==='settings'?'on':''; ?>">הגדרות</a>
    </div>
    <div class="nav-actions">
        <a href="../" target="_blank">לאתר ↗</a>
        <a href="logout.php">יציאה</a>
    </div>
</nav>
<main class="container">
<?php
}

function admin_footer() {
    ?>
</main>
</body>
</html>
<?php
}
