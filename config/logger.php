<?php
/* ============================================================
   config/logger.php — PantauPanen
   OWASP G: Security Logging & Monitoring
   ============================================================ */

/**
 * Catat aktivitas ke tabel activity_log dan file log
 *
 * @param mysqli  $conn    Koneksi database
 * @param string  $action  Jenis aksi: LOGIN_SUCCESS, LOGIN_FAILED, LOGOUT,
 *                         TAMBAH_PANEN, UPDATE_PANEN, DELETE_PANEN, REGISTER
 * @param int     $user_id ID user (0 jika belum login)
 * @param string  $info    Info tambahan (opsional)
 */
function write_log(mysqli $conn, string $action, int $user_id = 0, string $info = ''): void {

    $ip        = $_SERVER['REMOTE_ADDR']     ?? 'unknown';
    $ua        = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $timestamp = date('Y-m-d H:i:s');

    /* --- G1/G2/G3: Simpan ke database --- */
    $stmt = $conn->prepare(
        "INSERT INTO activity_log (user_id, action, info, ip_address, user_agent, created_at)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    if($stmt) {
        $stmt->bind_param("isssss", $user_id, $action, $info, $ip, $ua, $timestamp);
        $stmt->execute();
        $stmt->close();
    }

    /* --- G4: Simpan ke file log (tidak bisa diakses publik) --- */
    $log_dir  = dirname(__DIR__) . '/logs';
    $log_file = $log_dir . '/activity_' . date('Y-m') . '.log';

    /* Buat folder logs jika belum ada */
    if(!is_dir($log_dir)) {
        mkdir($log_dir, 0700, true); // Permission ketat: hanya owner
    }

    /* Buat .htaccess di folder logs agar tidak bisa diakses browser */
    $htaccess = $log_dir . '/.htaccess';
    if(!file_exists($htaccess)) {
        file_put_contents($htaccess, "Deny from all\n");
    }

    $line = sprintf(
        "[%s] [%s] user_id=%d ip=%s info=%s\n",
        $timestamp,
        $action,
        $user_id,
        $ip,
        $info ?: '-'
    );

    file_put_contents($log_file, $line, FILE_APPEND | LOCK_EX);
}