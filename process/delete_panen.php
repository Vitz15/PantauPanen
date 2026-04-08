<?php
session_start();
include '../config/csrf.php';
csrf_verify();
include '../config/koneksi.php';
include '../config/logger.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$id      = (int) ($_POST['id'] ?? 0);

if($id <= 0){
    header("Location: ../dashboard?error=invalid");
    exit;
}

$stmt = $conn->prepare("DELETE FROM panen WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);

if($stmt->execute() && $stmt->affected_rows > 0){
    write_log($conn,'DELETE_PANEN',$user_id,"id={$id}");
    header("Location: ../dashboard?deleted=1");
    exit;
} else {
    header("Location: ../dashboard?error=failed");
    exit;
}
?>