<?php
include 'session.php';

$timeout = 1800;

if(isset($_SESSION['last_activity'])){
    if(time() - $_SESSION['last_activity'] > $timeout){
        session_unset();
        session_destroy();

        setcookie(session_name(), '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'secure'   => true,
            'httponly' => true,
            'samesite' => 'Strict',
        ]);

        header("Location: login?error=timeout");
        exit;
    }
}

$_SESSION['last_activity'] = time();

if(!isset($_SESSION['login'])){
    header("Location: login");
    exit;
}