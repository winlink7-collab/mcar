<?php
require_once 'includes/header.php';

$OP_STATS = [
    ['k' => '24/7', 'l' => 'תמיכה', 'sub' => 'צוות עסקי זמין'],
    ['k' => '4', 'l' => 'שעות', 'sub' => 'לרכב חלופי'],
    ['k' => '1,240', 'l' => 'עסקים', 'sub' => 'בצי פעיל'],
    ['k' => '17%', 'l' => 'חסכון', 'sub' => 'ממוצע לעסק'],
];

$OP_BENEFITS = [
    ['num' => '01', 'icon' => 'drop',     'title' => 'פטור מע״מ מלא',      'body' => 'רכב הוא הוצאה מוכרת — אין מע״מ על הרכב עצמו, על הביטוח או על התחזוקה.',                                    'stat' => 'עד 17% פחות'],
    ['num' => '02', 'icon' => 'shield',   'title' => 'Cash Flow שפוי',       'body' => 'תשלום חודשי קבוע כולל הכל. בלי הפתעות של טיפול יקר או ביטוח שעלה באמצע השנה.',                          'stat' => '0 הפתעות'],
    ['num' => '03', 'icon' => 'clock',    'title' => 'רכב חלופי תמיד',        'body' => 'תקלה? תאונה? תחזוקה? מקבלים רכב חלופי תוך 4 שעות בכל הארץ, 365 יום בשנה.',                            'stat' => '4 שעות · 365 יום'],
    ['num' => '04', 'icon' => 'calendar', 'title' => 'דו״חות אוטומטיים',     'body' => 'PDF חודשי למנהלת החשבונות — הוצאות, ק״מ, דלק, תחזוקה ודו״חות תנועה לכל נהג.',                          'stat' => 'ניהול חכם'],
    ['num' => '05', 'icon' => 'users',    'title' => 'ניהול נהגים מרוכז',    'body' => 'dashboard חי של כל הצי: מי נוסע, במה, כמה ק״מ, ומה המצב של הרכב הבא לטיפול.',                         'stat' => 'dashboard חי'],
    ['num' => '06', 'icon' => 'leaf',     'title' => 'החלפה גמישה',           'body' => 'הצי לא מתאים? החליפו דגמים באמצע החוזה ללא עמלה — הצרכים של העסק משתנים, הליסינג צריך לעקוב אחריהם.', 'stat' => 'ללא עמלת החלפה'],
];

$OP_STEPS = [
    ['n' => 1, 'title' => 'פנייה ראשונית', 'body' => 'משאירים פרטי חברה + סוג צי מבוקש. נציג עסקי חוזר תוך 30 דקות.',                  'time' => '30 דקות'],
    ['n' => 2, 'title' => 'הצעה מותאמת',  'body' => 'מקבלים 3 חבילות חלופיות עם הצפי החודשי, ק״מ, תחזוקה וביטוח.',                 'time' => '24 שעות'],
    ['n' => 3, 'title' => 'אישור עקרוני', 'body' => 'אישור אשראי דיגיטלי מבנק שותף — דוחות לא מודפסים, הכל Online.',                'time' => '2–3 ימי עסקים'],
    ['n' => 4, 'title' => 'מסירה',          'body' => 'הרכב מגיע למשרד שלכם, שטוף, מתודלק, עם דוח מסירה דיגיטלי.',                    'time' => '7–14 ימים'],
];

// Private vs operational comparison table
$OP_COMPARE = [
    ['label' => 'מע״מ על הרכב',        'priv' => 'לא מוכר',             'op' => 'מוכר מלא + החזר',    'op_highlight' => true],
    ['label' => 'הוצאה מוכרת',           'priv' => 'לא',                    'op' => 'מלאה · עד 17%',     'op_highlight' => true],
    ['label' => 'תחזוקה וביטוח',         'priv' => 'נפרד · אתה מנהל',    'op' => 'כלול · אנחנו מנהלים', 'op_highlight' => false],
    ['label' => 'רכב חלופי בתיקון',      'priv' => 'תלוי בחוזה',            'op' => 'תמיד · 4 שעות',      'op_highlight' => true],
    ['label' => 'דו״חות לרו״ח',          'priv' => 'חסר · ידני',           'op' => 'אוטומטי · dashboard', 'op_highlight' => true],
    ['label' => 'החלפת רכב באמצע חוזה', 'priv' => 'קנס 5%–8%',            'op' => 'ללא עמלה',           'op_highlight' => true],
];

