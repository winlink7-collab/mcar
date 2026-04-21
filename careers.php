<?php
$page_title = 'קריירה';
$page_description = 'הצטרפו לצוות mcar — חברה שבונה את עתיד הליסינג בישראל. משרות פתוחות במוצר, הנדסה, מכירות ותפעול.';
require_once 'includes/header.php';

$JOBS = [
    ['title' => 'Senior Full-Stack Engineer', 'dept' => 'הנדסה', 'location' => 'תל אביב · היברידי', 'type' => 'משרה מלאה'],
    ['title' => 'Product Manager · Growth', 'dept' => 'מוצר', 'location' => 'תל אביב · מרוחק ישראל', 'type' => 'משרה מלאה'],
    ['title' => 'Business Development · Fleet', 'dept' => 'מכירות', 'location' => 'תל אביב · שטח', 'type' => 'משרה מלאה'],
    ['title' => 'UX Designer', 'dept' => 'מוצר', 'location' => 'תל אביב · היברידי', 'type' => 'משרה מלאה'],
    ['title' => 'נציג/ת שירות VIP', 'dept' => 'שירות לקוחות', 'location' => 'תל אביב', 'type' => 'משרה מלאה'],
    ['title' => 'אנליסט/ית נתונים', 'dept' => 'דאטה', 'location' => 'תל אביב · היברידי', 'type' => 'משרה מלאה'],
];

$PERKS = [
    ['icon' => 'sparkle',  'title' => 'Equity לכולם',      'body' => 'כל עובד/ת מקבל/ת אופציות ב-mcar מהיום הראשון. הצלחה משותפת, לא סיסמה.'],
    ['icon' => 'leaf',     'title' => '4 ימי עבודה גמישים', 'body' => 'עובדים מהמשרד בת״א יומיים בשבוע, שאר הימים מהבית או מאיפה שנוח לכם.'],
    ['icon' => 'shield',   'title' => 'בריאות ורווחה',     'body' => 'ביטוח בריאות פרטי מלא, קרן השתלמות מהיום הראשון, תקציב תזונה ובריאות אישי.'],
    ['icon' => 'bolt',     'title' => 'מעבדת לימוד',        'body' => 'תקציב ₪8,000 לשנה לכנסים, קורסים וספרים — כולל ימים בשכר ללמידה.'],
    ['icon' => 'users',    'title' => 'צוות קטן ואיכותי',   'body' => '94 עובדים. כל שני שבועות שישי אחר הצוות יושב יחד. כל אחד/ת מכיר/ה את כולם.'],
    ['icon' => 'calendar', 'title' => 'חופשה בלתי מוגבלת',  'body' => 'מדיניות חופשה ללא תקרה, עם מינימום של 18 ימים בשנה לפי חוק.'],
];
?>

<main class="page-enter container" style="padding: 40px 0 80px;">
    <!-- HERO -->
    <section class="page-hero">
        <div class="eyebrow" style="margin-bottom: 16px;">קריירה ב-mcar</div>
        <h1>בנו את עתיד הענף <span class="grad">יחד איתנו</span>.</h1>
        <p>אנחנו מחפשים אנשים שמאמינים ששוק הרכב בישראל צריך לעבוד אחרת. אם אתה/את חושב/ת כמונו — יש לנו מקום לך בצוות.</p>
        <div class="page-hero-stats">
            <div><strong>94</strong><span>עובדים</span></div>
            <div><strong>6</strong><span>משרות פתוחות</span></div>
            <div><strong>4.8/5</strong><span>Glassdoor</span></div>
            <div><strong>87%</strong><span>retention שנתי</span></div>
        </div>
    </section>

    <!-- PERKS -->
    <section style="padding: 60px 0;">
        <div class="sec-head" style="flex-direction: column; text-align: center; justify-content: center;">
            <h2>למה לעבוד פה?</h2>
            <p>רשימה כנה של מה שאנחנו מציעים — בלי סיסמאות של אתרי קריירה.</p>
        </div>
        <div class="perks-grid">
            <?php foreach ($PERKS as $p): ?>
            <div class="perk">
                <div class="perk-icon"><?php echo icon($p['icon'], 22); ?></div>
                <h4><?php echo $p['title']; ?></h4>
                <p><?php echo $p['body']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- OPEN POSITIONS -->
    <section style="padding: 60px 0; border-top: 1px solid var(--hairline);">
        <div class="sec-head">
            <div>
                <div class="eyebrow" style="margin-bottom: 14px;">משרות פתוחות</div>
                <h2><?php echo count($JOBS); ?> פוזיציות · <span class="grad">כולן פתוחות עכשיו</span></h2>
            </div>
            <p>שלחו קו״ח ל-<a href="mailto:jobs@mcar.co.il" style="color: var(--accent); font-weight: 700;">jobs@mcar.co.il</a> עם שם המשרה בנושא. נציג/ת HR חוזר/ת תוך 72 שעות.</p>
        </div>
        <div class="jobs-list">
            <?php foreach ($JOBS as $j): ?>
            <a href="mailto:jobs@mcar.co.il?subject=<?php echo urlencode($j['title']); ?>" class="job-row">
                <div class="job-main">
                    <div class="job-dept"><?php echo $j['dept']; ?></div>
                    <h4><?php echo $j['title']; ?></h4>
                </div>
                <div class="job-meta">
                    <span><?php echo icon('users', 14); ?> <?php echo $j['location']; ?></span>
                    <span><?php echo icon('clock', 14); ?> <?php echo $j['type']; ?></span>
                </div>
                <div class="job-arrow"><?php echo icon('chev', 18); ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- CTA -->
    <section style="padding: 40px 0;">
        <div class="info-cta">
            <div>
                <h3>לא רואה את המשרה שלך?</h3>
                <p>שלח/י לנו CV גם ככה. צוות HR סוקר כל פנייה ואם יש התאמה, אנחנו חוזרים.</p>
            </div>
            <a href="mailto:jobs@mcar.co.il" class="btn btn-primary btn-lg"><?php echo icon('mail', 16); ?> שלח/י CV</a>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
