<?php
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
$settings = load_json(SETTINGS_FILE);
$maintenance = $settings['maintenance'] ?? false;
$msg = $settings['maintenance_message'] ?? 'He thong dang hoat dong binh thuong.';
echo json_encode([
    'status'=>'success',
    'maintenance'=>$maintenance,
    'message'=>$msg
], JSON_UNESCAPED_UNICODE);
