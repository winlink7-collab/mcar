# DEPLOY-BACKEND.md — הוראות הקמת Backend

לאחר שהפריסה הראשונית הסטטית עובדת ([DEPLOY-CLOUDWAYS.md](DEPLOY-CLOUDWAYS.md)), עקוב אחרי המדריך הזה כדי להפעיל:
- 🗄️ מסד נתונים (MySQL) — לשמירת לידים + קטלוג רכבים
- ✉️ שליחת מיילים אמיתית — לידים נכנסים לתיבה שלך
- 🛡️ הגנת ספאם (reCAPTCHA v3)
- 🔐 פאנל ניהול ב-`/admin/`
- 🔒 הסתרת סיסמאות בקובץ `.env`

---

## שלב 1 — צור קובץ `.env` בשרת

ב-Cloudways → Application Access Details → SFTP. גלוש לתיקיית הריפו (לא `public_html` — תיקייה אחת מעל אם יש; אם הריפו ישירות ב-`public_html`, אז שם).

צור קובץ חדש בשם **`.env`** (עם נקודה בהתחלה) על בסיס [`.env.example`](.env.example).

```bash
# העתק את התבנית
cp .env.example .env
nano .env  # ערוך
```

מלא את הערכים האמיתיים:

```env
DB_HOST=localhost
DB_NAME=rdcbvmjwbe         # מ-Application Access Details ב-Cloudways
DB_USER=rdcbvmjwbe         # מ-Application Access Details
DB_PASS=********           # מ-Application Access Details (לחץ על העין)

MAIL_TO=vip@yourdomain.com           # האימייל שלך לקבלת לידים
MAIL_FROM=noreply@yourdomain.com

ADMIN_USER=saar
ADMIN_PASS_HASH=$2y$10$...           # ייצור בשלב הבא
```

---

## שלב 2 — צור hash לסיסמת המנהל

ב-SSH של Cloudways הרץ:

```bash
php -r "echo password_hash('YOUR-STRONG-PASSWORD-HERE', PASSWORD_BCRYPT);"
```

תקבל מחרוזת שמתחילה ב-`$2y$10$...`. העתק אותה ל-`.env` כערך של `ADMIN_PASS_HASH`.

> ⚠️ **לעולם אל תשמור את הסיסמה הגלויה ב-`.env`.** רק את ה-hash.

---

## שלב 3 — ייבא את סכמת המסד

ב-Cloudways → Database → **Launch Database Manager** (פותח phpMyAdmin).

1. בחר את המסד שלך (`rdcbvmjwbe` או דומה)
2. לחץ על **Import** בסרגל העליון
3. בחר את הקובץ `migrations/001_schema.sql` מהמחשב המקומי
4. **Go** — תוצרו 5 טבלאות: `categories`, `engine_types`, `cars`, `packages`, `leads`, `admin_sessions`

חזור שוב על **Import**, הפעם עם `migrations/002_seed.sql` — זה ימלא את הטבלאות בנתוני 12 הרכבים, 6 החבילות, וכו׳.

---

## שלב 4 — הגדרות SMTP (אופציונלי, מומלץ)

ב-Cloudways → **Application Settings** → **SMTP** — הם נותנים שירות SMTP חינם (powered by Elastic Email).

לחלופין, השתמש ב-SendGrid / Mailgun / SES.

הוסף ל-`.env`:

```env
SMTP_HOST=smtp.elasticemail.com
SMTP_PORT=587
SMTP_USER=your-smtp-user
SMTP_PASS=your-smtp-pass
SMTP_SECURE=tls
```

