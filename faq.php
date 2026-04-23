<?php
$cms_slug = 'faq';
$page_title = 'שאלות נפוצות';
$page_description = 'תשובות לשאלות הנפוצות על ליסינג פרטי, תפעולי, רכבים חשמליים, חוזים, תשלום ותחזוקה.';
require_once 'includes/cms.php';
require_once 'includes/header.php';

// Try to load FAQ from DB; fall back to hardcoded if DB missing.
$DB_FAQ_GROUPS = faq_groups_all();
if (!empty($DB_FAQ_GROUPS)) {
    $FAQ_GROUPS = [];
    foreach ($DB_FAQ_GROUPS as $g) {
        $items = [];
        foreach (faq_items_by_group($g['id']) as $it) {
            $items[] = ['q' => $it['question'], 'a' => $it['answer']];
        }
        $FAQ_GROUPS[] = [
            'id'    => $g['id'],
            'icon'  => $g['icon'] ?: 'sparkle',
            'title' => $g['label'],
            'sub'   => $g['sub'] ?: '',
            'items' => $items,
        ];
    }
}

if (empty($FAQ_GROUPS)) $FAQ_GROUPS = [
    [
        'id' => 'general',
        'icon' => 'sparkle',
        'title' => 'כללי',
        'sub' => 'מה זה mcar ואיך זה עובד',
        'items' => [
            ['q' => 'מה זה בעצם mcar?', 'a' => 'mcar הוא פורטל השוואה <strong>עצמאי ובלתי תלוי</strong> לליסינג ורכישת רכב בישראל. אנחנו לא יבואנים, לא סוכני מכירות ולא חברת ליסינג — אנחנו מציגים הצעות מאומתות מ-40+ יבואנים ומאפשרים לכם להשוות אותן בזמן אמת.'],
            ['q' => 'האם השירות של mcar בתשלום?', 'a' => 'לא. השירות <strong>חינמי לחלוטין</strong> לכם, הלקוחות. אנחנו מקבלים עמלת תיווך מהיבואן רק אחרי שעסקה נסגרת דרכנו — אבל זה לא משפיע על המחיר שמוצג לכם.'],
            ['q' => 'מה ההבדל בין ליסינג פרטי לתפעולי?', 'a' => '<strong>ליסינג פרטי</strong> מיועד לאנשים פרטיים ללא פטור מע״מ, עם חופש גבוה בבחירת דגם ואביזרים.<br><strong>ליסינג תפעולי</strong> מיועד לעצמאים וחברות, מאפשר החזר מע״מ והוצאה מוכרת לצרכי מס (עד 17% חיסכון).'],
            ['q' => 'אם אני מזמין דרך האתר — mcar חותמת על החוזה?', 'a' => 'לא. החוזה תמיד נחתם ישירות בין הלקוח ליבואן/חברת הליסינג. mcar היא הגשר שחוסך זמן ומראה אפשרויות, אבל אנחנו לא צד בעסקה.'],
        ],
    ],
    [
        'id' => 'operational',
        'icon' => 'shield',
        'title' => 'ליסינג תפעולי',
        'sub' => 'לעצמאים, חברות וסטארטאפים',
        'items' => [
            ['q' => 'מי זכאי לליסינג תפעולי?', 'a' => 'כל עצמאי, חברה, שותפות, עמותה או סטארטאפ רשומים ברשויות המס. אין מינימום ותק — גם עצמאי חדש יכול לקבל חבילה אחרי אישור אשראי.'],
            ['q' => 'מה היתרון הגדול ביותר של ליסינג תפעולי?', 'a' => '<strong>פטור ממע״מ מלא</strong> + הוצאה מוכרת מלאה. ההבדל ממוצע הוא <strong>17% חיסכון</strong> חודשי מול ליסינג פרטי באותו רכב. פלוס Cash Flow יציב — תשלום אחד שכולל הכל.'],
            ['q' => 'האם יש מינימום לכמות רכבים בצי?', 'a' => 'לא. מ-1 רכב (עצמאי) ועד 300+ (חברה גדולה) — אותו תהליך ואותה מחויבות ברמת שירות.'],
            ['q' => 'איך מתבצע הניהול של צי רכבים?', 'a' => 'dashboard דיגיטלי עם מעקב חי על כל רכב: ק״מ, דלק/טעינה, תחזוקה קרובה, דו״חות תנועה לכל נהג. PDF אוטומטי למנה״ח בכל תחילת חודש.'],
            ['q' => 'מה קורה אם צריך להחליף דגם באמצע חוזה?', 'a' => 'ב-mcar Business ההחלפה היא <strong>ללא עמלה</strong> — עסק משתנה, הצי צריך להתאים. זה אחד הדברים המרכזיים שמייחדים אותנו ממעבדות קלאסיות.'],
        ],
    ],
    [
        'id' => 'ev',
        'icon' => 'bolt',
        'title' => 'רכבים חשמליים',
        'sub' => 'EV-Ready, טעינה והטבות',
        'items' => [
            ['q' => 'האם עמדת טעינה ביתית כלולה?', 'a' => 'בחבילת Electric+ ובחבילת Smart (חשמלי) — <strong>כן, התקנה חינם</strong>. בחבילות אחרות זו תוספת של ₪3,000–4,000 חד-פעמית.'],
            ['q' => 'כמה קילומטרים אמיתיים אני אקבל מטעינה?', 'a' => 'תלוי בדגם. על בסיס 12,000 נסיעות שבדקנו ב-2026, הפער הממוצע בין הכרזת היצרן למציאות הוא <strong>14%</strong>. אנחנו מציגים לכם את הטווח האמיתי בזמן ההשוואה.'],
            ['q' => 'מה ההטבות המיסויות לרכב חשמלי ב-2026?', 'a' => 'מס קנייה מופחת (10% במקום 83%), פטור מאגרת רישוי לשנתיים ראשונות, ו-0% מס הכנסה על שימוש אישי בחברה (נכון ל-01/2026). העדכון האחרון משנה כמה חישובים — לבדוק ספציפית לפי דגם.'],
            ['q' => 'מה קורה אם יש תקלה בסוללה?', 'a' => 'אחריות יצרן מורחבת על הסוללה — בדרך כלל 8 שנים / 160,000 ק״מ. בנוסף, ב-mcar יש הסדר עם היבואן לרכב חלופי תוך 4 שעות אם הסוללה מושבתת.'],
        ],
    ],
    [
        'id' => 'contract',
        'icon' => 'calendar',
        'title' => 'חוזה ותשלום',
        'sub' => 'תנאים, משך, ויציאה מוקדמת',
        'items' => [
            ['q' => 'האם יש התחייבות תקופתית?', 'a' => 'החוזים שלנו <strong>גמישים מ-24 עד 60 חודשים</strong>. ניתן לסיים מוקדם בקנס מוגדר מראש, או להחליף רכב באמצע התקופה בהתאם למסלול.'],
            ['q' => 'מה כולל התשלום החודשי?', 'a' => '<strong>הכל בתשלום אחד:</strong> ביטוח מקיף + חובה, אחריות יצרן מלאה, טסט שנתי, טיפולים תקופתיים, אגרת רישוי, ושירותי דרך 24/7. דלק/חשמל וקנסות על הלקוח.'],
            ['q' => 'יציאה מוקדמת מהחוזה — כמה באמת עולה?', 'a' => 'תלוי בסוג החוזה ובפרק הזמן שעבר. פירוט מלא — ראו המדריך שלנו <a href="blog.php">בבלוג</a>. בקצרה: 5%–8% מיתרת החוזה אצל חברות רגילות, 3%–5% ב-mcar.'],
            ['q' => 'האם אפשר לשלם מראש?', 'a' => 'כן. ניתן לשלם 3 / 6 / 12 / 24 חודשים מראש ולקבל הנחה של 2%–7% על הסכום (לפי תקופה). מוצג במחשבון ההשוואה.'],
            ['q' => 'מה קורה אם פג הביטוח או שאני צריך לחדש רישוי?', 'a' => 'כלום — הכל אצלנו. הביטוח מתחדש אוטומטית בהתראה של 30 יום, אגרת רישוי משולמת אוטומטית, וטסט מתואם דרך האפליקציה.'],
        ],
    ],
    [
        'id' => 'service',
        'icon' => 'clock',
        'title' => 'תחזוקה ושירות',
        'sub' => 'מה קורה כשמשהו נשבר',
        'items' => [
            ['q' => 'מה קורה אם יש לי תקלה?', 'a' => 'התקשרו למוקד התמיכה (*4260 או WhatsApp) ותקבלו רכב חלופי <strong>תוך 4 שעות</strong> בכל הארץ. התיקון עצמו מתבצע במוסך מורשה, על חשבוננו.'],
            ['q' => 'מי מתחזק את הרכב?', 'a' => 'מוסכים מורשים של היבואן, לפי כללי האחריות היצרן. אתם לא משלמים — אתם רק מזמינים תור דרך האפליקציה.'],
            ['q' => 'האם יש תמיכה ב-24/7?', 'a' => '<strong>כן.</strong> מוקד תמיכה אנושי פעיל 24/7 בטלפון, WhatsApp ומייל. זמני תגובה ממוצעים: WhatsApp ~2 דק׳, טלפון ~30 שנ׳.'],
            ['q' => 'מה קורה בתאונה?', 'a' => '1. תקשרו ל-101 אם יש נפגעים. 2. צלמו את הזירה ואת הרכבים. 3. התקשרו אלינו (*4260) — אנחנו שולחים גורר, מזמינים רכב חלופי, ומטפלים בדיווח לביטוח. אתם חוזרים הביתה.'],
        ],
    ],
];

