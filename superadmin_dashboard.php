<?php
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

// Pagination & Search
$limit = 7;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? mysqli_real_escape_string($host, $_GET['search']) : '';
$searchQuery = $search ? "WHERE nama LIKE '%$search%' OR username LIKE '%$search%'" : "";

$countResult = mysqli_query($host, "SELECT COUNT(*) AS total FROM users $searchQuery");
$totalData = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalData / $limit);

$query = "SELECT * FROM users $searchQuery ORDER BY id DESC LIMIT $limit OFFSET $offset";
$users = mysqli_query($host, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Superadmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1100px;
            margin-top: 30px;
        }

        .action-buttons a {
            margin: 2px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>


<body class="bg-light">


    <?php include 'admin/layout/sidebar_superadmin.php'; ?>
    <div id="main-content" style="margin-left: 260px; padding: 20px;">
        <div class="container">
            <h3 class="mt-3 text-center"><strong>Data Pegawai</strong></h3>

            <!-- Alert Message -->
            <?php if (isset($_GET['message'])) : ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <?= htmlspecialchars($_GET['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                <a href="home_superadmin.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <a href="tambah_user.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Akun</a>

            </div>

            <!-- Search Form -->
            <form method="get" class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama atau username..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
            </form>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white shadow-sm">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tim</th>
                            <th>Status</th>
                            <th width="200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($users) > 0) :
                            $no = $offset + 1;
                            while ($row = mysqli_fetch_assoc($users)) : ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['role']) ?></td>
                                    <td><?= htmlspecialchars($row['sub_bagian']) ?></td>
                                    <td>
                                        <span class="badge <?= strtolower($row['status']) == 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="reset_password.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info" title="Reset Password">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </a>
                                        <?php if (strtolower($row['status']) == 'nonaktif') : ?>
                                            <a href="aktifkan_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success" title="Aktifkan Akun">
                                                <i class="bi bi-check-circle"></i>
                                            </a>
                                        <?php else : ?>
                                            <a href="nonaktif_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary" title="Nonaktifkan Akun">
                                                <i class="bi bi-slash-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="hapus_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" title="Hapus Akun" onclick="return confirm('Yakin hapus user ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Tidak ada data ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= $search ?>">Sebelumnya</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= $search ?>">Berikutnya</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <footer class="text-center py-3 border-top mt-5">
            <div class="container">
                <small class="text-muted">© <?php echo date('Y'); ?> <strong>Marthin Juan</strong>. All rights reserved.</small>
            </div>
        </footer>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>