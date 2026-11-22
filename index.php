<?php
require_once __DIR__ . '/includes/auth.php';
if (is_logged_in()) { header('Location: /admin/dashboard.php'); exit; }
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (do_login(trim($_POST['user'] ?? ''), $_POST['pass'] ?? '')) {
        header('Location: /admin/dashboard.php'); exit;
    } else {
        $msg = 'Sai tai khoan hoac mat khau!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Dang nhap - <?php echo h(SITE_NAME); ?></title>
<style>
body{font-family:Arial,sans-serif;background:#0f172a;color:#e2e8f0;display:flex;justify-content:center;align-items:center;height:100vh;margin:0}
.card{background:#1e293b;padding:30px;border-radius:12px;width:320px;box-shadow:0 10px 30px rgba(0,0,0,.4)}
h2{text-align:center;margin-top:0}
input{width:100%;padding:10px;margin:8px 0;border:1px solid #334155;border-radius:8px;background:#0f172a;color:#e2e8f0;box-sizing:border-box}
button{width:100%;padding:10px;background:#3b82f6;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:16px}
.err{color:#f87171;text-align:center}
</style>
</head>
<body>
<div class="card">
<h2><?php echo h(SITE_NAME); ?></h2>
<?php if($msg) echo '<p class="err">'.h($msg).'</p>'; ?>
<form method="post">
<input name="user" placeholder="Tai khoan" required>
<input name="pass" type="password" placeholder="Mat khau" required>
<button type="submit">Dang nhap</button>
</form>
<p style="font-size:12px;color:#94a3b8;text-align:center">Hay dat mat khau quan tri rieng trong config.php truoc khi su dung.</p>
</div>
</body>
</html>
