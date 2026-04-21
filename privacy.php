<?php
$page_title = 'מדיניות פרטיות';
$page_description = 'כיצד mcar אוסף, משתמש ומגן על המידע האישי שלכם. מדיניות שקופה בתאימות ל-GDPR וחוק הגנת הפרטיות הישראלי.';
require_once 'includes/header.php';

$SECTIONS = [
    ['id' => 'intro', 'title' => '1. פתיח', 'body' => '<p>ב-mcar אנחנו מאמינים שפרטיות היא זכות, לא פיצ׳ר. המסמך הזה מסביר בפירוט מה אנחנו אוספים, למה, ומה אפשר לעשות עם המידע.</p><p>המסמך תקף לכל המשתמשים בפורטל mcar, באפליקציה, ובשירותים נלווים.</p>'],
    ['id' => 'collect', 'title' => '2. מה אנחנו אוספים', 'body' => '<p>אנחנו אוספים רק מידע חיוני לשירות:</p><ul><li><strong>פרטי קשר:</strong> שם וטלפון כשאת/ה ממלא/ת טופס יצירת קשר.</li><li><strong>מייל:</strong> אם ביקשת להירשם לניוזלטר.</li><li><strong>העדפות חיפוש:</strong> סוג רכב, תקציב, ומשך עסקה — במטרה לשפר את החיפוש.</li><li><strong>נתוני גלישה:</strong> כתובת IP, דפדפן, מערכת הפעלה — לצרכי אבטחה ואנליטיקס.</li></ul><p><strong>אנחנו לא אוספים:</strong> ת.ז., פרטי חשבון בנק, פרטי אשראי, או מידע רפואי.</p>'],
    ['id' => 'usage', 'title' => '3. איך המידע משמש אותנו', 'body' => '<p>המידע משמש אך ורק ל:</p><ul><li>התאמת הצעות מחיר ליבואן הרלוונטי (רק עם אישורך).</li><li>יצירת קשר יזום מצד נציג mcar או יבואן שותף.</li><li>שיפור השירות והאתר.</li><li>אנליטיקס מצטבר (אף פעם לא ברמת משתמש יחיד).</li></ul><p><strong>לא משמש ל:</strong> מכירת מידע לצד שלישי, רימרקטינג ברשתות חברתיות ללא אישור, או בסיס נתונים חיצוני.</p>'],
    ['id' => 'share', 'title' => '4. שיתוף עם צד שלישי', 'body' => '<p>אנחנו משתפים מידע רק:</p><ul><li><strong>עם היבואן/חברת ליסינג</strong> — רק כשאת/ה מבקש/ת הצעת מחיר ספציפית, ורק את המידע הנדרש (שם, טלפון, דגם).</li><li><strong>עם ספקי שירות טכניים</strong> — למשל אחסון ענן (AWS), ניתוח אנליטיקס (PostHog). כל ספק חתום על DPA.</li><li><strong>בצו בית משפט</strong> — רק אם קיים צו חוקי תקף.</li></ul>'],
    ['id' => 'cookies', 'title' => '5. עוגיות (Cookies)', 'body' => '<p>האתר משתמש ב-3 סוגי עוגיות:</p><ul><li><strong>הכרחיות:</strong> מצב התחברות, העדפות תצוגה (Tweaks). לא ניתנות לניטרול.</li><li><strong>אנליטיקס:</strong> PostHog — אפשר לבטל מ-DNT של הדפדפן.</li><li><strong>פרסום:</strong> אנחנו <em>לא</em> משתמשים בעוגיות פרסום צד-שלישי.</li></ul><p>ניתן לנקות עוגיות בכל עת דרך הגדרות הדפדפן.</p>'],
    ['id' => 'rights', 'title' => '6. הזכויות שלך', 'body' => '<p>לפי חוק הגנת הפרטיות הישראלי ו-GDPR:</p><ul><li><strong>עיון:</strong> זכות לדעת איזה מידע יש עליך.</li><li><strong>תיקון:</strong> זכות לבקש תיקון מידע שגוי.</li><li><strong>מחיקה:</strong> זכות לבקש מחיקה (למעט מידע שחייבים לפי חוק).</li><li><strong>ניוד:</strong> זכות לקבל את המידע בפורמט דיגיטלי נייד.</li><li><strong>התנגדות:</strong> זכות להתנגד לעיבוד מסוים.</li></ul><p>למימוש זכויות: <a href="mailto:privacy@mcar.co.il">privacy@mcar.co.il</a>. מענה תוך 14 ימי עסקים.</p>'],
    ['id' => 'security', 'title' => '7. אבטחת מידע', 'body' => '<p>הנתונים מוגנים באמצעות:</p><ul><li>הצפנת TLS 1.3 בתעבורה.</li><li>הצפנת AES-256 במצב מנוחה.</li><li>שרתים בישראל (ולא בחו״ל לנתונים אישיים).</li><li>בקרת גישה פנימית (2FA לכל העובדים).</li><li>ביקורת אבטחה שנתית חיצונית.</li></ul>'],
    ['id' => 'minors', 'title' => '8. קטינים', 'body' => '<p>השירות מיועד לגילאי 18+. איננו אוספים מידע בידיעה על קטינים. אם נודע לנו שנמסר מידע על קטין, נמחק אותו מיידית.</p>'],
    ['id' => 'changes', 'title' => '9. שינויים במדיניות', 'body' => '<p>נודיע על שינויים מהותיים דרך האתר ו/או במייל למשתמשים רשומים לפחות 30 יום מראש.</p><p>עדכון אחרון: 20 באפריל 2026.</p>'],
    ['id' => 'contact', 'title' => '10. יצירת קשר', 'body' => '<p><strong>ממונה הגנת פרטיות (DPO):</strong> מיכל ברק<br><strong>מייל:</strong> <a href="mailto:privacy@mcar.co.il">privacy@mcar.co.il</a><br><strong>כתובת:</strong> דרך מנחם בגין 132, תל אביב<br><strong>טלפון:</strong> *4260</p>'],
];
?>

<main class="page-enter container legal-page" style="padding: 40px 0 80px;">
    <section class="page-hero">
        <div class="eyebrow" style="margin-bottom: 16px;">משפטי · GDPR + חוק הגנת הפרטיות</div>
        <h1>מדיניות <span class="grad">פרטיות</span>.</h1>
        <p>שקיפות מלאה על איך המידע שלך נאסף, נשמר, ומשמש. קוראים את הכל — לא רק את הקווים האדומים.</p>
    </section>

    <div class="legal-layout">
        <aside class="legal-toc">
            <div class="legal-toc-head"><?php echo icon('shield', 14); ?> תוכן</div>
            <?php foreach ($SECTIONS as $s): ?>
            <a href="#<?php echo $s['id']; ?>" class="legal-toc-link"><?php echo $s['title']; ?></a>
            <?php endforeach; ?>
        </aside>

        <article class="legal-body">
            <?php foreach ($SECTIONS as $s): ?>
            <section id="<?php echo $s['id']; ?>" class="legal-sec">
                <h2><?php echo $s['title']; ?></h2>
                <?php echo $s['body']; ?>
            </section>
            <?php endforeach; ?>
        </article>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
