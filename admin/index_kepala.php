<?php
session_start();
include("../config/koneksi.php");
include("layout/sidebar_kepala.php");

// Ambil data user yang sedang login
$currentUser = $_SESSION['user'];
$currentRole = $currentUser['role'];
$currentSubBagian = $currentUser['sub_bagian'];

$batas = 7;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
$previous = $halaman - 1;
$next = $halaman + 1;

// Search
$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$whereCari = $cari ? "AND (nama LIKE '%$cari%' OR username LIKE '%$cari%' OR role LIKE '%$cari%' OR sub_bagian LIKE '%$cari%')" : '';

// Filter akses berdasarkan sub_bagian
$whereAkses = "WHERE sub_bagian = '$currentSubBagian'";

// Total data
$queryTotal = mysqli_query($host, "SELECT COUNT(*) as total FROM users $whereAkses $whereCari");
$totalData = mysqli_fetch_assoc($queryTotal)['total'];
$totalHalaman = ceil($totalData / $batas);

// Data pengguna
$query = mysqli_query($host, "SELECT * FROM users $whereAkses $whereCari ORDER BY id DESC LIMIT $halaman_awal, $batas");
$no = $halaman_awal + 1;
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title">Manajemen User</h3>

            <!-- Form Search -->
            <form method="GET" class="row g-3 mb-3">
                <div class="col-md-6">
                    <input type="text" name="cari" class="form-control" placeholder="Cari nama, username, role, atau sub bagian" value="<?= htmlspecialchars($cari) ?>">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>

                </div>

            </form>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query) > 0) : ?>
                            <?php while ($data = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($data['nama']) ?></td>
                                    <td><?= htmlspecialchars($data['username']) ?></td>
                                    <td><?= htmlspecialchars($data['role']) ?></td>
                                    <td>
                                        <a href="edit_kepala.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                    </td>

                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">Tidak ada data ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?halaman=<?= $previous ?>&cari=<?= urlencode($cari) ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalHalaman; $i++) : ?>
                        <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                            <a class="page-link" href="?halaman=<?= $i ?>&cari=<?= urlencode($cari) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($halaman >= $totalHalaman) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?halaman=<?= $next ?>&cari=<?= urlencode($cari) ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
    <footer class="text-center py-3 border-top mt-5">
        <div class="container">
            <small class="text-muted">© <?php echo date('Y'); ?> <strong>Marthin Juan</strong>. All rights reserved.</small>
        </div>
    </footer>
</div>