<?php
include 'config/session.php';
include 'config/koneksi.php';

// Panen hari ini
$today = date('Y-m-d');
$q_hari = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah),0) as total FROM panen WHERE tanggal = '$today'"));
$panen_hari = number_format($q_hari['total']);

// Komoditas terbanyak
$q_kom = mysqli_fetch_assoc(mysqli_query($conn, "SELECT komoditas, COUNT(*) as cnt FROM panen GROUP BY komoditas ORDER BY cnt DESC LIMIT 1"));
$kom_aktif = $q_kom ? htmlspecialchars($q_kom['komoditas']) : '-';
$kom_cnt = $q_kom ? $q_kom['cnt'] : 0;

// Total petani unik
$q_petani = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT nama_petani) as total FROM panen"));
$total_petani = $q_petani['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PantauPanen - Sulawesi Utara</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/landing.css">
</head>
<body class="landing-body">

<!-- ANIMATED BACKGROUND CANVAS -->
<canvas id="bgCanvas"></canvas>

<div class="land-wrap">

  <!-- NAVBAR -->
  <nav class="land-nav">
    <div class="land-logo">
      <img src="assets/logo.svg" width="50" height="50" class="logo-img">
      <span class="land-logo-text">Pantau Ikan Skill</span>
    </div>
    <a href="login" class="land-nav-btn">Masuk &#x2192;</a>
  </nav>

  <!-- HERO -->
  <section class="land-hero">

    <div class="land-badge">
      <span class="land-badge-dot"></span>
      Platform Pertanian Digital
    </div>

    <h1 class="land-title">
      Monitor<br>
      <span class="land-title-accent">Hasil Panen</span>
    </h1>

    <p class="land-subtitle-region">Sulawesi Utara</p>

    <p class="land-desc">
      Catat, pantau, dan analisis data hasil pertanian Anda secara real-time. Satu platform untuk semua kebutuhan monitoring komoditas.
    </p>

    <div class="land-cta-group">
      <a href="login" class="land-btn-primary">
        &#x1F680; Mulai Sekarang
      </a>
      <a href="register" class="land-btn-secondary">
        Daftar Gratis &#x2192;
      </a>
    </div>

    <!-- STATS -->
    <div class="land-stats">
      <div class="land-stat">
        <div class="land-stat-num">15<span>+</span></div>
        <div class="land-stat-label">Komoditas</div>
      </div>
      <div class="land-stat">
        <div class="land-stat-num">100<span>%</span></div>
        <div class="land-stat-label">Digital</div>
      </div>
      <div class="land-stat">
        <div class="land-stat-num">24<span>/7</span></div>
        <div class="land-stat-label">Akses Data</div>
      </div>
    </div>

  </section>

  <!-- SCROLL INDICATOR -->
  <div class="land-scroll">
    <div class="land-scroll-line"></div>
    <div class="land-scroll-text">Scroll</div>
  </div>

</div>

<!-- FLOATING INFO CARDS -->
<div class="land-float-cards">
  <div class="land-float-card">
    <div class="land-float-card-label">Panen Hari Ini</div>
    <div class="land-float-card-value"><?php echo $panen_hari ?: '0'; ?></div>
    <span class="land-float-card-tag tag-green">&#x1F4C5; <?php echo date('d M Y'); ?></span>
  </div>
  <div class="land-float-card">
    <div class="land-float-card-label">Komoditas Terbanyak</div>
    <div class="land-float-card-value"><?php echo $kom_aktif ?: '-'; ?></div>
    <span class="land-float-card-tag tag-yellow"><?php echo $kom_cnt; ?> catatan</span>
  </div>
  <div class="land-float-card">
    <div class="land-float-card-label">Total Petani</div>
    <div class="land-float-card-value"><?php echo $total_petani; ?></div>
    <span class="land-float-card-tag tag-blue">Terdaftar</span>
  </div>
</div>

<script src="assets/js/landing.js"></script>

</body>
</html>