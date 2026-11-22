<?php
require_once __DIR__ . '/includes/auth.php';
require_login();

$settings = load_json(SETTINGS_FILE);
$keysData = load_json(KEYS_FILE);
if (!is_array($keysData)) $keysData = ['keys'=>[]];
if (!isset($keysData['keys']) || !is_array($keysData['keys'])) $keysData['keys'] = [];

$actionMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['action'] ?? '';
    if ($act === 'add_key') {
        $key = trim($_POST['key'] ?? '');
        if ($key === '') {
            $key = strtoupper(bin2hex(random_bytes(8)));
        }
        $days = intval($_POST['days'] ?? 30);
        if ($days < 1) $days = 30;
        $expiry = date('Y-m-d H:i:s', strtotime("+{$days} days"));
        // avoid duplicate
        $exists = false;
        foreach ($keysData['keys'] as $k) { if (($k['key'] ?? '') === $key) { $exists = true; break; } }
        if ($exists) {
            $actionMsg = 'Key da ton tai!';
        } else {
            $keysData['keys'][] = [
                'key' => $key,
                'expiry' => $expiry,
                'created' => date('Y-m-d H:i:s'),
                'note' => trim($_POST['note'] ?? '')
            ];
            save_json(KEYS_FILE, $keysData);
            $actionMsg = 'Da them key: ' . $key;
        }
    } elseif ($act === 'delete_key') {
        $key = $_POST['key'] ?? '';
        $new = [];
        foreach ($keysData['keys'] as $k) {
            if (($k['key'] ?? '') !== $key) $new[] = $k;
        }
        $keysData['keys'] = $new;
        save_json(KEYS_FILE, $keysData);
        $actionMsg = 'Da xoa key.';
    } elseif ($act === 'toggle_maintenance') {
        $settings['maintenance'] = !($settings['maintenance'] ?? false);
        $settings['maintenance_message'] = trim($_POST['maintenance_message'] ?? ($settings['maintenance_message'] ?? ''));
        save_json(SETTINGS_FILE, $settings);
        $actionMsg = 'Da cap nhat trang thai bao tri.';
    } elseif ($act === 'logout') {
        do_logout(); header('Location: /admin/index.php'); exit;
    }
    // reload
    $keysData = load_json(KEYS_FILE);
    $settings = load_json(SETTINGS_FILE);
}

$meta = load_json(PATCHES_META);
$patchCount = is_array($meta) ? count($meta) : 0;
$bu = base_url();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Dashboard - <?php echo h(SITE_NAME); ?></title>
<style>
body{font-family:Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0;padding:20px}
h1,h2{margin:0 0 12px}
.card{background:#1e293b;padding:16px;border-radius:12px;margin-bottom:16px;box-shadow:0 6px 18px rgba(0,0,0,.3)}
.row{display:flex;gap:12px;flex-wrap:wrap}
.stat{flex:1;min-width:160px;background:#0f172a;padding:12px;border-radius:10px;text-align:center}
.stat .n{font-size:28px;font-weight:bold;color:#3b82f6}
table{width:100%;border-collapse:collapse}
th,td{padding:8px;border-bottom:1px solid #334155;text-align:left;font-size:14px}
th{color:#94a3b8}
input,textarea{padding:8px;border:1px solid #334155;border-radius:8px;background:#0f172a;color:#e2e8f0;box-sizing:border-box}
button{padding:8px 12px;background:#3b82f6;color:#fff;border:none;border-radius:8px;cursor:pointer}
button.danger{background:#ef4444}
.badge{display:inline-block;padding:2px 8px;border-radius:999px;font-size:12px}
.ok{background:#16a34a}.off{background:#64748b}
.msg{background:#1d4ed8;padding:10px;border-radius:8px;margin-bottom:12px}
code{background:#0f172a;padding:2px 6px;border-radius:6px}
</style>
</head>
<body>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
<h1><?php echo h(SITE_NAME); ?> — Dashboard</h1>
<form method="post" style="margin:0"><input type="hidden" name="action" value="logout"><button class="danger" type="submit">Dang xuat</button></form>
</div>

<?php if($actionMsg) echo '<div class="msg">'.h($actionMsg).'</div>'; ?>

<div class="row">
<div class="stat"><div>So key</div><div class="n"><?php echo count($keysData['keys']); ?></div></div>
<div class="stat"><div>So patch</div><div class="n"><?php echo $patchCount; ?></div></div>
<div class="stat"><div>Bao tri</div><div class="n"><?php echo ($settings['maintenance']?'BAT':'TAT'); ?></div></div>
</div>

<div class="card">
<h2>Endpoints (cho app)</h2>
<p>Verify: <code><?php echo h($bu); ?>/ipa/jerry/api.php</code></p>
<p>Maintenance: <code><?php echo h($bu); ?>/ipa/jerry/apibaotri.php</code></p>
<p>List: <code><?php echo h($bu); ?>/ipa/jerry/list.php?v=1</code></p>
<p>Patch URL mau: <code><?php echo h($bu); ?>/ipa/jerry/ffnormal/AIM%20-%20ESP/Aim.3105</code></p>
</div>

<div class="card">
<h2>Quan ly Key</h2>
<form method="post" style="margin-bottom:12px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
<input type="hidden" name="action" value="add_key">
<input name="key" placeholder="Key (de trong de tu sinh)">
<input name="days" type="number" value="30" style="width:90px">
<input name="note" placeholder="Ghi chu">
<button type="submit">Them key</button>
</form>
<table>
<tr><th>Key</th><th>Het han</th><th>Ngay tao</th><th>Ghi chu</th><th></th></tr>
<?php foreach ($keysData['keys'] as $k): ?>
<tr>
<td><code><?php echo h($k['key']); ?></code></td>
<td><?php echo h($k['expiry']); ?></td>
<td><?php echo h($k['created'] ?? ''); ?></td>
<td><?php echo h($k['note'] ?? ''); ?></td>
<td>
<form method="post" style="margin:0" onsubmit="return confirm('Xoa key nay?')">
<input type="hidden" name="action" value="delete_key">
<input type="hidden" name="key" value="<?php echo h($k['key']); ?>">
<button class="danger" type="submit">Xoa</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($keysData['keys'])): ?>
<tr><td colspan="5" style="text-align:center;color:#94a3b8">Chua co key nao</td></tr>
<?php endif; ?>
</table>
</div>

<div class="card">
<h2>Bao tri (Maintenance)</h2>
<form method="post">
<input type="hidden" name="action" value="toggle_maintenance">
<p>Trang thai hien tai: <span class="badge <?php echo ($settings['maintenance']?'ok':'off'); ?>"><?php echo ($settings['maintenance']?'DANG BAT':'DANG TAT'); ?></span></p>
<textarea name="maintenance_message" rows="3" style="width:100%"><?php echo h($settings['maintenance_message'] ?? ''); ?></textarea>
<br><br>
<button type="submit"><?php echo ($settings['maintenance']?'Tat bao tri':'Bat bao tri'); ?></button>
</form>
</div>

<div class="card">
<h2>Huong dan ket noi app</h2>
<ol>
<li>Upload toan bo thu muc nay len cPanel (public_html).</li>
<li>Doi mat khau admin trong config.php.</li>
<li>Sua hosts tren iPhone jailbreak: <code>IP_SERVER solitudepremium.click</code> (hoac patch URL trong binary neu can).</li>
<li>Them key trong dashboard, dung key do trong app.</li>
</ol>
</div>
</body>
</html>
