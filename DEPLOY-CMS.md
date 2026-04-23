# DEPLOY-CMS.md — תוסף מערכת הניהול (Phase 1+2)

מסמך זה מתאר את הפעלת תוסף ה-CMS הנוסף שנבנה אחרי [DEPLOY-BACKEND.md](DEPLOY-BACKEND.md). אם רק עכשיו אתה מקים את האתר, התחל מ-DEPLOY-CLOUDWAYS → DEPLOY-BACKEND, ורק אחר כך לכאן.

## מה התוסף הזה כולל

✅ **9 דפי ניהול חדשים** ב-`/admin/`:
- `/admin/settings.php` — טלפון/WhatsApp/אימייל/GTM/scripts
- `/admin/menu.php` — תפריט עליון + פוטר + רשתות חברתיות
- `/admin/pages.php` — רשימת כל הדפים
- `/admin/page_edit.php` — עריכת hero / SEO / + WYSIWYG (Quill) לדפים מותאמים
- `/admin/faq.php` — CRUD לשאלות נפוצות
- `/admin/media.php` — העלאת תמונות עם המרה אוטומטית ל-WebP
- `/admin/car_edit.php` — עריכה מלאה של רכב
- `/admin/packages.php` — CRUD לחבילות
- `/admin/leads_export.php` — ייצוא לידים ל-CSV (Excel)

✅ **6 טבלאות חדשות** ב-DB: `pages`, `menu_items`, `settings`, `faq_groups`, `faq_items`, `social_links`, `media`

✅ **חיבור אמיתי**: header/footer/social/scripts כולם נטענים דינמית מ-DB. עריכה ב-`/admin/` משפיעה מיידית על האתר.

## שלב 1 — ייבא את הסכמה החדשה

ב-Cloudways → Database → **phpMyAdmin** → המסד שלך → **Import**:

```
migrations/003_cms_schema.sql
```

זה ייצור 6 טבלאות חדשות + יזרע אותן בתוכן הנוכחי של האתר (10 דפים, 13 פריטי תפריט, 13 הגדרות, 5 קבוצות FAQ עם 10 שאלות, 3 רשתות חברתיות).

> ⚠️ **חשוב**: זה לא מוחק את הטבלאות הקודמות. הוא רק מוסיף.

אחרי ייבוא, בדוק ב-phpMyAdmin:
```sql
SELECT COUNT(*) FROM pages;        -- צריך 11
SELECT COUNT(*) FROM menu_items;   -- 13
SELECT COUNT(*) FROM settings;     -- 13
SELECT COUNT(*) FROM faq_items;    -- 10
```

## שלב 2 — צור את תיקיית uploads/

ב-SFTP, וודא שתיקיית `uploads/` בשורש קיימת ויש לה הרשאות כתיבה (755):

```bash
chmod 755 uploads/
chmod 755 uploads/2026  # אם קיים
```

אם תיקיית `uploads/` לא קיימת, צור אותה דרך הfile manager של Cloudways.

## שלב 3 — בדיקה מהירה

1. גלוש ל-`https://yourdomain.com/admin/` — התחבר
2. צד עליון → **הגדרות** — ערוך טלפון/WhatsApp → שמור → גלוש לאתר → אמור להתעדכן (אם יש מקום באתר שמשתמש בהם — כרגע ה-static contact details עדיין hardcoded במקומות; שלב הבא ישלים)
3. **תפריט/פוטר** → שנה תווית של פריט → שמור → רענן את האתר → התווית התעדכנה ✓
4. **עמודים** → לחץ "ערוך" על דף הבית → שנה את ה-H1 → שמור → רענן → שונה ✓
5. **שאלות נפוצות** → ערוך/הוסף → שמור → גלוש ל-`/faq.php` → השינויים שם ✓
6. **מדיה** → העלה תמונה JPG → אמורה להומר אוטומטית ל-WebP → קישור מוכן להעתקה ✓
7. **רכבים** → לחץ "ערוך מלא" על רכב → שנה תכונות → שמור ✓
8. **לידים** → לחץ "📥 ייצא ל-CSV" → קובץ Excel מוריד עם כל הלידים ✓

