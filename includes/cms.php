<?php
/**
 * mcar CMS — read settings, pages, menu, faq, social from the database
 * with safe fallbacks to hardcoded defaults so the site never breaks.
 *
 * Cached per-request (singletons in static vars).
 */

require_once __DIR__ . '/db.php';

// ---------- SETTINGS ----------
function setting($key, $default = null) {
    static $cache = null;
    if ($cache === null) {
        $pdo = db();
        $cache = [];
        if ($pdo) {
            try {
                foreach ($pdo->query("SELECT `key`, `value` FROM settings") as $r) {
                    $cache[$r['key']] = $r['value'];
                }
            } catch (PDOException $e) { /* silent */ }
        }
    }
    return isset($cache[$key]) && $cache[$key] !== '' ? $cache[$key] : $default;
}

function settings_save($pairs) {
    $pdo = db();
    if (!$pdo) return false;
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`) VALUES (:k, :v) ON DUPLICATE KEY UPDATE `value` = :v2");
    foreach ($pairs as $k => $v) {
        $stmt->execute([':k' => $k, ':v' => $v, ':v2' => $v]);
    }
    return true;
}

// ---------- PAGES ----------
function page($slug) {
    static $cache = [];
    if (isset($cache[$slug])) return $cache[$slug];
    $pdo = db();
    if (!$pdo) return $cache[$slug] = null;
    try {
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = :s AND active = 1 LIMIT 1");
        $stmt->execute([':s' => $slug]);
        return $cache[$slug] = $stmt->fetch() ?: null;
    } catch (PDOException $e) {
        return $cache[$slug] = null;
    }
}

/** Helper: page field with fallback. */
function page_field($slug, $field, $fallback = '') {
    $p = page($slug);
    return ($p && isset($p[$field]) && $p[$field] !== '') ? $p[$field] : $fallback;
}

function pages_all($type = null) {
    $pdo = db();
    if (!$pdo) return [];
    try {
        $sql = "SELECT * FROM pages";
        if ($type) $sql .= " WHERE type = :t";
        $sql .= " ORDER BY sort ASC, id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($type ? [':t' => $type] : []);
        return $stmt->fetchAll();
    } catch (PDOException $e) { return []; }
}

// ---------- MENU ----------
function menu_items($location) {
    static $cache = [];
    if (isset($cache[$location])) return $cache[$location];
    $pdo = db();
    if (!$pdo) return $cache[$location] = [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE location = :l AND active = 1 ORDER BY sort ASC, id ASC");
        $stmt->execute([':l' => $location]);
        return $cache[$location] = $stmt->fetchAll();
    } catch (PDOException $e) { return $cache[$location] = []; }
}

// ---------- FAQ ----------
function faq_groups_all() {
    $pdo = db();
    if (!$pdo) return [];
    try {
        return $pdo->query("SELECT * FROM faq_groups ORDER BY sort ASC")->fetchAll();
    } catch (PDOException $e) { return []; }
}
function faq_items_by_group($group_id) {
    $pdo = db();
    if (!$pdo) return [];
    try {
        $stmt = $pdo->prepare("SELECT * FROM faq_items WHERE group_id = :g AND active = 1 ORDER BY sort ASC, id ASC");
        $stmt->execute([':g' => $group_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) { return []; }
}

// ---------- SOCIAL ----------
function social_links_all() {
    static $cache = null;
    if ($cache !== null) return $cache;
    $pdo = db();
    if (!$pdo) return $cache = [];
    try {
        return $cache = $pdo->query("SELECT * FROM social_links WHERE active = 1 ORDER BY sort ASC")->fetchAll();
    } catch (PDOException $e) { return $cache = []; }
}

// ---------- MEDIA ----------
function media_all($limit = 50) {
    $pdo = db();
    if (!$pdo) return [];
    try {
        $limit = (int)$limit;
        return $pdo->query("SELECT * FROM media ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    } catch (PDOException $e) { return []; }
}

function media_save($filename, $original, $mime, $size, $width, $height, $path, $url, $alt = '', $user = null) {
    $pdo = db();
    if (!$pdo) return false;
    try {
        $stmt = $pdo->prepare("INSERT INTO media (filename, original_name, mime, size_bytes, width, height, path, url, alt_text, uploaded_by)
                               VALUES (:fn, :on, :mi, :sz, :w, :h, :p, :u, :al, :ub)");
        $stmt->execute([':fn' => $filename, ':on' => $original, ':mi' => $mime, ':sz' => $size, ':w' => $width, ':h' => $height, ':p' => $path, ':u' => $url, ':al' => $alt, ':ub' => $user]);
        return (int)$pdo->lastInsertId();
    } catch (PDOException $e) { return false; }
}
