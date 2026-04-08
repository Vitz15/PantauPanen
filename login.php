<?php
include 'config/session.php';
include 'config/csrf.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - PantauPanen</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-dark">

<canvas id="bgCanvas"></canvas>

<div class="auth-dark-box">

  <!-- Back -->
  <a href="/" class="auth-back">&#x2190; Kembali ke Beranda</a>

  <!-- Logo -->
  <div class="auth-logo-row">
    <img src="assets/logo.svg" width="53" height="53" class="logo-img">
    <span class="auth-logo-text">PantauPanen</span>
  </div>

  <!-- Title -->
  <div class="auth-dark-title">Selamat Datang</div>
  <div class="auth-dark-sub">Masuk ke akun Anda untuk melanjutkan</div>

  <!-- Alerts -->
  <?php if(isset($_GET['error']) && $_GET['error'] == 'timeout'): ?>
    <div class="auth-alert auth-alert-error">&#x23F1; Session habis, silakan login kembali</div>
  <?php elseif(isset($_GET['error'])): ?>
    <div class="auth-alert auth-alert-error">&#x2715; Username atau password salah</div>
  <?php endif; ?>

  <?php if(isset($_GET['success'])): ?>
    <div class="auth-alert auth-alert-success">&#x2713; Registrasi berhasil! Silakan login</div>
  <?php endif; ?>

  <!-- Form -->
  <form action="process/login_process" method="POST" class="auth-dark-form">

    <div>
      <label class="auth-field-label">Username</label>
      <div class="auth-input-wrap">
        <i class="fa-solid fa-user auth-input-icon"></i>
        <input type="text" name="user" placeholder="Masukkan username" required>
      </div>
    </div>

    <div>
      <label class="auth-field-label">Password</label>
      <div class="auth-input-wrap">
        <i class="fa-solid fa-lock auth-input-icon"></i>
        <input type="password" id="password" name="pass" placeholder="Masukkan password" required>
        <i class="fa-solid fa-eye auth-eye"></i>
      </div>
    </div>

    <?php csrf_input(); ?>
    <button type="submit" class="auth-submit">Masuk &#x2192;</button>

  </form>

  <div class="auth-divider"></div>

  <div class="auth-dark-footer">
    Belum punya akun? <a href="register">Daftar Gratis</a>
  </div>

</div>

<!-- login.js harus di load SEBELUM auth-canvas.js agar AUTH_ORBS tersedia -->
<script src="assets/js/login.js" defer></script>
<script src="assets/js/auth-canvas.js" defer></script>

</body>
</html>