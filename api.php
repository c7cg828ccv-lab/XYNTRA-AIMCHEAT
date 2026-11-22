<?php
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

$keysData = load_json(KEYS_FILE);
if (!is_array($keysData) || !isset($keysData['keys'])) $keysData = ['keys'=>[]];

$action = $_REQUEST['action'] ?? $_REQUEST['act'] ?? '';
$key = trim($_REQUEST['key'] ?? $_REQUEST['keyCode'] ?? $_REQUEST['license'] ?? '');

if ($action === 'verify_app_key' || $action === 'verify' || $action === 'check_key' || $action === '') {
    if ($key === '') {
        echo json_encode(['status'=>'error','message'=>'Key khong duoc de trong.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    // Bao mat: danh sach trong khong co nghia la chap nhan moi key.
    if (empty($keysData['keys'])) {
        echo json_encode(['status'=>'error','message'=>'He thong chua co key hop le.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    // Tim key
    $found = null;
    foreach ($keysData['keys'] as $k) {
        if (($k['key'] ?? '') === $key) { $found = $k; break; }
    }
    if (!$found) {
        echo json_encode(['status'=>'error','message'=>'Ma Key khong ton tai tren he thong!'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    // Kiem tra het han
    $expiry = $found['expiry'] ?? '2099-12-31 23:59:59';
    if (strtotime($expiry) < time()) {
        echo json_encode(['status'=>'error','message'=>'Key da het han!'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    echo json_encode([
        'status'=>'success',
        'message'=>'Xac thuc thanh cong. Chao mung ban!',
        'expiryDate'=>$expiry,
        'expiry'=>$expiry,
        'keyExpiry'=>$expiry,
        'savedExpiry'=>$expiry
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['status'=>'error','message'=>'Hanh dong (Action) khong hop le.'], JSON_UNESCAPED_UNICODE);