// Case studies
$OP_CASES = [
    [
        'tag'      => 'M',
        'name'     => 'Meridian Law',
        'industry' => 'משרד עריכת דין',
        'quote'    => 'אחרי 4 שנים אנחנו יודעים שהיא עובדת. Executive, Prestige, Electric — הכל מכוסה בהצעה אחת. נהגים משפחתיים — פשוט לא feasible לדרך אחרת.',
        'stats'    => [['k' => '30', 'l' => 'רכבים בצי'], ['k' => '3.2y', 'l' => 'איתנו']],
    ],
    [
        'tag'      => 'AE',
        'name'     => 'Avir Engineering',
        'industry' => 'הנדסה · 86 עובדים',
        'quote'    => 'ההעברה לליסינג תפעולי חסכה לנו מחצית מעלויות הרכב שהיו לנו קודם. ההבדל ברור מעל לוח התקציב השנתי.',
        'stats'    => [['k' => '86', 'l' => 'נהגים'], ['k' => '12h', 'l' => 'תגובה ממוצעת']],
    ],
    [
        'tag'      => 'P',
        'name'     => 'Prism Tech',
        'industry' => 'סטארטאפ · צוות R&D',
        'quote'    => 'צוות R&D של 24. החלפנו את הליסינג הקודם ב-12 חודשים — חסכנו ₪38K בשנה. נהגים מרוחקים מכל הארץ — זה פשוט עובד.',
        'stats'    => [['k' => '24', 'l' => 'רכבים'], ['k' => '₪38K', 'l' => 'חסכון שנתי']],
    ],
];
?>

