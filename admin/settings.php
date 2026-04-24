<?php
require_once __DIR__ . '/_bootstrap.php';
require_once __DIR__ . '/_layout.php';
require_once __DIR__ . '/../includes/cms.php';

$pdo = db();
if (!$pdo) { admin_header('הגדרות'); echo '<div class="alert alert-warn">DB לא מחובר</div>'; admin_footer(); exit; }

require_once __DIR__ . '/../includes/whatsapp.php';

$message = null;
$test_result = null;

// Test WhatsApp button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check() && !empty($_POST['test_whatsapp'])) {
    $test_result = test_whatsapp_config();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check() && empty($_POST['test_whatsapp'])) {
    $pairs = $_POST['s'] ?? [];

    // Handle any file uploads — keys named s_file[setting_key]
    if (!empty($_FILES['s_file']['tmp_name']) && is_array($_FILES['s_file']['tmp_name'])) {
        foreach ($_FILES['s_file']['tmp_name'] as $key => $tmp) {
            if (!$tmp) continue;
            $file = [
                'tmp_name' => $tmp,
                'name'     => $_FILES['s_file']['name'][$key]     ?? 'upload',
                'type'     => $_FILES['s_file']['type'][$key]     ?? '',
                'size'     => $_FILES['s_file']['size'][$key]     ?? 0,
                'error'    => $_FILES['s_file']['error'][$key]    ?? UPLOAD_ERR_NO_FILE,
            ];
            $url = handle_image_upload($file, $_SESSION['admin_user'] ?? null);
            if ($url) $pairs[$key] = $url;
        }
    }

    if (settings_save($pairs)) {
        $message = 'נשמר בהצלחה ✓';
    }
}

$rows = $pdo->query("SELECT * FROM settings ORDER BY group_name, sort, `key`")->fetchAll();
$groups = [];
foreach ($rows as $r) $groups[$r['group_name']][] = $r;

$group_labels = [
    'contact'   => '📞 פרטי תקשורת',
    'whatsapp'  => '💬 התראות WhatsApp ללידים חדשים',
    'branding'  => '🎨 מיתוג ושיתוף',
    'analytics' => '📊 אנליטיקה וסקריפטים',
    'general'   => 'כללי',
];

$group_help = [
    'whatsapp' => '<strong>מקבלים הודעה ב-WhatsApp שלכם בכל פעם שלקוח משאיר פרטים באתר.</strong>
        <br><br>
        <b>הגדרה ב-3 שלבים:</b>
        <ol style="margin:10px 0;padding-right:22px;line-height:1.8">
            <li>שמרו במכשיר את המספר <code style="background:#fef3c7;padding:2px 8px;border-radius:5px;font-weight:700;">+34 644 51 95 23</code> (זה הבוט של CallMeBot)</li>
            <li>שלחו לבוט הזה הודעה ב-WhatsApp בדיוק את הטקסט הבא: <code style="background:#fef3c7;padding:2px 8px;border-radius:5px;direction:ltr;display:inline-block">I allow callmebot to send me messages</code></li>
            <li>הבוט יענה לכם תוך 1-2 דקות עם <b>API Key</b>. העתיקו אותו ושימו בשדה "CallMeBot API Key" למטה.</li>
        </ol>
        <p style="margin:8px 0 0">
            ✅ אחרי ההגדרה — מלאו <b>מספר WhatsApp שלכם</b> בפורמט <code>+972524260426</code> (עם +972, בלי 0 בתחילת הטלפון), שימו <code>1</code> בשדה ההפעלה, ושמרו.
        </p>
        <p style="margin:8px 0 0;color:#0f766e;font-weight:600">בדקו: גלשו לאתר → "קבל הצעת VIP" → מלאו ושלחו → אמורה להגיע הודעה ב-WhatsApp שלכם תוך 5 שניות.</p>',
];

admin_header('הגדרות אתר');

