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

$nama_petani = trim($_POST['nama_petani'] ?? '');
$tanggal     = $_POST['tanggal'] ?? '';
$komoditas   = trim($_POST['komoditas'] ?? '');
$jumlah      = $_POST['jumlah'] ?? 0;

if(empty($nama_petani) || empty($tanggal) || empty($komoditas) || $jumlah <= 0){
    header("Location: ../dashboard?error=invalid");
    exit;
}

$stmt = $conn->prepare("INSERT INTO panen (nama_petani, tanggal, komoditas, jumlah, user_id) VALUES (?,?,?,?,?)");
$stmt->bind_param("sssii", $nama_petani, $tanggal, $komoditas, $jumlah, $user_id);

if($stmt->execute()){
    write_log($conn,'TAMBAH_PANEN',$user_id,"komoditas={$komoditas} jumlah={$jumlah} petani={$nama_petani}");
    header("Location: ../dashboard?success=1");
    exit;
} else {
    header("Location: ../dashboard?error=failed");
    exit;
}
?>