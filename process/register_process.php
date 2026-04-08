<?php
session_set_cookie_params(['lifetime'=>0,'path'=>'/','domain'=>'','secure'=>true,'httponly'=>true,'samesite'=>'Strict']);
session_start();
include '../config/koneksi.php';
include '../config/csrf.php';
include '../config/logger.php';

/* E2: Verifikasi CSRF */
csrf_verify('../register.php');

$user    = trim($_POST['user']    ?? '');
$pass    = trim($_POST['pass']    ?? '');
$confirm = trim($_POST['confirm'] ?? '');

if(strlen($user) < 3 || strlen($pass) < 3){
    header("Location: ../register?error=short");
    exit;
}

if(strlen($user) > 50 || strlen($pass) > 128){
    header("Location: ../register?error=short");
    exit;
}

if(!preg_match('/^[a-zA-Z0-9_]+$/', $user)){
    header("Location: ../register?error=invalid");
    exit;
}

if($pass !== $confirm){
    header("Location: ../register?error=notmatch");
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    header("Location: ../register?error=exist");
    exit;
}

$hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $user, $hash);

if($stmt->execute()){
    write_log($conn, 'REGISTER', (int)$conn->insert_id, 'username='.$user);
    header("Location: ../login?success=1");
    exit;
} else {
    header("Location: ../register?error=failed");
    exit;
}