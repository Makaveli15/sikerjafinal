<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

// Pagination & Search
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? mysqli_real_escape_string($host, $_GET['search']) : '';
$searchQuery = $search ? "WHERE nama LIKE '%$search%' OR posisi LIKE '%$search%' OR email LIKE '%$search%'" : "";

$countResult = mysqli_query($host, "SELECT COUNT(*) AS total FROM mitra $searchQuery");
$totalData = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalData / $limit);

$query = "SELECT * FROM mitra $searchQuery ORDER BY id DESC LIMIT $limit OFFSET $offset";
$mitras = mysqli_query($host, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Mitra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-responsive {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <?php include 'admin/layout/sidebar_superadmin.php'; ?>

    <div id="main-content">
        <div class="container-fluid mt-3">
            <h4 class="text-center"><strong>Data Mitra</strong></h4>
            <div class="d-flex justify-content-between my-3 flex-wrap gap-2">
                <a href="home_superadmin.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <a href="tambah_mitra.php" class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Tambah Mitra</a>
            </div>

            <form method="get" class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, posisi, atau email..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered bg-white shadow-sm">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Alamat</th>
                            <th>JK</th>
                            <th>No. Telp</th>
                            <th>SOBAT ID</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($mitras) > 0) :
                            $no = $offset + 1;
                            while ($row = mysqli_fetch_assoc($mitras)) : ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="text-start"><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['posisi']) ?></td>
                                    <td class="text-start"><?= htmlspecialchars($row['alamat']) ?></td>
                                    <td><?= htmlspecialchars($row['jk']) ?></td>
                                    <td><?= htmlspecialchars($row['no_telp']) ?></td>
                                    <td><?= htmlspecialchars($row['sobat_id']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <a href="edit_mitra.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                                        <a href="hapus_mitra.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus mitra ini?')"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else : ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">Tidak ada data mitra ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center mt-3">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>