<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('עריכת עמוד'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

$id = (int)($_GET['id'] ?? 0);
$isNew = $id === 0;
$page = null;

if (!$isNew) {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $page = $stmt->fetch();
    if (!$page) { header('Location: pages.php'); exit; }
}

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $data = [
        ':slug'    => trim($_POST['slug'] ?? ''),
        ':eyebrow' => trim($_POST['eyebrow'] ?? '') ?: null,
        ':h1'      => trim($_POST['hero_h1'] ?? ''),
        ':lead'    => trim($_POST['hero_lead'] ?? '') ?: null,
        ':img'     => trim($_POST['hero_image'] ?? '') ?: null,
        ':content' => $_POST['content_html'] ?? null,
        ':seo_t'   => trim($_POST['seo_title'] ?? '') ?: null,
        ':seo_d'   => trim($_POST['seo_description'] ?? '') ?: null,
        ':og'      => trim($_POST['og_image'] ?? '') ?: null,
        ':active'  => !empty($_POST['active']) ? 1 : 0,
    ];

    if ($isNew) {
        if (!preg_match('/^[a-z0-9-]+$/', $data[':slug'])) {
            $message = 'Slug חייב להכיל רק אותיות קטנות, מספרים, ומקפים.';
        } else {
            $data[':type'] = 'custom';
            $stmt = $pdo->prepare("INSERT INTO pages (slug, type, eyebrow, hero_h1, hero_lead, hero_image, content_html, seo_title, seo_description, og_image, active)
                                   VALUES (:slug, :type, :eyebrow, :h1, :lead, :img, :content, :seo_t, :seo_d, :og, :active)");
            try {
                $stmt->execute($data);
                header('Location: page_edit.php?id=' . $pdo->lastInsertId() . '&saved=1');
                exit;
            } catch (PDOException $e) {
                $message = 'שגיאה: ' . $e->getMessage();
            }
        }
    } else {
        unset($data[':slug']); // can't change slug of existing page
        $data[':id'] = $id;
        $stmt = $pdo->prepare("UPDATE pages SET eyebrow=:eyebrow, hero_h1=:h1, hero_lead=:lead, hero_image=:img, content_html=:content, seo_title=:seo_t, seo_description=:seo_d, og_image=:og, active=:active WHERE id=:id");
        $stmt->execute($data);
        $message = 'נשמר ✓';
        // refresh
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $page = $stmt->fetch();
    }
}
if (!empty($_GET['saved'])) $message = 'נוצר ✓';

$isCustom = $isNew || ($page && $page['type'] === 'custom');

admin_header($isNew ? 'עמוד חדש' : 'עריכת עמוד · ' . ($page['slug']));
?>
<h1><?php echo $isNew ? '➕ עמוד חדש' : '✏️ עריכת עמוד'; ?>
    <?php if (!$isNew): ?><span style="font-family:JetBrains Mono;font-size:14px;color:#5a6892;font-weight:500;">(<?php echo htmlspecialchars($page['slug']); ?>)</span><?php endif; ?>
</h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<form method="POST">
<?php echo csrf_field(); ?>

<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 16px; font-size: 16px;">תוכן עיקרי</h2>

    <?php if ($isNew): ?>
    <div style="margin-bottom: 14px;"><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">Slug (URL)</label>
        <input type="text" name="slug" required placeholder="about-us-new" pattern="[a-z0-9-]+" style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;font-family:JetBrains Mono;">
        <small style="color:#5a6892;">אותיות קטנות, מספרים, מקפים בלבד. הדף יהיה זמין בכתובת <code>/[slug].php</code></small>
    </div>
    <?php endif; ?>

    <div style="margin-bottom: 14px;"><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">Eyebrow (תווית עליונה קטנה)</label>
        <input type="text" name="eyebrow" value="<?php echo htmlspecialchars($page['eyebrow'] ?? ''); ?>" placeholder="למשל: 'אודות mcar'" style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
    </div>

    <div style="margin-bottom: 14px;"><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">כותרת ראשית (H1)</label>
        <input type="text" name="hero_h1" value="<?php echo htmlspecialchars($page['hero_h1'] ?? ''); ?>" required style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;font-size:16px;">
    </div>

    <div style="margin-bottom: 14px;"><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">פסקה תיאור (Lead)</label>
        <textarea name="hero_lead" rows="3" style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;"><?php echo htmlspecialchars($page['hero_lead'] ?? ''); ?></textarea>
    </div>

    <div style="margin-bottom: 14px;"><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">תמונת Hero (URL או נתיב יחסי)</label>
        <input type="text" name="hero_image" value="<?php echo htmlspecialchars($page['hero_image'] ?? ''); ?>" placeholder="assets/img/photo.png או https://..." style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;font-family:JetBrains Mono;font-size:12px;">
        <small style="color:#5a6892;">העלה תמונה דרך <a href="media.php" style="color:#0f766e">המדיה</a> והעתק את ה-URL לכאן.</small>
    </div>
</div>

<?php if ($isCustom): ?>
<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 12px; font-size: 16px;">תוכן הדף (HTML)</h2>
    <p style="color:#5a6892;font-size:13px;margin:0 0 12px;">עורך WYSIWYG (Quill) — הקלד טקסט עשיר, הוסף קישורים, תמונות וכותרות. נשמר כ-HTML.</p>
    <div id="quill-editor" style="height: 400px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;"></div>
    <textarea id="content_html_field" name="content_html" style="display:none"><?php echo htmlspecialchars($page['content_html'] ?? ''); ?></textarea>
</div>
<?php endif; ?>

<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 16px; font-size: 16px;">SEO</h2>
    <div style="margin-bottom: 14px;"><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">Title (כותרת בכרטיסיה / Google)</label>
        <input type="text" name="seo_title" value="<?php echo htmlspecialchars($page['seo_title'] ?? ''); ?>" maxlength="200" style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;">
    </div>
    <div style="margin-bottom: 14px;"><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">Description (תיאור)</label>
        <textarea name="seo_description" rows="2" maxlength="500" style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;"><?php echo htmlspecialchars($page['seo_description'] ?? ''); ?></textarea>
    </div>
    <div><label style="display:block;font-weight:700;font-size:13px;margin-bottom:5px;">Open Graph Image (תמונה בשיתוף)</label>
        <input type="text" name="og_image" value="<?php echo htmlspecialchars($page['og_image'] ?? ''); ?>" placeholder="/uploads/og.jpg" style="width:100%;padding:10px 12px;border:1.5px solid rgba(0,35,102,.14);border-radius:8px;font-family:JetBrains Mono;font-size:12px;">
    </div>
</div>

<div class="card" style="margin-bottom: 20px;">
    <label style="display:inline-flex;align-items:center;gap:8px;font-weight:700;">
        <input type="checkbox" name="active" value="1" <?php echo (!isset($page['active']) || $page['active']) ? 'checked' : ''; ?>>
        עמוד פעיל (אם מבוטל — מחזיר 404)
    </label>
</div>

<div style="display: flex; gap: 10px;">
    <button type="submit" class="btn btn-primary">שמור</button>
    <a href="pages.php" class="btn">חזור</a>
</div>
</form>

<?php if ($isCustom): ?>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: { toolbar: [
        [{ header: [1,2,3,false] }],
        ['bold','italic','underline','strike'],
        [{ list:'ordered' },{ list:'bullet' }],
        ['link','blockquote'],
        [{ color: [] }, { background: [] }],
        ['clean']
    ]}
});
const initialHtml = document.getElementById('content_html_field').value;
if (initialHtml) quill.root.innerHTML = initialHtml;
const form = document.querySelector('form');
form.addEventListener('submit', () => {
    document.getElementById('content_html_field').value = quill.root.innerHTML;
});
// Force RTL
quill.root.setAttribute('dir', 'rtl');
quill.root.style.textAlign = 'right';
</script>
<?php endif; ?>

<?php admin_footer(); ?>
