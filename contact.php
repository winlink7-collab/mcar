<?php
require_once 'includes/db.php';
require_once 'includes/mail.php';
require_once 'includes/whatsapp.php';
require_once 'includes/security.php';
start_session_once();

$sent = false;
$lead_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Rate-limit (5 submits per minute per session)
    if (!rate_limit_ok('lead', 5, 60)) {
        $lead_error = 'יותר מדי בקשות. נסה שוב בעוד דקה.';
    }
    // 2. CSRF check (only if a token was sent — backwards-compatible)
    elseif (isset($_POST['_csrf']) && !csrf_check()) {
        $lead_error = 'הטוקן פג תוקף. רענן את הדף ונסה שוב.';
    }
    // 3. reCAPTCHA verification (skipped if not configured)
    elseif (!recaptcha_verify('contact')) {
        $lead_error = 'בדיקת אבטחה נכשלה. נסה שוב.';
    }
    else {
        $data = [
            'name'      => clean_str($_POST['name'] ?? '', 120),
            'phone'     => clean_phone($_POST['phone'] ?? ''),
            'email'     => clean_email($_POST['email'] ?? ''),
            'source'    => clean_str($_POST['source'] ?? 'contact-page', 80),
            'car_id'    => clean_str($_GET['car'] ?? $_POST['car'] ?? '', 60) ?: null,
            'pkg_id'    => clean_str($_GET['pkg'] ?? $_POST['pkg'] ?? '', 40) ?: null,
            'deal_type' => clean_str($_POST['deal_type'] ?? '', 40) ?: null,
            'message'   => clean_str($_POST['message'] ?? '', 2000),
        ];

        // Minimum validation
        if (!$data['name'] || !$data['phone']) {
            $lead_error = 'חסרים שדות חובה: שם וטלפון.';
        } else {
            // 4. Save to DB (returns false silently if DB not configured)
            $lead_id = save_lead($data);
            // 5. Send notifications (non-blocking — succeed even if any fail)
            @send_lead_email($data);
            @send_lead_whatsapp($data);
            $sent = true;
        }
    }
}

require_once 'includes/header.php';

require_once __DIR__ . '/includes/cms.php';
$OFFICE_INFO = [
    'tel_aviv' => [
        'name' => 'מטה ראשי · תל אביב',
        'address' => setting('contact_address', 'דרך מנחם בגין 132, תל אביב, קומה 18'),
        'hours'   => setting('contact_hours', 'א׳-ה׳ 8-20, ו׳ 8-13'),
    ]
];
?>

