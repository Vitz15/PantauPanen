<?php
include 'config/session.php';
include 'config/csrf.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - PantauPanen</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-dark">

<canvas id="bgCanvas"></canvas>

<div class="auth-dark-box">

  <a href="/" class="auth-back">&#x2190; Kembali ke Beranda</a>

  <div class="auth-logo-row">
    <div class="auth-logo-icon"><img src="assets/logo.svg" width="53" height="53" class="logo-img"></div>
    <span class="auth-logo-text">PantauPanen</span>
  </div>

  <div class="auth-dark-title">Buat Akun</div>
  <div class="auth-dark-sub">Daftar gratis dan mulai monitoring panen Anda</div>

  <!-- Alerts -->
  <?php if(isset($_GET['error'])): ?>
    <?php if($_GET['error'] == 'exist'): ?>
      <div class="auth-alert auth-alert-error">&#x2715; Username sudah digunakan</div>
    <?php elseif($_GET['error'] == 'short'): ?>
      <div class="auth-alert auth-alert-error">&#x2715; Username &amp; password minimal 3 karakter</div>
    <?php elseif($_GET['error'] == 'notmatch'): ?>
      <div class="auth-alert auth-alert-error">&#x2715; Password tidak cocok</div>
    <?php else: ?>
      <div class="auth-alert auth-alert-error">&#x2715; Terjadi kesalahan, coba lagi</div>
    <?php endif; ?>
  <?php endif; ?>

  <?php if(isset($_GET['success'])): ?>
    <div class="auth-alert auth-alert-success">&#x2713; Registrasi berhasil! Silakan login</div>
  <?php endif; ?>

  <!-- Form -->
  <form action="process/register_process" method="POST" class="auth-dark-form">

    <div>
      <label class="auth-field-label">Username</label>
      <div class="auth-input-wrap">
        <i class="fa-solid fa-user auth-input-icon"></i>
        <input type="text" name="user" id="reg_user" placeholder="Buat username unik" required>
      </div>
    </div>

    <div>
      <label class="auth-field-label">Password</label>
      <div class="auth-input-wrap">
        <i class="fa-solid fa-lock auth-input-icon"></i>
        <input type="password" id="password" name="pass" placeholder="Buat password kuat" required>
        <i class="fa-solid fa-eye auth-eye" data-target="password"></i>
      </div>
      <div class="strength-bar-wrap">
        <div class="strength-bar" id="strengthBar"></div>
      </div>
      <div class="strength-hint" id="strengthHint"></div>
    </div>

    <div>
      <label class="auth-field-label">Konfirmasi Password</label>
      <div class="auth-input-wrap">
        <i class="fa-solid fa-lock auth-input-icon"></i>
        <input type="password" id="confirm" name="confirm" placeholder="Ulangi password" required>
        <i class="fa-solid fa-eye auth-eye" data-target="confirm"></i>
      </div>
      <div class="match-hint" id="matchHint"></div>
    </div>

    <?php csrf_input(); ?>
    <button type="submit" class="auth-submit">Daftar Sekarang &#x2192;</button>

  </form>

  <div class="auth-terms">
    Dengan mendaftar, Anda setuju menggunakan platform ini<br>untuk keperluan monitoring pertanian.
  </div>

  <div class="auth-divider"></div>

  <div class="auth-dark-footer">
    Sudah punya akun? <a href="login">Masuk di sini</a>
  </div>

</div>

<!-- register.js harus di load SEBELUM auth-canvas.js agar AUTH_ORBS tersedia -->
<script src="assets/js/register.js"></script>
<script src="assets/js/auth-canvas.js"></script>

</body>
</html>