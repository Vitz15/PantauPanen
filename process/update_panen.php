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

$id          = (int) ($_POST['id'] ?? 0);
$nama_petani = trim($_POST['nama_petani'] ?? '');
$tanggal     = $_POST['tanggal'] ?? '';
$komoditas   = trim($_POST['komoditas'] ?? '');
$jumlah      = $_POST['jumlah'] ?? 0;

if($id <= 0 || empty($nama_petani) || empty($tanggal) || empty($komoditas) || $jumlah <= 0){
    header("Location: ../dashboard?error=invalid");
    exit;
}

$stmt = $conn->prepare("UPDATE panen SET nama_petani=?, tanggal=?, komoditas=?, jumlah=? WHERE id=? AND user_id=?");
$stmt->bind_param("sssiii", $nama_petani, $tanggal, $komoditas, $jumlah, $id, $user_id);

if($stmt->execute() && $stmt->affected_rows > 0){
    write_log($conn,'UPDATE_PANEN',$user_id,"id={$id} komoditas={$komoditas}");
    header("Location: ../dashboard?updated=1");
    exit;
} else {
    header("Location: ../dashboard?error=failed");
    exit;
}
?>