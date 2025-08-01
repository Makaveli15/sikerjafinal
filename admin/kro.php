<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";

// Pagination
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman - 1) * $batas;

// Hitung total data
$total = mysqli_query($host, "SELECT COUNT(*) as total FROM kro");
$totalData = mysqli_fetch_assoc($total)['total'];
$totalHalaman = ceil($totalData / $batas);

// Ambil data untuk halaman sekarang
$kro = mysqli_query($host, "SELECT * FROM kro ORDER BY kode_kro ASC LIMIT $halaman_awal, $batas");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data KRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'layout/sidebar.php'; ?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Data KRO</h4>
            <div>
                <a href="kegiatan.php" class="btn btn-secondary">Kembali</a>
                <a href="kro_tambah.php" class="btn btn-primary">+ Tambah KRO</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <?php if ($totalData > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th>Kode KRO</th>
                                    <th style="width: 160px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = $halaman_awal + 1;
                                while ($row = mysqli_fetch_assoc($kro)) :
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['kode_kro']) ?></td>
                                        <td>
                                            <a href="kro_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="kro_hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p class="text-center m-0">Data tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination -->
        <nav class="mt-3">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalHalaman; $i++) : ?>
                    <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                        <a class="page-link" href="?halaman=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <footer class="text-center py-3 border-top mt-5">
            <div class="container">
                <small class="text-muted">© <?php echo date('Y'); ?> <strong>Marthin Juan</strong>. All rights reserved.</small>
            </div>
        </footer>
    </div>
</body>

</html>