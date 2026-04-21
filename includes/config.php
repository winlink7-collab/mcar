<?php
/**
 * mcar Configuration & Data
 * This file contains all the site constants and the car catalog.
 */

// Site Settings
define('SITE_NAME', 'mcar');
define('SITE_TAGLINE', 'ליסינג פרטי ותפעולי');
define('BASE_URL', '/'); // Update this if the site is in a subdirectory

// Asset Versions (for cache busting)
define('ASSET_VERSION', '2.6.1');

// Categories
$CATEGORIES = [
    'suv' => [
        'label' => 'SUV יוקרה',
        'short' => 'SUV'
    ],
    'sedan' => [
        'label' => 'סדאן ביצועים',
        'short' => 'סדאן'
    ],
    'ev-family' => [
        'label' => 'חשמלי משפחתי',
        'short' => 'חשמלי'
    ]
];

// Engine Types
$ENGINE_TYPES = [
    'electric' => [
        'label' => 'חשמלי',
        'color' => '#14b8a6',
        'glyph' => '⚡'
    ],
    'hybrid' => [
        'label' => 'היברידי',
        'color' => '#0e9f6e',
        'glyph' => '◐'
    ],
    'gasoline' => [
        'label' => 'בנזין',
        'color' => '#5a6892',
        'glyph' => '⛽'
    ],
    'diesel' => [
        'label' => 'דיזל',
        'color' => '#78350f',
        'glyph' => '◼'
    ]
];

