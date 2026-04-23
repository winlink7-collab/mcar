<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('חבילות'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $a = $_POST['action'] ?? '';

    if ($a === 'update') {
        foreach (($_POST['pkg'] ?? []) as $id => $d) {
            if (!empty($d['delete'])) {
                $pdo->prepare("DELETE FROM packages WHERE id = :id")->execute([':id' => $id]);
            } else {
                $features = array_values(array_filter(array_map('trim', explode("\n", $d['features']))));
                $pdo->prepare("UPDATE packages SET title=:t, sub=:s, price=:pr, pitch=:p, features=:f, km=:k, fuel=:fu, featured=:ft, sort=:so, active=:ac WHERE id=:id")
                    ->execute([
                        ':t' => trim($d['title']),
                        ':s' => trim($d['sub']),
                        ':pr' => (int)$d['price'],
                        ':p' => trim($d['pitch']),
                        ':f' => json_encode($features, JSON_UNESCAPED_UNICODE),
                        ':k' => trim($d['km']),
                        ':fu' => trim($d['fuel']),
                        ':ft' => !empty($d['featured']) ? 1 : 0,
                        ':so' => (int)$d['sort'],
                        ':ac' => !empty($d['active']) ? 1 : 0,
                        ':id' => $id,
                    ]);
            }
        }
        $message = 'נשמר ✓';
    }

    if ($a === 'add') {
        $newId = preg_replace('/[^a-z0-9-]/', '-', strtolower(trim($_POST['id'])));
        if ($newId) {
            $features = array_values(array_filter(array_map('trim', explode("\n", $_POST['features']))));
            $pdo->prepare("INSERT INTO packages (id, title, sub, icon, price, pitch, features, km, fuel) VALUES (:i, :t, :s, :ic, :pr, :p, :f, :k, :fu)")
                ->execute([
                    ':i' => $newId, ':t' => trim($_POST['title']), ':s' => trim($_POST['sub']),
                    ':ic' => $_POST['icon'] ?: 'sparkle',
                    ':pr' => (int)$_POST['price'], ':p' => trim($_POST['pitch']),
                    ':f' => json_encode($features, JSON_UNESCAPED_UNICODE),
                    ':k' => trim($_POST['km']), ':fu' => trim($_POST['fuel']),
                ]);
            $message = 'נוסף ✓';
        }
    }
}

$pkgs = $pdo->query("SELECT * FROM packages ORDER BY sort, id")->fetchAll();

admin_header('חבילות');
?>
<h1>חבילות ליסינג <span style="color:#5a6892;font-size:16px;font-weight:500">(<?php echo count($pkgs); ?>)</span></h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<form method="POST">
<?php echo csrf_field(); ?>
<input type="hidden" name="action" value="update">

<?php foreach ($pkgs as $p):
    $features = json_decode($p['features'] ?? '[]', true);
