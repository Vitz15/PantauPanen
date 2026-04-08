<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

$conn = mysqli_connect("localhost", "pantaupanen", "PantauPanen@2026!", "pantaupanen_db");
if(!$conn){ 
    error_log("DB connection failed: " . mysqli_connect_error());
    die("Terjadi kesalahan sistem. Silakan coba lagi.");}
?>