// Car Catalog
$CARS = [
    [
        'id' => 'aurora-gt',
        'make' => 'Aurora',
        'model' => 'GT Line',
        'trim' => 'Executive',
        'category' => 'suv',
        'engine' => 'hybrid',
        'hp' => 340,
        'consumption' => '16.2 ק״מ/ל׳',
        'seats' => 5,
        'accel' => '6.1',
        'monthly' => [
            'private' => 4290,
            'operational' => 3890,
            'purchase' => 289000
        ],
        'stock' => 0.62,
        'bestValue' => false,
        'verified' => true,
        'features' => ['מושבים חשמליים', 'פנורמה', 'ראיית לילה', 'Level 2 ADAS'],
        'warranty' => '3 שנים / 100,000 ק״מ',
        'delivery' => 'עד 21 ימים'
    ],
    [
        'id' => 'nova-prime',
        'make' => 'Nova',
        'model' => 'Prime 7',
        'trim' => 'Signature',
        'category' => 'suv',
        'engine' => 'electric',
        'hp' => 408,
        'consumption' => '510 ק״מ טווח',
        'seats' => 7,
        'accel' => '5.3',
        'monthly' => [
            'private' => 4690,
            'operational' => 4190,
            'purchase' => 329000
        ],
        'stock' => 0.34,
        'bestValue' => true,
        'verified' => true,
        'features' => ['7 מושבים', 'טעינה 250kW', 'Matrix LED', 'Head-Up Display'],
        'warranty' => '5 שנים / 150,000 ק״מ',
        'delivery' => 'זמין מיידית'
    ],
    [
        'id' => 'stellar-s',
        'make' => 'Stellar',
        'model' => 'S Series',
        'trim' => 'Sport+',
        'category' => 'sedan',
        'engine' => 'gasoline',
        'hp' => 295,
        'consumption' => '12.8 ק״מ/ל׳',
        'seats' => 5,
        'accel' => '5.8',
        'monthly' => [
            'private' => 3690,
            'operational' => 3290,
            'purchase' => 249000
        ],
        'stock' => 0.88,
        'bestValue' => false,
        'verified' => true,
        'features' => ['הגה ספורט', 'מושבי ספורט', 'בלמי קרמיקה אופציה'],
        'warranty' => '3 שנים',
        'delivery' => 'עד 14 ימים'
    ],
    [
        'id' => 'helix-rs',
        'make' => 'Helix',
        'model' => 'RS Turbo',
        'trim' => 'Club',
        'category' => 'sedan',
        'engine' => 'gasoline',
        'hp' => 420,
        'consumption' => '11.1 ק״מ/ל׳',
        'seats' => 5,
        'accel' => '4.4',
        'monthly' => [
            'private' => 4190,
            'operational' => 3790,
            'purchase' => 278000
        ],
        'stock' => 0.21,
        'bestValue' => false,
        'verified' => true,
        'features' => ['טורבו כפול', 'מתלים אדפטיביים', 'בקרת שיוט אקטיבית'],
        'warranty' => '3 שנים / 80,000 ק״מ',
        'delivery' => 'עד 30 ימים'
    ],
    [
        'id' => 'orion-e',
        'make' => 'Orion',
        'model' => 'E-Family',
        'trim' => 'Comfort',
        'category' => 'ev-family',
        'engine' => 'electric',
        'hp' => 204,
        'consumption' => '430 ק״מ טווח',
        'seats' => 5,
        'accel' => '7.9',
        'monthly' => [
            'private' => 2890,
            'operational' => 2590,
            'purchase' => 189000
        ],
        'stock' => 0.74,
        'bestValue' => true,
        'verified' => true,
        'features' => ['טעינה ביתית 11kW', 'V2L', 'תא מטען 520 ל׳'],
        'warranty' => '8 שנים / סוללה',
        'delivery' => 'עד 14 ימים'
    ],
    [
        'id' => 'lumen-ev',
        'make' => 'Lumen',
        'model' => 'EV-5',
        'trim' => 'Long Range',
        'category' => 'ev-family',
        'engine' => 'electric',
        'hp' => 272,
        'consumption' => '560 ק״מ טווח',
        'seats' => 5,
        'accel' => '6.7',
        'monthly' => [
            'private' => 3190,
            'operational' => 2890,
            'purchase' => 219000
        ],
        'stock' => 0.55,
        'bestValue' => false,
        'verified' => true,
        'features' => ['טווח ארוך', 'משאבת חום', 'AWD'],
        'warranty' => '8 שנים / סוללה',
        'delivery' => 'זמין מיידית'
    ],
    [
        'id' => 'meridian-x',
        'make' => 'Meridian',
        'model' => 'X-Coupe',
        'trim' => 'Gran Turismo',
        'category' => 'sedan',
        'engine' => 'hybrid',
        'hp' => 385,
        'consumption' => '17.8 ק״מ/ל׳',
        'seats' => 4,
        'accel' => '4.9',
        'monthly' => [
            'private' => 4890,
            'operational' => 4390,
            'purchase' => 339000
        ],
        'stock' => 0.15,
        'bestValue' => false,
        'verified' => true,
        'features' => ['גג פנורמי', 'Bang Audio 18', 'Launch Control'],
        'warranty' => '4 שנים',
        'delivery' => 'עד 45 ימים'
    ],
    [
        'id' => 'atlas-q',
        'make' => 'Atlas',
        'model' => 'Q-SUV',
        'trim' => 'Pro',
        'category' => 'suv',
        'engine' => 'diesel',
        'hp' => 245,
        'consumption' => '14.5 ק״מ/ל׳',
        'seats' => 7,
        'accel' => '7.3',
        'monthly' => [
            'private' => 3490,
            'operational' => 3090,
            'purchase' => 239000
        ],
        'stock' => 0.92,
        'bestValue' => true,
        'verified' => true,
        'features' => ['7 מושבים', 'גרירה 2.5 טון', 'מצב שטח'],
        'warranty' => '3 שנים / 120,000 ק״מ',
        'delivery' => 'עד 21 ימים'
    ],
    [
        'id' => 'zenith-rv',
        'make' => 'Zenith',
        'model' => 'RV-Sport',
        'trim' => 'Edition 26',
        'category' => 'suv',
        'engine' => 'hybrid',
        'hp' => 310,
        'consumption' => '15.3 ק״מ/ל׳',
        'seats' => 5,
        'accel' => '6.4',
        'monthly' => [
            'private' => 3890,
            'operational' => 3490,
            'purchase' => 259000
        ],
        'stock' => 0.48,
        'bestValue' => false,
        'verified' => true,
        'features' => ['מתלים אוויר', 'חלון ב-C', '12 רמקולים'],
        'warranty' => '3 שנים',
        'delivery' => 'עד 28 ימים'
    ],
    [
        'id' => 'vertex-r',
        'make' => 'Vertex',
        'model' => 'R-Line',
        'trim' => 'Dynamic',
        'category' => 'sedan',
        'engine' => 'hybrid',
        'hp' => 265,
        'consumption' => '19.2 ק״מ/ל׳',
        'seats' => 5,
        'accel' => '6.9',
        'monthly' => [
            'private' => 3290,
            'operational' => 2990,
            'purchase' => 219000
        ],
        'stock' => 0.67,
        'bestValue' => false,
        'verified' => true,
        'features' => ['חיסכון מעולה', 'ADAS מלא', 'מושבי עור'],
        'warranty' => '3 שנים / 100,000 ק״מ',
        'delivery' => 'עד 21 ימים'
    ],
    [
        'id' => 'flux-e8',
        'make' => 'Flux',
        'model' => 'E-8',
        'trim' => 'Family+',
        'category' => 'ev-family',
        'engine' => 'electric',
        'hp' => 238,
        'consumption' => '490 ק״מ טווח',
        'seats' => 7,
        'accel' => '7.5',
        'monthly' => [
            'private' => 3490,
            'operational' => 3090,
            'purchase' => 249000
        ],
        'stock' => 0.41,
        'bestValue' => false,
        'verified' => true,
        'features' => ['7 מושבים', 'טעינה 170kW', 'Vehicle-to-Home'],
        'warranty' => '8 שנים / סוללה',
        'delivery' => 'עד 30 ימים'
    ],
    [
        'id' => 'pulse-gts',
        'make' => 'Pulse',
        'model' => 'GT-S',
        'trim' => 'Carbon',
        'category' => 'sedan',
        'engine' => 'electric',
        'hp' => 503,
        'consumption' => '470 ק״מ טווח',
        'seats' => 5,
        'accel' => '3.6',
        'monthly' => [
            'private' => 5490,
            'operational' => 4890,
            'purchase' => 389000
        ],
        'stock' => 0.08,
        'bestValue' => false,
        'verified' => true,
        'features' => ['פחמן אמיתי', 'מושבי פחמן', 'Race Mode'],
        'warranty' => '4 שנים / סוללה',
        'delivery' => 'המתנה 60 יום'
    ]
];