?>
<div class="card" style="margin-bottom:16px;">
    <div style="display:grid;grid-template-columns:1fr 1fr 100px 100px 60px 60px;gap:10px;margin-bottom:12px;">
        <div><label style="font-size:11px;color:#5a6892;">שם (Title)</label>
            <input type="text" name="pkg[<?php echo $p['id']; ?>][title]" value="<?php echo htmlspecialchars($p['title']); ?>" style="width:100%;padding:7px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-weight:700;"></div>
        <div><label style="font-size:11px;color:#5a6892;">תיאור משנה (Sub)</label>
            <input type="text" name="pkg[<?php echo $p['id']; ?>][sub]" value="<?php echo htmlspecialchars($p['sub']); ?>" style="width:100%;padding:7px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></div>
        <div><label style="font-size:11px;color:#5a6892;">מחיר ₪/חודש</label>
            <input type="number" name="pkg[<?php echo $p['id']; ?>][price]" value="<?php echo $p['price']; ?>" style="width:100%;padding:7px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></div>
        <div><label style="font-size:11px;color:#5a6892;">סדר</label>
            <input type="number" name="pkg[<?php echo $p['id']; ?>][sort]" value="<?php echo $p['sort']; ?>" style="width:100%;padding:7px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></div>
        <div><label style="font-size:11px;color:#5a6892;">מומלץ</label>
            <div style="padding:8px;text-align:center;"><input type="checkbox" name="pkg[<?php echo $p['id']; ?>][featured]" value="1" <?php echo $p['featured']?'checked':''; ?>></div></div>
        <div><label style="font-size:11px;color:#5a6892;">פעיל</label>
            <div style="padding:8px;text-align:center;"><input type="checkbox" name="pkg[<?php echo $p['id']; ?>][active]" value="1" <?php echo $p['active']?'checked':''; ?>></div></div>
    </div>
    <div style="margin-bottom:10px;">
        <label style="font-size:11px;color:#5a6892;">תיאור (Pitch)</label>
        <textarea name="pkg[<?php echo $p['id']; ?>][pitch]" rows="2" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-size:13px;"><?php echo htmlspecialchars($p['pitch']); ?></textarea>
    </div>
    <div style="display:grid;grid-template-columns:1fr 200px 200px 80px;gap:10px;margin-bottom:10px;">
        <div><label style="font-size:11px;color:#5a6892;">תכונות (שורה לכל אחת)</label>
            <textarea name="pkg[<?php echo $p['id']; ?>][features]" rows="3" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-size:13px;"><?php echo htmlspecialchars(implode("\n", $features)); ?></textarea></div>
        <div><label style="font-size:11px;color:#5a6892;">ק״מ שנתי</label>
            <input type="text" name="pkg[<?php echo $p['id']; ?>][km]" value="<?php echo htmlspecialchars($p['km']); ?>" style="width:100%;padding:7px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></div>
        <div><label style="font-size:11px;color:#5a6892;">סוג דלק</label>
            <input type="text" name="pkg[<?php echo $p['id']; ?>][fuel]" value="<?php echo htmlspecialchars($p['fuel']); ?>" style="width:100%;padding:7px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></div>
        <div><label style="font-size:11px;color:#5a6892;color:#b91c1c">מחק</label>
            <div style="padding:8px;text-align:center;"><input type="checkbox" name="pkg[<?php echo $p['id']; ?>][delete]" value="1"></div></div>
    </div>
</div>
<?php endforeach; ?>

<button type="submit" class="btn btn-primary">שמור הכל</button>
</form>

<div class="card" style="margin-top: 30px; background: #f9fafe;">
    <h2 style="margin: 0 0 14px; font-size: 16px;">➕ הוסף חבילה</h2>
    <form method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="add">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 120px;gap:10px;margin-bottom:10px;">
            <input type="text" name="id" required placeholder="ID (kebab-case)" pattern="[a-z0-9-]+" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;font-family:JetBrains Mono;">
            <input type="text" name="title" required placeholder="שם החבילה" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
            <input type="text" name="sub" placeholder="תיאור משנה" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
            <input type="number" name="price" required placeholder="מחיר ₪/חודש" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
        </div>
        <div style="display:grid;grid-template-columns:1fr 200px 200px;gap:10px;margin-bottom:10px;">
            <textarea name="pitch" required placeholder="תיאור החבילה (משפט אחד)" rows="2" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;"></textarea>
            <input type="text" name="km" placeholder="ק״מ שנתי" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
            <input type="text" name="fuel" placeholder="סוג דלק" style="padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
        </div>
        <textarea name="features" rows="3" placeholder="תכונות (שורה לכל אחת)" style="width:100%;padding:9px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;margin-bottom:10px;"></textarea>
        <input type="hidden" name="icon" value="sparkle">
        <button type="submit" class="btn btn-primary">+ הוסף חבילה</button>
    </form>
</div>

<?php admin_footer(); ?>
