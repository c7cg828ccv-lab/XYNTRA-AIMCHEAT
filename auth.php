<?php
require_once __DIR__ . '/../config.php';
session_start();
function is_logged_in(){ return !empty($_SESSION['admin']); }
function require_login(){
    if (!is_logged_in()) {
        header('Location: /admin/index.php');
        exit;
    }
}
function do_login($user,$pass){
    if ($user === ADMIN_USER && hash_equals(ADMIN_PASS, $pass)) {
        $_SESSION['admin'] = $user;
        return true;
    }
    return false;
}
function do_logout(){ $_SESSION = []; session_destroy(); }
?>
