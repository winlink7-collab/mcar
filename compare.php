<?php
require_once 'includes/header.php';

$ids = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];
$selected_cars = array_filter($CARS, function($car) use ($ids) {
    return in_array($car['id'], $ids);
});

if (count($selected_cars) < 2):
?>
<main class="page-enter container" style="padding: 100px 0; text-align: center;">
    <div style="width: 80px; height: 80px; margin: 0 auto 24px; border-radius: 24px; background: var(--accent-soft); color: var(--accent); display: grid; place-items: center;">
        <?php echo icon('swap', 32); ?>
    </div>
    <h2 style="font-size: 32px; font-weight: 900; margin-bottom: 20px;">מה נשווה?</h2>
    <p style="color: var(--ink-3); font-size: 16px; max-width: 50ch; margin: 0 auto 30px;">בחר לפחות 2 רכבים מהקטלוג כדי לראות השוואה מפורטת הכוללת ביצועים, אבזור ועלות בעלות מלאה.</p>
    <a href="grid.php" class="btn btn-primary">חזור לקטלוג הרכבים</a>
</main>
<?php else: 
    // Logic for comparison
    $prices = array_map(function($c) { return $c['monthly']['private']; }, $selected_cars);
    $min_price = min($prices);
?>

<main class="page-enter" style="padding-bottom: 80px;">
    <!-- HERO -->
    <section style="padding: 48px 0 32px; border-bottom: 1px solid var(--hairline); margin-bottom: 32px; background: radial-gradient(ellipse at 90% 10%, var(--accent-soft), transparent 60%);">
        <div class="container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: end;">
            <div>
                <div class="eyebrow" style="margin-bottom: 14px;">השוואה מלאה · <?php echo count($selected_cars); ?> רכבים</div>
                <h1 style="font-size: clamp(40px, 5vw, 68px); font-weight: 900; line-height: 0.98; letter-spacing: -0.04em;">רק <span class="grad" style="background: linear-gradient(135deg, var(--accent), var(--accent-2)); -webkit-background-clip: text; background-clip: text; color: transparent;">מספרים</span>.</h1>
                <p style="color: var(--ink-2); font-size: 16px; line-height: 1.6; margin-top: 18px;">כל הנתונים מעודכנים בזמן אמת. המדדים הטובים ביותר מסומנים אוטומטית.</p>
            </div>
            
            <div style="background: var(--navy-deep); color: white; padding: 24px; border-radius: var(--r-xl); position: relative; overflow: hidden;">
                <div style="position: relative; z-index: 1;">
                   <div style="font-size: 13px; font-weight: 700; color: var(--accent); margin-bottom: 8px;">העסקה המשתלמת</div>
                   <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 4px;">השוואה פעילה</h3>
                   <div style="font-size: 32px; font-weight: 900;"><?php echo format_ils($min_price); ?><span style="font-size: 14px; opacity: 0.6; margin-right: 6px;">/ חודש</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="container">
        <div style="display: grid; grid-template-columns: repeat(<?php echo count($selected_cars); ?>, 1fr); gap: 20px;">
            <?php 
            // Calculate TCO for each car (5 years = 60 months)
            $tco_data = [];
            $max_tco = 1; // Prevent div by zero
            foreach ($selected_cars as $c) {
                $t = $c['monthly']['private'] * 60;
                $tco_data[$c['id']] = $t;
                if ($t > $max_tco) $max_tco = $t;
            }
            $min_tco = min($tco_data);
            
            foreach ($selected_cars as $car): 
                $is_best = $car['monthly']['private'] === $min_price;
                $my_tco = $tco_data[$car['id']]; 
                $tco_pct = ($my_tco / $max_tco) * 100;
                $is_best_tco = $my_tco === $min_tco;
            ?>
            <div style="background: var(--surface); border: 2px solid <?php echo $is_best ? 'var(--accent)' : 'var(--surface-border)'; ?>; border-radius: var(--r-xl); overflow: hidden; box-shadow: var(--shadow-1);">
                <img src="<?php echo car_image_url($car, 600, 340); ?>"
                     alt="<?php echo $car['make'] . ' ' . $car['model']; ?>"
                     loading="lazy"
                     style="width: 100%; height: 180px; object-fit: cover; display: block;">
                <div style="padding: 24px; border-bottom: 1px solid var(--hairline);">
                    <h3 style="font-size: 22px; font-weight: 800;"><?php echo $car['make'] . ' ' . $car['model']; ?></h3>
                    <div style="font-size: 14px; color: var(--ink-3);"><?php echo $car['trim']; ?></div>
                    <div style="margin-top: 12px;"><?php echo render_engine_chip($car['engine']); ?></div>
                </div>
                
                <div style="padding: 24px; background: var(--bg-2); border-bottom: 1px solid var(--hairline);">
                    <div style="font-size: 13px; color: var(--ink-3); margin-bottom: 6px;">תשלום חודשי</div>
                    <div style="font-size: 28px; font-weight: 900; color: <?php echo $is_best ? 'var(--accent)' : 'var(--ink)'; ?>;"><?php echo format_ils($car['monthly']['private']); ?></div>
                </div>
                
                <div style="padding: 24px; display: grid; gap: 16px;">
                    <div>
                        <div style="font-size: 13px; color: var(--ink-3);">הספק</div>
                        <div style="font-weight: 800;"><?php echo $car['hp']; ?> כ״ס</div>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--ink-3);">0-100</div>
                        <div style="font-weight: 800;"><?php echo $car['accel']; ?> שניות</div>
                    </div>
                    <div>
                        <div style="font-size: 13px; color: var(--ink-3);">מקומות</div>
                        <div style="font-weight: 800;"><?php echo $car['seats']; ?></div>
                    </div>
                </div>
                
                <!-- TCO Chart Section -->
                <div class="tco-chart" style="padding: 24px; border-top: 1px solid var(--hairline); background: var(--bg-2);">
                    <div style="font-size: 13px; font-weight: 700; color: var(--ink); margin-bottom: 16px; display: flex; justify-content: space-between; align-items: baseline;">
                        <span>TCO (5 שנים)</span>
                        <span style="font-family: var(--font-display); font-size: 18px; font-weight: 800; color: <?php echo $is_best_tco ? '#10b981' : 'var(--ink)'; ?>;">₪<?php echo number_format($my_tco); ?></span>
                    </div>
                    <div class="tco-bar-wrap">
                        <div class="tco-bar-fill <?php echo $is_best_tco ? 'winner' : ''; ?>" style="width: <?php echo $tco_pct; ?>%;"></div>
                    </div>
                    <?php if ($is_best_tco): ?>
                    <div style="font-size: 11px; color: #059669; font-weight: 600; margin-top: 8px;">המשתלם ביותר לטווח ארוך</div>
                    <?php endif; ?>
                </div>

                <div style="padding: 24px; border-top: 1px solid var(--hairline);">
                    <div style="font-size: 13px; color: var(--ink-3); margin-bottom: 12px;">אבזור בולט</div>
                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 15px; display: grid; gap: 8px;">
                        <?php foreach (array_slice($car['features'], 0, 3) as $f): ?>
                        <li style="display: flex; gap: 8px;"><?php echo icon('check', 14); ?> <?php echo $f; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div style="padding: 20px; text-align: center;">
                    <a href="contact.php?car=<?php echo $car['id']; ?>" class="btn <?php echo $is_best ? 'btn-primary' : 'btn-outline'; ?>" style="width: 100%; justify-content: center;" data-offer-modal data-offer-source="compare-<?php echo $car['id']; ?>">בחר רכב זה</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
