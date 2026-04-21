<?php
$page_title = 'בלוג';
$page_description = 'תובנות, מדריכים וניתוחים על שוק הליסינג הישראלי. מאת צוות mcar.';
require_once 'includes/header.php';

$POSTS = [
    [
        'slug' => 'tco-guide',
        'category' => 'מדריך',
        'title' => 'מחשבון TCO · איך לחשב את עלות הבעלות האמיתית של רכב',
        'excerpt' => 'רוב הלקוחות מסתכלים רק על התשלום החודשי. במדריך הזה נסביר למה זה טעות יקרה, ומה ה-5 עלויות הנסתרות שחייבים לכלול בחישוב.',
        'author' => 'דנה פרידמן',
        'date' => '2026-04-15',
        'read' => '8 דק׳',
        'featured' => true,
    ],
    [
        'slug' => 'electric-2026',
        'category' => 'ניתוח',
        'title' => 'הטווח האמיתי של EV בישראל · דיווחי יצרן מול נתוני שטח',
        'excerpt' => 'ניתוח של 12,000 נסיעות על 8 דגמים חשמליים. הפער הממוצע: 14%. מתי הוא קטן ומתי הוא חזק.',
        'author' => 'רועי לב',
        'date' => '2026-04-08',
        'read' => '12 דק׳',
        'featured' => false,
    ],
    [
        'slug' => 'operational-2026',
        'category' => 'עסקי',
        'title' => 'ליסינג תפעולי ב-2026 · מה השתנה בתקנות המע״מ',
        'excerpt' => 'עדכון התקנות של רשות המיסים מינואר 2026 משנה את התמונה לעצמאים. מי מרוויח, מי מפסיד.',
        'author' => 'אבירם כהן',
        'date' => '2026-03-28',
        'read' => '6 דק׳',
        'featured' => false,
    ],
    [
        'slug' => 'family-suv',
        'category' => 'השוואה',
        'title' => 'SUV משפחתי לליסינג · 6 דגמים בטבלה אחת',
        'excerpt' => 'השוואה שקופה של 6 SUV משפחתיים ב-2026 — מבחינת מחיר חודשי, אחסון, בטיחות ומרחב אחורי.',
        'author' => 'מיכל ברק',
        'date' => '2026-03-20',
        'read' => '10 דק׳',
        'featured' => false,
    ],
    [
        'slug' => 'early-exit',
        'category' => 'מדריך',
        'title' => 'יציאה מוקדמת מליסינג · כמה באמת עולה?',
        'excerpt' => 'הפרקטיקה בשטח, ההסכמים מול החברות, והמהלכים שיכולים לחסוך לכם אלפי שקלים בהפסקה מוקדמת.',
        'author' => 'דנה פרידמן',
        'date' => '2026-03-12',
        'read' => '9 דק׳',
        'featured' => false,
    ],
    [
        'slug' => 'ev-charging',
        'category' => 'טכנולוגיה',
        'title' => 'עמדת טעינה ביתית · האם באמת צריך 11kW?',
        'excerpt' => 'כמה אמפר אתה באמת צריך? השוואה של זמני טעינה ריאליים בין 3.6kW, 7.4kW ו-11kW.',
        'author' => 'רועי לב',
        'date' => '2026-03-04',
        'read' => '7 דק׳',
        'featured' => false,
    ],
];

$CATS = ['הכל', 'מדריך', 'ניתוח', 'השוואה', 'עסקי', 'טכנולוגיה'];

function fmt_date_he($iso) {
    $months = [1=>'ינואר',2=>'פברואר',3=>'מרץ',4=>'אפריל',5=>'מאי',6=>'יוני',7=>'יולי',8=>'אוגוסט',9=>'ספטמבר',10=>'אוקטובר',11=>'נובמבר',12=>'דצמבר'];
    $t = strtotime($iso);
    return date('j', $t) . ' ב' . $months[(int)date('n', $t)] . ' ' . date('Y', $t);
}

$featured = array_filter($POSTS, fn($p) => !empty($p['featured']));
$featured = array_values($featured)[0] ?? null;
$others = array_filter($POSTS, fn($p) => empty($p['featured']));
?>

<main class="page-enter container" style="padding: 40px 0 80px;">
    <!-- HERO -->
    <section class="page-hero">
        <div class="eyebrow" style="margin-bottom: 16px;">בלוג mcar</div>
        <h1>ניתוחים, מדריכים, <span class="grad">ואמת על שוק הרכב</span>.</h1>
        <p>אנחנו לא מוכרים רכבים — אנחנו עוזרים לבחור. הבלוג מכיל מה שאנחנו לומדים תוך כדי: ניתוחי נתונים, עומק בתקנות, וטיפים מעשיים.</p>
    </section>

    <!-- CATEGORY PILLS -->
    <div class="blog-cats">
        <?php foreach ($CATS as $c): ?>
        <button type="button" class="pill<?php echo $c === 'הכל' ? ' on' : ''; ?>"><?php echo $c; ?></button>
        <?php endforeach; ?>
    </div>

    <?php if ($featured): ?>
    <!-- FEATURED POST -->
    <article class="blog-featured">
        <div class="blog-featured-meta">
            <span class="blog-cat"><?php echo icon('sparkle', 11); ?> מאמר המערכת · <?php echo $featured['category']; ?></span>
        </div>
        <h2><?php echo $featured['title']; ?></h2>
        <p class="blog-excerpt"><?php echo $featured['excerpt']; ?></p>
        <div class="blog-foot">
            <div class="blog-author">
                <div class="blog-avatar"><?php echo mb_substr($featured['author'], 0, 1, 'UTF-8'); ?></div>
                <div>
                    <div class="blog-name"><?php echo $featured['author']; ?></div>
                    <div class="blog-sub"><?php echo fmt_date_he($featured['date']); ?> · <?php echo $featured['read']; ?> קריאה</div>
                </div>
            </div>
            <a href="#" class="btn btn-primary">קרא את המאמר <?php echo icon('arrow', 14); ?></a>
        </div>
    </article>
    <?php endif; ?>

    <!-- POSTS GRID -->
    <div class="blog-grid">
        <?php foreach ($others as $p): ?>
        <article class="blog-card">
            <div class="blog-card-top">
                <span class="blog-cat-sm"><?php echo $p['category']; ?></span>
                <h3><?php echo $p['title']; ?></h3>
                <p><?php echo $p['excerpt']; ?></p>
            </div>
            <div class="blog-card-foot">
                <div class="blog-author">
                    <div class="blog-avatar sm"><?php echo mb_substr($p['author'], 0, 1, 'UTF-8'); ?></div>
                    <div class="blog-sub"><?php echo $p['author']; ?> · <?php echo $p['read']; ?></div>
                </div>
                <span class="blog-date"><?php echo fmt_date_he($p['date']); ?></span>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- NEWSLETTER -->
    <section style="padding: 60px 0 20px;">
        <div class="info-cta">
            <div>
                <h3>ניוזלטר חודשי · 1,800 מנויים</h3>
                <p>מאמר אחד מקיף בחודש. בלי ספאם, בלי מכירות. רק מה שאנחנו עצמנו היינו רוצים לקרוא.</p>
            </div>
            <form action="#" method="POST" style="display: flex; gap: 10px; min-width: 320px;">
                <input type="email" name="email" placeholder="your@email.com" required class="input-field" style="flex: 1;">
                <button type="submit" class="btn btn-primary">הירשם</button>
            </form>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
