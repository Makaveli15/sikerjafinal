<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$user = $_SESSION['user'] ?? null;
?>
<!-- Include Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
  .sidebar {
    width: 250px;
    background-color: #023e8a;
    min-height: 100vh;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    position: relative;
  }

  .sidebar.collapsed {
    width: 70px;
  }

  .sidebar .logo,
  .sidebar .app-name,
  .sidebar .nav-link span,
  .sidebar .user-info,
  .sidebar .logout-btn {
    transition: all 0.3s ease;
  }

  .sidebar.collapsed .app-name,
  .sidebar.collapsed .nav-link span,
  .sidebar.collapsed .user-info,
  .sidebar.collapsed .logout-btn {
    display: none;
  }

  .sidebar .nav-link {
    color: #ffffff;
    font-size: 15px;
    padding: 10px 20px;
    transition: background-color 0.2s ease;
  }

  .sidebar .nav-link:hover {
    background-color: #0077b6;
  }

  .sidebar .nav-link i {
    margin-right: 8px;
  }

  .toggle-btn {
    position: absolute;
    top: 15px;
    right: -15px;
    background: #0077b6;
    color: white;
    border: none;
    border-radius: 50%;
    padding: 5px 8px;
    cursor: pointer;
    z-index: 1000;
  }

  .sidebar .bottom-section {
    margin-top: auto;
    padding: 15px;
    color: #fff;
    font-size: 14px;
  }

  .sidebar .logo {
    max-width: 40px;
    margin-right: 10px;
  }

  .main-content {
    flex-grow: 1;
    padding: 20px;
  }

  .sidebar.collapsed .nav-link {
    text-align: center;
  }

  .sidebar.collapsed .nav-link i {
    margin: 0;
  }

  .sidebar.collapsed .logo {
    margin: auto;
  }
</style>

<div class="d-flex">
  <!-- Sidebar -->
  <div id="sidebar" class="sidebar">
    <div class="d-flex align-items-center p-3">
      <img src="../assets/img/bpsss.png" alt="Logo BPS" class="logo">
      <h5 class="text-white fw-bold mb-0 app-name">SIKERJA BPS</h5>
    </div>

    <ul class="nav flex-column">
      <li><a href="dashboard.php" class="nav-link"><i class="bi bi-house-door"></i> <span>Home</span></a></li>
      <li><a href="kegiatan.php" class="nav-link"><i class="bi bi-journal-text"></i> <span>Kegiatan</span></a></li>
      <li><a href="laporan.php" class="nav-link"><i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan</span></a></li>
      <li><a href="index.php" class="nav-link"><i class="bi bi-people-fill"></i> <span>Manajemen User</span></a></li>
    </ul>

    <div class="bottom-section">
      <div class="user-info mb-2">
        <i class="bi bi-person-circle"></i> <?= $user['nama']; ?><br>
        <small class="text-light"><?= $user['role']; ?></small>
      </div>
      <a href="../index.php" class="btn btn-light w-100 logout-btn"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </div>

    <button id="toggleSidebar" class="toggle-btn"><i class="bi bi-chevron-double-left"></i></button>
  </div>

  <!-- Konten utama (bisa kamu lanjutkan di file yang menyertakan sidebar ini) -->
  <div class="main-content">
    <!-- isi konten halaman -->