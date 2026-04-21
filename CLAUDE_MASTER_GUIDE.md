# CLAUDE_MASTER_GUIDE

> מפה טכנית מלאה של פרויקט mcar. קרא לפני עבודה. עבור הנחיות קצרות ראה [CLAUDE.md](CLAUDE.md).

---

## 1. ארכיטקטורה

אתר **PHP סטטי** בעברית/RTL לליסינג/השוואת רכבים. ללא DB, ללא composer, ללא npm, ללא build.

```
page.php
  └─ require_once 'includes/header.php'
       ├─ require_once 'includes/config.php'    ← כל הנתונים ($CARS, $PACKAGES, ...)
       └─ require_once 'includes/functions.php' ← פונקציות עזר
       ├─ [FOUC-prevention inline script]        ← קורא localStorage לפני paint
       ├─ <head> with dynamic $page_title / $page_description
       └─ <header> + open <main>
  └─ [page-specific logic + HTML]
  └─ require_once 'includes/footer.php'
       ├─ <footer> site-footer
       ├─ <dialog id="tweaksModal"> Tweaks panel (גלובלי)
       ├─ <dialog id="offerModal"> VIP offer modal (גלובלי)
       └─ <script src="assets/js/main.js">
```

## 2. מפת קבצים

### דפים (root)

| דף | תפקיד | גלובלים שבשימוש |
|----|-------|-----------------|
| [index.php](index.php) | דף הבית: hero + search + חבילות + יתרונות + המלצות + שותפים + סטטיסטיקות | `$CARS`, `$CATEGORIES`, `$ENGINE_TYPES`, `$PACKAGES`, `$TESTIMONIALS`, `$STATS`, `$PARTNERS` |
| [about.php](about.php) | אודות, story, timeline (5 עידנים), team (4 מייסדים) | local `$TIMELINE`, `$TEAM` |
| [grid.php](grid.php) | קטלוג עם filters (deal/cat/engine/seats/budget/sort/view) + sidebar + top stats. **תומך גם ב-`?ajax=1`** — מחזיר JSON עם `html`+`count`+`min_price`+`avg_price` במקום HTML מלא | `$CARS`, `$CATEGORIES`, `$ENGINE_TYPES`, `$DEAL_TYPES` |
| [compare.php](compare.php) | השוואה side-by-side (`?ids=a,b,c`). רכבים עם המחיר הנמוך מקבלים border accent + price accent | `$CARS` |
| [contact.php](contact.php) | צור קשר: hero עם 3 stats + 3 contact methods · form · sidebar עם office info + FAQ | `$DEAL_TYPES`, `$FAQ`, local `$OFFICE_INFO` |
| [operational.php](operational.php) | ליסינג תפעולי: hero + 4 stats + 6 יתרונות + טבלת השוואה + 4 שלבים + 3 case studies + CTA | local `$OP_STATS`, `$OP_BENEFITS`, `$OP_STEPS`, `$OP_COMPARE`, `$OP_CASES` |
| [careers.php](careers.php) | 6 משרות פתוחות (mailto) + 6 הטבות + hero stats | local `$JOBS`, `$PERKS` |
| [blog.php](blog.php) | 6 מאמרים (1 featured + 5 ברשימה) + טאבי קטגוריות + newsletter signup | local `$POSTS`, `$CATS` |
| [terms.php](terms.php) | 9 סקשנים משפטיים + TOC sticky | local `$SECTIONS` |
| [privacy.php](privacy.php) | 10 סקשנים (GDPR + חוק הגנת פרטיות) + TOC | local `$SECTIONS` |
| [accessibility.php](accessibility.php) | 6 פיצ׳רי נגישות + 4 סקשנים טקסט | local `$FEATURES` |
| [404.php](404.php) | דף שגיאה מינימלי | — |

### includes/

