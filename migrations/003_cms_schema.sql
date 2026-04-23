-- mcar — CMS schema + defaults
-- Run AFTER 002_seed.sql.
-- Adds tables for Pages, Menu, Settings, FAQ, Social, Media.
-- Includes seed of all current page content + nav so the site renders unchanged.

SET NAMES utf8mb4;

-- =====================================================
-- pages: editable content for every page (built-in + custom)
-- =====================================================
CREATE TABLE IF NOT EXISTS pages (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(100) NOT NULL UNIQUE,
    type            ENUM('builtin','custom') DEFAULT 'builtin',
    eyebrow         VARCHAR(200),
    hero_h1         TEXT,
    hero_lead       TEXT,
    hero_image      VARCHAR(500),
    content_html    LONGTEXT,
    seo_title       VARCHAR(200),
    seo_description VARCHAR(500),
    og_image        VARCHAR(500),
    sort            INT DEFAULT 0,
    active          TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active_type (active, type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- menu_items: header nav + footer link columns
-- =====================================================
CREATE TABLE IF NOT EXISTS menu_items (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    location  ENUM('header','footer_categories','footer_company','footer_support') NOT NULL,
    label     VARCHAR(100) NOT NULL,
    url       VARCHAR(300) NOT NULL,
    target    ENUM('_self','_blank') DEFAULT '_self',
    sort      INT DEFAULT 0,
    active    TINYINT(1) DEFAULT 1,
    INDEX idx_location_sort (location, sort, active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- settings: key/value site config
-- =====================================================
CREATE TABLE IF NOT EXISTS settings (
    `key`        VARCHAR(80) PRIMARY KEY,
    `value`      TEXT,
    `type`       ENUM('text','textarea','image','script','color','number') DEFAULT 'text',
    `group_name` VARCHAR(40) DEFAULT 'general',
    `label`      VARCHAR(160),
    `sort`       INT DEFAULT 0,
    updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- faq_items: editable Q&A
-- =====================================================
CREATE TABLE IF NOT EXISTS faq_items (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    group_id   VARCHAR(40) DEFAULT 'general',
    question   TEXT NOT NULL,
    answer     TEXT NOT NULL,
    sort       INT DEFAULT 0,
    active     TINYINT(1) DEFAULT 1,
    INDEX idx_group_sort (group_id, sort, active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS faq_groups (
    id     VARCHAR(40) PRIMARY KEY,
    label  VARCHAR(120) NOT NULL,
    icon   VARCHAR(40),
    sub    VARCHAR(200),
    sort   INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- social_links: footer social icons
-- =====================================================
CREATE TABLE IF NOT EXISTS social_links (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    platform  VARCHAR(40) NOT NULL,
    url       VARCHAR(400) NOT NULL,
    icon      VARCHAR(40) NOT NULL,
    sort      INT DEFAULT 0,
    active    TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- media: uploaded files
-- =====================================================
CREATE TABLE IF NOT EXISTS media (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    filename      VARCHAR(200) NOT NULL,
    original_name VARCHAR(200),
    mime          VARCHAR(60),
    size_bytes    INT,
    width         INT,
    height        INT,
    path          VARCHAR(500),
    url           VARCHAR(500),
    alt_text      VARCHAR(200),
    uploaded_by   VARCHAR(60),
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created (created_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SEED: Default pages (current site content)
-- =====================================================
INSERT IGNORE INTO pages (slug, type, eyebrow, hero_h1, hero_lead, hero_image, seo_title, seo_description, sort) VALUES
('home',          'builtin', '2026 · מחשבון ליסינג דור חדש · עדכון ינואר',
   'העתיד של ענף הליסינג בישראל — בטבלה אחת.',
   'מנוע ההשוואה המתקדם ביותר לרכב הבא שלך. פרטי, תפעולי ורכישה — עסקאות מאומתות ממעל 40 יבואנים, בזמן אמת, ללא עמלות נסתרות.',
   NULL, 'mcar · ליסינג פרטי ותפעולי', 'פורטל השוואת הליסינג המוביל בישראל. רכבים, מחירים, וליסינג תפעולי במקום אחד.', 1),
('about',         'builtin', 'אודות mcar',
   'בנינו את הפלטפורמה שהיינו רוצים ללקוחות שלנו.',
   'mcar הוא פורטל ההשוואה הבלתי תלוי הגדול בישראל לליסינג ורכישת רכב. לא מוכרים רכבים — עוזרים לבחור.',
   'assets/img/interior_lifestyle.png', 'אודות · mcar', 'הסיפור של mcar — חברת הליסינג העצמאית הגדולה בישראל.', 2),
('grid',          'builtin', NULL,
   'מצא את העסקה המשתלמת ביותר בענף הרכב הישראלי.',
   'סנן לפי תקציב, קטגוריה ומנוע. בחר את הרכב הבא שלך מתוך הקטלוג המלא.',
   NULL, 'קטלוג רכבים · mcar', 'כל הרכבים, כל המחירים, כל היבואנים — במקום אחד.', 3),
('contact',       'builtin', 'דברו איתנו',
   'שניה. נושמים. ומתחילים לחסוך.',
   'נציג VIP אמיתי חוזר אליכם תוך 60 שניות. בלי בוטים ובלי המתנות מיותרות.',
   NULL, 'צור קשר · mcar', 'נציג VIP חוזר תוך 60 שניות. WhatsApp, טלפון, או טופס.', 4),
('operational',   'builtin', 'ליסינג תפעולי · לעצמאים וחברות',
   'צי רכבים עסקי בלי כאב ראש, בלי הפתעות במאזן.',
   'ליסינג תפעולי מלא לעצמאים, סטארטאפים ועסקים. ההחזר החודשי קבוע, הוצאה מוכרת של עד 17%.',
   'assets/img/fleet_lifestyle.png', 'ליסינג תפעולי · mcar', 'ליסינג תפעולי לעצמאים וחברות — חיסכון של עד 17%.', 5),
('faq',           'builtin', 'מרכז התמיכה',
   'כל מה שרציתם לדעת על ליסינג בישראל.',
   'תשובות ישירות, בלי סוגריים של עורך דין. לא מצאתם את מה שחיפשתם? צוות ה-VIP שלנו חוזר תוך 60 שניות.',
   NULL, 'שאלות נפוצות · mcar', 'תשובות לשאלות נפוצות על ליסינג פרטי, תפעולי, ורכבים חשמליים.', 6),
('careers',       'builtin', 'קריירה ב-mcar',
   'בנו את עתיד הענף יחד איתנו.',
   'אנחנו מחפשים אנשים שמאמינים ששוק הרכב בישראל צריך לעבוד אחרת.',
   NULL, 'קריירה · mcar', 'משרות פתוחות ב-mcar. הצטרפו לצוות שמשנה את ענף הליסינג בישראל.', 7),
('blog',          'builtin', 'בלוג mcar',
   'ניתוחים, מדריכים, ואמת על שוק הרכב.',
   'אנחנו לא מוכרים רכבים — אנחנו עוזרים לבחור. הבלוג מכיל מה שאנחנו לומדים תוך כדי.',
   NULL, 'בלוג · mcar', 'ניתוחים ומדריכים על שוק הליסינג והרכב הישראלי.', 8),
('terms',         'builtin', 'משפטי · עדכון 20.04.2026', 'תנאי שימוש.', 'המסמך מגדיר את תנאי השימוש בפורטל mcar.', NULL, 'תנאי שימוש · mcar', 'תנאי השימוש המלאים של פורטל mcar.', 9),
('privacy',       'builtin', 'משפטי · GDPR', 'מדיניות פרטיות.', 'שקיפות מלאה על איך המידע שלך נאסף, נשמר, ומשמש.', NULL, 'מדיניות פרטיות · mcar', 'מדיניות הפרטיות של mcar בהתאם ל-GDPR ולחוק הגנת הפרטיות.', 10),
('accessibility', 'builtin', 'נגישות · ת״י 5568 AA', 'נגישות זה לא פיצ׳ר, זה ברירת מחדל.', 'mcar מחויבת להנגשת השירות לכלל המשתמשים, לרבות אנשים עם מוגבלויות.', NULL, 'נגישות · mcar', 'הצהרת הנגישות של mcar — ת״י 5568 רמת AA.', 11);

-- =====================================================
-- SEED: Menu items (current header nav)
-- =====================================================
INSERT IGNORE INTO menu_items (location, label, url, sort) VALUES
('header', 'דף הבית',         'index.php',        1),
('header', 'אודות',            'about.php',        2),
('header', 'רכבים',            'grid.php',         3),
('header', 'ליסינג תפעולי',     'operational.php',  4),
('header', 'צור קשר',          'contact.php',      5);

-- Footer columns
INSERT IGNORE INTO menu_items (location, label, url, sort) VALUES
('footer_company', 'אודותינו',   'about.php',     1),
('footer_company', 'צור קשר',    'contact.php',   2),
('footer_company', 'קריירה',     'careers.php',   3),
('footer_company', 'בלוג',       'blog.php',      4),
('footer_support', 'שאלות נפוצות','faq.php',       1),
('footer_support', 'תנאי שימוש',  'terms.php',     2),
('footer_support', 'פרטיות',      'privacy.php',   3),
('footer_support', 'נגישות',      'accessibility.php', 4);

-- (footer_categories is auto-populated from $CATEGORIES; no static seed needed)

-- =====================================================
-- SEED: Settings (contact info + scripts + branding)
-- =====================================================
INSERT IGNORE INTO settings (`key`, `value`, `type`, `group_name`, `label`, `sort`) VALUES
('contact_phone',    '*4260',                    'text',     'contact', 'מספר טלפון ראשי',                        1),
('contact_phone_display', '*4260',               'text',     'contact', 'תצוגת טלפון (מה שיוצג למשתמש)',          2),
('contact_whatsapp', '+972524260426',            'text',     'contact', 'מספר WhatsApp (פורמט בינלאומי, +972...)', 3),
('contact_whatsapp_display', '052-4260-426',     'text',     'contact', 'תצוגת WhatsApp',                         4),
('contact_email',    'vip@mcar.co.il',           'text',     'contact', 'אימייל ראשי',                            5),
('contact_address',  'דרך מנחם בגין 132, תל אביב, קומה 18', 'text', 'contact', 'כתובת',                        6),
('contact_hours',    'א׳-ה׳ 8-20, ו׳ 8-13',      'text',     'contact', 'שעות פעילות',                            7),

('site_name',        'mcar',                     'text',     'branding', 'שם האתר',                               1),
('site_tagline',     'ליסינג פרטי ותפעולי',      'text',     'branding', 'תיאור (טאגליין)',                       2),
('og_default_image', '',                          'image',    'branding', 'תמונת ברירת מחדל לשיתוף (Open Graph)',  3),

('gtm_id',           '',                          'text',     'analytics', 'Google Tag Manager ID (GTM-XXXXXXX)', 1),
('head_scripts',     '',                          'script',   'analytics', 'סקריפטים בתוך <head>',                2),
('body_scripts',     '',                          'script',   'analytics', 'סקריפטים לפני </body>',               3);

-- =====================================================
-- SEED: Social links (currently #-anchors)
-- =====================================================
INSERT IGNORE INTO social_links (platform, url, icon, sort) VALUES
('Facebook',  '#', 'facebook',  1),
('Instagram', '#', 'instagram', 2),
('LinkedIn',  '#', 'linkedin',  3);

-- =====================================================
-- SEED: FAQ groups + items (from current faq.php)
-- =====================================================
INSERT IGNORE INTO faq_groups (id, label, icon, sub, sort) VALUES
('general',     'כללי',                'sparkle',  'מה זה mcar ואיך זה עובד',           1),
('operational', 'ליסינג תפעולי',        'shield',   'לעצמאים, חברות וסטארטאפים',         2),
('ev',          'רכבים חשמליים',        'bolt',     'EV-Ready, טעינה והטבות',            3),
('contract',    'חוזה ותשלום',          'calendar', 'תנאים, משך, ויציאה מוקדמת',         4),
('service',     'תחזוקה ושירות',        'clock',    'מה קורה כשמשהו נשבר',               5);

INSERT IGNORE INTO faq_items (group_id, question, answer, sort) VALUES
('general',     'מה זה בעצם mcar?', 'mcar הוא פורטל השוואה <strong>עצמאי ובלתי תלוי</strong> לליסינג ורכישת רכב בישראל. אנחנו לא יבואנים, לא סוכני מכירות ולא חברת ליסינג — אנחנו מציגים הצעות מאומתות מ-40+ יבואנים.', 1),
('general',     'האם השירות של mcar בתשלום?', 'לא. השירות <strong>חינמי לחלוטין</strong>. אנחנו מקבלים עמלת תיווך מהיבואן רק אחרי שעסקה נסגרת.', 2),
('general',     'מה ההבדל בין ליסינג פרטי לתפעולי?', '<strong>ליסינג פרטי</strong> מיועד לאנשים פרטיים. <strong>ליסינג תפעולי</strong> מיועד לעצמאים וחברות, מאפשר החזר מע״מ והוצאה מוכרת.', 3),
('operational', 'מה היתרון הגדול של ליסינג תפעולי?', '<strong>פטור ממע״מ מלא</strong> + הוצאה מוכרת. ההבדל הממוצע הוא <strong>17% חיסכון</strong> חודשי.', 1),
('operational', 'האם יש מינימום לכמות רכבים?', 'לא. מ-1 רכב עד 300+ — אותו תהליך ואותה רמת שירות.', 2),
('ev',          'כמה ק״מ אמיתי מטעינה?', 'תלוי בדגם. הפער הממוצע בין הכרזת היצרן למציאות הוא <strong>14%</strong>.', 1),
('contract',    'מה כולל התשלום החודשי?', 'ביטוח מקיף + חובה, אחריות יצרן, טסט, טיפולים, רישוי, ושירותי דרך 24/7.', 1),
('contract',    'יציאה מוקדמת כמה עולה?', '5%–8% מיתרת החוזה אצל חברות רגילות, 3%–5% ב-mcar.', 2),
('service',     'מה קורה אם יש לי תקלה?', 'התקשרו למוקד ותקבלו רכב חלופי <strong>תוך 4 שעות</strong> בכל הארץ.', 1),
('service',     'האם יש תמיכה 24/7?', '<strong>כן.</strong> מוקד אנושי פעיל 24/7. WhatsApp ~2 דק׳, טלפון ~30 שנ׳.', 2);