// Flatten for schema.org JSON-LD
$all_items = [];
foreach ($FAQ_GROUPS as $g) {
    foreach ($g['items'] as $item) $all_items[] = $item;
}
?>

<main class="page-enter container" style="padding: 40px 0 80px;">
    <!-- HERO -->
    <section class="page-hero">
        <div class="eyebrow" style="margin-bottom: 16px;">מרכז התמיכה · <?php echo count($all_items); ?> שאלות</div>
        <h1>כל מה שרציתם לדעת<br>על <span class="grad">ליסינג בישראל</span>.</h1>
        <p>תשובות ישירות, בלי סוגריים של עורך דין. לא מצאתם את מה שחיפשתם? צוות ה-VIP שלנו חוזר אליכם תוך 60 שניות.</p>
    </section>

    <div class="faq-layout">
        <!-- Sidebar TOC -->
        <aside class="faq-toc">
            <div class="faq-toc-head"><?php echo icon('menu', 14); ?> קטגוריות</div>
            <?php foreach ($FAQ_GROUPS as $g): ?>
            <a href="#faq-<?php echo $g['id']; ?>" class="faq-toc-link">
                <span class="faq-toc-icon"><?php echo icon($g['icon'], 16); ?></span>
                <span>
                    <strong><?php echo $g['title']; ?></strong>
                    <span class="count"><?php echo count($g['items']); ?></span>
                </span>
            </a>
            <?php endforeach; ?>
        </aside>

        <!-- Content -->
        <div class="faq-content">
            <?php foreach ($FAQ_GROUPS as $g): ?>
            <section id="faq-<?php echo $g['id']; ?>" class="faq-group">
                <div class="faq-group-head">
                    <div class="faq-group-icon"><?php echo icon($g['icon'], 22); ?></div>
                    <div>
                        <h2><?php echo $g['title']; ?></h2>
                        <p><?php echo $g['sub']; ?></p>
                    </div>
                </div>

                <div class="faq-list">
                    <?php foreach ($g['items'] as $i => $item): ?>
                    <details class="faq-item">
                        <summary>
                            <span class="faq-q"><?php echo $item['q']; ?></span>
                            <span class="faq-plus" aria-hidden="true">+</span>
                        </summary>
                        <div class="faq-a">
                            <?php echo $item['a']; ?>
                        </div>
                    </details>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endforeach; ?>

            <!-- Still stuck CTA -->
            <div class="faq-cta">
                <div>
                    <div class="eyebrow" style="color: rgba(255,255,255,.7); margin-bottom: 8px;">לא מצאתם תשובה?</div>
                    <h3>נציג VIP יענה לכם<br>תוך 60 שניות.</h3>
                    <p>WhatsApp · טלפון · מייל — מה שנוח לכם. בלי בוטים.</p>
                </div>
                <div class="faq-cta-actions">
                    <a href="contact.php" class="btn btn-primary btn-lg" data-offer-modal data-offer-source="faq-cta">
                        <?php echo icon('sparkle', 16); ?> קבל הצעת VIP
                    </a>
                    <a href="https://wa.me/972524260426" class="btn btn-ghost btn-lg">
                        <span style="color: #25D366;"><?php echo icon('whatsapp', 16); ?></span> WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Schema.org FAQPage for SEO -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php
    $schema_items = [];
    foreach ($all_items as $item) {
        $q = json_encode($item['q'], JSON_UNESCAPED_UNICODE);
        $a = json_encode(strip_tags($item['a']), JSON_UNESCAPED_UNICODE);
        $schema_items[] = '{"@type":"Question","name":'.$q.',"acceptedAnswer":{"@type":"Answer","text":'.$a.'}}';
    }
    echo implode(',', $schema_items);
    ?>
  ]
}
</script>

<?php require_once 'includes/footer.php'; ?>
