<?php
/* ============================================================
   config/csrf.php — PantauPanen
   OWASP E: CSRF Token Helper
   ============================================================ */

/**
 * Generate CSRF token unik per session (E1, E2, E3)
 * Token dibuat sekali per session dan disimpan di $_SESSION
 */
function csrf_generate(): string {
    if(empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Cetak hidden input CSRF — gunakan di dalam setiap <form>
 * Contoh: <?php csrf_input(); ?>
 */
function csrf_input(): void {
    $token = htmlspecialchars(csrf_generate(), ENT_QUOTES, 'UTF-8');
    echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Verifikasi CSRF token dari POST — panggil di setiap process file
 * Jika gagal, redirect dengan error dan hentikan eksekusi
 */
function csrf_verify(string $redirect = '../dashboard'): void {
    $token_post    = $_POST['csrf_token']    ?? '';
    $token_session = $_SESSION['csrf_token'] ?? '';

    /* E2: Verifikasi server-side dengan hash_equals (timing-safe) */
    if(empty($token_post) || empty($token_session) ||
       !hash_equals($token_session, $token_post)) {
        http_response_code(403);
        header("Location: " . $redirect . "?error=csrf");
        exit;
    }
}