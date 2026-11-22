<?php
// ===== CAU HINH - CHINH SUA TRUOC KHI UPLOAD =====
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'CHANGE_THIS_TO_A_LONG_RANDOM_PASSWORD'); // Bat buoc doi truoc khi dua len hosting
define('SITE_NAME', 'CheatiOSVip Key Manager');
define('DATA_DIR', __DIR__ . '/data');
define('KEYS_FILE', DATA_DIR . '/keys.json');
define('SETTINGS_FILE', DATA_DIR . '/settings.json');
define('PATCHES_META', DATA_DIR . '/patches_meta.json');

// Tao thu muc data neu chua co
if (!is_dir(DATA_DIR)) { @mkdir(DATA_DIR, 0755, true); }

// Khoi tao file neu chua co
if (!file_exists(KEYS_FILE)) { file_put_contents(KEYS_FILE, json_encode(['keys'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); }
if (!file_exists(SETTINGS_FILE)) { file_put_contents(SETTINGS_FILE, json_encode(['maintenance'=>false,'maintenance_message'=>'He thong dang bao tri. Vui long doi.'], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); }

function load_json($file) {
    if (!file_exists($file)) return null;
    $raw = file_get_contents($file);
    $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
    return json_decode($raw, true);
}
function save_json($file, $data) {
    return file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), LOCK_EX);
}
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function base_url() {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
}
?>
