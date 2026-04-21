<?php
$is_ajax = isset($_GET['ajax']) && $_GET['ajax'] === '1';
if (!$is_ajax) {
    require_once 'includes/header.php';
} else {
    require_once 'includes/config.php';
    require_once 'includes/functions.php';
}

// --- Parse filter inputs ---
$selected_deal    = isset($_GET['deal']) && in_array($_GET['deal'], ['private','operational','purchase']) ? $_GET['deal'] : 'private';
$selected_cat     = isset($_GET['cat']) ? $_GET['cat'] : 'all';
$selected_engine  = isset($_GET['engine']) ? $_GET['engine'] : 'all';
$selected_seats   = isset($_GET['seats']) ? $_GET['seats'] : 'all';
$selected_budget  = isset($_GET['budget']) && $_GET['budget'] !== 'all' ? (int)$_GET['budget'] : 6000;
$selected_sort    = isset($_GET['sort']) ? $_GET['sort'] : 'best';
$selected_view    = isset($_GET['view']) && $_GET['view'] === 'list' ? 'list' : 'grid';

// --- Filter cars ---
$filtered_cars = array_values(array_filter($CARS, function($car) use ($selected_cat, $selected_engine, $selected_seats, $selected_budget, $selected_deal) {
    if ($selected_cat !== 'all' && $car['category'] !== $selected_cat) return false;
    if ($selected_engine !== 'all' && $car['engine'] !== $selected_engine) return false;
    if ($selected_seats !== 'all') {
        if ($selected_seats === '4' && $car['seats'] < 4) return false;
        if ($selected_seats === '5' && $car['seats'] !== 5) return false;
        if ($selected_seats === '7' && $car['seats'] < 7) return false;
    }
    if ($car['monthly'][$selected_deal] > $selected_budget) return false;
    return true;
}));

// --- Sort ---
usort($filtered_cars, function($a, $b) use ($selected_sort, $selected_deal) {
    switch ($selected_sort) {
        case 'price_low':  return $a['monthly'][$selected_deal] <=> $b['monthly'][$selected_deal];
        case 'price_high': return $b['monthly'][$selected_deal] <=> $a['monthly'][$selected_deal];
        case 'hp':         return $b['hp'] <=> $a['hp'];
        case 'stock':      return $b['stock'] <=> $a['stock'];
        case 'best':
        default:
            // best-value first, then verified, then by stock desc
            $ab = !empty($a['bestValue']) ? 1 : 0;
            $bb = !empty($b['bestValue']) ? 1 : 0;
            if ($ab !== $bb) return $bb - $ab;
            return $b['stock'] <=> $a['stock'];
    }
});

// --- Stats ---
$count = count($filtered_cars);
$prices = array_map(function($c) use ($selected_deal) { return $c['monthly'][$selected_deal]; }, $filtered_cars);
$min_price = $count ? min($prices) : 0;
$avg_price = $count ? (int)round(array_sum($prices) / $count) : 0;

$deal_labels = [
    'private'      => 'פרטי',
    'operational'  => 'תפעולי',
    'purchase'     => 'רכישה'
];

// Count cars per deal type (for sidebar) — all 12 cars carry all three price variants
$deal_counts = [
    'private'     => count($CARS),
    'operational' => count($CARS),
    'purchase'    => count($CARS),
];

// Build URL helper to keep the rest of the GET params when toggling one
function build_url($overrides = []) {
    $params = array_merge($_GET, $overrides);
    return 'grid.php?' . http_build_query($params);
}

