<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

include "../config/koneksi.php";
$currentUser = $_SESSION['user'];
$role = strtolower(trim($currentUser['role']));

// Perbolehkan hanya kepala kantor dan kepala umum
if ($role !== 'kepala kantor' && $role !== 'kepala umum') {
    echo "Akses ditolak!";
    exit;
}

// Ambil semua komentar dari user dengan role kepala
$query = mysqli_query($host, "
    SELECT 
        kk.id,
        k.nama_kegiatan,
        u.nama AS nama_user,
        kk.komentar,
        kk.tanggal
    FROM komentar_kegiatan kk
    JOIN kegiatan k ON kk.kegiatan_id = k.id
    JOIN users u ON kk.user_id = u.id
    WHERE u.role = 'kepala kantor' OR u.role = 'kepala umum'
    ORDER BY kk.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Komentar Kepala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar khusus kepala -->
        <?php include "layout/sidebar_kepala.php"; ?>

        <!-- Konten utama -->
        <div class="container mt-4">
            <h3>Komentar Kepala terhadap Kegiatan</h3>
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Nama Kepala</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_kegiatan']) ?></td>
                            <td><?= htmlspecialchars($row['nama_user']) ?></td>
                            <td><?= htmlspecialchars($row['komentar']) ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>