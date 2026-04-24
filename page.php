<?php
/**
 * mcar — Dynamic page renderer
 * Resolves /page.php?slug=... or /<slug>/ (via .htaccess) to a CMS page.
 * Renders the page with shared header/footer + Quill content_html.
 */

require_once __DIR__ . '/includes/cms.php';

$slug = $_GET['slug'] ?? '';
$slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));

if (!$slug) {
    header('Location: index.php', true, 302);
    exit;
}

// Don't intercept built-in pages — they have their own .php files
$builtin = ['home','index','about','contact','grid','operational','careers','blog','faq','terms','privacy','accessibility','404'];
if (in_array($slug, $builtin, true)) {
    $target = ($slug === 'home' || $slug === 'index') ? 'index.php' : $slug . '.php';
    header('Location: ' . $target, true, 302);
    exit;
}

$cms_page = page($slug);
if (!$cms_page || !$cms_page['active'] || $cms_page['type'] !== 'custom') {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

// Set meta + render
$page_title       = $cms_page['seo_title']       ?: $cms_page['hero_h1'];
$page_description = $cms_page['seo_description'] ?: '';
$cms_slug         = $slug;

require_once __DIR__ . '/includes/header.php';
?>

<main class="page-enter container" style="padding: 40px 0 80px;">

    <?php if ($cms_page['eyebrow'] || $cms_page['hero_h1'] || $cms_page['hero_lead']): ?>
    <section class="page-hero">
        <?php if ($cms_page['eyebrow']): ?>
        <div class="eyebrow" style="margin-bottom: 16px;"><?php echo htmlspecialchars($cms_page['eyebrow']); ?></div>
        <?php endif; ?>
        <?php if ($cms_page['hero_h1']): ?>
        <h1><?php echo htmlspecialchars($cms_page['hero_h1']); ?></h1>
        <?php endif; ?>
        <?php if ($cms_page['hero_lead']): ?>
        <p><?php echo htmlspecialchars($cms_page['hero_lead']); ?></p>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <?php if ($cms_page['hero_image']): ?>
    <div class="page-hero-image" style="margin: 24px 0; border-radius: var(--r-2xl); overflow: hidden; box-shadow: var(--shadow-3);">
        <img src="<?php echo htmlspecialchars($cms_page['hero_image']); ?>"
             alt="<?php echo htmlspecialchars($cms_page['hero_h1']); ?>"
             loading="lazy"
             style="width: 100%; height: auto; display: block;">
    </div>
    <?php endif; ?>

    <?php if ($cms_page['content_html']): ?>
    <article class="cms-content">
        <?php echo $cms_page['content_html']; /* Quill HTML — admins can write raw HTML */ ?>
    </article>
    <?php endif; ?>

</main>

<style>
.cms-content {
    max-width: 760px;
    margin: 40px auto;
    font-size: 17px;
    line-height: 1.7;
    color: var(--ink-2);
}
.cms-content h1, .cms-content h2, .cms-content h3, .cms-content h4 {
    color: var(--ink);
    margin: 1.5em 0 0.5em;
    line-height: 1.2;
}
.cms-content h1 { font-size: 32px; font-weight: 900; }
.cms-content h2 { font-size: 26px; font-weight: 800; }
.cms-content h3 { font-size: 21px; font-weight: 700; }
.cms-content p, .cms-content ul, .cms-content ol { margin: 1em 0; }
.cms-content ul, .cms-content ol { padding-right: 24px; }
.cms-content li { margin: 0.4em 0; }
.cms-content a { color: var(--accent); text-decoration: underline; }
.cms-content a:hover { text-decoration: none; }
.cms-content strong { color: var(--ink); font-weight: 700; }
.cms-content blockquote {
    border-right: 4px solid var(--accent);
    background: var(--bg-2);
    padding: 16px 24px;
    border-radius: var(--r-md);
    margin: 1.5em 0;
    color: var(--ink);
}
.cms-content img {
    max-width: 100%; height: auto;
    border-radius: var(--r-md);
    margin: 1em 0;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