// Handle AJAX Request Early
if ($is_ajax) {
    ob_start();
    if (empty($filtered_cars)): ?>
    <div class="empty-state">
        <h3>לא נמצאו רכבים העונים לסינון.</h3>
        <button onclick="window.location='grid.php'" class="btn btn-ghost" style="margin-top: 20px;">נקה הכל</button>
    </div>
    <?php else:
        foreach ($filtered_cars as $car):
            $price = $car['monthly'][$selected_deal];
            $stock_pct = (int)($car['stock'] * 100);
            $stock_class = $car['stock'] > 0.6 ? 'high' : ($car['stock'] > 0.3 ? 'mid' : 'low');
            $stock_label = $car['stock'] > 0.6 ? 'זמינות גבוהה' : ($car['stock'] > 0.3 ? 'זמינות בינונית' : 'מלאי מוגבל');
        ?>
        <div class="deal-card grid-card">
            <?php if (!empty($car['bestValue'])): ?>
            <div class="deal-ribbon"><span class="pulse"></span>העסקה המשתלמת</div>
            <?php endif; ?>
            <?php if (!empty($car['verified'])): ?>
            <div class="verify-chip"><?php echo icon('verify', 12); ?> מאומת</div>
            <?php endif; ?>

            <div class="deal-art">
                <?php echo render_car_frame($car, 'sm'); ?>
            </div>

            <div class="deal-body">
                <div class="deal-title-row">
                    <div>
                        <div class="deal-title"><?php echo $car['make'] . ' ' . $car['model']; ?></div>
                        <div class="deal-sub"><?php echo $car['trim'] . ' · ' . $CATEGORIES[$car['category']]['short']; ?></div>
                    </div>
                    <?php echo render_engine_chip($car['engine']); ?>
                </div>

                <div class="deal-specs">
                    <div class="spec">
                        <div class="k"><?php echo $car['seats']; ?></div>
                        <div class="l">מקומות</div>
                    </div>
                    <div class="spec">
                        <div class="k"><?php echo $car['accel']; ?><span class="u">s</span></div>
                        <div class="l">0—100</div>
                    </div>
                    <div class="spec">
                        <div class="k"><?php echo $car['hp']; ?></div>
                        <div class="l">כ״ס</div>
                    </div>
                </div>

                <div class="deal-availability">
                    <div class="bar bar-<?php echo $stock_class; ?>"><div style="width: <?php echo $stock_pct; ?>%;"></div></div>
                    <div class="row">
                        <span class="lbl"><?php echo $stock_label; ?></span>
                        <span class="val">מלאי</span>
                    </div>
                </div>

                <div class="deal-price-row">
                    <div class="deal-price-now">
                        <div class="k"><span class="sym">₪</span><?php echo number_format($price); ?></div>
                        <div class="l">לחודש · <?php echo $deal_labels[$selected_deal]; ?></div>
                    </div>
                </div>

                <div class="grid-card-cta">
                    <a href="compare.php?ids=<?php echo $car['id']; ?>" class="btn btn-outline btn-icon" title="השווה" aria-label="השווה"><?php echo icon('swap', 14); ?></a>
                    <a href="contact.php?car=<?php echo $car['id']; ?>" class="btn btn-outline grid-cta-main" data-offer-modal data-offer-source="grid-<?php echo $car['id']; ?>"><?php echo icon('sparkle', 14); ?> קבל הצעה</a>
                </div>
            </div>
        </div>
        <?php endforeach;
    endif;
    $html = ob_get_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'html' => $html,
        'count' => $count,
        'min_price' => number_format($min_price),
        'avg_price' => number_format($avg_price)
    ]);
    exit;
}
?>

