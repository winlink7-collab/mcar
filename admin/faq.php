<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('שאלות נפוצות'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $a = $_POST['action'] ?? '';
    if ($a === 'add') {
        $stmt = $pdo->prepare("INSERT INTO faq_items (group_id, question, answer, sort) VALUES (:g, :q, :a, :s)");
        $stmt->execute([':g' => $_POST['group_id'], ':q' => trim($_POST['question']), ':a' => trim($_POST['answer']), ':s' => (int)($_POST['sort'] ?? 99)]);
        $message = 'נוסף ✓';
    }
    if ($a === 'update') {
        foreach (($_POST['items'] ?? []) as $id => $d) {
            if (!empty($d['delete'])) {
                $pdo->prepare("DELETE FROM faq_items WHERE id = :id")->execute([':id' => (int)$id]);
            } else {
                $pdo->prepare("UPDATE faq_items SET question=:q, answer=:a, sort=:s, group_id=:g, active=:ac WHERE id=:id")
                    ->execute([':q' => trim($d['question']), ':a' => trim($d['answer']), ':s' => (int)$d['sort'], ':g' => $d['group_id'], ':ac' => !empty($d['active']) ? 1 : 0, ':id' => (int)$id]);
            }
        }
        $message = 'נשמר ✓';
    }
}

$groups = $pdo->query("SELECT * FROM faq_groups ORDER BY sort")->fetchAll();
$items = $pdo->query("SELECT * FROM faq_items ORDER BY group_id, sort, id")->fetchAll();
$by_group = [];
foreach ($items as $it) $by_group[$it['group_id']][] = $it;

admin_header('שאלות נפוצות');
?>
<h1>שאלות נפוצות (FAQ)</h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<form method="POST">
<?php echo csrf_field(); ?>
<input type="hidden" name="action" value="update">

<?php foreach ($groups as $g): ?>
<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 16px; font-size: 16px;"><?php echo htmlspecialchars($g['label']); ?> <span style="color:#5a6892;font-weight:500;font-size:13px;">(<?php echo count($by_group[$g['id']] ?? []); ?>)</span></h2>
    <?php foreach (($by_group[$g['id']] ?? []) as $it): ?>
    <div style="border:1px solid rgba(0,35,102,.06);border-radius:10px;padding:14px;margin-bottom:10px;background:#fafbfd;">
        <div style="display:grid;grid-template-columns:60px 1fr 60px 60px;gap:10px;align-items:start;margin-bottom:8px;">
            <input type="number" name="items[<?php echo $it['id']; ?>][sort]" value="<?php echo $it['sort']; ?>" style="padding:6px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;width:100%;">
            <input type="text" name="items[<?php echo $it['id']; ?>][question]" value="<?php echo htmlspecialchars($it['question']); ?>" required style="padding:6px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-weight:600;">
            <label style="display:flex;align-items:center;gap:4px;font-size:12px;justify-content:center;"><input type="checkbox" name="items[<?php echo $it['id']; ?>][active]" value="1" <?php echo $it['active']?'checked':''; ?>>פעיל</label>
            <label style="display:flex;align-items:center;gap:4px;font-size:12px;color:#b91c1c;justify-content:center;"><input type="checkbox" name="items[<?php echo $it['id']; ?>][delete]" value="1">מחק</label>
        </div>
        <textarea name="items[<?php echo $it['id']; ?>][answer]" rows="3" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-size:13px;line-height:1.6;"><?php echo htmlspecialchars($it['answer']); ?></textarea>
        <input type="hidden" name="items[<?php echo $it['id']; ?>][group_id]" value="<?php echo $it['group_id']; ?>">
    </div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<button type="submit" class="btn btn-primary">שמור שינויים</button>
</form>

<div class="card" style="margin-top: 30px; background: #f9fafe;">
    <h2 style="margin: 0 0 14px; font-size: 16px;">➕ הוסף שאלה</h2>
    <form method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="add">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:10px;">
            <select name="group_id" required style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
                <?php foreach ($groups as $g): ?><option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['label']); ?></option><?php endforeach; ?>
            </select>
            <input type="number" name="sort" value="99" placeholder="סדר" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
        </div>
        <input type="text" name="question" required placeholder="השאלה" style="width:100%;padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;margin-bottom:10px;">
        <textarea name="answer" required rows="3" placeholder="התשובה (HTML מותר: <strong>, <a>, <br>...)" style="width:100%;padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;margin-bottom:10px;"></textarea>
        <button type="submit" class="btn btn-primary">+ הוסף</button>
    </form>
</div>

<?php admin_footer(); ?>