| קובץ | תוכן |
|------|------|
| [includes/config.php](includes/config.php) | 9 arrays גלובליים + 4 constants (`SITE_NAME`, `SITE_TAGLINE`, `BASE_URL`, `ASSET_VERSION`) |
| [includes/functions.php](includes/functions.php) | 7 פונקציות עזר |
| [includes/header.php](includes/header.php) | `<head>` דינמי (page_title/description) + FOUC script + navbar + logo-mark + Tweaks gear + mobile toggle + scroll-progress |
| [includes/footer.php](includes/footer.php) | site-footer (4 עמודות) + Tweaks modal + Offer modal + scripts |

### assets/

| קובץ | תוכן |
|------|------|
| [assets/css/style.css](assets/css/style.css) | design tokens + dark mode + 3 accents + 40+ קומפוננטות |
| [assets/js/main.js](assets/js/main.js) | Offer modal, Tweaks panel, mobile nav, scroll progress, header scroll effect |
| `assets/img/` | ריק. תמונות הן placehold.co / picsum.photos |

### ארכיון (לא לערוך)

`mcar.html` (1.2MB גרסה ישנה), `src/*.jsx` (פרוטוטיפ React), `debug/`, `scraps/`, `uploads/` — לא חלק מהאתר החי.

## 3. גלובלים ב-config.php

| משתנה | שדות | שימוש |
|-------|------|-------|
| `$CATEGORIES` | `label`, `short` | `suv`, `sedan`, `ev-family` |
| `$ENGINE_TYPES` | `label`, `color`, `glyph` | `electric`, `hybrid`, `gasoline`, `diesel` |
| `$CARS` | 18 שדות (ראה למטה) | 12 רכבים |
| `$PARTNERS` | מערך strings | 12 שמות מותג |
| `$DEAL_TYPES` | `id`, `label`, `sub` | `private`, `operational`, `purchase` |
| `$PACKAGES` | `id`, `title`, `sub`, `icon`, `price`, `pitch`, `features`, `km`, `fuel`, `featured` | 6 חבילות בדף הבית |
| `$TESTIMONIALS` | `name`, `role`, `quote` | 3 ציטוטי לקוחות |
| `$STATS` | `k`, `label` | 4 סטטיסטיקות (דף הבית) |
| `$FAQ` | `q`, `a` | 4 שאלות נפוצות (דף הבית + contact) |

### סכמת `$CARS`

```php
[
    'id'          => 'nova-prime',          // kebab-case ייחודי
    'make'        => 'Nova',
    'model'       => 'Prime 7',
    'trim'        => 'Signature',
    'category'    => 'suv',                 // מפתח ב-$CATEGORIES
    'engine'      => 'electric',            // מפתח ב-$ENGINE_TYPES
    'hp'          => 408,
    'consumption' => '510 ק״מ טווח',        // string חופשי
    'seats'       => 7,
    'accel'       => '5.3',                 // string, שניות 0-100
    'monthly' => [                          // 3 מפתחות חובה
        'private'     => 4690,
        'operational' => 4190,
        'purchase'    => 329000
    ],
    'stock'       => 0.34,                  // 0–1 (אחוז זמינות)
    'bestValue'   => true,                  // תגית "העסקה המשתלמת"
    'verified'    => true,                  // תגית "מאומת"
    'features'    => ['7 מושבים', ...],
    'warranty'    => '5 שנים / 150,000 ק״מ',
    'delivery'    => 'זמין מיידית'
]
```

## 4. פונקציות ב-functions.php

| פונקציה | חתימה | שימוש |
|---------|-------|-------|
| `format_ils` | `format_ils($amount)` | `"₪4,690"` |
| `icon` | `icon($name, $size=20, $stroke=1.6)` | SVG inline. 23 שמות (ראה למטה) |
| `render_engine_chip` | `render_engine_chip($engine_id)` | תגית מנוע צבעונית |
| `render_car_frame` | `render_car_frame($car, $size='lg')` | SVG רכב במסגרת. size: `lg` / `sm` |
| `car_image_url` | `car_image_url($car, $w, $h)` | placehold.co URL בצבע המנוע |
| `scene_image_url` | `scene_image_url($seed, $w, $h)` | picsum.photos URL יציב |
| `active_class` | `active_class($page)` | `"active"` אם בדף הנוכחי |

