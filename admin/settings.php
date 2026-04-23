<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('הגדרות'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $pairs = $_POST['s'] ?? [];
    if (settings_save($pairs)) {
        $message = 'נשמר בהצלחה ✓';
    }
}

$rows = $pdo->query("SELECT * FROM settings ORDER BY group_name, sort, `key`")->fetchAll();
$groups = [];
foreach ($rows as $r) $groups[$r['group_name']][] = $r;

$group_labels = [
    'contact'   => '📞 פרטי תקשורת',
    'branding'  => '🎨 מיתוג ושיתוף',
    'analytics' => '📊 אנליטיקה וסקריפטים',
    'general'   => 'כללי',
];

admin_header('הגדרות אתר');
?>
<h1>הגדרות אתר</h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<form method="POST">
<?php echo csrf_field(); ?>
<?php foreach ($groups as $gname => $items): ?>
<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 18px; font-size: 17px;"><?php echo $group_labels[$gname] ?? $gname; ?></h2>
    <?php foreach ($items as $s): ?>
    <div style="margin-bottom: 18px;">
        <label style="display: block; font-size: 13px; font-weight: 700; color: #5a6892; margin-bottom: 6px;">
            <?php echo htmlspecialchars($s['label'] ?: $s['key']); ?>
            <code style="font-size: 11px; color: #8891b3; font-weight: 400; margin-right: 8px;"><?php echo $s['key']; ?></code>
        </label>
        <?php if ($s['type'] === 'textarea' || $s['type'] === 'script'): ?>
            <textarea name="s[<?php echo htmlspecialchars($s['key']); ?>]" rows="4" style="width: 100%; padding: 10px 12px; border: 1.5px solid rgba(0,35,102,.14); border-radius: 8px; font-family: <?php echo $s['type']==='script'?'JetBrains Mono':'inherit'; ?>; font-size: 13px;"><?php echo htmlspecialchars($s['value']); ?></textarea>
        <?php else: ?>
            <input type="<?php echo $s['type']==='number'?'number':'text'; ?>" name="s[<?php echo htmlspecialchars($s['key']); ?>]" value="<?php echo htmlspecialchars($s['value']); ?>" style="width: 100%; padding: 10px 12px; border: 1.5px solid rgba(0,35,102,.14); border-radius: 8px; font-size: 14px;">
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<div style="display: flex; gap: 10px; margin-top: 20px;">
    <button type="submit" class="btn btn-primary">שמור הכל</button>
    <a href="settings.php" class="btn">בטל</a>
</div>
</form>

<?php admin_footer(); ?>
