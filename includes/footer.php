        <!-- Site Footer -->
        <footer class="site-footer">
            <div class="container">
                <div class="footer-top">
                    <a href="index.php" class="logo">
                        <span class="logo-mark" aria-hidden="true">m</span>
                        <span>mcar</span>
                    </a>
                    <p>פורטל השוואת הליסינג המוביל בישראל. שקיפות, מהירות וחסכון אמיתי לכל רכב.</p>
                </div>
                <?php
                require_once __DIR__ . '/cms.php';
                $foot_company = menu_items('footer_company');
                $foot_support = menu_items('footer_support');
                ?>
                <div class="footer-grid">
                    <div class="footer-col">
                        <h4>קטגוריות</h4>
                        <nav class="footer-links">
                            <?php foreach ($GLOBALS['CATEGORIES'] as $id => $cat): ?>
                            <a href="grid.php?cat=<?php echo $id; ?>" class="footer-link"><?php echo $cat['label']; ?></a>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                    <div class="footer-col">
                        <h4>חברה</h4>
                        <nav class="footer-links">
                            <?php if (!empty($foot_company)): foreach ($foot_company as $it): ?>
                            <a href="<?php echo htmlspecialchars($it['url']); ?>" class="footer-link" target="<?php echo $it['target']; ?>"><?php echo htmlspecialchars($it['label']); ?></a>
                            <?php endforeach; else: ?>
                            <a href="about.php" class="footer-link">אודותינו</a>
                            <a href="contact.php" class="footer-link">צור קשר</a>
                            <a href="careers.php" class="footer-link">קריירה</a>
                            <a href="blog.php" class="footer-link">בלוג</a>
                            <?php endif; ?>
                        </nav>
                    </div>
                    <div class="footer-col">
                        <h4>תמיכה</h4>
                        <nav class="footer-links">
                            <?php if (!empty($foot_support)): foreach ($foot_support as $it): ?>
                            <a href="<?php echo htmlspecialchars($it['url']); ?>" class="footer-link" target="<?php echo $it['target']; ?>"><?php echo htmlspecialchars($it['label']); ?></a>
                            <?php endforeach; else: ?>
                            <a href="faq.php" class="footer-link">שאלות נפוצות</a>
                            <a href="terms.php" class="footer-link">תנאי שימוש</a>
                            <a href="privacy.php" class="footer-link">פרטיות</a>
                            <a href="accessibility.php" class="footer-link">נגישות</a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>

                <div class="hair" style="margin: 60px 0 30px;"></div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <div style="font-size: 15px; color: var(--ink-4); font-family: var(--font-mono);">
                        © <?php echo date('Y'); ?> mcar Israel. כל הזכויות שמורות.
                    </div>
                    <div style="display: flex; gap: 20px; color: var(--ink-4);">
                        <?php
                        $socials = social_links_all();
                        if (empty($socials)) {
                            $socials = [
                                ['platform'=>'Facebook','url'=>'#','icon'=>'facebook'],
                                ['platform'=>'Instagram','url'=>'#','icon'=>'instagram'],
                                ['platform'=>'LinkedIn','url'=>'#','icon'=>'linkedin'],
                            ];
                        }
                        foreach ($socials as $sl):
                            $href = !empty($sl['url']) && $sl['url'] !== '#' ? $sl['url'] : '#';
                        ?>
                        <a href="<?php echo htmlspecialchars($href); ?>" title="<?php echo htmlspecialchars($sl['platform']); ?>" aria-label="<?php echo htmlspecialchars($sl['platform']); ?>" <?php echo $href !== '#' ? 'target="_blank" rel="noopener"' : ''; ?>><?php echo icon($sl['icon'], 18); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </footer>
    </div><!-- #app -->

    <!-- Tweaks Panel (global) -->
    <dialog class="tweaks-modal" id="tweaksModal" aria-labelledby="tweaksTitle">
        <div class="tweaks-inner">
            <div class="tweaks-head">
                <button type="button" class="tweaks-close" data-tweaks-close aria-label="סגור">
                    <?php echo icon('x', 18); ?>
                </button>
                <span class="tweaks-live">
                    <span class="pulse"></span> LIVE
                </span>
                <h3 id="tweaksTitle"><?php echo icon('sparkle', 16); ?> Tweaks</h3>
            </div>

            <div class="tweaks-group">
                <div class="tweaks-label">מצב תצוגה</div>
                <div class="tweaks-pills" role="radiogroup" aria-label="מצב תצוגה">
                    <button type="button" class="tweaks-pill" data-tweak-mode="dark"  role="radio"><span class="tw-glyph">🌙</span> Dark</button>
                    <button type="button" class="tweaks-pill" data-tweak-mode="light" role="radio"><span class="tw-glyph">☀</span> Light</button>
                </div>
            </div>

            <div class="tweaks-group">
                <div class="tweaks-label">צבע ראשי</div>
                <div class="tweaks-pills" role="radiogroup" aria-label="צבע ראשי">
                    <button type="button" class="tweaks-pill tweaks-accent" data-tweak-accent="teal"   role="radio"><span class="tw-swatch" style="background: #14b8a6;"></span> טורקיז</button>
                    <button type="button" class="tweaks-pill tweaks-accent" data-tweak-accent="violet" role="radio"><span class="tw-swatch" style="background: #6d28d9;"></span> סגול</button>
                    <button type="button" class="tweaks-pill tweaks-accent" data-tweak-accent="navy"   role="radio"><span class="tw-swatch" style="background: #002366;"></span> נייבי</button>
                </div>
            </div>

            <div class="tweaks-group">
                <div class="tweaks-label">פינות</div>
                <div class="tweaks-pills" role="radiogroup" aria-label="פינות">
                    <button type="button" class="tweaks-pill" data-tweak-radius="regular" role="radio">רגילות</button>
                    <button type="button" class="tweaks-pill" data-tweak-radius="large"   role="radio">גדולות</button>
                </div>
            </div>

            <div class="tweaks-footer">
                <button type="button" class="tweaks-reset" data-tweaks-reset>
                    <?php echo icon('x', 12); ?> איפוס ברירת מחדל
                </button>
            </div>
        </div>
    </dialog>

    <!-- VIP Offer Modal (global) -->
    <dialog class="offer-modal" id="offerModal" aria-labelledby="offerModalTitle">
        <form method="POST" action="contact.php" class="offer-modal-inner" id="offerModalForm">
            <button type="button" class="offer-modal-close" data-offer-close aria-label="סגור">
                <?php echo icon('x', 18); ?>
            </button>
            <div class="offer-modal-tag">
                <?php echo icon('sparkle', 12); ?>
                VIP OFFER
            </div>
            <h3 id="offerModalTitle">הצעה אישית תוך 60 שניות</h3>
            <p class="offer-modal-lead">מלאו שני שדות. נציג בכיר יצור קשר עם הצעה מאומתת מול היבואן — בלי לעבור דרך מוקדי מכירות.</p>

            <?php
            require_once __DIR__ . '/security.php';
            echo csrf_field();
            ?>
            <input type="hidden" name="source" id="offerModalSource" value="modal">

            <div class="offer-field-row">
                <div class="offer-field">
                    <label for="offerName">שם מלא</label>
                    <div class="offer-input">
                        <?php echo icon('users', 16); ?>
                        <input type="text" id="offerName" name="name" placeholder="ישראל ישראלי" required autocomplete="name">
                    </div>
                </div>
                <div class="offer-field">
                    <label for="offerPhone">טלפון</label>
                    <div class="offer-input">
                        <?php echo icon('phone', 16); ?>
                        <input type="tel" id="offerPhone" name="phone" placeholder="050-1234567" required autocomplete="tel" pattern="0[0-9\-]{8,11}">
                    </div>
                </div>
            </div>

            <ul class="offer-bullets">
                <li><?php echo icon('check', 14, 3); ?> הצעה תוך 60 שניות</li>
                <li><?php echo icon('check', 14, 3); ?> מחיר מאומת מול היבואן</li>
                <li><?php echo icon('check', 14, 3); ?> ללא התחייבות</li>
                <li><?php echo icon('check', 14, 3); ?> ליווי VIP לכל התקופה</li>
            </ul>

            <div class="offer-modal-footer">
                <button type="submit" class="btn btn-primary btn-lg">
                    <?php echo icon('sparkle', 16); ?> קבל הצעה
                </button>
                <span class="offer-secure">
                    <?php echo icon('shield', 14); ?> המידע מוצפן
                </span>
            </div>
        </form>
    </dialog>

    <!-- Mobile Sticky Contact Bar (shown only on mobile) -->
    <div class="mobile-sticky-bar">
        <a href="tel:*4260" class="msb-btn msb-call"><?php echo icon('phone', 18); ?> חייג עכשיו</a>
        <a href="https://wa.me/972524260426" class="msb-btn msb-wa" aria-label="צ'אט בוואטסאפ" title="WhatsApp"><?php echo icon('whatsapp', 22); ?> וואטסאפ</a>
    </div>

    <!-- Social Proof Toast -->
    <div class="social-proof-toast" id="sp-toast" aria-live="polite">
        <div class="sp-icon"><?php echo icon('sparkle', 16); ?></div>
        <div class="sp-body">
            <div class="sp-text" id="sp-msg"></div>
            <div class="sp-time">לפני מספר רגעים</div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="assets/js/main.js?v=<?php echo ASSET_VERSION; ?>"></script>

    <!-- Custom <body> scripts (from Settings — Pixel, Hotjar, etc.) -->
    <?php $_body = setting('body_scripts'); if ($_body): ?>
    <?php echo $_body; ?>
    <?php endif; ?>
</body>
</html>