## שלב 4 — מה דברים הבונה (Pages Builder) יכול לעשות

לחץ על **עמודים** → **➕ עמוד חדש**:

- Slug: `my-new-page` → URL יהיה `/my-new-page.php`
- כותרת H1, תיאור, תמונה
- **תוכן עשיר ב-Quill** (WYSIWYG): bold, italic, רשימות, לינקים, צבעים, כותרות
- SEO Title + Description + OG Image לכל עמוד נפרד

> ℹ️ **שימו לב**: כדי שעמוד חדש יוכל להופיע בכתובת בפועל (לא רק ב-DB), צריך לוודא שיש קובץ PHP שמטפל בו. כרגע יש דפים מובנים (index/about/...). לעמודים נוצרים ב-Builder, התוכן נשמר ב-DB אבל אין routing אוטומטי. זה הוסרה לפיתוח עתידי (Phase 3).

## שלב 5 — אבטחה

ה-`.htaccess` כבר חוסם:
- `/migrations/*.sql`
- `/.env`
- `/includes/*.php` (גישה ישירה)

ודא שגלישה ל-`/admin/` ללא login מפנה ל-`login.php`.

## שלב 6 — תחזוקה שוטפת

### עדכון מחיר רכב
`/admin/cars.php` → ערוך 3 שדות → "שמור מחיר" → מיידי באתר

### הוספת רכב חדש
`/admin/cars.php` → ➕ **הוסף רכב חדש** → טופס מלא

### שינוי טקסט בדף הבית
`/admin/pages.php` → ערוך "home" → שנה H1/lead/eyebrow → שמור

### החלפת לוגו / תמונת ברירת מחדל לשיתוף
`/admin/media.php` → העלה → העתק URL → `/admin/settings.php` → הדבק ב-`og_default_image`

### הוספת Pixel/GTM
`/admin/settings.php` → "אנליטיקה וסקריפטים" → הדבק ב-`gtm_id` או `head_scripts`

---

## בעיות נפוצות

| בעיה | פתרון |
|------|-------|
| "DB לא מחובר" ב-admin | ודא ש-`.env` תקין + שהסכמה (003) יובאה |
| העלאת תמונה נכשלת | בדוק הרשאות תיקיית `uploads/` (755) |
| Quill לא נטען | בדוק חסימת CDN של jsdelivr בדפדפן/firewall |
| "Image is not editable" אחרי העלאה | אם GD לא מותקן → המרה ל-WebP מדולגת, התמונה נשארת בפורמט המקור |
| שינויים בתפריט לא מופיעים | רענן עם `Ctrl+F5`. הקאש של הדפדפן יכול להחזיק |

---

## מבנה קבצים שנוצרו ב-Phase 1+2

```
mcar/
├── migrations/
│   └── 003_cms_schema.sql          ← (חדש) 6 טבלאות + seed
│
├── includes/
│   └── cms.php                     ← (חדש) helpers: setting(), page(), menu_items(), ...
│
└── admin/
    ├── settings.php                ← (חדש)
    ├── menu.php                    ← (חדש)
    ├── pages.php                   ← (חדש)
    ├── page_edit.php               ← (חדש) WYSIWYG via Quill CDN
    ├── faq.php                     ← (חדש)
    ├── media.php                   ← (חדש) WebP conversion
    ├── car_edit.php                ← (חדש) full car CRUD
    ├── packages.php                ← (חדש)
    └── leads_export.php            ← (חדש) CSV download
```

---

## מה עוד אפשר להוסיף בעתיד (Phase 3)

- **Routing דינמי** ל-Pages Builder: `index.php?slug=my-new-page` או rewrite ב-.htaccess
- **TinyMCE** במקום Quill (עורך עשיר יותר)
- **Image cropping** ב-Media Manager (כרגע רק העלאה + WebP)
- **Multilingual** (עברית/אנגלית/ערבית)
- **Multiple admin users** עם הרשאות (סוכן/מנהל תוכן)
- **Lead activity log** (היסטוריית עדכונים לכל ליד)
- **Cache layer** (APCu/Redis) — חשוב כשיש 100+ רכבים
- **Pagination + Infinite Scroll** בקטלוג
