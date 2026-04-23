<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';

$pdo = db();

// Update price quickly
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check() && !empty($_POST['action'])) {
    if ($_POST['action'] === 'update_price' && !empty($_POST['id'])) {
        $monthly = [
            'private'     => (int)$_POST['private'],
            'operational' => (int)$_POST['operational'],
            'purchase'    => (int)$_POST['purchase'],
        ];
        $stmt = $pdo->prepare("UPDATE cars SET monthly = :m WHERE id = :id");
        $stmt->execute([':m' => json_encode($monthly), ':id' => $_POST['id']]);
    }
    if ($_POST['action'] === 'toggle_active' && !empty($_POST['id'])) {
        $pdo->prepare("UPDATE cars SET active = 1 - active WHERE id = :id")->execute([':id' => $_POST['id']]);
    }
    header('Location: cars.php');
    exit;
}

$rows = $pdo->query("SELECT * FROM cars ORDER BY active DESC, featured DESC, id ASC")->fetchAll();

admin_header('רכבים');
?>
<h1>רכבים <span style="color:#5a6892;font-size:16px;font-weight:500">(<?php echo count($rows); ?>)</span></h1>

<div class="alert alert-info">
    💡 <strong>טיפ:</strong> שינוי מחיר נכנס לתוקף מיידית באתר. כדי להוסיף רכב חדש או לערוך פרטים מורחבים, ערוך ישירות ב-phpMyAdmin.
</div>

<div class="card" style="padding: 0; overflow: hidden;">
<table>
    <thead><tr><th>ID</th><th>רכב</th><th>קטגוריה / מנוע</th><th>מחיר פרטי</th><th>תפעולי</th><th>רכישה</th><th>פעיל</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r):
        $monthly = json_decode($r['monthly'], true);
    ?>
    <tr>
        <td><code style="font-size:12px;"><?php echo htmlspecialchars($r['id']); ?></code></td>
        <td><strong><?php echo htmlspecialchars($r['make'] . ' ' . $r['model']); ?></strong><br>
            <small style="color:#5a6892;"><?php echo htmlspecialchars($r['trim']); ?></small></td>
        <td><?php echo htmlspecialchars($r['category']); ?> · <?php echo htmlspecialchars($r['engine']); ?></td>
        <form method="POST" style="display:contents;">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="update_price">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($r['id']); ?>">
            <td><input type="number" name="private" value="<?php echo (int)$monthly['private']; ?>" style="width:90px;padding:6px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></td>
            <td><input type="number" name="operational" value="<?php echo (int)$monthly['operational']; ?>" style="width:90px;padding:6px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></td>
            <td><input type="number" name="purchase" value="<?php echo (int)$monthly['purchase']; ?>" style="width:110px;padding:6px 8px;border:1px solid rgba(0,35,102,.14);border-radius:6px;font-family:JetBrains Mono;"></td>
            <td><span class="badge <?php echo $r['active'] ? 'badge-qualified' : 'badge-lost'; ?>"><?php echo $r['active'] ? 'פעיל' : 'מוסתר'; ?></span></td>
            <td><button type="submit" class="btn btn-primary" style="font-size:12px;padding:6px 10px;">שמור</button></td>
        </form>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php admin_footer(); ?>
