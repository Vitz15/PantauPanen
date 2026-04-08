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
include '../config/koneksi.php';
include '../config/csrf.php';
include '../config/logger.php';

/* E2: Verifikasi CSRF token */
csrf_verify('../login.php');

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data:; connect-src 'self';");

$user = trim($_POST['user'] ?? '');
$pass = trim($_POST['pass'] ?? '');

if(empty($user) || empty($pass)){
    header("Location: ../login?error=1");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1){
    $row = $result->fetch_assoc();

    if(password_verify($pass, $row['password'])){

        session_regenerate_id(true);

        $_SESSION['login']         = true;
        $_SESSION['username']      = $row['username'];
        $_SESSION['user_id']       = $row['id'];
        $_SESSION['last_activity'] = time();
        $_SESSION['ip']            = $_SERVER['REMOTE_ADDR'];
        $_SESSION['ua']            = $_SERVER['HTTP_USER_AGENT'];

        // G1: Catat login berhasil
        write_log($conn, 'LOGIN_SUCCESS', $row['id'], "username={$user}");

        header("Location: ../dashboard");
        exit;
    }
}

// G2: Catat login gagal
write_log($conn, 'LOGIN_FAILED', 0, "username={$user}");

header("Location: ../login?error=1");
exit;