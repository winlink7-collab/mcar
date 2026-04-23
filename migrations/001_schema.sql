-- mcar — Initial schema
-- Run this on Cloudways → Launch Database Manager (phpMyAdmin)
-- After importing, run 002_seed.sql to populate cars data.

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- =====================================================
-- categories: car categories
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    id      VARCHAR(40) PRIMARY KEY,
    label   VARCHAR(100) NOT NULL,
    short   VARCHAR(50) NOT NULL,
    sort    INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- engine_types
-- =====================================================
CREATE TABLE IF NOT EXISTS engine_types (
    id     VARCHAR(40) PRIMARY KEY,
    label  VARCHAR(100) NOT NULL,
    color  VARCHAR(20) NOT NULL,
    glyph  VARCHAR(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- cars: vehicle catalog
-- =====================================================
CREATE TABLE IF NOT EXISTS cars (
    id          VARCHAR(60) PRIMARY KEY,
    make        VARCHAR(80) NOT NULL,
    model       VARCHAR(80) NOT NULL,
    trim        VARCHAR(80),
    category    VARCHAR(40) NOT NULL,
    engine      VARCHAR(40) NOT NULL,
    hp          INT,
    consumption VARCHAR(60),
    seats       TINYINT,
    accel       VARCHAR(20),
    monthly     JSON NOT NULL COMMENT 'Map: {private, operational, purchase}',
    stock       DECIMAL(3,2) DEFAULT 0.5,
    best_value  TINYINT(1) DEFAULT 0,
    verified    TINYINT(1) DEFAULT 1,
    features    JSON,
    warranty    VARCHAR(120),
    delivery    VARCHAR(120),
    image_url   VARCHAR(500),
    active      TINYINT(1) DEFAULT 1,
    featured    TINYINT(1) DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_engine (engine),
    INDEX idx_active_featured (active, featured),
    FOREIGN KEY (category) REFERENCES categories(id) ON UPDATE CASCADE,
    FOREIGN KEY (engine)   REFERENCES engine_types(id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- packages
-- =====================================================
CREATE TABLE IF NOT EXISTS packages (
    id         VARCHAR(40) PRIMARY KEY,
    title      VARCHAR(60) NOT NULL,
    sub        VARCHAR(120),
    icon       VARCHAR(40),
    price      INT NOT NULL,
    pitch      TEXT,
    features   JSON,
    km         VARCHAR(60),
    fuel       VARCHAR(60),
    featured   TINYINT(1) DEFAULT 0,
    sort       INT DEFAULT 0,
    active     TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- leads: contact form submissions
-- =====================================================
CREATE TABLE IF NOT EXISTS leads (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120) NOT NULL,
    phone       VARCHAR(40),
    email       VARCHAR(160),
    source      VARCHAR(80) DEFAULT 'web',
    car_id      VARCHAR(60),
    pkg_id      VARCHAR(40),
    deal_type   VARCHAR(40),
    message     TEXT,
    ip          VARCHAR(45),
    user_agent  VARCHAR(500),
    status      ENUM('new','contacted','qualified','closed','lost') DEFAULT 'new',
    notes       TEXT,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_created (created_at DESC),
    INDEX idx_status (status),
    INDEX idx_car (car_id),
    INDEX idx_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- admin_sessions (optional — used by admin panel)
-- =====================================================
CREATE TABLE IF NOT EXISTS admin_sessions (
    token       VARCHAR(64) PRIMARY KEY,
    user        VARCHAR(60) NOT NULL,
    ip          VARCHAR(45),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at  TIMESTAMP NOT NULL,
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
