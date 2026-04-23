<?php
require_once __DIR__ . '/_bootstrap.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!rate_limit_ok('admin_login', 5, 300)) {
        $error = 'יותר מדי ניסיונות. המתן 5 דקות.';
    } elseif (!csrf_check()) {
        $error = 'הטוקן פג תוקף. רענן ונסה שוב.';
    } else {
        $u = trim($_POST['user'] ?? '');
        $p = $_POST['pass'] ?? '';
        $envUser = env('ADMIN_USER', 'admin');
        $envHash = env('ADMIN_PASS_HASH');

        if ($envHash && $u === $envUser && password_verify($p, $envHash)) {
            session_regenerate_id(true);
            $_SESSION['admin_user'] = $envUser;
            $_SESSION['admin_last'] = time();
            header('Location: index.php');
            exit;
        }
        $error = 'שם משתמש או סיסמה שגויים.';
    }
}

if (!empty($_GET['timeout'])) $error = 'הסשן פג תוקף. נא להתחבר מחדש.';
?>
<!doctype html>
<html lang="he" dir="rtl">
<head>
<meta charset="utf-8">
<title>כניסת מנהל · mcar</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<style>
    body { font-family: 'Rubik', system-ui, sans-serif; background: linear-gradient(135deg, #f4f6fb, #eaeef6); display: grid; place-items: center; min-height: 100vh; margin: 0; color: #0a1740; }
    .card { background: #fff; border: 1px solid rgba(0,35,102,.08); border-radius: 24px; padding: 36px 32px; width: 380px; max-width: calc(100% - 24px); box-shadow: 0 30px 80px -20px rgba(0,35,102,.25); }
    .logo-mark { width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #14b8a6, #0f766e); color: #fff; display: grid; place-items: center; font-weight: 900; font-size: 22px; margin: 0 auto 18px; }
    h1 { font-size: 22px; text-align: center; margin: 0 0 6px; }
    .sub { text-align: center; color: #5a6892; font-size: 13px; margin-bottom: 24px; }
    label { display: block; font-size: 12px; font-weight: 700; color: #5a6892; text-transform: uppercase; letter-spacing: .08em; margin: 14px 0 6px; }
    input[type=text], input[type=password] { width: 100%; padding: 12px 14px; border: 1.5px solid rgba(0,35,102,.14); border-radius: 12px; font-size: 15px; box-sizing: border-box; font-family: inherit; }
    input:focus { outline: none; border-color: #0f766e; box-shadow: 0 0 0 3px rgba(15,118,110,.1); }
    button { width: 100%; padding: 14px; background: linear-gradient(180deg, #14b8a6, #0f766e); color: #fff; border: none; border-radius: 12px; font-weight: 800; font-size: 15px; margin-top: 22px; cursor: pointer; box-shadow: 0 8px 20px -8px #0f766e; }
    .err { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; padding: 10px 14px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; }
</style>
</head>
<body>
    <form class="card" method="POST" autocomplete="on">
        <div class="logo-mark">m</div>
        <h1>כניסת מנהל</h1>
        <div class="sub">mcar admin · עברית · RTL</div>
        <?php if ($error): ?><div class="err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <?php echo csrf_field(); ?>
        <label>שם משתמש</label>
        <input type="text" name="user" required autofocus autocomplete="username">
        <label>סיסמה</label>
        <input type="password" name="pass" required autocomplete="current-password">
        <button type="submit">היכנס</button>
    </form>
</body>
</html>
