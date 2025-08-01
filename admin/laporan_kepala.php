<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

include "../config/koneksi.php";
$user = $_SESSION['user'];
$role = $user['role'];

// Validasi role
if ($role !== 'Kepala Kantor' && $role !== 'Kepala Sub Bagian Umum') {
    echo "Akses ditolak.";
    exit;
}

// Konfigurasi
$perPage = 7;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $perPage;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_sub = isset($_GET['sub_bagian']) ? $_GET['sub_bagian'] : '';

// Ambil daftar sub bagian unik
$sub_bagian_list = mysqli_query($host, "SELECT DISTINCT sub_bagian FROM laporan");

// Query dengan filter dan pencarian
$sql = "SELECT * FROM laporan WHERE 1 ";
$params = [];

if ($filter_sub != '') {
    $sql .= "AND sub_bagian = ? ";
    $params[] = $filter_sub;
}
if ($search != '') {
    $sql .= "AND nama_file LIKE ? ";
    $params[] = "%$search%";
}
$sql .= "ORDER BY tanggal_upload DESC LIMIT $start, $perPage";

// Hitung total untuk pagination
$count_sql = "SELECT COUNT(*) FROM laporan WHERE 1 ";
$count_params = [];

if ($filter_sub != '') {
    $count_sql .= "AND sub_bagian = '$filter_sub' ";
}
if ($search != '') {
    $count_sql .= "AND nama_file LIKE '%$search%' ";
}
$count_result = mysqli_query($host, $count_sql);
$totalRows = mysqli_fetch_row($count_result)[0];
$totalPages = ceil($totalRows / $perPage);

// Ambil data laporan
$stmt = mysqli_prepare($host, $sql);

if (count($params) > 0) {
    $types = str_repeat("s", count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
?>

<?php include 'layout/sidebar_kepala.php'; ?>
<div class="main-content">
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Laporan Tim</h5>
                <form class="d-flex" method="GET" action="">
                    <?php if ($filter_sub) : ?>
                        <input type="hidden" name="sub_bagian" value="<?= htmlspecialchars($filter_sub) ?>">
                    <?php endif; ?>
                    <input class="form-control me-2" type="text" name="search" placeholder="Cari nama file..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="card-body">
                <!-- Filter Sub Bagian -->
                <form method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="sub_bagian" class="form-select" onchange="this.form.submit()">
                                <option value="">Pilih Tim</option>
                                <?php while ($sb = mysqli_fetch_assoc($sub_bagian_list)) : ?>
                                    <option value="<?= $sb['sub_bagian'] ?>" <?= ($filter_sub == $sb['sub_bagian']) ? 'selected' : '' ?>>
                                        <?= $sb['sub_bagian'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Tabel Laporan -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-secondary">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tim</th>
                                <th>Nama File</th>
                                <th>Tanggal Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0) : ?>
                                <?php $no = $start + 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['sub_bagian']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_file']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal_upload'])) ?></td>
                                        <td class="text-center">
                                            <a href="../uploads/laporan/<?= $row['nama_file'] ?>" class="btn btn-sm btn-success" download>
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada laporan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1) : ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&sub_bagian=<?= urlencode($filter_sub) ?>&search=<?= urlencode($search) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer class="text-center py-3 border-top mt-5">
        <div class="container">
            <small class="text-muted">© <?php echo date('Y'); ?> <strong>Marthin Juan</strong>. All rights reserved.</small>
        </div>
    </footer>
</div>