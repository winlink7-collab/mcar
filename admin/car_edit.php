<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('עריכת רכב'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$id = $_GET['id'] ?? null;
$isNew = empty($id);
$car = null;

if (!$isNew) {
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $car = $stmt->fetch();
    if (!$car) { header('Location: cars.php'); exit; }
}

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    if (!empty($_POST['delete']) && !$isNew) {
        $pdo->prepare("DELETE FROM cars WHERE id = :id")->execute([':id' => $id]);
        header('Location: cars.php?deleted=1');
        exit;
    }

    // Handle inline image upload — overrides URL field if file present
    $uploaded_url = null;
    if (!empty($_FILES['image_file']['tmp_name'])) {
        $uploaded_url = handle_image_upload($_FILES['image_file'], $_SESSION['admin_user'] ?? null);
        if (!$uploaded_url) $message = 'העלאת התמונה נכשלה. בדוק שזה JPG/PNG/WebP עד 10MB.';
    }
    $final_image = $uploaded_url ?: trim($_POST['image_url'] ?? '');

    $monthly = json_encode([
        'private'     => (int)$_POST['private'],
        'operational' => (int)$_POST['operational'],
        'purchase'    => (int)$_POST['purchase'],
    ]);
    $features = array_filter(array_map('trim', explode("\n", $_POST['features'] ?? '')));
    $features_json = json_encode(array_values($features), JSON_UNESCAPED_UNICODE);

    $data = [
        ':make'        => trim($_POST['make']),
        ':model'       => trim($_POST['model']),
        ':trim'        => trim($_POST['trim']),
        ':category'    => $_POST['category'],
        ':engine'      => $_POST['engine'],
        ':hp'          => (int)$_POST['hp'],
        ':consumption' => trim($_POST['consumption']),
        ':seats'       => (int)$_POST['seats'],
        ':accel'       => trim($_POST['accel']),
        ':monthly'     => $monthly,
        ':stock'       => max(0, min(1, (float)$_POST['stock'])),
        ':best_value'  => !empty($_POST['best_value']) ? 1 : 0,
        ':verified'    => !empty($_POST['verified']) ? 1 : 0,
        ':features'    => $features_json,
        ':warranty'    => trim($_POST['warranty']),
        ':delivery'    => trim($_POST['delivery']),
        ':image_url'   => $final_image ?: null,
        ':active'      => !empty($_POST['active']) ? 1 : 0,
    ];

    if ($isNew) {
        $newId = preg_replace('/[^a-z0-9-]/', '-', strtolower(trim($_POST['id'])));
        if (!$newId) {
            $message = 'נדרש ID (kebab-case).';
        } else {
            $data[':id'] = $newId;
            $sql = "INSERT INTO cars (id, make, model, trim, category, engine, hp, consumption, seats, accel, monthly, stock, best_value, verified, features, warranty, delivery, image_url, active)
                    VALUES (:id, :make, :model, :trim, :category, :engine, :hp, :consumption, :seats, :accel, :monthly, :stock, :best_value, :verified, :features, :warranty, :delivery, :image_url, :active)";
            try {
                $pdo->prepare($sql)->execute($data);
                header('Location: car_edit.php?id=' . urlencode($newId) . '&saved=1');
                exit;
            } catch (PDOException $e) {
                $message = 'שגיאה: ' . $e->getMessage();
            }
        }
    } else {
        $data[':id'] = $id;
        $sql = "UPDATE cars SET make=:make, model=:model, trim=:trim, category=:category, engine=:engine, hp=:hp, consumption=:consumption, seats=:seats, accel=:accel, monthly=:monthly, stock=:stock, best_value=:best_value, verified=:verified, features=:features, warranty=:warranty, delivery=:delivery, image_url=:image_url, active=:active WHERE id=:id";
        $pdo->prepare($sql)->execute($data);
        $message = 'נשמר ✓';
        // Refresh
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $car = $stmt->fetch();
    }
}
if (!empty($_GET['saved'])) $message = 'נשמר ✓';

