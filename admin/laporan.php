<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

include "../config/koneksi.php";
$user = $_SESSION['user'];
$role = $user['role'];
$sub_bagian = $user['sub_bagian'];

if ($role != 'Admin Tim') {
    echo "Akses ditolak.";
    exit;
}

$msg = '';

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $get = mysqli_query($host, "SELECT * FROM laporan WHERE id = $id AND sub_bagian = '$sub_bagian'");
    if ($row = mysqli_fetch_assoc($get)) {
        $file_path = "../uploads/laporan/" . $row['nama_file'];
        if (file_exists($file_path)) unlink($file_path);
        mysqli_query($host, "DELETE FROM laporan WHERE id = $id");
        $msg = "Laporan berhasil dihapus.";
    }
}

// Proses upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['laporan'])) {
    $file_name = $_FILES['laporan']['name'];
    $file_tmp = $_FILES['laporan']['tmp_name'];
    $target_dir = "../uploads/laporan/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $target_file = $target_dir . basename($file_name);

    if (move_uploaded_file($file_tmp, $target_file)) {
        $tanggal_upload = date("Y-m-d");
        $simpan = mysqli_query($host, "INSERT INTO laporan (sub_bagian, nama_file, tanggal_upload) VALUES ('$sub_bagian', '$file_name', '$tanggal_upload')");
        $msg = $simpan ? "Laporan berhasil diupload." : "Gagal menyimpan ke database.";
    } else {
        $msg = "Gagal upload laporan.";
    }
}

// Search & Pagination
$search = isset($_GET['search']) ? mysqli_real_escape_string($host, $_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 7;
$offset = ($page - 1) * $limit;
$where = "WHERE sub_bagian = '$sub_bagian'";
if ($search != '') {
    $where .= " AND nama_file LIKE '%$search%'";
}

$total_data = mysqli_fetch_assoc(mysqli_query($host, "SELECT COUNT(*) as total FROM laporan $where"))['total'];
$total_page = ceil($total_data / $limit);
$query = mysqli_query($host, "SELECT * FROM laporan $where ORDER BY tanggal_upload DESC LIMIT $limit OFFSET $offset");
?>

<?php include 'layout/sidebar.php'; ?>
<div class="main-content">
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Upload Laporan Tim</h5>
            </div>
            <div class="card-body">
                <?php if ($msg) : ?>
                    <div class="alert alert-info"><?= $msg ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-9">
                            <input type="file" name="laporan" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-upload"></i> Upload</button>
                        </div>
                    </div>
                </form>

                <form class="mb-3" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Cari nama file...">
                        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Tanggal Upload</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query) > 0) : ?>
                                <?php $no = $offset + 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['nama_file']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal_upload'])) ?></td>
                                        <td class="text-center">
                                            <a href="../uploads/laporan/<?= $row['nama_file'] ?>" class="btn btn-sm btn-success" download>
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                            <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus laporan ini?')" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada laporan ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_page > 1) : ?>
                    <nav>
                        <ul class="pagination justify-content-center mt-3">
                            <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
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