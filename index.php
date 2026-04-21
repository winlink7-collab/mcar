<?php
require_once 'includes/header.php';

// Find a featured car (Nova Prime 7)
$featured_car = null;
foreach ($CARS as $car) {
    if ($car['id'] === 'nova-prime') {
        $featured_car = $car;
        break;
    }
}
if (!$featured_car) $featured_car = $CARS[0];

$priv_price = $featured_car['monthly']['private'];
$op_price = $featured_car['monthly']['operational'];
$savings = $priv_price - $op_price;
$cat_info = $CATEGORIES[$featured_car['category']];
?>

<main class="page-enter">
    <!-- HERO -->
    <section class="hero container" style="position: relative;">
        <!-- Premium Ambient Background -->
        <div style="position: absolute; inset: -150px -100px -100px -100px; z-index: -1; pointer-events: none; opacity: 0.85;">
            <img src="assets/img/bg_abstract.png" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.15; filter: blur(30px) saturate(200%); mask-image: linear-gradient(to bottom, black 40%, transparent 100%); -webkit-mask-image: linear-gradient(to bottom, black 40%, transparent 100%);">
        </div>

        <div class="hero-top-split">
            <div class="hero-copy">
                <div class="hero-eyebrow-row">
                    <div class="hero-eyebrow">
                        <span class="tag" style="padding: 4px 10px; border-radius: 999px; background: var(--accent-soft); color: var(--accent); font-weight: 700; font-size: 13px; margin-left: 10px;">2026</span>
                        מחשבון ליסינג דור חדש · עדכון ינואר
                    </div>
                </div>

                <h1>העתיד של ענף הליסינג<br>
                    <span class="grad" style="background: linear-gradient(135deg, var(--accent) 0%, var(--accent-2) 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">בישראל</span> — בטבלה אחת.
                </h1>

                <p class="hero-sub" style="margin-top: 22px; font-size: 20px; color: var(--ink-3); line-height: 1.55; max-width: 58ch;">
                    מנוע ההשוואה המתקדם ביותר לרכב הבא שלך. פרטי, תפעולי ורכישה — עסקאות מאומתות
                    ממעל 40 יבואנים, בזמן אמת, ללא עמלות נסתרות.
                </p>
            </div>

            <!-- Featured Deal Card -->
            <div class="deal-card" role="region" aria-label="עסקה נבחרת">
                <div class="deal-ribbon">
                    <span class="pulse"></span>
                    העסקה החמה השבוע
                </div>
                <div class="deal-stock">
                    <span class="pulse"></span>
                    <?php echo $featured_car['delivery']; ?>
                </div>

                <div class="deal-art">
                    <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800" alt="<?php echo $featured_car['make'] . ' ' . $featured_car['model']; ?>" style="width: 100%; height: auto; border-radius: var(--r-xl); transform: scale(1.1) translateX(5%); filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));">
                </div>

                <div class="deal-body">
                    <div class="deal-title-row">
                        <div>
                            <div class="deal-title"><?php echo $featured_car['make'] . ' ' . $featured_car['model']; ?></div>
                            <div class="deal-sub"><?php echo $featured_car['trim'] . ' · ' . $cat_info['label']; ?></div>
                        </div>
                        <?php echo render_engine_chip($featured_car['engine']); ?>
                    </div>

                    <div class="deal-specs">
                        <div class="spec">
                            <div class="k"><?php echo $featured_car['hp']; ?><span class="u"> כ״ס</span></div>
                            <div class="l">הספק</div>
                        </div>
                        <div class="spec">
                            <div class="k"><?php echo $featured_car['accel']; ?><span class="u">s</span></div>
                            <div class="l">0 — 100</div>
                        </div>
                        <div class="spec">
                            <div class="k"><?php echo $featured_car['seats']; ?></div>
                            <div class="l">מקומות</div>
                        </div>
                    </div>

                    <div class="deal-availability">
                        <div class="bar"><div style="width: <?php echo (int)($featured_car['stock'] * 100); ?>%;"></div></div>
                        <div class="row">
                            <span class="lbl">זמינות מלאי</span>
                            <span class="val"><?php echo (int)($featured_car['stock'] * 100); ?>% · <?php echo $featured_car['delivery']; ?></span>
                        </div>
                    </div>

                    <div class="deal-price-row">
                        <div class="deal-price-now">
                            <div class="l">החל מ־ / חודש · פרטי</div>
                            <div class="k"><span class="sym">₪</span><?php echo number_format($priv_price); ?><span class="per">/ חו׳</span></div>
                        </div>
                        <?php if ($savings > 0): ?>
                        <div class="deal-save">
                            <div class="k">חסכון <?php echo format_ils($savings); ?></div>
                            <div class="l">בתפעולי לעסק</div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="deal-cta-row single">
                        <a href="contact.php?car=<?php echo $featured_car['id']; ?>" class="btn btn-primary" data-offer-modal data-offer-source="hero-deal">
                            <?php echo icon('sparkle', 14); ?>
                            קבל הצעה מותאמת
                        </a>
                    </div>
                </div>

                <div class="deal-footer-note">
                    <span class="left"><?php echo icon('verify', 12); ?> מחיר מאומת · <?php echo count($PARTNERS); ?> יבואנים</span>
                    <span class="right">עודכן לפני 4 דק׳</span>
                </div>
            </div>
        </div>

        <!-- SEARCH -->
        <form action="grid.php" method="GET" class="search-shell">
            <div class="search-tabs">
                <div class="seg" role="tablist">
                    <button type="button" data-active="true">ליסינג פרטי</button>
                    <button type="button" data-active="false">ליסינג תפעולי</button>
                    <button type="button" data-active="false">רכישה מלאה</button>
                </div>
            </div>
            <div class="search-grid">
                <div class="search-cell">
                    <label for="search-cat">סוג רכב</label>
                    <select id="search-cat" name="cat">
                        <option value="all">כל הסוגים</option>
                        <?php foreach ($CATEGORIES as $id => $cat): ?>
                        <option value="<?php echo $id; ?>"><?php echo $cat['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="search-cell">
                    <label for="search-budget">תקציב חודשי</label>
                    <select id="search-budget" name="budget">
                        <option value="all">ללא הגבלת תקציב</option>
                        <option value="2500">עד ₪2,500</option>
                        <option value="3000">עד ₪3,000</option>
                        <option value="3500">עד ₪3,500</option>
                        <option value="4000">עד ₪4,000</option>
                    </select>
                </div>
                <div class="search-cell">
                    <label for="search-term">משך עסקה</label>
                    <select id="search-term" name="term">
                        <option value="36">36 חודשים</option>
                        <option value="48">48 חודשים</option>
                        <option value="60">60 חודשים</option>
                    </select>
                </div>
                <div class="search-cell">
                    <label for="search-engine">מנוע</label>
                    <select id="search-engine" name="engine">
                        <option value="all">כל המנועים</option>
                        <?php foreach ($ENGINE_TYPES as $id => $eng): ?>
                        <option value="<?php echo $id; ?>"><?php echo $eng['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="search-submit">
                    <?php echo icon('search', 18, 2); ?>
                    <span>השווה עכשיו</span>
                </button>
            </div>
        </form>

        <div class="trust-row">
            <span><?php echo icon('verify', 16); ?> עסקאות מאומתות</span>
            <span><?php echo icon('shield', 16); ?> אישור מיידי</span>
            <span><?php echo icon('clock', 16); ?> תמיכה 24/7</span>
            <span><?php echo icon('check', 16); ?> ללא עמלות נסתרות</span>
        </div>

        <div class="stats-strip">
            <?php foreach ($STATS as $s): ?>
            <div class="stat-card">
                <div class="stat-k"><?php echo $s['k']; ?></div>
                <div class="stat-label"><?php echo $s['label']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- PACKAGES -->
    <section class="pkg-sec container" id="packages">
        <div class="pkg-head">
            <div>
                <div class="eyebrow" style="margin-bottom: 14px;">חבילות ליסינג</div>
                <h2>שש חבילות. <span class="grad">אפס הפתעות.</span></h2>
            </div>
            <p>כל חבילה כוללת רכב + תחזוקה מלאה + ביטוח + רישוי ב־One Price. אין תוספות נסתרות, אין עמלות יציאה, אין סעיפים בכוכבית.</p>
        </div>

        <div class="pkg-toolbar">
            <div class="pkg-toolbar-left">
                <?php echo icon('clock', 14); ?>
                <span>משך התקשרות: <strong>36 חודשים</strong> · מחירים מתעדכנים אוטומטית</span>
            </div>
            <div class="pkg-tabs">
                <button type="button" data-on="false">24 חודשים</button>
                <button type="button" data-on="true">36 חודשים</button>
                <button type="button" data-on="false">48 חודשים</button>
            </div>
        </div>

        <div class="pkg-grid">
            <?php foreach ($PACKAGES as $pkg): ?>
            <div class="pkg-card<?php echo !empty($pkg['featured']) ? ' featured' : ''; ?>">
                <?php if (!empty($pkg['featured'])): ?>
                <span class="pkg-ribbon"><?php echo icon('sparkle', 10); ?> הפופולרית</span>
                <?php endif; ?>
                <div class="pkg-header">
                    <div class="pkg-icon"><?php echo icon($pkg['icon'], 22); ?></div>
                    <div class="pkg-title-block">
                        <h3><?php echo $pkg['title']; ?></h3>
                        <div class="sub"><?php echo $pkg['sub']; ?></div>
                    </div>
                </div>
                <div class="pkg-price-block">
                    <span class="pkg-from">החל מ־</span>
                    <span class="pkg-price"><span class="sym">₪</span><?php echo number_format($pkg['price']); ?></span>
                    <span class="pkg-price-term">/ חודש</span>
                </div>
                <p class="pkg-pitch"><?php echo $pkg['pitch']; ?></p>
                <ul class="pkg-feat">
                    <?php foreach ($pkg['features'] as $f): ?>
                    <li><span class="pkg-feat-ic"><?php echo icon('check', 11, 3); ?></span><?php echo $f; ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="pkg-footer">
                    <a href="contact.php?pkg=<?php echo $pkg['id']; ?>" class="btn pkg-cta <?php echo !empty($pkg['featured']) ? 'btn-primary' : 'btn-outline'; ?>" data-offer-modal data-offer-source="pkg-<?php echo $pkg['id']; ?>">
                        <?php echo icon('sparkle', 14); ?> קבל הצעה
                    </a>
                    <div class="pkg-spec-row">
                        <div><span>ק״מ שנתי</span><strong><?php echo $pkg['km']; ?></strong></div>
                        <div><span>סוג דלק</span><strong><?php echo $pkg['fuel']; ?></strong></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="pkg-compare-note">
            <p><strong>לא בטוחים איזו חבילה מתאימה?</strong><br>נציג VIP יבנה לכם הצעה אישית תוך 60 שניות — מותאמת בדיוק לצרכים שלכם.</p>
            <a href="contact.php" class="btn btn-primary" data-offer-modal data-offer-source="pkg-compare-note"><?php echo icon('sparkle', 16); ?> קבל הצעה אישית</a>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="container" style="padding: 80px 0; border-top: 1px solid var(--hairline);">
        <div class="sec-head">
            <div>
                <div class="eyebrow" style="margin-bottom: 14px;">מה שמבדיל אותנו</div>
                <h2>כלי השוואה שחוסך לך<br>שעות ומאות שקלים בחודש.</h2>
            </div>
            <p>כל רכב בעמוד ההשוואה כולל את עלות הבעלות הריאלית ל־5 שנים — לא רק את התשלום החודשי. זו הדרך היחידה לדעת באמת איזו עסקה משתלמת.</p>
        </div>
        <div class="features">
            <div class="feat">
                <div class="feat-icon"><?php echo icon('calendar', 26); ?></div>
                <h4>מחשבון TCO אמיתי</h4>
                <p>עלות כוללת כולל דלק, ביטוח, פחת ומע״מ. מציג את העסקה הזולה באמת — לא רק את זו שנראית זולה.</p>
            </div>
            <div class="feat">
                <div class="feat-icon"><?php echo icon('shield', 26); ?></div>
                <h4>עסקאות מאומתות בלבד</h4>
                <p>כל הצעה נבדקת מול היבואן לפני שהיא עולה לפורטל. אם התג שלנו עליה — זה המחיר שתשלם.</p>
            </div>
            <div class="feat">
                <div class="feat-icon"><?php echo icon('bolt', 26); ?></div>
                <h4>אישור בתוך 60 שניות</h4>
                <p>מלאו חמישה שדות, קבלו אישור עקרוני מבנק שותף ומחיר סופי מהיבואן — תוך דקה.</p>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="testimonials container">
        <div class="sec-head">
            <div>
                <div class="eyebrow" style="margin-bottom: 14px;">מה הלקוחות אומרים</div>
                <h2>38,920 עסקאות שנסגרו<br>דרכנו מאז 2021.</h2>
            </div>
        </div>
        <div class="testi-grid">
            <?php foreach ($TESTIMONIALS as $t): ?>
            <div class="testi">
                <div class="testi-stars">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.6 6 6.4.5-4.9 4.3 1.5 6.3L12 17l-5.6 3.1 1.5-6.3L3 9.5 9.4 9z"></path></svg>
                    <?php endfor; ?>
                </div>
                <p class="testi-quote">״<?php echo $t['quote']; ?>״</p>
                <div class="testi-author">
                    <div class="testi-avatar"><?php echo mb_substr($t['name'], 0, 1, 'UTF-8'); ?></div>
                    <div>
                        <div class="testi-name"><?php echo $t['name']; ?></div>
                        <div class="testi-role"><?php echo $t['role']; ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- PARTNERS -->
    <section class="container" style="padding: 80px 0;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 40px;">
            <div style="flex: 1; height: 1px; background: linear-gradient(to right, transparent, var(--surface-border-strong));"></div>
            <h3 style="font-size: 15px; font-weight: 600; color: var(--ink-3); letter-spacing: .2em; text-transform: uppercase; font-family: var(--font-mono);">שותפים ויבואנים</h3>
            <div style="flex: 1; height: 1px; background: linear-gradient(to left, transparent, var(--surface-border-strong));"></div>
        </div>
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; opacity: 0.5;">
            <?php foreach (array_slice($PARTNERS, 0, 8) as $p): ?>
            <div style="font-family: var(--font-display); font-size: 20px; font-weight: 800; color: var(--ink-4);"><?php echo $p; ?></div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
