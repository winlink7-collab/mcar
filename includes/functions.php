<?php
/**
 * mcar Utility Functions
 */

/**
 * Format currency in ILS
 */
function format_ils($amount) {
    return '₪' . number_format($amount, 0, '.', ',');
}

/**
 * Render an SVG icon
 */
function icon($name, $size = 20, $stroke = 1.6) {
    $icons = [
        'search'   => '<circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path>',
        'menu'     => '<path d="M3 6h18M3 12h18M3 18h18"></path>',
        'x'        => '<path d="M18 6 6 18M6 6l12 12"></path>',
        'check'    => '<path d="m5 12 5 5L20 7"></path>',
        'arrow'    => '<path d="M14 6 20 12 14 18 M20 12 H4"></path>',
        'chev'     => '<path d="m9 6 6 6-6 6"></path>',
        'chevD'    => '<path d="m6 9 6 6 6-6"></path>',
        'shield'   => '<path d="M12 3 4 6v6c0 5 3.5 8.5 8 9 4.5-.5 8-4 8-9V6z"></path><path d="m9 12 2 2 4-4"></path>',
        'bolt'     => '<path d="M13 3 4 14h7l-1 7 9-11h-7z"></path>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 9h18M8 3v4M16 3v4"></path>',
        'drop'     => '<path d="M12 3s6 7 6 12a6 6 0 0 1-12 0c0-5 6-12 6-12Z"></path>',
        'leaf'     => '<path d="M20 4C10 4 4 10 4 20c10 0 16-6 16-16Z"></path><path d="M4 20 20 4"></path>',
        'users'    => '<circle cx="9" cy="8" r="4"></circle><path d="M2 21v-1a7 7 0 0 1 14 0v1"></path>',
        'mail'     => '<rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>',
        'phone'    => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>',
        'sparkle'  => '<path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3z"></path>',
        'swap'     => '<path d="m7 10 5 5 5-5"></path><path d="m17 14-5-5-5 5"></path>',
        'verify'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path>',
        'clock'    => '<circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline>',
        'whatsapp' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-7.6 8.38 8.38 0 0 1 3.8.9L21 4.5ZM16.5 13a1 1 0 0 0-1-1H11v-4.5a1 1 0 0 0-2 0V13a1 1 0 0 0 1 1h5.5a1 1 0 0 0 1-1Z"></path>',
        'facebook' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>',
        'instagram'=> '<rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.5" cy="6.5" r="0.8" fill="currentColor"></circle>',
        'linkedin' => '<rect x="3" y="3" width="18" height="18" rx="3"></rect><path d="M8 10v7M8 7v.01M12 17v-4a2 2 0 0 1 4 0v4M12 13v-3"></path>'
    ];

    $path = isset($icons[$name]) ? $icons[$name] : '';
    
    return sprintf(
        '<svg width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%.1f" stroke-linecap="round" stroke-linejoin="round" class="icon icon-%s">%s</svg>',
        $size, $size, $stroke, $name, $path
    );
}

/**
 * Render an engine chip
 */
function render_engine_chip($engine_type_id) {
    global $ENGINE_TYPES;
    $info = $ENGINE_TYPES[$engine_type_id];
    return sprintf(
        '<span class="chip" style="color: %s; background: %s15; border-color: %s30">
            <span style="font-size: 1.1em; margin-left: 4px;">%s</span>
            %s
        </span>',
        $info['color'], $info['color'], $info['color'], $info['glyph'], $info['label']
    );
}

/**
 * Render the shared car-illustration SVG inside a styled frame.
 * Used on the home hero and in the catalog grid cards.
 */
function render_car_frame($car, $size = 'lg') {
    $label = htmlspecialchars($car['make'] . ' ' . $car['model'], ENT_QUOTES, 'UTF-8');
    $tag = htmlspecialchars($car['make'], ENT_QUOTES, 'UTF-8');
    ob_start();
    ?>
    <div class="car-frame" data-size="<?php echo $size; ?>" aria-label="<?php echo $label; ?>">
        <div class="car-frame-stripes" aria-hidden="true"></div>
        <div class="car-frame-glow" aria-hidden="true"></div>
        <svg viewBox="0 0 350 160" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMax meet" style="position: relative; z-index: 1; width: 100%; height: 100%;">
            <defs>
                <linearGradient id="carBody-<?php echo $car['id']; ?>" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="var(--accent)" stop-opacity=".9"></stop>
                    <stop offset="1" stop-color="var(--accent)" stop-opacity=".65"></stop>
                </linearGradient>
                <linearGradient id="floor-<?php echo $car['id']; ?>" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0" stop-color="rgba(0,35,102,.1)"></stop>
                    <stop offset="1" stop-color="rgba(0,35,102,0)"></stop>
                </linearGradient>
            </defs>
            <ellipse cx="175" cy="145" rx="140" ry="8" fill="url(#floor-<?php echo $car['id']; ?>)"></ellipse>
            <g>
                <path d="M30 85 Q50 82 75 80 L95 55 Q105 45 125 44 L210 44 Q230 44 245 56 L280 78 Q305 82 320 88 Q325 92 325 100 L325 112 Q325 118 320 118 L30 118 Q25 118 25 112 L25 92 Q25 87 30 85 Z" fill="url(#carBody-<?php echo $car['id']; ?>)" opacity=".95"></path>
                <path d="M102 58 L205 58 Q222 58 232 66 L255 80 L105 80 Q97 80 99 73 L102 58Z" fill="rgba(255,255,255,0.45)"></path>
                <path d="M165 58 L165 80" stroke="rgba(0,35,102,.2)" stroke-width="1.5"></path>
                <circle cx="88" cy="118" r="20" fill="#0a1740"></circle>
                <circle cx="88" cy="118" r="11" fill="none" stroke="#5a6892" stroke-width="2"></circle>
                <circle cx="88" cy="118" r="4" fill="#c9d0e0"></circle>
                <circle cx="258" cy="118" r="20" fill="#0a1740"></circle>
                <circle cx="258" cy="118" r="11" fill="none" stroke="#5a6892" stroke-width="2"></circle>
                <circle cx="258" cy="118" r="4" fill="#c9d0e0"></circle>
            </g>
            <?php if ($car['engine'] === 'electric'): ?>
            <g opacity=".9"><path d="M310 40 l-6 9 h5 l-3 10 7-11 h-5 z" fill="var(--accent)"></path></g>
            <?php endif; ?>
        </svg>
        <span class="car-frame-tag">vehicle render · <?php echo $tag; ?></span>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Build a placeholder car image URL based on the car's engine color + brand.
 */
function car_image_url($car, $w = 600, $h = 400) {
    global $ENGINE_TYPES;
    $color = isset($ENGINE_TYPES[$car['engine']]['color']) ? $ENGINE_TYPES[$car['engine']]['color'] : '#0f766e';
    $bg = ltrim($color, '#');
    $text = urlencode($car['make'] . ' ' . $car['model']);
    return "https://placehold.co/{$w}x{$h}/{$bg}/ffffff?text={$text}&font=montserrat";
}

/**
 * Stable scenic placeholder (picsum) seeded per topic.
 */
function scene_image_url($seed, $w = 800, $h = 600) {
    return "https://picsum.photos/seed/" . urlencode($seed) . "/{$w}/{$h}";
}

/**
 * Get active class for nav links
 */
function active_class($page) {
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
    if ($current_page == 'index' && $page == 'home') return 'active';
    return ($current_page == $page) ? 'active' : '';
}
