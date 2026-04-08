<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'domain'   => '',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict',
]);

session_start();
include 'config/koneksi.php';
include 'config/logger.php';

// G1: Catat logout sebelum session dihancurkan
$user_id  = $_SESSION['user_id'] ?? 0;
$username = $_SESSION['username'] ?? '-';
write_log($conn, 'LOGOUT', $user_id, "username={$username}");

session_unset();
session_destroy();

setcookie(session_name(), '', [
    'expires'  => time() - 3600,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Strict',
]);

header("Location: login");
exit;
?>