### אייקונים זמינים ב-icon()

```
search, menu, x, check, arrow, chev, chevD,
shield, bolt, calendar, drop, leaf, users,
mail, phone, sparkle, swap, verify, clock,
whatsapp, facebook, instagram, linkedin
```

## 5. Design System (CSS variables)

### Accent (מוחלף ע״י Tweaks)

```css
[data-accent="teal"]   { --accent: #0f766e; --accent-2: #14b8a6; }
[data-accent="violet"] { --accent: #5b21b6; --accent-2: #7c3aed; }
[data-accent="navy"]   { --accent: #002366; --accent-2: #0a2f7a; }
```

### Mode

```css
[data-mode="light"]   /* default */
[data-mode="dark"]    { --bg: #060a1a; --surface: #0e1533; --ink: #eaf0ff; ... }
```

### Radius

```css
[data-radius="regular"] { --r-sm:6px; --r-md:10px; --r-lg:14px; --r-xl:18px; }
[data-radius="large"]   { --r-sm:10px; --r-md:16px; --r-lg:22px; --r-xl:30px; --r-2xl:40px; } /* default */
```

### Spacing / Typography

- Body: 17px, line-height 1.6
- Font display: Heebo + Assistant + JetBrains Mono (Google Fonts)
- Direction: RTL תמיד

## 6. Modals גלובליים

### Tweaks (⚙ בהדר)

