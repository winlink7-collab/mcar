<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('עמודים'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    if (!empty($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM pages WHERE id = :id AND type = 'custom'")->execute([':id' => (int)$_POST['delete_id']]);
        $message = 'עמוד נמחק ✓';
    }
}

$pages = $pdo->query("SELECT * FROM pages ORDER BY type ASC, sort ASC, id ASC")->fetchAll();

admin_header('עמודים');
?>
<h1>עמודים</h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<div style="display: flex; gap: 10px; margin-bottom: 20px;">
    <a href="page_edit.php?action=new" class="btn btn-primary">➕ עמוד חדש</a>
    <span style="color: #5a6892; font-size: 13px; align-self: center;">לעמודים קיימים — לחץ על "ערוך" כדי לשנות hero/SEO/תמונה</span>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
<table>
    <thead><tr><th>סוג</th><th>Slug / URL</th><th>כותרת</th><th>פעיל</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($pages as $p): ?>
    <tr>
        <td>
            <?php if ($p['type'] === 'builtin'): ?>
                <span class="badge badge-new">מובנה</span>
            <?php else: ?>
                <span class="badge badge-qualified">מותאם</span>
            <?php endif; ?>
        </td>
        <td><code style="font-size:13px;"><?php echo htmlspecialchars($p['slug']); ?></code></td>
        <td><strong><?php echo htmlspecialchars($p['hero_h1'] ?: '(ללא כותרת)'); ?></strong></td>
        <td><?php echo $p['active'] ? '✓' : '✗'; ?></td>
        <td>
            <a href="page_edit.php?id=<?php echo $p['id']; ?>" class="btn">ערוך</a>
            <a href="../<?php echo $p['slug']==='home' ? '' : htmlspecialchars($p['slug']).'.php'; ?>" target="_blank" class="btn">צפה ↗</a>
            <?php if ($p['type'] === 'custom'): ?>
            <form method="POST" style="display:inline" onsubmit="return confirm('למחוק לצמיתות?');">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>">
                <button type="submit" class="btn" style="color:#b91c1c">מחק</button>
            </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php admin_footer(); ?>
