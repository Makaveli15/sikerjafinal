<?php
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

$totalUser = mysqli_fetch_assoc(mysqli_query($host, "SELECT COUNT(*) as total FROM users"))['total'];
$totalMitra = mysqli_fetch_assoc(mysqli_query($host, "SELECT COUNT(*) as total FROM mitra"))['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Superadmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s ease-in-out;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            position: fixed;
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
        }

        .welcome {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .footer-note {
            font-size: 0.9rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include 'admin/layout/sidebar_superadmin.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="mb-4">
                <span class="welcome">👋 Selamat Datang, Superadmin</span>
                <p class="footer-note">Anda memiliki akses penuh untuk mengelola data pengguna dan mitra.</p>
            </div>

            <div class="row g-4">
                <!-- Total Pegawai -->
                <div class="col-md-6 col-lg-6">
                    <div class="card card-hover shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-people-fill text-primary fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title">Total Pegawai</h6>
                                <h3 class="fw-bold"><?= $totalUser ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Mitra -->
                <div class="col-md-6 col-lg-6">
                    <div class="card card-hover shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="bi bi-person-lines-fill text-success fs-1"></i>
                            </div>
                            <div>
                                <h6 class="card-title">Total Mitra</h6>
                                <h3 class="fw-bold"><?= $totalMitra ?></h3>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Info Panel -->
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="alert alert-info shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Gunakan menu di sidebar untuk mengelola akun pegawai dan data mitra. Perubahan akan langsung tersimpan ke database.
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

</body>

</html>