$cats = $pdo->query("SELECT * FROM categories ORDER BY id")->fetchAll();
$engines = $pdo->query("SELECT * FROM engine_types ORDER BY id")->fetchAll();
$monthly = $car ? json_decode($car['monthly'], true) : ['private' => 3000, 'operational' => 2700, 'purchase' => 200000];
$features = $car ? json_decode($car['features'] ?? '[]', true) : [];

admin_header($isNew ? 'רכב חדש' : ($car['make'] . ' ' . $car['model']));
?>
<h1><?php echo $isNew ? '➕ רכב חדש' : '✏️ עריכת רכב'; ?>
    <?php if (!$isNew): ?><span style="font-family:JetBrains Mono;color:#5a6892;font-size:14px;font-weight:500;">(<?php echo htmlspecialchars($id); ?>)</span><?php endif; ?>
</h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<div class="card">
    <h3 style="margin:0 0 14px;font-size:14px;color:#5a6892;">פרטים בסיסיים</h3>
    <?php if ($isNew): ?>
    <p style="margin-bottom:10px;"><label>ID (kebab-case)</label><input type="text" name="id" required pattern="[a-z0-9-]+" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></p>
    <?php endif; ?>
    <p><label>יצרן</label><input type="text" name="make" value="<?php echo htmlspecialchars($car['make'] ?? ''); ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label>דגם</label><input type="text" name="model" value="<?php echo htmlspecialchars($car['model'] ?? ''); ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label>גימור (Trim)</label><input type="text" name="trim" value="<?php echo htmlspecialchars($car['trim'] ?? ''); ?>" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label>קטגוריה</label>
        <select name="category" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;">
        <?php foreach ($cats as $c): ?><option value="<?php echo $c['id']; ?>" <?php echo (($car['category']??'') === $c['id'])?'selected':''; ?>><?php echo htmlspecialchars($c['label']); ?></option><?php endforeach; ?>
        </select>
    </p>
    <p><label>סוג מנוע</label>
        <select name="engine" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;">
        <?php foreach ($engines as $e): ?><option value="<?php echo $e['id']; ?>" <?php echo (($car['engine']??'') === $e['id'])?'selected':''; ?>><?php echo htmlspecialchars($e['label']); ?></option><?php endforeach; ?>
        </select>
    </p>
</div>

<div class="card">
    <h3 style="margin:0 0 14px;font-size:14px;color:#5a6892;">מפרט טכני</h3>
    <p><label>הספק (כ״ס)</label><input type="number" name="hp" value="<?php echo (int)($car['hp']??200); ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label>צריכת דלק / טווח</label><input type="text" name="consumption" value="<?php echo htmlspecialchars($car['consumption']??''); ?>" placeholder="15.2 ק״מ/ל׳ או 450 ק״מ טווח" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label>מקומות</label><input type="number" name="seats" value="<?php echo (int)($car['seats']??5); ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label>תאוצה 0-100 (שניות)</label><input type="text" name="accel" value="<?php echo htmlspecialchars($car['accel']??'7.0'); ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
</div>

<div class="card">
    <h3 style="margin:0 0 14px;font-size:14px;color:#5a6892;">תמחור (₪/חודש)</h3>
    <p><label>פרטי</label><input type="number" name="private" value="<?php echo (int)$monthly['private']; ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></p>
    <p><label>תפעולי</label><input type="number" name="operational" value="<?php echo (int)$monthly['operational']; ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></p>
    <p><label>רכישה מלאה (₪)</label><input type="number" name="purchase" value="<?php echo (int)$monthly['purchase']; ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></p>
</div>

