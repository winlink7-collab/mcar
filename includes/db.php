<?php
/**
 * mcar Database — PDO singleton
 * Returns a PDO instance if .env is configured, else returns null.
 * The site continues working with config.php arrays as fallback.
 */

require_once __DIR__ . '/env.php';

function db() {
    static $pdo = null;
    if ($pdo !== null) return $pdo === false ? null : $pdo;

    $host = env('DB_HOST');
    $name = env('DB_NAME');
    $user = env('DB_USER');
    $pass = env('DB_PASS');

    if (!$host || !$name || !$user) {
        $pdo = false; // mark as tried + failed
        return null;
    }

    try {
        $charset = env('DB_CHARSET', 'utf8mb4');
        $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        if (env('APP_DEBUG') === 'true') {
            error_log('DB connection failed: ' . $e->getMessage());
        }
        $pdo = false;
        return null;
    }
}

/**
 * Insert a lead (contact form submission) into the leads table.
 * Returns inserted ID on success, false on failure or no DB.
 */
function save_lead($data) {
    $pdo = db();
    if (!$pdo) return false;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO leads (name, phone, email, source, car_id, pkg_id, deal_type, message, ip, user_agent, created_at)
            VALUES (:name, :phone, :email, :source, :car_id, :pkg_id, :deal_type, :message, :ip, :ua, NOW())
        ");
        $stmt->execute([
            ':name'      => $data['name'] ?? null,
            ':phone'     => $data['phone'] ?? null,
            ':email'     => $data['email'] ?? null,
            ':source'    => $data['source'] ?? 'web',
            ':car_id'    => $data['car_id'] ?? null,
            ':pkg_id'    => $data['pkg_id'] ?? null,
            ':deal_type' => $data['deal_type'] ?? null,
            ':message'   => $data['message'] ?? null,
            ':ip'        => $_SERVER['REMOTE_ADDR'] ?? null,
            ':ua'        => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
        return (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log('save_lead failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Fetch all cars from DB, ordered by creation date.
 * Falls back to $CARS array from config.php if DB is not connected.
 */
function get_cars() {
    $pdo = db();
    if (!$pdo) {
        global $CARS;
        return $CARS ?? [];
    }

    try {
        $rows = $pdo->query("SELECT * FROM cars WHERE active = 1 ORDER BY featured DESC, id ASC")->fetchAll();
        // Decode the JSON columns back to arrays so the existing code can use them
        foreach ($rows as &$r) {
            if (isset($r['monthly']) && is_string($r['monthly']))   $r['monthly']  = json_decode($r['monthly'], true);
            if (isset($r['features']) && is_string($r['features'])) $r['features'] = json_decode($r['features'], true);
            $r['bestValue'] = !empty($r['best_value']);
            $r['verified']  = !empty($r['verified']);
        }
        return $rows;
    } catch (PDOException $e) {
        global $CARS;
        return $CARS ?? [];
    }
}