// Current WhatsApp number — shown prominently at top
$current_wa         = setting('admin_whatsapp', '');
$current_wa_display = setting('contact_whatsapp_display', '');
?>
<h1>הגדרות אתר</h1>
<?php if ($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

<!-- PROMINENT: WhatsApp number for all inquiries -->
<div class="card" style="margin-bottom: 24px; border: 2px solid var(--accent); background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 60%); box-shadow: 0 4px 16px -4px rgba(15,118,110,.15);">
    <div style="display:flex;align-items:flex-start;gap:16px;margin-bottom:18px;">
        <div style="width:48px;height:48px;background:#25D366;color:#fff;border-radius:12px;display:grid;place-items:center;flex-shrink:0;font-size:24px;">💬</div>
        <div>
            <h2 style="margin:0 0 4px;font-size:18px;color:var(--ink);">מספר WhatsApp ראשי של האתר</h2>
            <p style="margin:0;font-size:13px;color:var(--ink-3);">כל פניות הלקוחות מהאתר מגיעות למספר הזה. שנה כאן כדי להעביר את הפניות למספר אחר — ייכנס לתוקף מיידית בכל האתר.</p>
        </div>
    </div>

    <form method="POST" style="margin:0;">
        <?php echo csrf_field(); ?>
        <div style="display:grid;grid-template-columns:2fr 1fr auto;gap:12px;align-items:end;">
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:var(--ink-2);margin-bottom:6px;">📱 מספר WhatsApp (פורמט בינלאומי)</label>
                <input type="tel" name="s[admin_whatsapp]" value="<?php echo htmlspecialchars($current_wa); ?>" placeholder="+972542067606" required
                       pattern="\+\d{10,15}"
                       style="font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:600;direction:ltr;text-align:left;padding:12px 14px;border:2px solid var(--border-strong);border-radius:8px;">
                <small style="font-size:11px;color:var(--ink-3);">דוגמה: 050-1234567 → הקלד <code>+972501234567</code> (עם +972, בלי 0 בתחילה)</small>
            </div>
            <div>
                <label style="display:block;font-size:13px;font-weight:600;color:var(--ink-2);margin-bottom:6px;">תצוגת מספר באתר</label>
                <input type="text" name="s[contact_whatsapp_display]" value="<?php echo htmlspecialchars($current_wa_display); ?>" placeholder="054-1234567"
                       style="font-family:'JetBrains Mono',monospace;font-size:14px;padding:12px 14px;border:2px solid var(--border-strong);border-radius:8px;">
                <small style="font-size:11px;color:var(--ink-3);">איך הטלפון נראה ללקוח (אופציונלי)</small>
            </div>
            <!-- Also mirror to contact_whatsapp for legacy -->
            <input type="hidden" name="s[contact_whatsapp]" value="<?php echo htmlspecialchars($current_wa); ?>" id="_wa_mirror">
            <button type="submit" class="btn btn-primary" style="padding:13px 22px;font-size:14px;font-weight:700;white-space:nowrap;">💾 שמור מספר</button>
        </div>
    </form>

    <script>
    // Mirror admin_whatsapp to contact_whatsapp on save
    document.querySelector('input[name="s[admin_whatsapp]"]').addEventListener('input', function() {
        document.getElementById('_wa_mirror').value = this.value;
    });
    </script>

    <div style="margin-top:16px;padding:12px 16px;background:#fef3c7;border:1px solid #fde68a;border-radius:8px;font-size:13px;color:#92400e;">
        <strong>📍 המספר הזה משמש ב:</strong>
        כל כפתורי "קבל הצעה" באתר · ה-bar הצף במובייל · דף צור קשר · FAQ · ליסינג תפעולי ·
        ההתראות אוטומטיות (אם הפעלת CallMeBot למטה).
    </div>
</div>

<form method="POST" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
<?php foreach ($groups as $gname => $items): ?>
<div class="card" style="margin-bottom: 20px;">
    <h2 style="margin: 0 0 18px; font-size: 17px;"><?php echo $group_labels[$gname] ?? $gname; ?></h2>
    <?php if (!empty($group_help[$gname])): ?>
    <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:14px 18px;border-radius:10px;font-size:13px;line-height:1.6;margin-bottom:20px;">
        <?php echo $group_help[$gname]; ?>
    </div>
    <?php endif; ?>

    <?php if ($gname === 'whatsapp'): ?>
    <div style="background:var(--bg);border:1px solid var(--border);padding:12px 16px;border-radius:10px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
        <div style="font-size:13px;color:var(--ink-2);">
            <strong>בדיקת חיבור:</strong> אחרי שהזנת את המפתח ושמרת — לחץ כדי לשלוח הודעת בדיקה לטלפון שלך.
        </div>
        <form method="POST" style="margin:0;">
            <?php echo csrf_field(); ?>
            <button type="submit" name="test_whatsapp" value="1" class="btn btn-primary" style="white-space:nowrap;">📨 שלח בדיקה</button>
        </form>
    </div>
    <?php if ($test_result !== null): ?>
        <?php if ($test_result['ok']): ?>
        <div class="alert alert-info" style="margin-bottom:20px;">✅ <?php echo htmlspecialchars($test_result['message']); ?></div>
        <?php else: ?>
        <div class="alert alert-err" style="margin-bottom:20px;">❌ <?php echo htmlspecialchars($test_result['message']); ?></div>
        <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>
    <?php foreach ($items as $s): ?>
    <div style="margin-bottom: 18px;">
        <label style="display: block; font-size: 13px; font-weight: 700; color: var(--ink-2); margin-bottom: 6px;">
            <?php echo htmlspecialchars($s['label'] ?: $s['key']); ?>
            <code style="font-size: 11px; color: var(--ink-4); font-weight: 400; margin-right: 8px;"><?php echo $s['key']; ?></code>
        </label>
        <?php if ($s['type'] === 'image'): ?>
            <div style="display:grid;grid-template-columns:160px 1fr;gap:14px;align-items:start;">
                <div>
                    <?php if (!empty($s['value'])): ?>
                        <img src="<?php echo htmlspecialchars($s['value']); ?>" style="width:100%;aspect-ratio:1.91/1;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
                    <?php else: ?>
                        <div style="width:100%;aspect-ratio:1.91/1;background:var(--bg);border:2px dashed var(--border-strong);border-radius:8px;display:grid;place-items:center;color:var(--ink-4);font-size:11px;">תמונה</div>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="file" name="s_file[<?php echo htmlspecialchars($s['key']); ?>]" accept="image/*" style="width:100%;padding:10px;border:2px dashed var(--border-strong);border-radius:8px;background:var(--bg);cursor:pointer;font-size:13px;">
                    <details style="margin-top:6px;">
                        <summary style="font-size:12px;color:var(--ink-3);cursor:pointer;">או URL ידני</summary>
                        <input type="text" name="s[<?php echo htmlspecialchars($s['key']); ?>]" value="<?php echo htmlspecialchars($s['value']); ?>" placeholder="/uploads/og.jpg" style="margin-top:6px;font-family:'JetBrains Mono',monospace;font-size:12px;">
                    </details>
                </div>
            </div>
        <?php elseif ($s['type'] === 'textarea' || $s['type'] === 'script'): ?>
            <textarea name="s[<?php echo htmlspecialchars($s['key']); ?>]" rows="4" style="font-family: <?php echo $s['type']==='script'?'JetBrains Mono, monospace':'inherit'; ?>;"><?php echo htmlspecialchars($s['value']); ?></textarea>
        <?php else: ?>
            <input type="<?php echo $s['type']==='number'?'number':'text'; ?>" name="s[<?php echo htmlspecialchars($s['key']); ?>]" value="<?php echo htmlspecialchars($s['value']); ?>">
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
