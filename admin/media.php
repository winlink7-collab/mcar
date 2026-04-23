<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('מדיה'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$message = null;

// ---- DELETE ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check() && !empty($_POST['delete_id'])) {
    $stmt = $pdo->prepare("SELECT path FROM media WHERE id = :id");
    $stmt->execute([':id' => (int)$_POST['delete_id']]);
    $row = $stmt->fetch();
    if ($row && file_exists($row['path']) && strpos($row['path'], '/uploads/') !== false) {
        @unlink($row['path']);
    }
    $pdo->prepare("DELETE FROM media WHERE id = :id")->execute([':id' => (int)$_POST['delete_id']]);
    header('Location: media.php?deleted=1');
    exit;
}

// ---- UPLOAD ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check() && !empty($_FILES['file']['tmp_name'])) {
    $file = $_FILES['file'];
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!isset($allowed[$file['type']])) {
        $message = 'סוג קובץ לא נתמך (JPG/PNG/WebP/GIF בלבד).';
    } elseif ($file['size'] > 10 * 1024 * 1024) {
        $message = 'הקובץ גדול מ-10MB.';
    } else {
        $year = date('Y'); $month = date('m');
        $dir = __DIR__ . "/../uploads/{$year}/{$month}";
        if (!is_dir($dir)) @mkdir($dir, 0755, true);

        $ext = $allowed[$file['type']];
        $base = preg_replace('/[^a-z0-9-]/i', '-', pathinfo($file['name'], PATHINFO_FILENAME));
        $base = substr(strtolower($base), 0, 40) ?: 'file';
        $name = $base . '-' . substr(md5(uniqid('', true)), 0, 6) . '.' . $ext;
        $target = "{$dir}/{$name}";
        $relPath = "uploads/{$year}/{$month}/{$name}";

        if (move_uploaded_file($file['tmp_name'], $target)) {
            // Get dimensions
            $width = 0; $height = 0;
            if (function_exists('getimagesize')) {
                $info = @getimagesize($target);
                if ($info) { $width = $info[0]; $height = $info[1]; }
            }

            // Try WebP conversion (if not already WebP and GD available)
            if ($ext !== 'webp' && function_exists('imagewebp')) {
                $webpName = preg_replace('/\.(jpg|png|gif)$/', '.webp', $name);
                $webpTarget = "{$dir}/{$webpName}";
                $img = null;
                if ($ext === 'jpg') $img = @imagecreatefromjpeg($target);
                if ($ext === 'png') $img = @imagecreatefrompng($target);
                if ($ext === 'gif') $img = @imagecreatefromgif($target);
                if ($img && imagewebp($img, $webpTarget, 82)) {
                    imagedestroy($img);
                    // Use WebP as the served file (smaller, modern)
                    $name = $webpName;
                    $target = $webpTarget;
                    $relPath = "uploads/{$year}/{$month}/{$webpName}";
                    $ext = 'webp';
                }
            }

            $url = '/' . $relPath;
            media_save($name, $file['name'], 'image/' . $ext, filesize($target), $width, $height, $target, $url, '', $_SESSION['admin_user']);
            header('Location: media.php?uploaded=1');
            exit;
        } else {
            $message = 'העלאה נכשלה. בדוק הרשאות תיקיית uploads/.';
        }
    }
}

if (!empty($_GET['uploaded'])) $message = 'הועלה בהצלחה ✓';
if (!empty($_GET['deleted'])) $message = 'נמחק ✓';

$items = media_all(100);
admin_header('מדיה');
?>
<h1>מדיה <span style="color:#5a6892;font-size:16px;font-weight:500">(<?php echo count($items); ?>)</span></h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 14px; font-size: 16px;">📤 העלה תמונה</h2>
    <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px; align-items: center;">
        <?php echo csrf_field(); ?>
        <input type="file" name="file" accept="image/*" required style="flex: 1; padding: 8px; border: 1.5px dashed rgba(0,35,102,.2); border-radius: 8px;">
        <button type="submit" class="btn btn-primary">העלה</button>
    </form>
    <p style="color:#5a6892;font-size:12px;margin:10px 0 0;">JPG/PNG/WebP/GIF · עד 10MB · ממיר אוטומטית ל-WebP אם GD זמין</p>
</div>

<?php if (empty($items)): ?>
<div class="card" style="text-align:center;padding:60px;color:#8891b3;">אין מדיה עדיין.</div>
<?php else: ?>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
    <?php foreach ($items as $m): ?>
    <div style="background: #fff; border: 1px solid rgba(0,35,102,.08); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(10,23,64,.04);">
        <div style="aspect-ratio: 4/3; background: #f4f6fb; overflow: hidden;">
            <img src="<?php echo htmlspecialchars($m['url']); ?>" alt="<?php echo htmlspecialchars($m['alt_text']); ?>" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; display: block;">
        </div>
        <div style="padding: 12px;">
            <div style="font-size: 12px; color: #5a6892; margin-bottom: 4px;"><?php echo $m['width']; ?>×<?php echo $m['height']; ?> · <?php echo round($m['size_bytes']/1024); ?>KB</div>
            <input type="text" value="<?php echo htmlspecialchars($m['url']); ?>" readonly onclick="this.select()" style="width: 100%; padding: 6px 8px; border: 1px solid rgba(0,35,102,.14); border-radius: 6px; font-family: JetBrains Mono; font-size: 11px;">
            <form method="POST" style="margin-top: 8px;" onsubmit="return confirm('למחוק?');">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="delete_id" value="<?php echo $m['id']; ?>">
                <button type="submit" style="font-size: 11px; color: #b91c1c; background: none; border: none; cursor: pointer;">🗑 מחק</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php admin_footer(); ?>
