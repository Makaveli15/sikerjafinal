<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}
include "../config/koneksi.php";

// Ambil data user yang sedang login
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Kepala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .card-box {
            border-left: 5px solid #0077b6;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <!-- Include sidebar khusus kepala -->
        <?php include 'layout/sidebar_kepala.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="mt-1 d-flex gap-2 flex-wrap">
                <span>Selamat datang, <strong><?= htmlspecialchars($user['nama']) ?></strong>,</span>
                <span>Anda login sebagai <strong><?= $user['role'] ?></strong></span>
                <?php if (!empty($user['sub_bagian'])) : ?>
                    <span>Sub Bagian: <strong><?= $user['sub_bagian'] ?></strong></span>
                <?php endif; ?>
            </div>

            <h3 class="mb-3">DASHBOARD</h3>
            <div class="row g-4">
                <!-- Total Kegiatan -->
                <div class="col-md-6">
                    <div class="card card-box shadow-sm">
                        <div class="card-body">
                            <h6>Total Kegiatan</h6>
                            <h3 class="text-primary">
                                <?php
                                $kegiatan = mysqli_query($host, "SELECT COUNT(*) as total FROM kegiatan");
                                echo mysqli_fetch_assoc($kegiatan)['total'];
                                ?>
                            </h3>
                            <i class="bi bi-journal-text fs-2 text-secondary"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Sub Bagian -->
                <div class="col-md-6">
                    <div class="card card-box shadow-sm">
                        <div class="card-body">
                            <h6>Jumlah Tim</h6>
                            <h3 class="text-success">
                                <?php
                                $result = mysqli_query($host, "SELECT COUNT(DISTINCT sub_bagian) as total FROM users WHERE sub_bagian IS NOT NULL AND sub_bagian != ''");
                                echo mysqli_fetch_assoc($result)['total'];
                                ?>
                            </h3>
                            <i class="bi bi-diagram-3 fs-2 text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 🔽 Tambahan: Tabel Rata-rata Progres & Realisasi per Sub Bagian -->
            <div class="mt-5">
                <h4 class="mb-3">Rata-rata Progres & Realisasi Anggaran per Sub Bagian</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Tim</th>
                                <th>Rata-rata Progres</th>
                                <th>Rata-rata Realisasi Anggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $querySub = mysqli_query($host, "
        SELECT pic AS sub_bagian, 
            ROUND(AVG(progres), 2) AS avg_progres, 
            ROUND(AVG(realisasi), 2) AS avg_realisasi
        FROM kegiatan
        WHERE pic IS NOT NULL AND pic != ''
        GROUP BY pic
        ORDER BY pic ASC
    ");
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($querySub)) :
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['sub_bagian']) ?></td>
                                    <td><?= $row['avg_progres'] ?>%</td>
                                    <td>Rp <?= number_format($row['avg_realisasi'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <!-- 🔼 End Tambahan -->

            <!-- Modal wajib ubah password -->
            <?php if (isset($_SESSION['force_change_password']) && $_SESSION['force_change_password'] === true) : ?>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    echo "<script>alert('Password berhasil diubah.'); location.href='dashboard_kepala.php';</script>";
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
        </div> <!-- End Main Content -->
    </div> <!-- End d-flex -->

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            const icon = toggleBtn.querySelector('i');
            icon.classList.toggle('bi-chevron-double-left');
            icon.classList.toggle('bi-chevron-double-right');
        });
    </script>

</body>

</html>