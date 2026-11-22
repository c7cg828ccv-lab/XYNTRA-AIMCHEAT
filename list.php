<?php
require_once __DIR__ . '/../../config.php';
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
$meta = load_json(PATCHES_META);
if (!is_array($meta)) $meta = [];
$base = base_url();
$out = [];
foreach ($meta as $it) {
    $game = $it['gameType'] ?? '';
    $folder = $it['folder'] ?? '';
    $filename = $it['filename'] ?? '';
    $path = '/ipa/jerry/' . $game . '/' . rawurlencode($folder) . '/' . rawurlencode($filename);
    // rawurlencode encodes spaces as %20; but folder may have spaces; keep as original style? Original used %20. Good.
    $it['url'] = $base . $path;
    $out[] = $it;
}
echo json_encode($out, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
