<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: ../index.php");
  exit;
}
include "../config/koneksi.php";

// Ambil data user
$user = $_SESSION['user'];
$subBagian = $user['sub_bagian'];

// Ambil data progres dan realisasi
$query = mysqli_query($host, "SELECT 
    COUNT(*) AS total_kegiatan,
    AVG(progres) AS avg_progres,
    SUM(realisasi) AS total_realisasi,
    SUM(target_anggaran) AS total_anggaran
  FROM kegiatan 
  WHERE pic = '$subBagian'");
$data = mysqli_fetch_assoc($query);

// Data default
$totalKegiatan = $data['total_kegiatan'] ?? 0;
$avgProgres = round($data['avg_progres'] ?? 0, 2);
$totalRealisasi = $data['total_realisasi'] ?? 0;
$totalAnggaran = $data['total_anggaran'] ?? 0;

function formatRupiah($angka)
{
  return "Rp " . number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background-color: #f1f5f9;
    }

    .sidebar {
      width: 250px;
      background-color: #023e8a;
      min-height: 100vh;
    }

    .sidebar .nav-link {
      color: #fff;
    }

    .main-content {
      flex-grow: 1;
      padding: 20px;
    }

    .card-box {
      border-left: 6px solid #0077b6;
      border-radius: 0.5rem;
    }

    .card-icon {
      font-size: 2rem;
      color: #0077b6;
    }

    .label-small {
      font-size: 0.9rem;
      color: #555;
    }
  </style>
</head>

<body>

  <div class="d-flex">
    <!-- Sidebar -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content container-fluid">
      <div class="mb-3">
        <h4>Selamat datang, <strong><?= htmlspecialchars($user['nama']) ?></strong></h4>
      </div>

      <h5 class="mb-3 mt-3">Statistik Tim <?= htmlspecialchars($user['sub_bagian']) ?></h5>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="card card-box shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="label-small">Total Kegiatan</div>
                <h4><?= $totalKegiatan ?></h4>
              </div>
              <i class="bi bi-list-task card-icon"></i>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card card-box shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="label-small">Rata-rata Progres</div>
                <h4><?= $avgProgres ?>%</h4>
              </div>
              <i class="bi bi-bar-chart-line card-icon"></i>
            </div>
          </div>
        </div>
        <div class="col-md-">
          <div class="card card-box shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <div class="label-small">Total Realisasi</div>
                <h4><?= formatRupiah($totalRealisasi) ?></h4>
              </div>
              <i class="bi bi-cash-coin card-icon"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="row mb-6">
        <!-- Hanya Grafik Anggaran -->
        <div class="col-md-">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6>Realisasi vs Anggaran</h6>
              <canvas id="anggaranChart"></canvas>
              <div class="mt-2">
                <small><strong>Total Anggaran:</strong> <?= formatRupiah($totalAnggaran) ?></small><br>
                <small><strong>Total Realisasi:</strong> <?= formatRupiah($totalRealisasi) ?></small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal wajib ubah password -->
      <?php if (isset($_SESSION['force_change_password']) && $_SESSION['force_change_password'] === true) : ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <div class="modal fade" id="gantiPasswordModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog">
            <form method="post" class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Ganti Password</h5>
              </div>
              <div class="modal-body">
                <p>Silakan ganti password karena Anda masih menggunakan password default.</p>
                <div class="mb-3">
                  <label>Password Baru</label>
                  <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label>Ulangi Password</label>
                  <input type="password" name="confirm_password" class="form-control" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="change_password" class="btn btn-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>

        <script>
          var modal = new bootstrap.Modal(document.getElementById('gantiPasswordModal'));
          modal.show();
        </script>
      <?php endif; ?>

      <?php
      // Proses ubah password
      if (isset($_POST['change_password'])) {
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];
        if ($new === $confirm) {
          $hashed = password_hash($new, PASSWORD_DEFAULT);
          $userId = $_SESSION['user']['id'];
          mysqli_query($host, "UPDATE users SET password='$hashed' WHERE id=$userId");

          unset($_SESSION['force_change_password']);
          echo "<script>alert('Password berhasil diubah.'); location.href='dashboard.php';</script>";
        } else {
          echo "<script>alert('Password tidak cocok.');</script>";
        }
      }
      ?>

      <footer class="text-center py-3 border-top mt-5">
        <div class="container">
          <small class="text-muted">© <?php echo date('Y'); ?> <strong>Marthin Juan</strong>. All rights reserved.</small>
        </div>
      </footer>

    </div>
  </div>

  <script>
    new Chart(document.getElementById('anggaranChart'), {
      type: 'bar',
      data: {
        labels: ['Anggaran', 'Realisasi'],
        datasets: [{
          label: 'Jumlah (Rp)',
          data: [<?= $totalAnggaran ?>, <?= $totalRealisasi ?>],
          backgroundColor: ['#ffb703', '#219ebc']
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + value.toLocaleString('id-ID');
              }
            }
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>

  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      const icon = toggleBtn.querySelector('i');
      icon.classList.toggle('bi-chevron-double-left');
      icon.classList.toggle('bi-chevron-double-right');
    });
  </script>

</body>

</html>