<main class="page-enter" style="padding-bottom: 60px;">
    <!-- HERO -->
    <section style="padding: 56px 0 40px; border-bottom: 1px solid var(--hairline); margin-bottom: 48px; position: relative; overflow: hidden;">
        <!-- Atmosphere BG -->
        <div style="position: absolute; inset: 0; z-index: -1; pointer-events: none;">
            <img src="assets/img/contact_sunset.png" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.75; mix-blend-mode: luminosity; mask-image: linear-gradient(to right, transparent 5%, black 60%); -webkit-mask-image: linear-gradient(to right, transparent 5%, black 60%);">
        </div>
        <div class="container" style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 48px; align-items: end; position: relative; z-index: 1;">
            <div>
                <div class="eyebrow" style="margin-bottom: 16px;">דברו איתנו</div>
                <h1 style="font-size: clamp(44px, 5.8vw, 84px); font-weight: 900; line-height: 0.95; letter-spacing: -0.04em;">
                    שניה. נושמים.<br>
                    <span class="grad" style="background: linear-gradient(135deg, var(--accent), var(--accent-2)); -webkit-background-clip: text; background-clip: text; color: transparent;">ומתחילים לחסוך.</span>
                </h1>
                <p style="color: var(--ink-2); font-size: 17px; line-height: 1.6; max-width: 54ch; margin-top: 22px;">
                    נציג VIP אמיתי חוזר אליכם תוך 60 שניות. בלי בוטים ובלי המתנות מיותרות. צוות המומחים שלנו זמין עבורך לכל שאלה.
                </p>

                <div style="display: flex; gap: 30px; margin-top: 40px; padding-top: 28px; border-top: 1px solid var(--hairline);">
                    <div>
                        <div style="font-family: var(--font-display); font-size: 30px; font-weight: 800;">60<span style="font-size: 55%; color: var(--accent);">שנ׳</span></div>
                        <div style="font-size: 13px; color: var(--ink-3); font-family: var(--font-mono); text-transform: uppercase;">זמן תגובה</div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-display); font-size: 30px; font-weight: 800;">24/7</div>
                        <div style="font-size: 13px; color: var(--ink-3); font-family: var(--font-mono); text-transform: uppercase;">צוות זמין</div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-display); font-size: 30px; font-weight: 800;">9.4</div>
                        <div style="font-size: 13px; color: var(--ink-3); font-family: var(--font-mono); text-transform: uppercase;">שביעות רצון</div>
                    </div>
                </div>
            </div>

            <?php
            $c_phone = setting('contact_phone', '*4260');
            $c_phone_disp = setting('contact_phone_display', $c_phone);
            $c_wa = setting('contact_whatsapp', '+972524260426');
            $c_wa_disp = setting('contact_whatsapp_display', '052-4260-426');
            $c_wa_clean = preg_replace('/[^0-9]/', '', $c_wa);
            $c_email = setting('contact_email', 'vip@mcar.co.il');
            ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <a href="https://wa.me/<?php echo htmlspecialchars($c_wa_clean); ?>" class="btn btn-ghost" style="padding: 18px; border-radius: var(--r-lg); flex-direction: column; align-items: flex-start; gap: 4px;">
                    <div style="color: #25D366;"><?php echo icon('whatsapp', 20); ?></div>
                    <div style="font-size: 12px; color: var(--ink-3); font-family: var(--font-mono);">WhatsApp</div>
                    <div style="font-size: 16px; font-weight: 800;"><?php echo htmlspecialchars($c_wa_disp); ?></div>
                </a>
                <a href="tel:<?php echo htmlspecialchars($c_phone); ?>" class="btn btn-ghost" style="padding: 18px; border-radius: var(--r-lg); flex-direction: column; align-items: flex-start; gap: 4px;">
                    <div style="color: var(--accent);"><?php echo icon('phone', 18); ?></div>
                    <div style="font-size: 12px; color: var(--ink-3); font-family: var(--font-mono);">VIP LINE</div>
                    <div style="font-size: 16px; font-weight: 800;"><?php echo htmlspecialchars($c_phone_disp); ?></div>
                </a>
                <a href="mailto:<?php echo htmlspecialchars($c_email); ?>" class="btn btn-ghost" style="padding: 18px; border-radius: var(--r-lg); flex-direction: column; align-items: flex-start; gap: 4px; grid-column: span 2;">
                    <div style="color: var(--accent);"><?php echo icon('mail', 18); ?></div>
                    <div style="font-size: 12px; color: var(--ink-3); font-family: var(--font-mono);">אימייל</div>
                    <div style="font-size: 16px; font-weight: 800;"><?php echo htmlspecialchars($c_email); ?></div>
                </a>
            </div>
        </div>
    </section>

    <!-- FORM SECTION -->
    <section class="container">
        <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 28px; align-items: start;">
            <?php if ($sent): ?>
            <div style="padding: 60px 48px; text-align: center; background: var(--surface); border: 1px solid var(--surface-border); border-radius: var(--r-xl); box-shadow: var(--shadow-2);">
                <div style="width: 80px; height: 80px; margin: 0 auto 24px; border-radius: 24px; background: var(--accent); color: white; display: grid; place-items: center;">
                    <?php echo icon('check', 36, 3); ?>
                </div>
                <h3 style="font-size: 32px; font-weight: 900; margin-bottom: 12px;">תודה רבה!</h3>
                <p style="color: var(--ink-3); font-size: 15px; max-width: 40ch; margin: 0 auto 20px;">קיבלנו את פנייתך. נציג VIP יחזור אליך תוך פחות מ-60 שניות.</p>
                <a href="contact.php" class="btn btn-ghost"><?php echo icon('arrow', 14); ?> חזור אחורה</a>
            </div>
            <?php else: ?>
            <form action="contact.php" method="POST" style="background: var(--surface); border: 1px solid var(--surface-border); border-radius: var(--r-xl); overflow: hidden; box-shadow: var(--shadow-1);">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="source" value="contact-page">
                <?php if ($lead_error): ?>
                <div style="padding: 14px 24px; background: #fef2f2; color: #b91c1c; border-bottom: 1px solid #fecaca; font-weight: 600; font-size: 14px;">
                    <?php echo htmlspecialchars($lead_error); ?>
                </div>
                <?php endif; ?>
                <div class="form-progress" style="height: 3px; background: var(--bg-2); position: relative;">
                    <div style="position: absolute; top: 0; right: 0; height: 100%; width: 33%; background: var(--accent); transition: width 0.3s;"></div>
                </div>
                <div style="padding: 28px 32px; background: var(--bg-2); border-bottom: 1px solid var(--hairline); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2 style="font-size: 28px; font-weight: 800;">בקשה להצעה</h2>
                        <p style="color: var(--ink-3); font-size: 14px; margin-top: 4px;">נציג אנושי יחזור אליך עם הצעה מותאמת.</p>
                    </div>
                    <div style="padding: 6px 12px; background: var(--accent-soft); color: var(--accent); border-radius: 999px; font-size: 13px; font-weight: 700; font-family: var(--font-mono);">
                        תגובה ב-60 שנ׳
                    </div>
                </div>
                <div style="padding: 32px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; margin-bottom: 8px;">שם מלא</label>
                            <input type="text" name="name" required placeholder="ישראל ישראלי" style="width: 100%; padding: 12px; border-radius: var(--r-md); border: 1px solid var(--surface-border-strong); background: var(--bg);">
                        </div>
                        <div>
                            <label style="display: block; font-size: 14px; font-weight: 700; margin-bottom: 8px;">טלפון</label>
                            <input type="tel" name="phone" required placeholder="050-1234567" style="width: 100%; padding: 12px; border-radius: var(--r-md); border: 1px solid var(--surface-border-strong); background: var(--bg);">
                        </div>
                    </div>
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 14px; font-weight: 700; margin-bottom: 8px;">סוג עסקה מועדף</label>
                        <div style="display: flex; gap: 10px;">
                            <?php foreach ($DEAL_TYPES as $i => $d): ?>
                            <label class="deal-type-pill" style="flex: 1; padding: 12px; border: 1px solid var(--surface-border-strong); border-radius: var(--r-md); cursor: pointer; text-align: center; transition: border-color .15s, background .15s, color .15s;">
                                <input type="radio" name="deal_type" value="<?php echo $d['id']; ?>" <?php echo $i === 0 ? 'checked' : ''; ?> class="sr">
                                <div style="font-weight: 700; font-size: 14px;"><?php echo $d['label']; ?></div>
                                <div style="font-size: 13px; color: var(--ink-3); margin-top: 2px; font-family: var(--font-mono);"><?php echo $d['sub']; ?></div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                        <?php echo icon('sparkle', 16); ?>
                        שלח בקשה עכשיו
                    </button>
                    <p style="margin-top: 16px; font-size: 14px; color: var(--ink-4); text-align: center;"><?php echo icon('shield', 12); ?> המידע שלך מאובטח ולא יועבר לצדדים שלישיים.</p>
                </div>
            </form>
            <?php endif; ?>

            <aside style="display: flex; flex-direction: column; gap: 20px;">
                <div style="padding: 24px; background: var(--surface); border: 1px solid var(--surface-border); border-radius: var(--r-xl);">
                    <h4 style="font-size: 14px; font-weight: 700; color: var(--ink-3); font-family: var(--font-mono); text-transform: uppercase; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <?php echo icon('clock', 14); ?> זמני תגובה
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span>WhatsApp</span>
                            <span style="color: var(--ok); font-weight: 700;">~2 דק׳</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span>טלפון</span>
                            <span style="color: var(--ok); font-weight: 700;">~30 שנ׳</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span>טופס אתר</span>
                            <span style="color: var(--ok); font-weight: 700;">~60 שנ׳</span>
                        </div>
                    </div>
                </div>

                <div style="background: var(--surface); border: 1px solid var(--surface-border); border-radius: var(--r-xl); overflow: hidden;">
                    <div style="height: 150px; position: relative;">
                        <img src="<?php echo scene_image_url('mcar-tel-aviv-office', 800, 400); ?>"
                             alt="<?php echo $OFFICE_INFO['tel_aviv']['name']; ?>"
                             loading="lazy"
                             style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(15,118,110,0.55), rgba(20,184,166,0.35)); display: grid; place-items: center; color: white;">
                            <?php echo icon('verify', 48); ?>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <h4 style="font-size: 16px; font-weight: 800;"><?php echo $OFFICE_INFO['tel_aviv']['name']; ?></h4>
                        <p style="color: var(--ink-3); font-size: 15px; margin-top: 4px;"><?php echo $OFFICE_INFO['tel_aviv']['address']; ?></p>
                        <div style="margin-top: 14px; padding-top: 14px; border-top: 1px solid var(--hairline); font-size: 14px; color: var(--ink-3);">
                            שעות פעילות: <strong><?php echo $OFFICE_INFO['tel_aviv']['hours']; ?></strong>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="container" style="margin-top: 80px; padding-top: 80px; border-top: 1px solid var(--hairline);">
        <h2 style="font-size: 32px; font-weight: 900; margin-bottom: 40px;">שאלות נפוצות</h2>
        <div style="display: grid; gap: 12px;">
            <?php foreach ($FAQ as $item): ?>
            <details style="background: var(--surface); border: 1px solid var(--surface-border); border-radius: var(--r-lg); overflow: hidden;">
                <summary style="padding: 20px 24px; cursor: pointer; font-weight: 700; list-style: none; display: flex; justify-content: space-between; align-items: center;">
                    <?php echo $item['q']; ?>
                    <span style="color: var(--accent); transition: transform 0.3s;">+</span>
                </summary>
                <div style="padding: 0 24px 20px; color: var(--ink-3); line-height: 1.6;">
                    <?php echo $item['a']; ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
