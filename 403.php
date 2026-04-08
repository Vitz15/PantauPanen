<?php
include 'config/session.php';
$username = $_SESSION['username'] ?? null; // tetap null kalau belum login
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>403 - Akses Ditolak</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- CSS EKSTERNAL YANG SAMA DENGAN ERROR 404 -->
<link rel="stylesheet" href="/assets/css/error.css">
</head>
<body>

<div class="error-wrap">

  <div class="error-card">

    <div class="error-code">403</div>

    <div class="error-title">Akses Ditolak</div>

    <div class="error-desc">
      Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
    </div>

    <div class="error-actions">
      <?php if($username): ?>
        <a href="/dashboard" class="error-btn primary">
          <i class="fa-solid fa-chart-line"></i> Kembali ke Dashboard
        </a>
      <?php else: ?>
        <a href="/" class="error-btn primary">
          <i class="fa-solid fa-house"></i> Kembali ke Beranda
        </a>
      <?php endif; ?>
    </div>

  </div>

</div>

</body>
</html>