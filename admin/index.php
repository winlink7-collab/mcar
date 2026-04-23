<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';

$pdo = db();
$dbConnected = (bool)$pdo;

$stats = [
    'leads_today'  => 0,
    'leads_week'   => 0,
    'leads_total'  => 0,
    'leads_new'    => 0,
];

if ($dbConnected) {
    try {
        $stats['leads_today'] = (int)$pdo->query("SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()")->fetchColumn();
        $stats['leads_week']  = (int)$pdo->query("SELECT COUNT(*) FROM leads WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
        $stats['leads_total'] = (int)$pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();
        $stats['leads_new']   = (int)$pdo->query("SELECT COUNT(*) FROM leads WHERE status='new'")->fetchColumn();
    } catch (PDOException $e) {
        $dbConnected = false;
    }
}

admin_header('דשבורד');
?>
<h1>שלום, <?php echo htmlspecialchars($_SESSION['admin_user']); ?> 👋</h1>

<?php if (!$dbConnected): ?>
<div class="alert alert-warn">
    <strong>⚠ מסד הנתונים לא מחובר.</strong> הטפסים באתר ימשיכו לעבוד (יישלחו רק במייל), אבל לידים לא יישמרו ולא יוצגו כאן.
    <br>הגדר את משתני <code>DB_*</code> בקובץ <code>.env</code> והרץ את <code>migrations/001_schema.sql</code>.
    <br>ראה <a href="../DEPLOY-BACKEND.md">DEPLOY-BACKEND.md</a> להוראות.
</div>
<?php endif; ?>

<div class="stat-row">
    <div class="stat"><div class="k"><?php echo $stats['leads_today']; ?></div><div class="l">לידים היום</div></div>
    <div class="stat"><div class="k"><?php echo $stats['leads_week']; ?></div><div class="l">לידים השבוע</div></div>
    <div class="stat"><div class="k"><?php echo $stats['leads_new']; ?></div><div class="l">חדשים (לטיפול)</div></div>
    <div class="stat"><div class="k"><?php echo $stats['leads_total']; ?></div><div class="l">סה״כ לידים</div></div>
</div>

<?php if ($dbConnected && $stats['leads_total'] > 0): ?>
<div class="card">
    <h2 style="margin: 0 0 16px; font-size: 18px;">5 לידים אחרונים</h2>
    <table>
        <thead><tr><th>זמן</th><th>שם</th><th>טלפון</th><th>רכב</th><th>מקור</th><th>סטטוס</th></tr></thead>
        <tbody>
        <?php
        $rows = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5")->fetchAll();
        foreach ($rows as $r):
        ?>
        <tr>
            <td><?php echo date('d/m H:i', strtotime($r['created_at'])); ?></td>
            <td><strong><?php echo htmlspecialchars($r['name']); ?></strong></td>
            <td><a href="tel:<?php echo htmlspecialchars($r['phone']); ?>"><?php echo htmlspecialchars($r['phone']); ?></a></td>
            <td><?php echo htmlspecialchars($r['car_id'] ?: '—'); ?></td>
            <td><?php echo htmlspecialchars($r['source']); ?></td>
            <td><span class="badge badge-<?php echo htmlspecialchars($r['status']); ?>"><?php echo htmlspecialchars($r['status']); ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div style="margin-top: 16px; text-align: center;"><a href="leads.php" class="btn btn-primary">כל הלידים →</a></div>
</div>
<?php else: ?>
<div class="card" style="text-align: center; padding: 60px 20px; color: #5a6892;">
    אין לידים עדיין. כשמשתמש שולח טופס באתר, הוא יופיע כאן.
</div>
<?php endif; ?>

<?php admin_footer(); ?>