// Partners
$PARTNERS = ['Aurora', 'Nova', 'Stellar', 'Helix', 'Orion', 'Lumen', 'Meridian', 'Atlas', 'Zenith', 'Vertex', 'Flux', 'Pulse'];

// Deal Types
$DEAL_TYPES = [
    [
        'id' => 'private',
        'label' => 'ליסינג פרטי',
        'sub' => 'גמיש, ללא התחייבות תאגידית'
    ],
    [
        'id' => 'operational',
        'label' => 'ליסינג תפעולי',
        'sub' => 'לעצמאים וחברות, פטור מע״מ'
    ],
    [
        'id' => 'purchase',
        'label' => 'רכישה מלאה',
        'sub' => 'מימון ישיר או מזומן'
    ]
];

// Leasing Packages (shown on home page)
$PACKAGES = [
    [
        'id' => 'essential', 'title' => 'Essential', 'sub' => 'חבילה בסיסית', 'icon' => 'users',
        'price' => 1790, 'pitch' => 'רכב פנאי/עירוני עם תחזוקה מלאה. מושלם לנהג פרטי שלא רוצה הפתעות.',
        'features' => ['תחזוקה מלאה + טיפולים', 'ביטוח חובה + מקיף', 'רישוי שנתי כלול', 'רכב חלופי בתיקון'],
        'km' => '15,000 ק״מ/שנה', 'fuel' => 'דלק מוחזר', 'featured' => false
    ],
    [
        'id' => 'smart', 'title' => 'Smart', 'sub' => 'החבילה החכמה', 'icon' => 'bolt',
        'price' => 2290, 'pitch' => 'רכב היברידי/חשמלי חסכוני עם כל הטוב של Essential ויותר ק״מ.',
        'features' => ['כולל טעינה ביתית (חשמלי)', '20K ק״מ שנתי', 'צמיגי חורף/קיץ', 'אפליקציית ניהול אישית'],
        'km' => '20,000 ק״מ/שנה', 'fuel' => 'Eco ready', 'featured' => false
    ],
    [
        'id' => 'family', 'title' => 'Family', 'sub' => 'לכל המשפחה', 'icon' => 'users',
        'price' => 2890, 'pitch' => 'SUV או מיניוואן 7 מקומות — נוחות של בית על גלגלים, בלי פשרות.',
        'features' => ['7 מקומות · מטען ענק', 'ISOFIX ומערכות בטיחות Tier 1', 'ביטוח כל הנהגים במשפחה', 'Roadside assistance 24/7'],
        'km' => '25,000 ק״מ/שנה', 'fuel' => 'Hybrid/Diesel', 'featured' => true
    ],
    [
        'id' => 'executive', 'title' => 'Executive', 'sub' => 'ליסינג עסקי', 'icon' => 'shield',
        'price' => 3290, 'pitch' => 'ליסינג תפעולי לעצמאים וחברות. ניכוי מלא מהוצאות, פטור מע״מ.',
        'features' => ['ניכוי מלא למע״מ', 'גיבוי חשבוניות חודשי', 'רכב חלופי Premium', 'דו״ח חודשי למנה״ח'],
        'km' => '30,000 ק״מ/שנה', 'fuel' => 'ללא מע״מ', 'featured' => false
    ],
    [
        'id' => 'prestige', 'title' => 'Prestige', 'sub' => 'רכבי יוקרה', 'icon' => 'sparkle',
        'price' => 4890, 'pitch' => 'BMW, מרצדס, אאודי בדגמים בכירים. עם concierge אישי והחלפה שנתית.',
        'features' => ['קונסיירז׳ אישי 24/7', 'שטיפה שבועית כלולה', 'דגמי דלוקס ואופציית Chauffeur', 'אופציית החלפה ל־12 ח׳'],
        'km' => '25,000 ק״מ/שנה', 'fuel' => 'Premium', 'featured' => false
    ],
    [
        'id' => 'electric-plus', 'title' => 'Electric+', 'sub' => 'חשמלי בלבד', 'icon' => 'leaf',
        'price' => 1990, 'pitch' => 'חבילה ייעודית לרכבים חשמליים בלבד — עם כל הטבות המדינה בתוך המחיר.',
        'features' => ['מטען ביתי מותקן חינם', 'מנוי רשת טעינה ציבורית', 'הטבות מיסוי מופחת', 'תחזוקה חשמלית ייעודית'],
        'km' => '25,000 ק״מ/שנה', 'fuel' => '100% חשמלי', 'featured' => false
    ],
];

