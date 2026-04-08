<?php
include 'config/auth.php';
include 'config/koneksi.php';
include 'config/csrf.php';
include 'config/logger.php';

$username = $_SESSION['username'];
$user_id  = (int) $_SESSION['user_id'];

header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; style-src 'self' https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data:; connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;");

// ================================================================
// C3 FIX: Semua query menggunakan prepared statement
// ================================================================

// Chart data
$stmt_chart = $conn->prepare("SELECT komoditas, SUM(jumlah) as total FROM panen WHERE user_id=? GROUP BY komoditas");
$stmt_chart->bind_param("i", $user_id);
$stmt_chart->execute();
$data_chart = $stmt_chart->get_result();
$labels = []; $values = [];
while($d = $data_chart->fetch_assoc()){ $labels[] = $d['komoditas']; $values[] = $d['total']; }
$stmt_chart->close();

// Cek mode edit
$edit_mode = false;
$edit_data = null;
if(isset($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $stmt_edit = $conn->prepare("SELECT * FROM panen WHERE id=? AND user_id=?");
  $stmt_edit->bind_param("ii", $edit_id, $user_id);
  $stmt_edit->execute();
  $result_edit = $stmt_edit->get_result();
  if($edit_data = $result_edit->fetch_assoc()) $edit_mode = true;
  $stmt_edit->close();
}

// Data cards
$stmt_count = $conn->prepare("SELECT COUNT(*) as total FROM panen WHERE user_id=?");
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$count = $stmt_count->get_result()->fetch_assoc();
$stmt_count->close();

$stmt_sum = $conn->prepare("SELECT SUM(jumlah) as total FROM panen WHERE user_id=?");
$stmt_sum->bind_param("i", $user_id);
$stmt_sum->execute();
$sum = $stmt_sum->get_result()->fetch_assoc();
$stmt_sum->close();

$stmt_kom = $conn->prepare("SELECT COUNT(DISTINCT komoditas) as total FROM panen WHERE user_id=?");
$stmt_kom->bind_param("i", $user_id);
$stmt_kom->execute();
$kom = $stmt_kom->get_result()->fetch_assoc();
$stmt_kom->close();

// Data tabel
$stmt_tabel = $conn->prepare("SELECT * FROM panen WHERE user_id=? ORDER BY tanggal DESC");
$stmt_tabel->bind_param("i", $user_id);
$stmt_tabel->execute();
$res = $stmt_tabel->get_result();
$stmt_tabel->close();

// ================================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - PantauPanen</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="db-wrap">

  <!-- SIDEBAR -->
  <aside class="db-sidebar">
    <div class="db-logo">
      <img src="assets/logo.svg" width="50" height="50" class="logo-img">
      <span class="db-logo-name">PantauPanen</span>
    </div>

    <div class="db-nav-section">Menu</div>
    <a href="dashboard.php" class="db-nav-item active">
      <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>

    <div class="db-sidebar-spacer"></div>

    <div class="db-sidebar-footer">
      <div class="db-sidebar-uname"><i class="fa-solid fa-user db-user-icon"></i><?php echo htmlspecialchars( $username); ?></div>
      <div class="db-sidebar-role">Operator / Admin</div>
    </div>

    <a href="logout" class="db-nav-item logout-btn">
      <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
  </aside>

  <!-- MAIN -->
  <main class="db-main">

    <!-- TOPBAR -->
    <div class="db-topbar">
      <div>
        <h1>Dashboard</h1>
        <p><?php echo date('l, d F Y'); ?></p>
      </div>
      <div class="topbar-flex">
        <div class="db-live-badge">
          <span class="db-dot"></span>
          <?php echo htmlspecialchars($username); ?>
        </div>
        <!-- Tombol logout mobile -->
        <a href="logout" class="db-btn db-btn-ghost db-logout-mobile">
          <i class="fa-solid fa-right-from-bracket"></i>
        </a>
      </div>
    </div>

    <!-- NOTIF -->
    <?php if(isset($_GET['success'])): ?>
      <div class="db-notif db-notif-ok"><i class="fa-solid fa-check"></i> Data berhasil disimpan.</div>
    <?php elseif(isset($_GET['updated'])): ?>
      <div class="db-notif db-notif-ok"><i class="fa-solid fa-check"></i> Data berhasil diperbarui.</div>
    <?php elseif(isset($_GET['deleted'])): ?>
      <div class="db-notif db-notif-err"><i class="fa-solid fa-trash"></i> Data berhasil dihapus.</div>
    <?php elseif(isset($_GET['error'])): ?>
      <div class="db-notif db-notif-err"><i class="fa-solid fa-xmark"></i> Terjadi kesalahan. Coba lagi.</div>
    <?php endif; ?>

    <!-- STAT CARDS -->
    <div class="db-cards">
      <div class="db-card">
        <div class="db-card-icon ic-g"><i class="fa-solid fa-list-check"></i></div>
        <div class="db-card-label">Total Data</div>
        <div class="db-card-val"><?php echo $count['total']; ?></div>
        <div class="db-card-hint">Catatan panen tersimpan</div>
      </div>
      <div class="db-card">
        <div class="db-card-icon ic-y"><i class="fa-solid fa-wheat-awn"></i></div>
        <div class="db-card-label">Total Hasil Panen</div>
        <div class="db-card-val"><?php echo number_format($sum['total'] ?? 0); ?></div>
        <div class="db-card-hint">Jumlah keseluruhan</div>
      </div>
      <div class="db-card">
        <div class="db-card-icon ic-b"><i class="fa-solid fa-tags"></i></div>
        <div class="db-card-label">Jenis Komoditas</div>
        <div class="db-card-val"><?php echo $kom['total']; ?></div>
        <div class="db-card-hint">Komoditas unik tercatat</div>
      </div>
    </div>

    <!-- FORM -->
    <div class="db-panel">
      <?php if($edit_mode): ?>
        <div class="db-panel-head">
          <span class="db-panel-title"><i class="fa-solid fa-pen icon-edit"></i>Edit Data Panen</span>
          <span class="db-tag db-tag-warn">Mode Edit</span>
        </div>
        <form action="process/update_panen.php" method="POST" class="db-form">
          <input type="hidden" name="id" value="<?php echo (int)$edit_data['id']; ?>">
          <?php csrf_input(); ?>
          <div class="db-field">
            <label>Nama Petani</label>
            <input type="text" name="nama_petani" placeholder="Nama petani" value="<?php echo htmlspecialchars($edit_data['nama_petani']); ?>" required>
          </div>
          <div class="db-field">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?php echo htmlspecialchars($edit_data['tanggal']); ?>" required>
          </div>
          <div class="db-field">
            <label>Komoditas</label>
            <input type="text" name="komoditas" placeholder="Komoditas" value="<?php echo htmlspecialchars($edit_data['komoditas']); ?>" required>
          </div>
          <div class="db-field field-small">
            <label>Jumlah</label>
            <input type="number" name="jumlah" placeholder="0" value="<?php echo (int)$edit_data['jumlah']; ?>" required>
          </div>
          <div class="form-actions">
            <button type="submit" class="db-btn db-btn-orange"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            <a href="dashboard.php" class="db-btn db-btn-ghost"><i class="fa-solid fa-xmark"></i> Batal</a>
          </div>
        </form>
      <?php else: ?>
        <div class="db-panel-head">
          <span class="db-panel-title"><i class="fa-solid fa-plus icon-add"></i> Tambah Data Panen</span>
          <span class="db-tag">Input Baru</span>
        </div>
        <form action="process/tambah_panen.php" method="POST" class="db-form">
          <?php csrf_input(); ?>
          <div class="db-field">
            <label>Nama Petani</label>
            <input type="text" name="nama_petani" placeholder="Nama petani" required>
          </div>
          <div class="db-field">
            <label>Tanggal</label>
            <input type="date" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="db-field">
            <label>Komoditas</label>
            <input type="text" name="komoditas" placeholder="Komoditas" required>
          </div>
          <div class="db-field field-small">
            <label>Jumlah</label>
            <input type="number" name="jumlah" placeholder="0" required>
          </div>
          <div class="form-submit">
            <button type="submit" class="db-btn db-btn-green"><i class="fa-solid fa-plus"></i> Tambah Data</button>
          </div>
        </form>
      <?php endif; ?>
    </div>

    <!-- SEARCH -->
    <div class="db-search">
      <div class="db-search-inner">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="liveSearch" placeholder="Cari nama petani atau komoditas..." autocomplete="off">
      </div>
      <span id="searchCount"></span>
    </div>

    <!-- TABLE -->
    <div class="db-table-box">
      <table class="db-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama Petani</th>
            <th>Tanggal</th>
            <th>Komoditas</th>
            <th>Jumlah</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="tableBody">
        <?php
        $no = 1;
        if($res->num_rows > 0){
          while($r = $res->fetch_assoc()){
            $np       = htmlspecialchars($r['nama_petani'], ENT_QUOTES, 'UTF-8');
            $tgl      = htmlspecialchars($r['tanggal'],     ENT_QUOTES, 'UTF-8');
            $km       = htmlspecialchars($r['komoditas'],   ENT_QUOTES, 'UTF-8');
            $jml      = (int) $r['jumlah'];
            $rid      = (int) $r['id'];
            $csrf_tok = htmlspecialchars(csrf_generate(), ENT_QUOTES, 'UTF-8');
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$np}</td>
                    <td>{$tgl}</td>
                    <td><span class='kom-pill'>{$km}</span></td>
                    <td><span class='jml-val'>{$jml}</span></td>
                    <td><div class='act-wrap'>
                      <a href='dashboard.php?edit={$rid}' class='act-btn act-edit'><i class='fa-solid fa-pen'></i> Edit</a>
                      <form method='POST' action='process/delete_panen.php' class='inline-form form-delete'><input type='hidden' name='id' value='{$rid}'><input type='hidden' name='csrf_token' value='{$csrf_tok}'><button type='submit' class='act-btn act-delete'><i class='fa-solid fa-trash'></i> Hapus</button></form>
                    </div></td>
                  </tr>";
            $no++;
          }
        } else {
          echo "<tr id='emptyRow'><td colspan='6' class='empty-row'>Belum ada data. Tambahkan data panen pertama Anda.</td></tr>";
        }
        ?>
        </tbody>
      </table>
    </div>

    <!-- CHART -->
    <!-- Data chart di-pass via data attribute, bukan inline script -->
    <div class="db-chart-box">
      <div class="db-chart-head"><i class="fa-solid fa-chart-bar icon-chart"></i> Grafik Total Panen per Komoditas</div>
      <canvas id="chart"
        class="chart-canvas"
        data-labels="<?php echo htmlspecialchars(json_encode($labels), ENT_QUOTES, 'UTF-8'); ?>"
        data-values="<?php echo htmlspecialchars(json_encode($values), ENT_QUOTES, 'UTF-8'); ?>">
      </canvas>
    </div>

  </main>
</div>

<script src="assets/js/dashboard.js"></script>
<!-- DELETE MODAL -->
<div id="deleteModal" class="delete-modal">
  <div class="delete-box">
    <div class="delete-title">Hapus Data?</div>
    <div class="delete-text">Data yang dihapus tidak dapat dikembalikan.</div>

    <div class="delete-actions">
      <button id="cancelDelete" class="btn-cancel">Batal</button>
      <button id="confirmDelete" class="btn-delete">Hapus</button>
    </div>
  </div>
</div>
</body>
</html>