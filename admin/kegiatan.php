<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: ../index.php");
  exit;
}

include "../config/koneksi.php";

// Ambil data user login
$sub_bagian = $_SESSION['user']['sub_bagian'];
$role = $_SESSION['user']['role'];

// Pagination
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman - 1) * $batas;

// Pencarian dan Filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($host, $_GET['search']) : '';
$whereClauses = [];

if ($search) {
  $whereClauses[] = "(kro LIKE '%$search%' OR nama_kegiatan LIKE '%$search%')";
}

if ($role !== 'Kepala Kantor') {
  $whereClauses[] = "pic = '" . mysqli_real_escape_string($host, $sub_bagian) . "'";
}

$where = count($whereClauses) ? "WHERE " . implode(' AND ', $whereClauses) : '';

$queryTotal = mysqli_query($host, "SELECT COUNT(*) as total FROM kegiatan $where");
$totalData = mysqli_fetch_assoc($queryTotal)['total'];
$totalHalaman = ceil($totalData / $batas);

$query = mysqli_query($host, "SELECT * FROM kegiatan $where ORDER BY id DESC LIMIT $halaman_awal, $batas");
$bulanSekarang = date('Y-m');
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Data Kegiatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .progress-bar strong {
      font-size: 0.85rem;
    }
  </style>
</head>

<body class="bg-light">
  <?php include 'layout/sidebar.php'; ?>
  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Kegiatan</h5>
        <div>
          <a href="kegiatan_tambah.php" class="btn btn-light btn-sm me-2"><i class="bi bi-plus-circle"></i> Tambah</a>
          <a href="kro.php" class="btn btn-warning btn-sm"><i class="bi bi-list-check"></i> KRO</a>
        </div>
      </div>

      <div class="card-body">
        <!-- Search & Filter -->
        <!-- Form Pencarian Full Width -->
        <div class="mb-3">
          <form class="d-flex" method="GET">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control me-2" placeholder="Cari kegiatan...">
            <button class="btn btn-secondary" type="submit"><i class="bi bi-search"></i></button>
          </form>
        </div>


        <!-- Tabel Kegiatan -->
        <!-- Tabel Kegiatan -->
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
              <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>KRO</th>
                <th>Detail Kegiatan</th>
                <th>Target Anggaran</th> <!-- Ganti dari Penyerapan -->
                <th>Progres</th>
                <th>PIC</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = $halaman_awal + 1;
              while ($row = mysqli_fetch_assoc($query)) :
                $progres = intval($row['progres']);
                $warna = 'bg-success';
                if ($progres < 40) $warna = 'bg-danger';
                elseif ($progres < 70) $warna = 'bg-warning';
              ?>
                <tr>
                  <td class="text-center"><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($row['kro']) ?></td>
                  <td class="text-center"><?= htmlspecialchars($row['detail_kegiatan']) ?></td>
                  <td class="text-center">Rp<?= number_format($row['target_anggaran'], 0, ',', '.') ?></td> <!-- ganti -->
                  <td>
                    <div class="progress" style="height: 22px;">
                      <div class="progress-bar <?= $warna ?>" role="progressbar" style="width: <?= max($progres, 100) ?>%;">
                        <strong><?= $progres ?>%</strong>
                      </div>
                    </div>
                  </td>
                  <td class="text-center"><?= htmlspecialchars($row['pic']) ?></td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="kegiatan_detail.php?id=<?= $row['id'] ?>" class="btn btn-info"><i class="bi bi-eye"></i></a>
                      <a href="kegiatan_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                      <a href="kegiatan_hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus kegiatan ini?')"><i class="bi bi-trash"></i></a>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
              <?php if ($totalData == 0) : ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">Tidak ada data kegiatan.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>


        <!-- Pagination -->
        <?php if ($totalHalaman > 1) : ?>
          <nav>
            <ul class="pagination justify-content-center">
              <?php for ($i = 1; $i <= $totalHalaman; $i++) : ?>
                <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                  <a class="page-link" href="?halaman=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>

        <!-- Export & Kembali -->
        <!-- Export & Kembali -->
        <!-- Export Buttons Aligned Bottom Right -->
        <div class="d-flex justify-content-end mt-4">
          <div class="d-flex flex-wrap gap-3">

            <!-- Export Excel -->
            <form method="get" action="export_kegiatan_excel.php" class="d-flex align-items-end gap-2">
              <div>
                <label for="bulan_excel" class="form-label mb-0 small text-muted">Export Excel</label>
                <input type="month" name="bulan" id="bulan_excel" class="form-control form-control-sm" value="<?= $bulanSekarang ?>" required>
                <input type="hidden" name="sub_bagian" value="<?= $_SESSION['user']['sub_bagian'] ?>">
              </div>
              <button type="submit" class="btn btn-success btn-sm mt-1">
                <i class="bi bi-file-earmark-excel"></i> Excel
              </button>
            </form>

            <!-- Export PDF -->
            <form method="get" action="export_kegiatan_pdf.php" class="d-flex align-items-end gap-2">
              <div>
                <label for="bulan_pdf" class="form-label mb-0 small text-muted">Export PDF</label>
                <input type="month" name="bulan" id="bulan_pdf" class="form-control form-control-sm" value="<?= $bulanSekarang ?>" required>
                <input type="hidden" name="sub_bagian" value="<?= $_SESSION['user']['sub_bagian'] ?>">
              </div>
              <button type="submit" class="btn btn-danger btn-sm mt-1">
                <i class="bi bi-file-earmark-pdf"></i> PDF
              </button>
            </form>

          </div>
        </div>


      </div>
    </div>
    <footer class="text-center py-3 border-top mt-5">
      <div class="container">
        <small class="text-muted">© <?php echo date('Y'); ?> <strong>Marthin Juan</strong>. All rights reserved.</small>
      </div>
    </footer>
  </div>
  </div>

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