- מזהה: `#tweaksModal`, `<dialog>`
- טריגר: `#tweaks-toggle`
- אפשרויות: מצב (Dark/Light) · צבע (teal/violet/navy) · פינות (regular/large)
- שמירה: `localStorage.mcar_tweaks` = `{mode, accent, radius}`
- טעינה: FOUC script ב-`<head>` [includes/header.php:15-24](includes/header.php#L15)
- Apply: JS `applyTweaks(t)` ב-[assets/js/main.js:108](assets/js/main.js#L108)

### Offer Modal (VIP)

- מזהה: `#offerModal`, `<dialog>`
- טריגר: כל element עם `[data-offer-modal]` (30+ באתר)
- שומר את href של הטריגר כ-form action — מעביר `?car=/?pkg=/?type=` ל-contact.php
- שדות: name + phone · hidden source (למשל `pkg-family`, `grid-nova-prime`, `op-final`)

## 7. רכיבי UI עיקריים

| מחלקה | תיאור |
|-------|-------|
| `.deal-card` | כרטיס עסקה עם `.deal-ribbon`, `.deal-stock`, `.deal-art`, `.deal-body`, `.deal-footer-note` |
| `.car-frame` | מסגרת לאיור הרכב (`data-size="lg"/"sm"`) |
| `.pkg-card` | כרטיס חבילה בדף הבית |
| `.search-shell` | ה-pill הארוך של חיפוש — tabs floating מעל ימין + 4 cells + כפתור |
| `.filter-sidebar` | sidebar סינון ב-grid |
| `.grid-card` | variation מוקטנת של `.deal-card` לקטלוג |
| `.op-compare-table` | טבלת השוואה 3x7 בליסינג תפעולי |
| `.op-case` | כרטיס case study |
| `.legal-layout` | `[TOC sticky] [body]` בדפים משפטיים |
| `.page-hero` | כותרת סטנדרטית לדפים משניים |

## 8. Gotchas מלאים

1. **`footer.php` משתמש ב-`$GLOBALS['CATEGORIES']`**. שינוי שם המשתנה ב-config ישבור את הפוטר.
2. **`$CARS` ב-callbacks דורש capture**: `array_filter($CARS, function($c) use ($CARS, $x) { ... })`.
3. **ב-dark mode `var(--ink)` הוא בהיר**. אל תשתמש בו כ-background עם טקסט לבן. השתמש ב-`var(--navy-deep)` (תמיד כהה).
4. **`stock` הוא 0–1 (אחוז)**, לא מספר מלאי. `0.34` = "34% זמין".
5. **`active_class` מטפל במקרה מיוחד** ל-`index.php` — גם `'home'` וגם `'index'` עובדים.
6. **אין cache busting אוטומטי** מעבר ל-`?v=ASSET_VERSION`. לאחר עריכת CSS/JS — הגדל את הקבוע.
7. **כפתורי social בפוטר**: `icon('facebook'), icon('instagram'), icon('linkedin')` **כן** מוגדרים ב-functions.php (נוספו בסשן קודם).
8. **FOUC script חייב להיות לפני ה-`<link rel="stylesheet">`** — הוא מגדיר `data-mode`/`data-accent` לפני שה-CSS נטען.
9. **`<dialog>` API**: השתמש ב-`.showModal()` / `.close()`. `open` attribute הוא fallback.
10. **ה-grid של ה-search** הוא `1fr 1fr 1fr 1fr auto` בסדר **DOM**: 4 cells + button. ב-RTL זה מתהפך ויזואלית (button בקצה השמאלי).

## 9. ניווט (query params)

| URL | תוצאה |
|-----|-------|
| `grid.php` | כל הרכבים, sort=best, deal=private |
| `grid.php?cat=suv&engine=electric` | פילטר כפול |
| `grid.php?deal=operational&budget=3500` | מחירים בליסינג תפעולי עד ₪3,500 |
| `grid.php?sort=price_low&view=list` | מיון מחיר עולה, תצוגת רשימה |
| `compare.php?ids=aurora-gt,nova-prime,pulse-gts` | השוואת 3 רכבים |
| `contact.php?car=nova-prime` | טופס עם preset לרכב |
| `contact.php?pkg=family` | טופס עם preset לחבילה |
| `contact.php?type=business` | טופס עסקי |

אין `.htaccess`. URLs חייבים סיומת `.php`.

## 10. הרצה + אימות

```bash
# Start (PHP 7.4+)
/c/xampp/php/php.exe -S localhost:8765 -t c:/mcar

# Lint all
for f in *.php includes/*.php; do /c/xampp/php/php.exe -l "$f"; done

# Smoke-test all pages
for p in "" about.php grid.php compare.php contact.php operational.php \
         careers.php blog.php terms.php privacy.php accessibility.php; do
  curl -s -o /dev/null -w "/$p → %{http_code}\n" "http://localhost:8765/$p"
done
```

## 11. היסטוריית ASSET_VERSION

| גרסה | מה נוסף |
|------|---------|
| 1.0.0 | בסיס |
| 1.1.0 | fix social icons + search form wrapper + radio pills + mobile nav + typo |
| 1.2.0 | הוספת תמונות לכל דף + הגדלת טקסט גלובלי |
| 1.3.0 | 6 חבילות + 3 features + 3 testimonials בדף הבית |
| 1.4.0 | SVG car illustration + availability bar + deal-footer-note + search tabs |
| 1.5.0 | רענון grid.php: top stats + toolbar + sidebar + grid cards משודרגים |
| 1.6.0 | operational.php מלא: hero + 6 benefits + comparison + cases + CTA |
| 1.7.0 | Offer modal גלובלי + 30 triggers |
| 1.8.0 | שיפור stats-strip בדף הבית (גדולות, prefix `+`) |
| 2.0.0 | Tweaks panel + 5 דפים חדשים (careers, blog, terms, privacy, accessibility) |
| 2.1.0 | תיקוני dark mode: `var(--ink)` → `var(--navy-deep)` ב-4 מקומות |
| 2.2.0 | Search cells מחדש: 4 pills נפרדות עם labels accent |
| 2.3.0 | Search shell חזר ל-pill אחד עם dividers פנימיים (לפי צילום) |
