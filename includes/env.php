<?php
/**
 * Tiny .env loader (no dependencies).
 * Reads c:\mcar\.env (or APP_ROOT/.env on the server) and populates $_ENV.
 *
 * Usage: env('DB_HOST', 'localhost')
 */

if (!function_exists('env')) {

    function load_env($path = null) {
        $path = $path ?: dirname(__DIR__) . '/.env';
        if (!is_readable($path)) return false;

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') continue;
            if (strpos($line, '=') === false) continue;

            list($key, $val) = array_map('trim', explode('=', $line, 2));
            // Strip surrounding quotes if present
            if (strlen($val) >= 2 && (($val[0] === '"' && substr($val, -1) === '"') || ($val[0] === "'" && substr($val, -1) === "'"))) {
                $val = substr($val, 1, -1);
            }
            $_ENV[$key] = $val;
            putenv("$key=$val");
        }
        return true;
    }

    function env($key, $default = null) {
        if (isset($_ENV[$key])) return $_ENV[$key];
        $val = getenv($key);
        return $val === false ? $default : $val;
    }

    // Auto-load on include
    load_env();
}