<main class="page-enter container" style="padding: 40px 0 80px;">
    <!-- HERO -->
    <section class="grid-hero">
        <div>
            <div class="eyebrow" style="margin-bottom: 14px;">כלי השוואה · עדכון יומי</div>
            <h1>מצא את העסקה<br>ה<span class="grad">משתלמת ביותר</span><br>בענף הרכב הישראלי.</h1>
            <p>סנן לפי תקציב, קטגוריה ומנוע. בחר עד 3 רכבים להשוואה צד-בצד — כולל מחשבון תשלום חי ומחירי TCO ל-5 שנים.</p>
        </div>
        <div class="grid-hero-stats">
            <div class="stat"><div class="k"><?php echo $count; ?></div><div class="l">רכבים תואמים</div></div>
            <div class="stat"><div class="k"><span class="sym">₪</span><?php echo number_format($min_price); ?></div><div class="l">הזול ביותר / חודשי</div></div>
            <div class="stat"><div class="k"><span class="sym">₪</span><?php echo number_format($avg_price); ?></div><div class="l">ממוצע בתצוגה</div></div>
            <div class="stat"><div class="k">0<span class="sym">/3</span></div><div class="l">נבחרו להשוואה</div></div>
        </div>
    </section>

    <!-- TOOLBAR -->
    <div class="grid-toolbar">
        <form action="grid.php" method="GET" class="grid-toolbar-left">
            <?php
            // Preserve the other GET params
            foreach (['deal','cat','engine','seats','budget','view'] as $k) {
                if (isset($_GET[$k])) echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($_GET[$k]).'">';
            }
            ?>
            <label class="toolbar-select">
                <span class="toolbar-select-label">מיון:</span>
                <select name="sort" onchange="this.form.submit()">
                    <option value="best"       <?php echo $selected_sort==='best'?'selected':''; ?>>המשתלם ביותר</option>
                    <option value="price_low"  <?php echo $selected_sort==='price_low'?'selected':''; ?>>מחיר: מהנמוך לגבוה</option>
                    <option value="price_high" <?php echo $selected_sort==='price_high'?'selected':''; ?>>מחיר: מהגבוה לנמוך</option>
                    <option value="hp"         <?php echo $selected_sort==='hp'?'selected':''; ?>>הספק</option>
                    <option value="stock"      <?php echo $selected_sort==='stock'?'selected':''; ?>>זמינות במלאי</option>
                </select>
            </label>

            <div class="view-toggle" role="tablist">
                <a href="<?php echo build_url(['view'=>'grid']); ?>" class="<?php echo $selected_view==='grid'?'on':''; ?>" title="תצוגת גריד" aria-label="גריד"><?php echo icon('menu', 16); ?> גריד</a>
                <a href="<?php echo build_url(['view'=>'list']); ?>" class="<?php echo $selected_view==='list'?'on':''; ?>" title="תצוגת רשימה" aria-label="רשימה"><?php echo icon('menu', 16); ?> רשימה</a>
            </div>
        </form>

        <div class="grid-toolbar-right">
            מציג <strong><?php echo $count; ?> רכבים</strong> · ליסינג <?php echo $deal_labels[$selected_deal]; ?>
        </div>
    </div>

    <!-- LAYOUT: sidebar + cards -->
    <div class="grid-layout">
        <!-- SIDEBAR -->
        <aside class="filter-sidebar">
            <form action="grid.php" method="GET">
                <?php
                // Preserve sort + view across filter submissions
                if (isset($_GET['sort'])) echo '<input type="hidden" name="sort" value="'.htmlspecialchars($_GET['sort']).'">';
                if (isset($_GET['view'])) echo '<input type="hidden" name="view" value="'.htmlspecialchars($_GET['view']).'">';
                ?>
                <div class="filter-head">
                    <span><?php echo icon('menu', 16); ?> סינון</span>
                    <a href="grid.php" class="filter-reset">איפוס</a>
                </div>

                <!-- Deal type -->
                <div class="filter-group">
                    <div class="filter-label">סוג עסקה</div>
                    <div class="filter-radio-stack">
                        <?php foreach ($DEAL_TYPES as $dt): ?>
                        <label class="radio-row<?php echo $selected_deal===$dt['id']?' on':''; ?>">
                            <input type="radio" name="deal" value="<?php echo $dt['id']; ?>" <?php echo $selected_deal===$dt['id']?'checked':''; ?> onchange="this.form.submit()">
                            <span class="radio-name"><?php echo $dt['label']; ?></span>
                            <span class="radio-count"><?php echo $deal_counts[$dt['id']]; ?> יבואנים</span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Budget -->
                <div class="filter-group">
                    <div class="filter-label-row">
                        <span class="filter-label">תקציב / חודשי</span>
                        <span class="filter-value">עד ₪<?php echo number_format($selected_budget); ?></span>
                    </div>
                    <input type="range" name="budget" min="2000" max="6000" step="250" value="<?php echo $selected_budget; ?>" class="budget-range" oninput="this.nextElementSibling.firstElementChild.nextElementSibling.textContent='עד ₪'+Number(this.value).toLocaleString();" onchange="this.form.submit()">
                    <div class="budget-ticks"><span>₪6K</span><span>₪2K</span></div>
                </div>

                <!-- Categories -->
                <div class="filter-group">
                    <div class="filter-label">קטגוריות</div>
                    <div class="filter-pills">
                        <a href="<?php echo build_url(['cat'=>'all']); ?>" class="pill<?php echo $selected_cat==='all'?' on':''; ?>">הכל</a>
                        <?php foreach ($CATEGORIES as $id => $cat): ?>
                        <a href="<?php echo build_url(['cat'=>$id]); ?>" class="pill<?php echo $selected_cat===$id?' on':''; ?>"><?php echo $cat['short']; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Engine -->
                <div class="filter-group">
                    <div class="filter-label">מנוע</div>
                    <div class="filter-pills">
                        <a href="<?php echo build_url(['engine'=>'all']); ?>" class="pill<?php echo $selected_engine==='all'?' on':''; ?>">הכל</a>
                        <?php foreach ($ENGINE_TYPES as $id => $eng): ?>
                        <a href="<?php echo build_url(['engine'=>$id]); ?>" class="pill pill-engine<?php echo $selected_engine===$id?' on':''; ?>" style="--pill-accent: <?php echo $eng['color']; ?>;">
                            <span class="glyph"><?php echo $eng['glyph']; ?></span> <?php echo $eng['label']; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Seats -->
                <div class="filter-group">
                    <div class="filter-label">מספר מושבים</div>
                    <div class="filter-pills">
                        <?php foreach ([['all','הכל'],['4','+4'],['5','5'],['7','+7']] as $opt): list($val,$txt) = $opt; ?>
                        <a href="<?php echo build_url(['seats'=>$val]); ?>" class="pill<?php echo $selected_seats===$val?' on':''; ?>"><?php echo $txt; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        </aside>

        <!-- RESULTS -->
        <div class="grid-results<?php echo $selected_view==='list' ? ' view-list' : ''; ?>">
            <?php if (empty($filtered_cars)): ?>
            <div class="empty-state">
                <h3>לא נמצאו רכבים העונים לסינון.</h3>
                <a href="grid.php" class="btn btn-ghost" style="margin-top: 20px;">נקה הכל</a>
            </div>
            <?php else: ?>
                <?php foreach ($filtered_cars as $car):
                    $price = $car['monthly'][$selected_deal];
                    $stock_pct = (int)($car['stock'] * 100);
                    $stock_class = $car['stock'] > 0.6 ? 'high' : ($car['stock'] > 0.3 ? 'mid' : 'low');
                    $stock_label = $car['stock'] > 0.6 ? 'זמינות גבוהה' : ($car['stock'] > 0.3 ? 'זמינות בינונית' : 'מלאי מוגבל');
                ?>
                <div class="deal-card grid-card">
                    <?php if (!empty($car['bestValue'])): ?>
                    <div class="deal-ribbon"><span class="pulse"></span>העסקה המשתלמת</div>
                    <?php endif; ?>
                    <?php if (!empty($car['verified'])): ?>
                    <div class="verify-chip"><?php echo icon('verify', 12); ?> מאומת</div>
                    <?php endif; ?>

                    <div class="deal-art">
                        <?php echo render_car_frame($car, 'sm'); ?>
                    </div>

                    <div class="deal-body">
                        <div class="deal-title-row">
                            <div>
                                <div class="deal-title"><?php echo $car['make'] . ' ' . $car['model']; ?></div>
                                <div class="deal-sub"><?php echo $car['trim'] . ' · ' . $CATEGORIES[$car['category']]['short']; ?></div>
                            </div>
                            <?php echo render_engine_chip($car['engine']); ?>
                        </div>

                        <div class="deal-specs">
                            <div class="spec">
                                <div class="k"><?php echo $car['seats']; ?></div>
                                <div class="l">מקומות</div>
                            </div>
                            <div class="spec">
                                <div class="k"><?php echo $car['accel']; ?><span class="u">s</span></div>
                                <div class="l">0—100</div>
                            </div>
                            <div class="spec">
                                <div class="k"><?php echo $car['hp']; ?></div>
                                <div class="l">כ״ס</div>
                            </div>
                        </div>

                        <div class="deal-availability">
                            <div class="bar bar-<?php echo $stock_class; ?>"><div style="width: <?php echo $stock_pct; ?>%;"></div></div>
                            <div class="row">
                                <span class="lbl"><?php echo $stock_label; ?></span>
                                <span class="val">מלאי</span>
                            </div>
                        </div>

                        <div class="deal-price-row">
                            <div class="deal-price-now">
                                <div class="k"><span class="sym">₪</span><?php echo number_format($price); ?></div>
                                <div class="l">לחודש · <?php echo $deal_labels[$selected_deal]; ?></div>
                            </div>
                        </div>

                        <div class="grid-card-cta">
                            <a href="compare.php?ids=<?php echo $car['id']; ?>" class="btn btn-outline btn-icon" title="השווה רכב" aria-label="השווה רכב"><?php echo icon('swap', 14); ?> <span class="btn-icon-label">השווה</span></a>
                            <a href="contact.php?car=<?php echo $car['id']; ?>" class="btn btn-outline grid-cta-main" data-offer-modal data-offer-source="grid-<?php echo $car['id']; ?>"><?php echo icon('sparkle', 14); ?> קבל הצעה</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
