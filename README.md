# mcar

פורטל השוואת ליסינג ישראלי — אתר PHP סטטי, ללא DB, ללא build step.

## הרצה מקומית

```bash
# Windows (XAMPP נתקן ב-C:\xampp)
C:\xampp\php\php.exe -S localhost:8765 -t C:\mcar

# Linux / macOS
php -S localhost:8765 -t /path/to/mcar
```

פתח [http://localhost:8765](http://localhost:8765). אין build, אין composer, אין npm — רענון דפדפן מספיק.

## דרישות

- **PHP 7.4+** (משתמש ב-`??=`, `fn() =>`, typed properties לא נחוצים)
- אפס הרחבות PHP חיצוניות (אין PDO, אין cURL)
- אפס dependencies

## מבנה

```
c:\mcar\
├─ index.php           ← דף הבית (hero, חבילות, יתרונות, המלצות, שותפים)
├─ about.php           ← אודות, timeline, צוות
├─ grid.php            ← קטלוג 12 רכבים עם sidebar סינון
├─ compare.php         ← השוואה side-by-side
├─ contact.php         ← צור קשר + טופס
├─ operational.php     ← ליסינג תפעולי (hero, 6 יתרונות, טבלת השוואה, case studies)
├─ careers.php         ← קריירה + 6 משרות פתוחות
├─ blog.php            ← בלוג + 6 מאמרים
├─ terms.php           ← תנאי שימוש (9 סקשנים)
├─ privacy.php         ← מדיניות פרטיות (10 סקשנים, GDPR)
├─ accessibility.php   ← הצהרת נגישות (ת״י 5568 AA)
├─ 404.php             ← דף שגיאה
│
├─ includes/
│  ├─ config.php       ← כל הנתונים ($CARS, $PACKAGES, $STATS, …)
│  ├─ functions.php    ← פונקציות עזר (icon, format_ils, render_car_frame, …)
│  ├─ header.php       ← <head>, navbar, Tweaks gear, FOUC script
│  └─ footer.php       ← site-footer, Tweaks modal, Offer modal, scripts
│
├─ assets/
│  ├─ css/style.css    ← design system + כל הרכיבים
│  ├─ js/main.js       ← Offer modal, Tweaks panel, mobile nav, scroll progress
│  └─ img/             ← ריק (תמונות מגיעות מ-placehold.co / picsum.photos)
│
├─ CLAUDE.md                   ← הנחיות תמציתיות ל-AI assistant
├─ CLAUDE_MASTER_GUIDE.md     ← מפה טכנית מלאה
└─ README.md                   ← הקובץ הזה
```

## עדכון נתונים

כל הנתונים מאוחסנים כ-PHP arrays ב-[includes/config.php](includes/config.php).

### הוספת רכב חדש

ערוך `$CARS` ב-[includes/config.php](includes/config.php):

```php
[
    'id'          => 'my-car',              // kebab-case, ייחודי
    'make'        => 'MyBrand',
    'model'       => 'Cool 5',
    'trim'        => 'Sport',
    'category'    => 'suv',                 // חייב להיות ב-$CATEGORIES
    'engine'      => 'electric',            // חייב להיות ב-$ENGINE_TYPES
    'hp'          => 300,
    'consumption' => '450 ק״מ טווח',
    'seats'       => 5,
    'accel'       => '5.5',
    'monthly'     => [                      // כל 3 שדות חובה
        'private'     => 3500,
        'operational' => 3100,
        'purchase'    => 230000
    ],
    'stock'       => 0.65,                  // 0–1 (אחוז זמינות)
    'bestValue'   => false,
    'verified'    => true,
    'features'    => ['7 מושבים', 'ADAS'],
    'warranty'    => '3 שנים',
    'delivery'    => 'עד 21 ימים'
],
```

אין cache — רענון דפדפן מראה את הרכב מיד.

### עדכון מחיר

חפש את ה-`id` ב-`$CARS` ושנה את השדה ב-`monthly`. זה הכל.

### עדכון ASSET_VERSION

אם ערכת CSS/JS ולא רואה שינוי בדפדפן — הגדל את `ASSET_VERSION` ב-[includes/config.php:13](includes/config.php#L13) (למשל `2.3.0` → `2.4.0`).

## פיצ׳רים בולטים

- **Tweaks panel** — כפתור גלגל שיניים בהדר פותח פאנל שמאפשר למשתמש להחליף: Dark/Light · צבע ראשי (teal/violet/navy) · רדיוס פינות. נשמר ב-localStorage, נטען לפני ה-paint (אין FOUC)
- **Offer modal** — מודל VIP גלובלי שמופעל מכל כפתור `data-offer-modal` (30+ באתר). שומר את ה-`?car=/?pkg=/?type=` context
- **איור רכב SVG** — פונקציה `render_car_frame($car, $size)` מייצרת SVG מעוצב עם צבע לפי סוג המנוע
- **Tweaks החלפה חיה** — כל העיצוב מבוסס CSS variables שמוחלפות לפי `data-mode` ו-`data-accent` על `<html>`
- **נגישות** — ניווט במקלדת, ARIA labels, focus-visible, `prefers-reduced-motion`
- **RTL מלא** — כיוון עברית/ימין-לשמאל בכל האתר

## תרומה ועזרה

- פנייה משפטית: `legal@mcar.co.il`
- פנייה בפרטיות: `privacy@mcar.co.il`
- נגישות: `access@mcar.co.il`
- קריירה: `jobs@mcar.co.il`
- כללי: `vip@mcar.co.il`

© mcar Israel.