<div class="card">
    <h3 style="margin:0 0 14px;font-size:14px;color:#5a6892;">תגים ומלאי</h3>
    <p><label>זמינות מלאי (0.00 — 1.00)</label><input type="number" step="0.01" min="0" max="1" name="stock" value="<?php echo $car['stock']??'0.5'; ?>" required style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" name="best_value" value="1" <?php echo !empty($car['best_value'])?'checked':''; ?>> 🏆 העסקה המשתלמת</label></p>
    <p><label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" name="verified" value="1" <?php echo (!isset($car['verified']) || $car['verified'])?'checked':''; ?>> ✓ מאומת</label></p>
    <p><label style="display:flex;align-items:center;gap:8px;"><input type="checkbox" name="active" value="1" <?php echo (!isset($car['active']) || $car['active'])?'checked':''; ?>> פעיל באתר</label></p>
    <p><label>אחריות</label><input type="text" name="warranty" value="<?php echo htmlspecialchars($car['warranty']??''); ?>" placeholder="3 שנים / 100,000 ק״מ" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
    <p><label>זמן מסירה</label><input type="text" name="delivery" value="<?php echo htmlspecialchars($car['delivery']??''); ?>" placeholder="זמין מיידית או עד 21 ימים" style="width:100%;padding:8px 10px;border:1px solid rgba(0,35,102,.14);border-radius:6px;"></p>
</div>

<div class="card" style="grid-column:1/-1;">
    <h3 style="margin:0 0 14px;font-size:14px;color:#5a6892;">תכונות (Features)</h3>
    <p style="color:#5a6892;font-size:12px;margin:0 0 8px;">תכונה אחת לכל שורה. למשל: 7 מושבים</p>
    <textarea name="features" rows="6" style="width:100%;padding:10px 12px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-size:14px;"><?php echo htmlspecialchars(implode("\n", $features)); ?></textarea>
</div>

<div class="card" style="grid-column:1/-1;">
    <h3 style="margin:0 0 14px;font-size:14px;color:#5a6892;">🖼️ תמונת הרכב</h3>

    <div style="display:grid;grid-template-columns:200px 1fr;gap:20px;align-items:start;">
        <div>
            <?php if (!empty($car['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($car['image_url']); ?>" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
            <?php else: ?>
                <div style="width:100%;aspect-ratio:4/3;background:var(--bg);border:2px dashed var(--border-strong);border-radius:8px;display:grid;place-items:center;color:var(--ink-4);font-size:13px;">ללא תמונה</div>
            <?php endif; ?>
        </div>

        <div>
            <label style="font-size:13px;color:var(--ink-2);font-weight:600;margin-bottom:6px;">העלה תמונה חדשה</label>
            <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif"
                   style="width:100%;padding:10px;border:2px dashed var(--border-strong);border-radius:8px;background:var(--bg);cursor:pointer;font-size:13px;">
            <p style="font-size:12px;color:var(--ink-3);margin:6px 0 16px;">JPG / PNG / WebP / GIF · עד 10MB · ממיר אוטומטית ל-WebP</p>

            <details>
                <summary style="font-size:12px;color:var(--ink-3);cursor:pointer;padding:6px 0;">או הזן URL ידנית</summary>
                <input type="text" name="image_url" value="<?php echo htmlspecialchars($car['image_url']??''); ?>"
                       placeholder="/uploads/2026/04/nova-prime.webp"
                       style="margin-top:8px;font-family:'JetBrains Mono',monospace;font-size:12px;">
            </details>
        </div>
    </div>
</div>

</div>

<div style="display: flex; gap: 10px; margin-top: 20px;">
    <button type="submit" class="btn btn-primary">שמור</button>
    <a href="cars.php" class="btn">חזור</a>
    <?php if (!$isNew): ?>
    <button type="submit" name="delete" value="1" class="btn" style="color:#b91c1c;margin-right:auto" onclick="return confirm('למחוק לצמיתות את ' + '<?php echo htmlspecialchars($car['make'].' '.$car['model']); ?>'+'?');">🗑 מחק רכב</button>
    <?php endif; ?>
</div>
</form>

<style>label{display:block;font-size:12px;font-weight:700;color:#5a6892;margin-bottom:4px}</style>

<?php admin_footer(); ?>
