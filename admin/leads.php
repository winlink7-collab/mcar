<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';

$pdo = db();

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check() && !empty($_POST['lead_id']) && !empty($_POST['status'])) {
    $allowed = ['new','contacted','qualified','closed','lost'];
    if (in_array($_POST['status'], $allowed, true)) {
        $stmt = $pdo->prepare("UPDATE leads SET status = :s WHERE id = :id");
        $stmt->execute([':s' => $_POST['status'], ':id' => (int)$_POST['lead_id']]);
    }
    header('Location: leads.php');
    exit;
}

$filter = $_GET['status'] ?? 'all';
$where = $filter !== 'all' ? "WHERE status = :s" : "";
$stmt = $pdo->prepare("SELECT * FROM leads $where ORDER BY created_at DESC LIMIT 200");
if ($filter !== 'all') $stmt->execute([':s' => $filter]);
else $stmt->execute();
$rows = $stmt->fetchAll();

admin_header('לידים');
?>
<h1>לידים <span style="color:#5a6892;font-size:16px;font-weight:500">(<?php echo count($rows); ?>)</span></h1>

<div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; align-items: center;">
    <?php foreach (['all'=>'הכל','new'=>'חדשים','contacted'=>'יצרנו קשר','qualified'=>'מתאים','closed'=>'סגור','lost'=>'אבוד'] as $k=>$v): ?>
    <a href="?status=<?php echo $k; ?>" class="btn <?php echo $filter===$k?'btn-primary':''; ?>"><?php echo $v; ?></a>
    <?php endforeach; ?>
    <a href="leads_export.php" class="btn" style="margin-right: auto;">📥 ייצא ל-CSV</a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
<table>
    <thead><tr><th>זמן</th><th>שם</th><th>טלפון</th><th>אימייל</th><th>רכב/חבילה</th><th>מקור</th><th>סטטוס</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r):
        $phone_clean = preg_replace('/[^0-9]/', '', $r['phone']);
        // Convert Israeli local (05x…) to international (972…)
        if (strlen($phone_clean) > 0 && $phone_clean[0] === '0') $phone_clean = '972' . substr($phone_clean, 1);
        $wa_text = "שלום " . $r['name'] . "! 👋 פניתם אלינו ב-mcar" . ($r['car_id'] ? " לגבי " . $r['car_id'] : '') . ". כאן נציג/ה ה-VIP שיצור/תיצור איתכם קשר. מתי נוח לכם לדבר?";
    ?>
    <tr>
        <td><?php echo date('d/m/y H:i', strtotime($r['created_at'])); ?></td>
        <td><strong><?php echo htmlspecialchars($r['name']); ?></strong></td>
        <td><a href="tel:<?php echo htmlspecialchars($r['phone']); ?>"><?php echo htmlspecialchars($r['phone']); ?></a></td>
        <td><?php echo htmlspecialchars($r['email'] ?: '—'); ?></td>
        <td><?php echo htmlspecialchars($r['car_id'] ?: $r['pkg_id'] ?: '—'); ?></td>
        <td><code style="font-size:12px;color:#5a6892;"><?php echo htmlspecialchars($r['source']); ?></code></td>
        <td>
            <div style="display:flex;gap:4px;align-items:center;">
                <a href="https://wa.me/<?php echo $phone_clean; ?>?text=<?php echo urlencode($wa_text); ?>" target="_blank" title="שלח WhatsApp ללקוח" style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;background:#25D366;color:#fff;border-radius:6px;text-decoration:none;flex-shrink:0;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2a9.96 9.96 0 0 0-8.6 14.94L2 22l5.22-1.36a9.96 9.96 0 0 0 4.82 1.22A9.96 9.96 0 1 0 12.04 2m0 1.67a8.28 8.28 0 0 1 8.3 8.29 8.28 8.28 0 0 1-8.3 8.29 8.24 8.24 0 0 1-4.21-1.15l-.3-.18-3.1.82.83-3.02-.2-.31a8.24 8.24 0 0 1-1.29-4.45 8.28 8.28 0 0 1 8.27-8.29"/></svg>
                </a>
                <form method="POST" style="display:inline-flex;gap:4px;align-items:center;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="lead_id" value="<?php echo $r['id']; ?>">
                    <select name="status" onchange="this.form.submit()" style="padding:5px 8px;border-radius:6px;border:1px solid rgba(0,35,102,.14);font-size:12px;">
                        <?php foreach (['new'=>'חדש','contacted'=>'נוצר קשר','qualified'=>'מתאים','closed'=>'סגור','lost'=>'אבוד'] as $k=>$v): ?>
                        <option value="<?php echo $k; ?>" <?php echo $r['status']===$k?'selected':''; ?>><?php echo $v; ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($rows)): ?>
    <tr><td colspan="7" style="text-align:center;padding:60px;color:#8891b3;">אין לידים בקטגוריה זו</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>

<?php admin_footer(); ?>
