<?php
$page_title = 'הצהרת נגישות';
$page_description = 'הצהרת הנגישות של mcar — בתאימות לתקן ישראלי ת״י 5568 ברמה AA ולהנחיות WCAG 2.1.';
require_once 'includes/header.php';

$FEATURES = [
    ['icon' => 'verify',   'title' => 'ניווט במקלדת',          'body' => 'כל האתר נגיש מלא במקלדת בלבד. Tab להתקדמות, Shift+Tab לאחור, Enter לאקטיבציה, Esc לסגירת חלונות.'],
    ['icon' => 'check',    'title' => 'קורא מסך',                'body' => 'כל אלמנט אינטראקטיבי מסומן ב-ARIA — כפתורים, חלונות, טבלאות השוואה ופסי התקדמות. תואם NVDA ו-VoiceOver.'],
    ['icon' => 'sparkle',  'title' => 'ניגודיות צבעים',         'body' => 'כל הטקסטים עומדים ב-WCAG AA (4.5:1). מצב Dark דרך כפתור Tweaks משפר ניגודיות למשתמשים עם רגישות לאור.'],
    ['icon' => 'users',    'title' => 'גודל טקסט מתכוונן',     'body' => 'פריסת האתר תומכת בהגדלה עד 200% ללא פגיעה בתוכן או בפונקציונליות. הטקסט דינמי ולא ייחתך.'],
    ['icon' => 'bolt',     'title' => 'תנועה מופחתת',            'body' => 'אנימציות מכבדות את ההעדפה prefers-reduced-motion של מערכת ההפעלה.'],
    ['icon' => 'leaf',     'title' => 'שפה ברורה',               'body' => 'השתדלות מתמשכת לשפה פשוטה, ללא ז׳רגון משפטי מיותר. מונחים טכניים מוסברים בלחיצה.'],
];
?>

<main class="page-enter container legal-page" style="padding: 40px 0 80px;">
    <section class="page-hero">
        <div class="eyebrow" style="margin-bottom: 16px;">נגישות · ת״י 5568 AA · WCAG 2.1</div>
        <h1>נגישות זה <span class="grad">לא פיצ׳ר</span>,<br>זה ברירת מחדל.</h1>
        <p>mcar מחויבת להנגשת השירות לכלל המשתמשים, לרבות אנשים עם מוגבלויות. המסמך הבא מסכם את רמת הנגישות של האתר ואת דרכי יצירת הקשר בנושא.</p>
    </section>

    <!-- Features -->
    <section style="padding: 40px 0 60px;">
        <div class="sec-head" style="flex-direction: column; text-align: center; justify-content: center;">
            <h2>מה כבר פועל</h2>
            <p>6 עקרונות שמיושמים באתר כרגע — לא כהצהרת כוונות, אלא כקוד שרץ.</p>
        </div>
        <div class="perks-grid">
            <?php foreach ($FEATURES as $f): ?>
            <div class="perk">
                <div class="perk-icon"><?php echo icon($f['icon'], 22); ?></div>
                <h4><?php echo $f['title']; ?></h4>
                <p><?php echo $f['body']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Commitment -->
    <section class="legal-body" style="max-width: 720px; margin: 0 auto; padding: 40px 0;">
        <section class="legal-sec">
            <h2>התחייבות לסטנדרטים</h2>
            <p>האתר פועל בהתאם לתקן ישראלי <strong>ת״י 5568</strong> ברמת AA, ולהנחיות <strong>WCAG 2.1</strong> של W3C. נבדק באופן שוטף על-ידי מומחי נגישות מוסמכים.</p>
            <p>סקירה חיצונית אחרונה בוצעה על-ידי <strong>Access Israel</strong> בתאריך 12 בפברואר 2026. דוח הסקירה זמין לבקשה ב-<a href="mailto:access@mcar.co.il">access@mcar.co.il</a>.</p>
        </section>

        <section class="legal-sec">
            <h2>טכנולוגיות נגישות נתמכות</h2>
            <ul>
                <li>קוראי מסך: NVDA (Windows), JAWS, VoiceOver (macOS/iOS), TalkBack (Android).</li>
                <li>הגדלת מסך: ZoomText, הגדלת מערכת הפעלה.</li>
                <li>ניווט קולי: Voice Control / Voice Access.</li>
                <li>כל הדפדפנים המודרניים (Chrome, Safari, Firefox, Edge) מגרסת 2022 ואילך.</li>
            </ul>
        </section>

        <section class="legal-sec">
            <h2>חלקים שעדיין לא נגישים במלואם</h2>
            <p>אנחנו עובדים על שיפור מתמיד. כרגע בעבודה:</p>
            <ul>
                <li>שיפור ניגודיות בתצוגה הכהה של דף ההשוואה (צפי: מאי 2026).</li>
                <li>תרגום התוכן לערבית (צפי: Q3 2026).</li>
            </ul>
        </section>

        <section class="legal-sec">
            <h2>דיווח על בעיית נגישות</h2>
            <p>נתקלת בבעיה? נשמח לדעת — ננסה לתקן תוך 14 ימי עסקים.</p>
            <p>
                <strong>רכז/ת נגישות:</strong> מיכל ברק<br>
                <strong>מייל:</strong> <a href="mailto:access@mcar.co.il">access@mcar.co.il</a><br>
                <strong>טלפון:</strong> *4260 (שלוחה 3)<br>
                <strong>כתובת:</strong> דרך מנחם בגין 132, תל אביב (גישה לכיסאות גלגלים, חניית נכים, עמדת קבלה מונמכת).
            </p>
        </section>

        <section class="legal-sec">
            <h2>עדכון אחרון</h2>
            <p>הצהרה זו עודכנה ב-20 באפריל 2026.</p>
        </section>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