// Testimonials
$TESTIMONIALS = [
    [
        'name' => 'נועה ל.',
        'role' => 'עו״ד, ת״א',
        'quote' => 'ההבדל בין ההצעה הראשונה לאחרונה היה 480₪ לחודש. mcar חסכו לי שעות של שיחות מול סוכנים.'
    ],
    [
        'name' => 'ר. אבירם',
        'role' => 'מנכ״ל Startup',
        'quote' => 'ליסינג תפעולי לצוות שלם בטבלה אחת. הזמן מ־בקשה ל־חוזה היה פחות משבוע.'
    ],
    [
        'name' => 'יעל + אבי',
        'role' => 'משפחה, הרצליה',
        'quote' => 'עברנו לחשמלי משפחתי. הכלי השוואה הבהיר את עלות הבעלות הריאלית לחמש שנים קדימה.'
    ]
];

// Stats
$STATS = [
    [
        'k' => '+12,480',
        'label' => 'רכבים בהשוואה פעילה'
    ],
    [
        'k' => '38,920',
        'label' => 'לקוחות שסגרו דרכנו'
    ],
    [
        'k' => '780M<span class="sym">₪</span>',
        'label' => 'נפח עסקאות שנתי'
    ],
    [
        'k' => '9.4/10',
        'label' => 'ציון שביעות רצון'
    ]
];

// FAQ
$FAQ = [
    [
        'q' => 'מה ההבדל בין ליסינג פרטי לתפעולי?',
        'a' => 'ליסינג פרטי מיועד לאנשים פרטיים ללא פטור מע״מ, עם חופש גבוה בבחירת דגם ואביזרים. ליסינג תפעולי מיועד לעצמאים וחברות, מאפשר החזר מע״מ והוצאה מוכרת לצרכי מס.'
    ],
    [
        'q' => 'מה כולל התשלום החודשי?',
        'a' => 'ביטוח מקיף + חובה, אחריות יצרן מלאה, טסט, טיפולים, רישוי, ושירותי דרך 24/7. דלק/חשמל וקנסות על הלקוח.'
    ],
    [
        'q' => 'האם יש התחייבות תקופתית?',
        'a' => 'החוזים שלנו גמישים מ־24 עד 60 חודשים. ניתן לסיים מוקדם בקנס מוגדר מראש או להחליף רכב באמצע התקופה בהתאם למסלול.'
    ],
    [
        'q' => 'מה לגבי רכבים חשמליים?',
        'a' => 'יש לנו מסלול EV־Ready עם מתקן טעינה ביתי בהתקנה, ותיאום זמינות טעינה מהירה בתחנות שותפות.'
    ]
];
