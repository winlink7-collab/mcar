# CLAUDE.md — הנחיות פרויקט mcar

קובץ זה נטען אוטומטית בתחילת כל סשן. קרא אותו ראשון. עבור ארכיטקטורה מפורטת ראה [CLAUDE_MASTER_GUIDE.md](CLAUDE_MASTER_GUIDE.md).

## כללי זהב

1. **שפה**: כתוב הודעות למשתמש (סער) **בעברית**. שמות משתנים, שמות קבצים, פקודות shell — באנגלית.
2. **אל תצטרף לקבצים בארכיון**: `src/`, `mcar.html`, `debug/`, `scraps/`, `uploads/` — אלה לא חלק מהאתר החי. אל תערוך אותם ואל תצטט מהם כדוגמה.
3. **כל נתון ב-`includes/config.php`**: אין DB, אין API, אין JSON. עדכוני נתונים = עריכת PHP arrays.
4. **אל תוסיף תלויות**: אין composer, אין npm. הכל PHP + HTML + CSS + JS vanilla. שמור על זה.
5. **אחרי עריכת CSS/JS: הגדל `ASSET_VERSION`** ב-[includes/config.php](includes/config.php). אחרת הדפדפן יציג גרסה מ-cache.

## הרצה לבדיקה

PHP מותקן בנתיב `/c/xampp/php/php.exe`. השרת של פרויקט מורץ דרך:

```bash
/c/xampp/php/php.exe -S localhost:8765 -t c:/mcar
```

לפני סיום משימה: הרץ `curl -s -o /dev/null -w "%{http_code}" http://localhost:8765/PAGE.php` לכל דף שנגעת בו, + `php -l` על כל קובץ PHP שערכת.

## קונבנציות

### מתי להשתמש ב-render_car_frame() במקום <img>

הפרויקט משתמש ב-SVG inline עם `render_car_frame($car, $size)` — לא תמונות אמיתיות. אל תחליף את ה-SVG ב-`<img>` אלא אם המשתמש ביקש זאת מפורשות.

### הוספת כפתור "קבל הצעה" חדש

תמיד הוסף `data-offer-modal data-offer-source="..."` על ה-`<a href="contact.php?…">`. ה-JS ב-[assets/js/main.js](assets/js/main.js) יירט את הקליק ויפתח את ה-modal עם ההקשר הנכון. ה-href משמש כ-fallback אם JS מבוטל.

### עיצוב דף חדש

השתמש במחלקות קיימות:
- `<section class="page-hero">` לכותרת העמוד (יש CSS מוכן: [assets/css/style.css](assets/css/style.css) — חפש `.page-hero`)
- `<h1>` עם `<span class="grad">` לכותרת הצבעונית בגרדיאנט
- `<div class="eyebrow">` לתווית העליונה הקטנה
- `<div class="sec-head">` לכותרת סקשן

### דפים משפטיים (terms/privacy/accessibility)

השתמש ב-`<main class="legal-page">` + `$SECTIONS` array + לופ שמייצר `<section class="legal-sec">`. הסייד-בר עם TOC הוא class `.legal-toc`.

### meta tags לכל דף

הגדר `$page_title` ו-`$page_description` **לפני** `require_once 'includes/header.php'`. ה-header ישתמש בהם אוטומטית.

```php
<?php
$page_title = 'שם הדף';
$page_description = 'תיאור קצר לגוגל.';
require_once 'includes/header.php';
?>
```

## Gotchas מוכרים

- `footer.php` מסתמך על `$GLOBALS['CATEGORIES']`. אל תשנה את שם המשתנה `$CATEGORIES` ב-config.
- `$CARS` ב-callbacks דורש `use ($CARS)` או `global $CARS` — לא זמין אוטומטית.
- ב-dark mode `var(--ink)` הופך ל**בהיר**. אל תשתמש בו כ-background עם טקסט לבן — השתמש ב-`var(--navy-deep)` במקום (תמיד כהה).
- `stock` ברכב הוא 0–1 (אחוז), לא מספר מלאי.
- `active_class('home')` ≠ `active_class('index')` בדף הבית — שתיהן עובדות (יש case special).
- המודלים (Tweaks + Offer) הם `<dialog>` אמיתי — משתמשים ב-`.showModal()` ו-`.close()`, לא ב-`.hidden`.

## שירותי תמונות חיצוניים

- `car_image_url($car, w, h)` — placehold.co עם צבע המנוע
- `scene_image_url($seed, w, h)` — picsum.photos לפי seed יציב

שניהם דורשים אינטרנט. אל תעצור שרת לוקאלי כשמשתמש בודק תמונות.

## מה לא לעשות (אלא אם התבקש מפורשות)

- לא להחליף את ה-SVG של הרכב בתמונה אמיתית
- לא להוסיף DB / composer / npm
- לא לערוך קבצי ארכיון (`src/`, `mcar.html`, `debug/`, `scraps/`, `uploads/`)
- לא למחוק את `$TESTIMONIALS`, `$STATS`, `$PARTNERS` — גם אם נראה שלא בשימוש (הם משמשים בדפים שונים)
- לא לשנות את מבנה ה-`$CARS` (הוספת/הסרת שדות) — הרבה מקומות בקוד מניחים את הסכמה הנוכחית

## זרימת עבודה מומלצת

1. קרא את CLAUDE_MASTER_GUIDE.md למפה מלאה
2. הבן את המשימה
3. זהה את הקבצים הרלוונטיים (רוב שינויי עיצוב = CSS + PHP אחד; נתונים = config.php)
4. בצע את השינוי
5. הגדל ASSET_VERSION אם שינית CSS/JS
6. אמת: `php -l` + curl ל-`/page.php?params` + grep לתוכן מצופה
7. דווח בקצרה למשתמש (עברית, bullet points עם קישורים לקבצים)

## אנשי הקשר בפרויקט

המשתמש הוא **סער יוסף**. פרויקט עברי/RTL. אל תניח שעיצוב או אסתטיקה אנגלית-ברירת-מחדל מתאימה — תמיד חשוב RTL ועברית קודם.