<main class="page-enter" style="padding-bottom: 80px;">
    <!-- HERO -->
    <section class="op-hero">
        <div class="container op-hero-inner">
            <div class="op-hero-image">
                <img src="assets/img/fleet_lifestyle.png"
                     alt="צי עסקי mcar Business" loading="lazy" style="object-position: center;">
                <div class="op-hero-overlay-top">העסקה החמה · צי עסקי בינוני</div>
                <div class="op-hero-overlay-stats">
                    <div class="tier best"><span>חסכון שנתי</span><strong>₪12k</strong></div>
                    <div class="tier"><span>תחזוקה שנתית</span><strong>₪45k</strong></div>
                    <div class="tier"><span>פרמיית ביטוח</span><strong>₪58k</strong></div>
                </div>
            </div>
            <div class="op-hero-copy">
                <div class="eyebrow" style="margin-bottom: 16px;">ליסינג תפעולי · לעצמאים וחברות</div>
                <h1>
                    צי רכבים עסקי <span class="grad">בלי כאב ראש</span>,<br>
                    בלי הפתעות במאזן.
                </h1>
                <p>ליסינג תפעולי מלא לעצמאים, סטארטאפים ועסקים. ההחזר החודשי קבוע, הוצאה מוכרת של עד 17%, וניהול דיגיטלי שהנהלת החשבונות תאהב.</p>
                <div class="op-hero-cta">
                    <a href="contact.php?type=business" class="btn btn-primary btn-lg" data-offer-modal data-offer-source="op-hero">
                        <?php echo icon('sparkle', 16); ?> קבל הצעה עסקית
                    </a>
                    <a href="grid.php" class="btn btn-ghost btn-lg">השווה רכבים</a>
                </div>
                <div class="op-stats">
                    <?php foreach ($OP_STATS as $s): ?>
                    <div class="op-stat">
                        <div class="k"><?php echo $s['k']; ?></div>
                        <div class="l"><?php echo $s['l']; ?></div>
                        <div class="sub"><?php echo $s['sub']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- BENEFITS -->
    <section class="container op-benefits">
        <div class="sec-head" style="text-align: center; justify-content: center; flex-direction: column; margin-bottom: 44px;">
            <h2>שש סיבות למה עסקים עוברים אלינו<br>מ-<span class="grad">Leumi, Albar, Shlomo</span>.</h2>
            <p style="max-width: 56ch; margin: 14px auto 0; color: var(--ink-3);">אלה לא סיסמאות — אלה הדברים שלקוחות עסקיים שלנו מציינים שוב ושוב אחרי 12 חודשים של עבודה. מהנתונים, בלי פילטר של אגף שיווק.</p>
        </div>
        <div class="op-benefit-grid">
            <?php foreach ($OP_BENEFITS as $b): ?>
            <div class="op-benefit">
                <div class="op-benefit-top">
                    <div class="op-benefit-icon"><?php echo icon($b['icon'], 22); ?></div>
                    <div class="op-benefit-num"><?php echo $b['num']; ?></div>
                </div>
                <h4><?php echo $b['title']; ?></h4>
                <p><?php echo $b['body']; ?></p>
                <div class="op-benefit-stat"><?php echo icon('check', 12, 3); ?> <?php echo $b['stat']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- COMPARISON TABLE -->
    <section class="container op-compare-sec">
        <div class="sec-head" style="text-align: center; justify-content: center; flex-direction: column;">
            <h2>פרטי מול תפעולי.<br>מה מתאים לעסק שלכם?</h2>
            <p style="max-width: 54ch; margin: 14px auto 0; color: var(--ink-3);">בחרו לפי הטבלה. אומדן שתואם לנסיבות הספציפיות של העסק.</p>
        </div>
        <div class="op-compare-table">
            <div class="op-compare-th">מדד</div>
            <div class="op-compare-th">ליסינג פרטי</div>
            <div class="op-compare-th ours">ליסינג תפעולי</div>
            <?php foreach ($OP_COMPARE as $row): ?>
            <div class="op-compare-cell label"><?php echo $row['label']; ?></div>
            <div class="op-compare-cell priv"><?php echo $row['priv']; ?></div>
            <div class="op-compare-cell op<?php echo !empty($row['op_highlight']) ? ' hi' : ''; ?>">
                <?php if (!empty($row['op_highlight'])) echo icon('check', 14, 3); ?>
                <?php echo $row['op']; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- STEPS -->
    <section class="container op-steps-sec">
        <div class="sec-head" style="text-align: center; justify-content: center; flex-direction: column;">
            <h2>מפנייה ועד מפתחות — <span class="grad">שבועיים</span>.</h2>
            <p style="max-width: 56ch; margin: 14px auto 0; color: var(--ink-3);">תהליך דיגיטלי מלא. אין פגישות פיזיות, אין עלויות סמויות, אין יישורה של שעונים.</p>
        </div>
        <div class="op-steps">
            <?php foreach ($OP_STEPS as $s): ?>
            <div class="op-step">
                <div class="op-step-num"><?php echo $s['n']; ?></div>
                <h4><?php echo $s['title']; ?></h4>
                <p><?php echo $s['body']; ?></p>
                <div class="op-step-time"><?php echo icon('clock', 12); ?> <?php echo $s['time']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- CASE STUDIES -->
    <section class="container op-cases-sec">
        <div class="sec-head">
            <div>
                <div class="eyebrow" style="margin-bottom: 14px;">CASE STUDIES</div>
                <h2><span class="grad">1,240</span> עסקים.<br>140 ענפים.</h2>
            </div>
            <p>מרגישים באספה אותם הסיפורים. הלקוחות שלנו משתפים איתנו דברים באמת, בצוות רשותי צופים שעוקבים, על פני צי מ-3 רכבים עד 300.</p>
        </div>
        <div class="op-cases">
            <?php foreach ($OP_CASES as $c): ?>
            <div class="op-case">
                <div class="op-case-head">
                    <div class="op-case-tag"><?php echo $c['tag']; ?></div>
                    <div class="op-case-meta">
                        <div class="op-case-name"><?php echo $c['name']; ?></div>
                        <div class="op-case-industry"><?php echo $c['industry']; ?></div>
                    </div>
                </div>
                <p class="op-case-quote">״<?php echo $c['quote']; ?>״</p>
                <div class="op-case-stats">
                    <?php foreach ($c['stats'] as $st): ?>
                    <div>
                        <div class="k"><?php echo $st['k']; ?></div>
                        <div class="l"><?php echo $st['l']; ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="container">
        <div class="op-final-cta">
            <div class="op-final-copy">
                <h3>חושבים להעביר את הצי?<br>דברו עם מומחה עסקי.</h3>
                <p>נציג יחזור אליכם תוך 30 דקות עם הצעה ראשונית. נפגש ונציג ב-60 דקות הצעה מפורטת עם כל התחשיבים לעסק שלכם.</p>
            </div>
            <div class="op-final-actions">
                <a href="contact.php?type=business" class="btn btn-primary btn-lg" data-offer-modal data-offer-source="op-final">
                    <?php echo icon('sparkle', 16); ?> קבל הצעה עסקית
                </a>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', setting('contact_whatsapp', '+972524260426')); ?>" class="btn btn-ghost btn-lg">
                    <span style="color: #25D366;"><?php echo icon('whatsapp', 16); ?></span> WhatsApp עכשיו
                </a>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
