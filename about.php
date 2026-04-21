<?php
require_once 'includes/header.php';

$TIMELINE = [
    ['year' => '2021', 'title' => 'mcar נולדת', 'body' => 'שלושה יוצאי ענף הרכב והפינטק מייסדים את mcar אחרי שחוו בעצמם את חוסר השקיפות בענף הליסינג הישראלי.', 'chip' => '3 מייסדים · Tel Aviv'],
    ['year' => '2022', 'title' => 'הפלטפורמה עולה לאוויר', 'body' => 'גרסה ציבורית ראשונה עם 8 יבואנים שותפים ומחשבון TCO שקוף. בתוך 11 חודשים: יותר מ־6,000 עסקאות נסגרו.', 'chip' => 'סבב Seed · ₪14M'],
    ['year' => '2023', 'title' => 'הרחבה לתפעולי ולעסקים', 'body' => 'השקת מוצר mcar Business — ליסינג תפעולי אוטומטי לעצמאים ולחברות צמיחה. שותפויות עם 4 בנקים.', 'chip' => 'Series A · ₪68M'],
    ['year' => '2024', 'title' => 'עידן החשמלי', 'body' => 'מוצר EV-Ready: חבילת ליסינג הכוללת התקנת עמדת טעינה ביתית, חוזה חשמל מוזל, ותיאום זמינות טעינה.', 'chip' => '40+ יבואנים'],
    ['year' => '2026', 'title' => 'הפלטפורמה המובילה בישראל', 'body' => 'לראשונה מאז הקמת הענף, פורטל השוואה עצמאי עוקף את הדילרים המקוונים.', 'chip' => 'היום · 94 עובדים']
];

$TEAM = [
    ['name' => 'אבירם כהן', 'role' => 'מייסד שותף ומנכ״ל', 'bio' => '15 שנות ניסיון כמנהל ליסינג בבנקים מובילים. בוגר MBA, INSEAD.'],
    ['name' => 'דנה פרידמן', 'role' => 'מייסדת שותפה וסמנכ״לית מוצר', 'bio' => 'לשעבר ראש צוות מוצר ב־Payoneer. מומחית לחוויית משתמש.'],
    ['name' => 'רועי לב', 'role' => 'מייסד שותף ו־CTO', 'bio' => 'בנה שלוש מערכות חיתום בקנה מידה גדול. חובב רכבים היברידיים.'],
    ['name' => 'מיכל ברק', 'role' => 'סמנכ״לית פעילות', 'bio' => 'אחראית על רשת השותפויות עם היבואנים והבנקים.']
];
?>

