<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="he" dir="rtl" data-mode="light" data-accent="teal" data-radius="large">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title . ' · ' . SITE_NAME : SITE_NAME . ' · ' . SITE_TAGLINE; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8') : 'פורטל השוואת הליסינג המוביל בישראל. רכבים, מחירים, וליסינג תפעולי במקום אחד.'; ?>">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="apple-touch-icon" href="favicon.svg">
    <meta name="theme-color" content="#0f766e">
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title . ' · ' . SITE_NAME : SITE_NAME . ' · ' . SITE_TAGLINE; ?>">
    <meta property="og:description" content="<?php echo isset($page_description) ? htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8') : 'פורטל השוואת הליסינג המוביל בישראל.'; ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="he_IL">

    <!-- FOUC-prevention: apply saved Tweaks before paint -->
    <script>
    (function() {
        try {
            var t = JSON.parse(localStorage.getItem('mcar_tweaks') || '{}');
            var root = document.documentElement;
            if (t.mode)   root.setAttribute('data-mode', t.mode);
            if (t.accent) root.setAttribute('data-accent', t.accent);
            if (t.radius) root.setAttribute('data-radius', t.radius);
        } catch(e) {}
    })();
    </script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Core Styles -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo ASSET_VERSION; ?>">
    
    <!-- Custom Page Styles -->
    <?php if (isset($page_styles)): ?>
    <style><?php echo $page_styles; ?></style>
    <?php endif; ?>
</head>
<body>
    <!-- Background elements -->
    <div class="mesh"></div>
    <div class="grain"></div>

    <div id="app">
        <!-- Site Header -->
        <header class="site-header" id="site-header">
            <div class="container header-inner">
                <a href="index.php" class="logo" aria-label="mcar">
                    <span class="logo-mark" aria-hidden="true">m</span>
                    <span>mcar</span>
                </a>
                
                <nav class="nav-links" id="nav-links">
                    <!-- Mobile-only CTA at top of drawer -->
                    <a href="contact.php" class="nav-cta" data-offer-modal data-offer-source="mobile-nav">
                        <?php echo icon('sparkle', 16); ?>
                        קבל הצעת VIP
                    </a>
                    <a href="index.php" class="nav-link <?php echo active_class('index'); ?>">דף הבית</a>
                    <a href="about.php" class="nav-link <?php echo active_class('about'); ?>">אודות</a>
                    <a href="grid.php" class="nav-link <?php echo active_class('grid'); ?>">רכבים</a>
                    <a href="operational.php" class="nav-link <?php echo active_class('operational'); ?>">ליסינג תפעולי</a>
                    <a href="contact.php" class="nav-link <?php echo active_class('contact'); ?>">צור קשר</a>
                </nav>

                <div class="header-actions">
                    <a href="contact.php" class="btn btn-primary btn-sm header-cta" data-offer-modal data-offer-source="header">
                        <?php echo icon('sparkle', 14); ?>
                        <span class="btn-label">קבל הצעת VIP</span>
                    </a>
                    <button type="button" class="mini-btn" id="tweaks-toggle" aria-label="התאמה אישית של העיצוב" title="התאמה אישית של העיצוב">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a10 10 0 0 0 0 20c1.1 0 2-.9 2-2 0-.5-.2-1-.5-1.4-.3-.3-.5-.8-.5-1.3 0-1.1.9-2 2-2h2.4a5 5 0 0 0 4.6-5c0-4.4-4.5-8-10-8z"/>
                            <circle cx="7"  cy="10" r="1.4" fill="#e11d48"/>
                            <circle cx="11" cy="6.5" r="1.4" fill="#f59e0b"/>
                            <circle cx="15" cy="8"  r="1.4" fill="#14b8a6"/>
                            <circle cx="17" cy="12" r="1.4" fill="#6d28d9"/>
                        </svg>
                    </button>
                    <button type="button" class="nav-toggle" id="nav-toggle" aria-label="פתח תפריט" aria-controls="nav-links" aria-expanded="false">
                        <?php echo icon('menu', 22); ?>
                    </button>
                </div>
            </div>
            <!-- Scroll Progress Indicator with moving car -->
            <div class="scroll-progress-track" aria-hidden="true">
                <div id="scroll-progress" class="scroll-progress-bar"></div>
                <div id="scroll-progress-car" class="scroll-progress-car">
                    <svg viewBox="0 0 48 22" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="spcBody" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="var(--accent-2)"/>
                                <stop offset="1" stop-color="var(--accent)"/>
                            </linearGradient>
                        </defs>
                        <path d="M3 12 Q5 10 9 9 L13 4 Q15 2.5 18 2.5 L30 2.5 Q33 2.5 35 4 L39 9 Q43 10 44 13 L44 15 Q44 16 43 16 L5 16 Q3.5 16 3.5 15 Z" fill="url(#spcBody)"/>
                        <path d="M14 5.5 L29 5.5 Q31 5.5 32.5 6.8 L36 9 L13 9 L14 5.5Z" fill="rgba(255,255,255,0.55)"/>
                        <circle cx="11" cy="17" r="3.2" fill="#0a1740"/>
                        <circle cx="11" cy="17" r="1.4" fill="#c9d0e0"/>
                        <circle cx="37" cy="17" r="3.2" fill="#0a1740"/>
                        <circle cx="37" cy="17" r="1.4" fill="#c9d0e0"/>
                    </svg>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
