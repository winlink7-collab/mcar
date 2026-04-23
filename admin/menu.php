<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('תפריט'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_all') {
        $items = $_POST['items'] ?? [];
        $pdo->beginTransaction();
        try {
            foreach ($items as $id => $data) {
                if (!empty($data['delete'])) {
                    $pdo->prepare("DELETE FROM menu_items WHERE id = :id")->execute([':id' => (int)$id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE menu_items SET label=:l, url=:u, sort=:s, target=:t, active=:a WHERE id=:id");
                    $stmt->execute([
                        ':l' => trim($data['label']),
                        ':u' => trim($data['url']),
                        ':s' => (int)$data['sort'],
                        ':t' => $data['target'] === '_blank' ? '_blank' : '_self',
                        ':a' => !empty($data['active']) ? 1 : 0,
                        ':id' => (int)$id,
                    ]);
                }
            }
            $pdo->commit();
            $message = 'נשמר ✓';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $message = 'שגיאה: ' . $e->getMessage();
        }
    }
    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO menu_items (location, label, url, sort) VALUES (:loc, :l, :u, :s)");
        $stmt->execute([
            ':loc' => $_POST['location'],
            ':l'   => trim($_POST['label']),
            ':u'   => trim($_POST['url']),
            ':s'   => (int)($_POST['sort'] ?? 99),
        ]);
        $message = 'פריט נוסף ✓';
    }
    if ($action === 'save_social') {
        $socials = $_POST['social'] ?? [];
        foreach ($socials as $id => $data) {
            $pdo->prepare("UPDATE social_links SET url=:u, active=:a WHERE id=:id")->execute([
                ':u'  => trim($data['url']),
                ':a'  => !empty($data['active']) ? 1 : 0,
                ':id' => (int)$id,
            ]);
        }
        $message = 'רשתות חברתיות נשמרו ✓';
    }
}

$locations = [
    'header'            => '☰ תפריט ראשי (header)',
    'footer_company'    => '🏢 פוטר · חברה',
    'footer_support'    => '💬 פוטר · תמיכה',
];

admin_header('תפריט ופוטר');
?>
<h1>תפריט ופוטר</h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<form method="POST">
<?php echo csrf_field(); ?>
<input type="hidden" name="action" value="save_all">

<?php foreach ($locations as $loc => $label):
    $items = menu_items($loc);
    // re-fetch to bypass cache after save
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE location = :l ORDER BY sort ASC, id ASC");
    $stmt->execute([':l' => $loc]);
    $items = $stmt->fetchAll();
?>
<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 16px; font-size: 16px;"><?php echo $label; ?></h2>
    <table>
        <thead><tr><th style="width:60px">סדר</th><th>תווית</th><th>URL</th><th style="width:100px">חלון</th><th style="width:60px">פעיל</th><th style="width:60px">מחק</th></tr></thead>
        <tbody>
        <?php foreach ($items as $it): ?>
        <tr>
            <td><input type="number" name="items[<?php echo $it['id']; ?>][sort]" value="<?php echo (int)$it['sort']; ?>" style="width:50px;padding:5px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></td>
            <td><input type="text" name="items[<?php echo $it['id']; ?>][label]" value="<?php echo htmlspecialchars($it['label']); ?>" style="width:100%;padding:5px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></td>
            <td><input type="text" name="items[<?php echo $it['id']; ?>][url]" value="<?php echo htmlspecialchars($it['url']); ?>" style="width:100%;padding:5px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;font-size:12px;"></td>
            <td><select name="items[<?php echo $it['id']; ?>][target]" style="padding:5px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-size:12px;"><option value="_self" <?php echo $it['target']==='_self'?'selected':''; ?>>אותו טאב</option><option value="_blank" <?php echo $it['target']==='_blank'?'selected':''; ?>>טאב חדש</option></select></td>
            <td style="text-align:center"><input type="checkbox" name="items[<?php echo $it['id']; ?>][active]" value="1" <?php echo $it['active']?'checked':''; ?>></td>
            <td style="text-align:center"><input type="checkbox" name="items[<?php echo $it['id']; ?>][delete]" value="1"></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endforeach; ?>

<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">שמור את כל השינויים</button>
</form>

<!-- ADD NEW ITEM -->
<div class="card" style="margin-bottom: 20px; background: #f9fafe;">
    <h2 style="margin: 0 0 14px; font-size: 16px;">➕ הוסף פריט חדש</h2>
    <form method="POST" style="display: grid; grid-template-columns: 1.5fr 2fr 2fr 80px auto; gap: 10px; align-items: end;">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="add">
        <div><label style="font-size:12px;color:#5a6892;">מיקום</label>
        <select name="location" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:8px;">
            <?php foreach ($locations as $k => $v): ?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php endforeach; ?>
        </select></div>
        <div><label style="font-size:12px;color:#5a6892;">תווית</label><input type="text" name="label" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:8px;"></div>
        <div><label style="font-size:12px;color:#5a6892;">URL</label><input type="text" name="url" required placeholder="about.php או https://..." style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:8px;font-family:JetBrains Mono;font-size:12px;"></div>
        <div><label style="font-size:12px;color:#5a6892;">סדר</label><input type="number" name="sort" value="99" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:8px;"></div>
        <button type="submit" class="btn btn-primary">+ הוסף</button>
    </form>
</div>

<!-- SOCIAL LINKS -->
<form method="POST">
<?php echo csrf_field(); ?>
<input type="hidden" name="action" value="save_social">
<div class="card">
    <h2 style="margin: 0 0 16px; font-size: 16px;">🔗 רשתות חברתיות (פוטר)</h2>
    <?php foreach ($pdo->query("SELECT * FROM social_links ORDER BY sort")->fetchAll() as $sl): ?>
    <div style="display: grid; grid-template-columns: 120px 1fr 80px; gap: 10px; align-items: center; margin-bottom: 10px;">
        <strong><?php echo htmlspecialchars($sl['platform']); ?></strong>
        <input type="text" name="social[<?php echo $sl['id']; ?>][url]" value="<?php echo htmlspecialchars($sl['url']); ?>" placeholder="https://facebook.com/..." style="padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:8px;font-family:JetBrains Mono;font-size:12px;">
        <label style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px;"><input type="checkbox" name="social[<?php echo $sl['id']; ?>][active]" value="1" <?php echo $sl['active']?'checked':''; ?>> פעיל</label>
    </div>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">שמור רשתות</button>
</div>
</form>

<?php admin_footer(); ?>