<main class="page-enter container" style="padding: 40px 0 80px;">
    <!-- HERO -->
    <section class="about-hero">
        <div class="about-hero-copy">
            <div class="eyebrow" style="margin-bottom: 16px;">אודות mcar</div>
            <h1>בנינו את הפלטפורמה שהיינו רוצים <span class="grad">ללקוחות שלנו</span>.</h1>
            <p>mcar הוא פורטל ההשוואה הבלתי תלוי הגדול בישראל לליסינג ורכישת רכב. לא מוכרים רכבים — עוזרים לבחור. אין אינטרס בעסקה מסוימת, יש אינטרס בעסקה הנכונה עבורך.</p>
        </div>
        <div class="about-hero-image">
            <img src="assets/img/interior_lifestyle.png"
                 alt="mcar luxury interior lifestyle"
                 loading="lazy">
            <div class="about-hero-badge">
                <div class="about-hero-badge-eyebrow">קצה הטכנולוגיה. חוויית הנהיגה.</div>
                <div class="about-hero-badge-title">תל אביב · מודל עסקי חדש</div>
            </div>
        </div>
    </section>

    <!-- STORY -->
    <section style="padding: 80px 0; border-top: 1px solid var(--hairline);">
        <div style="display: grid; grid-template-columns: 280px 1fr; gap: 64px;">
            <aside>
                <div class="eyebrow">הסיפור שלנו</div>
                <h2 style="font-size: 36px; font-weight: 900; line-height: 1.05;">איך התחיל הכל. בחמש שנים, שינינו את ענף הליסינג הישראלי.</h2>
            </aside>
            <div style="font-size: 17px; line-height: 1.7; color: var(--ink-2);">
                <p><strong>ב־2020 ניסה אחד מהמייסדים לקבל הצעה לליסינג משפחתי.</strong> הוא קיבל 6 הצעות מ־6 יבואנים, כל אחת עם מבנה תמחור שונה, תנאים שונים, ואחריות שונה. כדי להבין אם הצעה אחת משתלמת יותר מהשנייה הוא נדרש לפתוח גיליון Excel עם 28 שורות.</p>
                <p>זה היה הרגע. אם לאדם מקצועי מהענף — לקח שלושה שבועות להשוות, מה אמור לעשות הצרכן הממוצע? חצי שנה אחר כך נולדה mcar.</p>
                <div style="margin: 32px 0; padding: 28px 32px; border-right: 3px solid var(--accent); background: var(--bg-2); font-size: 26px; font-weight: 700; border-radius: 0 var(--r-md) var(--r-md) 0;">
                    ״אנחנו לא מתחרים ביבואנים. אנחנו מתחרים באי־הוודאות שיש לצרכן מולם.״
                </div>
            </div>
        </div>
    </section>

    <!-- TIMELINE -->
    <section style="padding: 60px 0 80px;">
        <div class="eyebrow" style="margin-bottom: 14px;">ציר זמן</div>
        <h2 style="font-size: 36px; font-weight: 900; margin-bottom: 40px;">מחברת בחדר ישיבות למובילה בישראל.</h2>
        <div style="position: relative; padding-right: 24px;">
            <div style="position: absolute; top: 10px; bottom: 10px; right: 7px; width: 2px; background: linear-gradient(to bottom, var(--accent), var(--accent-2), transparent);"></div>
            <?php foreach ($TIMELINE as $t): ?>
            <div style="position: relative; padding-right: 36px; padding-bottom: 36px;">
                <div style="position: absolute; right: 0; top: 6px; width: 16px; height: 16px; border-radius: 50%; background: var(--surface); border: 3px solid var(--accent); box-shadow: 0 0 0 5px var(--accent-soft);"></div>
                <div style="font-family: var(--font-mono); font-size: 15px; font-weight: 700; color: var(--accent); letter-spacing: .08em;"><?php echo $t['year']; ?></div>
                <h4 style="font-size: 22px; font-weight: 800; margin: 6px 0 8px;"><?php echo $t['title']; ?></h4>
                <p style="color: var(--ink-3); line-height: 1.6; font-size: 15px; max-width: 60ch;"><?php echo $t['body']; ?></p>
                <span class="chip" style="margin-top: 12px;"><?php echo icon('sparkle', 12); ?> <?php echo $t['chip']; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- TEAM -->
    <section style="padding: 80px 0 60px; border-top: 1px solid var(--hairline);">
        <div class="eyebrow" style="margin-bottom: 14px;">ההנהלה</div>
        <h2 style="font-size: 36px; font-weight: 900; margin-bottom: 40px;">הצוות שבנה את mcar.</h2>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
            <?php foreach ($TEAM as $m): ?>
            <div style="background: var(--surface); border: 1px solid var(--surface-border); border-radius: var(--r-xl); overflow: hidden; padding: 20px;">
                <div style="aspect-ratio: 1/1; background: linear-gradient(135deg, var(--accent), var(--accent-2)); border-radius: 50%; width: 60px; height: 60px; display: grid; place-items: center; color: white; font-weight: 900; font-size: 24px; margin-bottom: 16px;">
                    <?php echo mb_substr($m['name'], 0, 1, 'UTF-8'); ?>
                </div>
                <div style="font-weight: 800; font-size: 17px;"><?php echo $m['name']; ?></div>
                <div style="font-size: 15px; color: var(--accent); font-weight: 600; margin-top: 2px;"><?php echo $m['role']; ?></div>
                <p style="font-size: 15px; color: var(--ink-3); margin-top: 10px; line-height: 1.55;"><?php echo $m['bio']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
