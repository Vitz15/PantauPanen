<?php
function csrf_generate(): string {
    if(empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_input(): void {
    $token = htmlspecialchars(csrf_generate(), ENT_QUOTES, 'UTF-8');
    echo '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

function csrf_verify(string $redirect = '../dashboard'): void {
    $token_post    = $_POST['csrf_token']    ?? '';
    $token_session = $_SESSION['csrf_token'] ?? '';

    if(empty($token_post) || empty($token_session) ||
       !hash_equals($token_session, $token_post)) {
        http_response_code(403);
        header("Location: " . $redirect . "?error=csrf");
        exit;
    }
}