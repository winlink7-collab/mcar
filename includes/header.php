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
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@300;400;500;600;700;800;900&family=Assistant:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
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
                    <span>mcar<span class="dot">.</span></span>
                </a>
                
                <nav class="nav-links" id="nav-links">
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
                    <button type="button" class="mini-btn" id="tweaks-toggle" aria-label="הגדרות תצוגה" title="הגדרות תצוגה">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1A2 2 0 1 1 4.3 17l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/></svg>
                    </button>
                    <button type="button" class="nav-toggle" id="nav-toggle" aria-label="פתח תפריט" aria-controls="nav-links" aria-expanded="false">
                        <?php echo icon('menu', 22); ?>
                    </button>
                </div>
            </div>
            <!-- Scroll Progress Indicator -->
            <div id="scroll-progress" style="position: absolute; bottom: 0; right: 0; height: 3px; background: var(--accent); width: 0%; transition: width 0.1s; z-index: 10;"></div>
        </header>

        <!-- Main Content Area -->
