# מדריך פריסה ל-Cloudways

מדריך מהיר להעלאת mcar ל-Cloudways. זמן משוער: **15 דקות**.

## מה כבר מוכן

- ✓ `robots.txt` — לזחלני SEO
- ✓ `sitemap.xml` — מפת אתר ל-Google
- ✓ `favicon.svg` — אייקון לטאב הדפדפן
- ✓ `.htaccess` — אבטחה, cache, gzip, https redirect (מוערך)
- ✓ Open Graph meta tags — לשיתוף ברשתות חברתיות

## שלב 1 — קבל פרטי SFTP

1. בקונסולת Cloudways, לחץ על **Server Management** (לא Application — זה ברמת השרת)
2. לחץ על **Master Credentials**
3. תראה:
   - Public IP: `XXX.XXX.XXX.XXX`
   - Username: `master_xxxxxxx`
   - Password: `xxxxxxx`
   - Port: 22

## שלב 2 — התקן FileZilla (חינם)

הורד מ-[filezilla-project.org](https://filezilla-project.org/). זה ה-FTP/SFTP client הפופולרי ביותר.

## שלב 3 — התחבר לשרת

ב-FileZilla → **File > Site Manager**:

| שדה | ערך |
|-----|-----|
| Host | ה-Public IP מצעד 1 |
| Port | 22 |
| Protocol | **SFTP** (לא FTP) |
| Logon Type | Normal |
| User | ה-Username מצעד 1 |
| Password | ה-Password מצעד 1 |

לחץ **Connect**. אם הוא שואל על host key — Accept.

## שלב 4 — נווט לתיקיית האפליקציה

בצד הימני (השרת) נווט לנתיב:

```
/applications/phpstack-679104-6365725/public_html/
```

(החלף את `phpstack-679104-6365725` בשם האפליקציה שלך)

זו התיקייה ש-`https://phpstack-679104-6365725.cloudwaysa...` מצביע אליה.

## שלב 5 — נקה את התיקייה הקיימת

ב-`public_html/` יש קובץ ברירת מחדל של Cloudways (לרוב `index.php` עם תוכן דמו). מחק את כל הקבצים שם.

## שלב 6 — העלה את כל הקבצים מ-`c:\mcar`

בצד השמאלי (המחשב שלך) נווט ל-`c:\mcar\`.

**העלה את כל הקבצים, אבל לא את התיקיות הבאות:**

| העלה | אל תעלה |
|------|---------|
| כל קובצי `.php` בשורש (`index.php`, `about.php`, וכו׳) | `mcar.html` (1.2MB ארכיון ישן) |
| כל קובצי `.md` (אופציונלי, לדוקומנטציה) | `src/` (פרוטוטיפ React) |
| `assets/` (CSS + JS + img) | `debug/` (צילומי מסך) |
| `includes/` (config, functions, header, footer) | `scraps/` |
| `robots.txt`, `sitemap.xml`, `favicon.svg`, `.htaccess` | `uploads/` (תמונות לא בשימוש) |

הדרך הקלה: בחר הכל, לחץ קליק ימני על הזבל → "Add to queue", העלה, ואז במידע מחק את התיקיות שצריך.

> **טיפ**: אפשר גם פשוט להעלות את הכל. אז אל תשכח להוסיף ל-`.htaccess` חסימת גישה לתיקיות הארכיון.

## שלב 7 — בדוק שזה עובד

פתח בדפדפן את ה-Application URL מהקונסולה (`https://phpstack-679104-6365725.cloudwaysa...`).

האתר צריך לעלות. אם רואה דף לבן או שגיאה:
- ודא שגרסת PHP **7.4+** ב-Application Settings → Application Setting → PHP Version
- ראה את ה-error log ב-Monitoring → Apache Logs

## שלב 8 — חבר דומיין משלך (אם יש)

ב-**Domain Management**:
1. לחץ **Add Domain**
2. כתוב את הדומיין (`mcar.co.il` למשל)
3. אצל ספק הדומיין שלך, צור שני A records המצביעים ל-Public IP של Cloudways:
   - `@` → `XXX.XXX.XXX.XXX`
   - `www` → `XXX.XXX.XXX.XXX`
4. חכה 5–60 דקות לפרופגציה

## שלב 9 — הפעל SSL (חינם)

ב-**SSL Certificate**:
1. בחר **Let's Encrypt**
2. הזן את האימייל והדומיין שהוספת
3. לחץ **Install Certificate**
4. אחרי 1-2 דקות יהיה SSL

**אחרי SSL** — חזור ל-`.htaccess` ב-FileZilla והסר את ה-`#` מהבלוק `RewriteEngine On / RewriteCond %{HTTPS} off / RewriteRule …` (5 שורות בערך). זה יוודא שכל בקשה http תופנה אוטומטית ל-https.

## שלב 10 — הגדרות סופיות

ב-**Application Settings** של Cloudways:
- **PHP Version**: 7.4+ (8.1 או 8.2 מומלץ)
- **PHP-FPM Settings**: ברירת מחדל מספיקה
- **Varnish**: אפשר להפעיל לקאש (אופציונלי, מאיץ הרבה)
- **Cron Jobs**: לא צריך — האתר סטטי-דינמי

ב-**Application > Application Settings > Application URL**:
- אם הוספת דומיין, הגדר אותו כ-Primary URL

## שלב 11 — טסטים אחרי פריסה

גלוש לדפים האלה ובדוק שכל אחד עובד:

```
/                      → דף הבית
/grid.php              → קטלוג
/grid.php?cat=suv      → פילטר עובד
/compare.php?ids=nova-prime,aurora-gt  → השוואה
/contact.php           → טופס
/operational.php       → ליסינג עסקי
/faq.php               → שאלות נפוצות
/careers.php           → קריירה
/blog.php              → בלוג
/terms.php             → תנאי שימוש
/privacy.php           → פרטיות
/accessibility.php     → נגישות
/no-such-page.php      → 404 page
/robots.txt            → אמור להציג טקסט
/sitemap.xml           → אמור להציג XML
/favicon.svg           → לוגו "m" ירוק
```

ובדוק:
- ⚙ **Tweaks**: לחץ על גלגל השיניים בהדר. בחר "סגול" — הצבע מתחלף. רענן את הדף — נשמר.
- 🎨 **Modal "VIP"**: לחץ על "קבל הצעת VIP" בהדר. המודל נפתח.
- 📱 **Mobile**: צמצם את הדפדפן ל-400px. תפריט המבורגר עובד.

## שלב 12 — עדכונים בעתיד

לעדכון קוד:
1. ערוך מקומית ב-`c:\mcar`
2. ב-FileZilla, גרור את הקבצים שערכת ל-`/applications/.../public_html/`
3. אם ערכת CSS/JS, הגדל את `ASSET_VERSION` ב-[includes/config.php](includes/config.php) — הדפדפנים יורידו גרסה חדשה אוטומטית

או — אם תרצה אוטומציה מלאה — Cloudways תומך ב-**Deployment via GIT** (בסיידבר). העלה את הפרויקט ל-GitHub, חבר ב-Cloudways, וכל push יפרס אוטומטית.

## אם משהו לא עובד

| בעיה | פתרון |
|------|-------|
| דף לבן | PHP version → ודא 7.4+. בדוק `error.log` ב-Monitoring |
| 500 Internal Server Error | ב-`.htaccess` — נסה למחוק זמנית את הבלוק האחרון של `<IfModule mod_headers.c>`. אם נפתר → mod_headers לא מופעל בשרת |
| תמונות לא טוענות | placehold.co + picsum דורשים אינטרנט. שרת ה-Cloudways צריך גישה החוצה — לרוב יש |
| Tweaks לא נשמרים | פתח DevTools → Console. אם רואה שגיאה — שלח לי, אעזור לתקן |
| הפונטים נראים לא טוב | Google Fonts צריכים גישה ל-fonts.googleapis.com. לרוב עובד |

## בקצרה

```
1. Master Credentials → SFTP
2. FileZilla → Connect
3. Upload c:\mcar\* → /applications/.../public_html/
4. בדוק Application URL
5. הוסף דומיין → SSL → ערוך .htaccess (הסר # מ-RewriteEngine)
```

זהו. בהצלחה!