(אופציה ב': השאר ריק — האתר ישתמש ב-`mail()` של PHP. עובד אבל פחות מהימן ויכול להיכנס לספאם.)

### להפעיל PHPMailer (אם בחרת SMTP):
1. הורד את [PHPMailer](https://github.com/PHPMailer/PHPMailer/releases) (גרסה 6.x)
2. צור תיקייה: `includes/lib/PHPMailer/`
3. העתק רק 3 קבצים: `PHPMailer.php`, `SMTP.php`, `Exception.php`
4. הקוד יזהה אוטומטית ויעבור להשתמש ב-SMTP

---

## שלב 5 — הגדר reCAPTCHA v3 (חובה לפרודקשן)

1. לך ל-[Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin/create)
2. צור אתר חדש:
   - Label: mcar
   - Type: **reCAPTCHA v3**
   - Domains: `mcar.co.il`, `phpstack-679104-6365725.cloudwaysapps.com`
3. תקבל **Site Key** + **Secret Key**
4. הוסף ל-`.env`:

```env
RECAPTCHA_SITE_KEY=6Lc...
RECAPTCHA_SECRET_KEY=6Lc...
RECAPTCHA_MIN_SCORE=0.5
```

(אם לא מגדיר — האתר עובד בלי reCAPTCHA, אבל פתוח לבוטים.)

---

## שלב 6 — בדוק שהכל עובד

### בדיקה 1: DB מחובר?
לך ל-`https://yourdomain.com/admin/` והתחבר עם הפרטים מ-`.env`.

ב-דשבורד תראה "0 לידים" (לא "מסד הנתונים לא מחובר"). אם רואה אזהרה — בדוק את `.env` ושסכמה יובאה.

### בדיקה 2: טופס שולח?
- גלוש לאתר → "קבל הצעת VIP" → מלא שם+טלפון → שלח
- חזור ל-`/admin/leads.php` — אמור להופיע הליד החדש
- בדוק את המייל שלך ב-`MAIL_TO`

### בדיקה 3: ניהול רכבים?
- `/admin/cars.php` — שנה מחיר → רענן את `/grid.php` באתר → המחיר התעדכן

---

## שלב 7 — אבטחה סופית

ודא ש:
- [ ] קובץ `.env` **לא** מופיע ברשימת הקבצים בגיט (`git status` לא צריך להראות אותו)
- [ ] ניסיון גישה ל-`https://domain/.env` מחזיר **403 Forbidden** (`.htaccess` חוסם)
- [ ] ניסיון גישה ל-`https://domain/migrations/001_schema.sql` מחזיר **403**
- [ ] `/admin/` בלי login מפנה ל-`login.php`
- [ ] reCAPTCHA פעיל (`recaptcha_enabled()` מחזיר true)

---

## מבנה הקבצים שנוצרו

```
c:\mcar\
├── .env                        ← סיסמאות (לא בגיט!)
├── .env.example                ← תבנית בלי סיסמאות
├── .htaccess                   ← חסם .env, .sql, includes/
│
├── includes/
│   ├── env.php                 ← טוען .env לתוך $_ENV
│   ├── db.php                  ← חיבור PDO + save_lead() + get_cars()
│   ├── mail.php                ← send_lead_email() — mail() או SMTP
│   ├── security.php            ← CSRF + reCAPTCHA + rate limit
│   └── lib/PHPMailer/          ← (אופציונלי, אם הוספת)
│
├── migrations/
│   ├── 001_schema.sql          ← CREATE TABLE
│   └── 002_seed.sql            ← INSERT INTO (12 רכבים, 6 חבילות)
│
└── admin/                      ← פאנל ניהול
    ├── _bootstrap.php          ← auth check
    ├── _layout.php             ← header/footer של ה-admin
    ├── login.php               ← מסך התחברות
    ├── logout.php
    ├── index.php               ← דשבורד (סטטיסטיקות)
    ├── leads.php               ← רשימת לידים + שינוי סטטוס
    └── cars.php                ← עריכת מחירים מהירה
```

---

## טיפול בבעיות

| בעיה | פתרון |
|------|-------|
| `Database connection failed` ב-`/admin/` | ודא ש-DB_PASS נכון ב-.env. בדוק ב-phpMyAdmin שאתה יכול להתחבר עם אותם פרטים |
| `/admin/login.php` נותן Internal Server Error | ייתכן ש-PHP לא מוצא את `password_hash`. ודא PHP 7.4+ |
| מייל לא מגיע | בדוק ב-`error_log` של Cloudways. אם משתמש ב-`mail()`, נסה SMTP במקום |
| reCAPTCHA תמיד נכשל | ודא שאתה מגדיר את הדומיין הנכון ב-Google reCAPTCHA console |
| לידים לא נכנסים ל-DB | ודא שהסכמה יובאה. נסה ידנית ב-phpMyAdmin: `SELECT * FROM leads;` |

לבעיות נוספות — בדוק את ה-error log: Cloudways → Monitoring → **PHP Error Log**.
