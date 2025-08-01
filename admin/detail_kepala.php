<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}
include "../config/koneksi.php";

// Ambil ID
if (!isset($_GET['id'])) {
    header("Location: kegiatan.php");
    exit;
}
$id = intval($_GET['id']);
$query = mysqli_query($host, "SELECT * FROM kegiatan WHERE id = $id");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='kegiatan.php';</script>";
    exit;
}

// Ambil mitra yang terkait dengan kegiatan
$mitraResult = mysqli_query($host, "
    SELECT m.nama 
    FROM kegiatan_mitra km 
    JOIN mitra m ON km.mitra_id = m.id 
    WHERE km.kegiatan_id = $id
");
$mitraList = [];
while ($row = mysqli_fetch_assoc($mitraResult)) {
    $mitraList[] = $row['nama'];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'layout/sidebar_kepala.php'; ?>
    <div class="container mt-4">
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Kegiatan</h5>
                <a href="kepala_kegiatan.php" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="25%">Nama Kegiatan</th>
                        <td><?= htmlspecialchars($data['nama_kegiatan']) ?></td>
                    </tr>
                    <tr>
                        <th>KRO</th>
                        <td><?= htmlspecialchars($data['kro']) ?></td>
                    </tr>
                    <tr>
                        <th>Detail Kegiatan</th>
                        <td><?= nl2br(htmlspecialchars($data['detail_kegiatan'])) ?></td>
                    </tr>
                    <tr>
                        <th>Target Anggaran</th>
                        <td><?= htmlspecialchars($data['target_anggaran']) ?></td>
                    </tr>
                    <tr>
                        <th>Realisasi Anggaran</th>
                        <td><?= htmlspecialchars($data['realisasi']) ?></td>
                    </tr>
                    <tr>
                        <th>Progres</th>
                        <td><?= htmlspecialchars($data['progres']) ?>%</td>
                    </tr>
                    <tr>
                        <th>Kendala</th>
                        <td><?= nl2br(htmlspecialchars($data['kendala'])) ?></td>
                    </tr>
                    <tr>
                        <th>Solusi</th>
                        <td><?= nl2br(htmlspecialchars($data['solusi'])) ?></td>
                    </tr>
                    <tr>
                        <th>Tindak Lanjut</th>
                        <td><?= nl2br(htmlspecialchars($data['tindak_lanjut'])) ?></td>
                    </tr>
                    <tr>
                        <th>Batas Waktu Tindak Lanjut</th>
                        <td><?= htmlspecialchars($data['batas_waktu']) ?></td>
                    </tr>
                    <tr>
                        <th>PIC</th>
                        <td><?= htmlspecialchars($data['pic']) ?></td>
                    </tr>
                    <tr>
                        <th>Mitra Terkait</th>
                        <td>
                            <?php
                            if (count($mitraList) > 0) {
                                echo '<ul class="mb-0">';
                                foreach ($mitraList as $namaMitra) {
                                    echo '<li>' . htmlspecialchars($namaMitra) . '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '<span class="text-muted">Tidak ada mitra terdaftar</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <td><?= htmlspecialchars($data['tanggal_mulai']) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td><?= htmlspecialchars($data['tanggal_selesai']) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tombol Tampilkan Log -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#logPerubahan">
                <i class="bi bi-clock-history"></i> Lihat Progres Perubahan
            </button>
        </div>

        <!-- Log Perubahan -->
        <div class="collapse" id="logPerubahan">
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">Log Perubahan (Sebelum Perubahan)</h6>
                </div>
                <div class="card-body">
                    <?php
                    $logs = mysqli_query($host, "SELECT * FROM log_kegiatan WHERE kegiatan_id = '$id' ORDER BY updated_at DESC");
                    if (mysqli_num_rows($logs) > 0) {
                        while ($log = mysqli_fetch_assoc($logs)) {
                            echo "<div class='border p-3 mb-4 bg-light rounded'>";
                            echo "<p class='mb-3'><strong>Tanggal Update:</strong> " . htmlspecialchars($log['updated_at']) . "</p>";

                            $oldData = json_decode($log['old_data'], true);

                            echo "<h6 class='text-danger'>Data Sebelum Diubah:</h6>";
                            echo "<ul class='list-group'>";
                            foreach ($oldData as $key => $value) {
                                echo "<li class='list-group-item d-flex justify-content-between'>";
                                echo "<strong>" . htmlspecialchars($key) . "</strong>";
                                echo "<span>" . nl2br(htmlspecialchars($value)) . "</span>";
                                echo "</li>";
                            }
                            echo "</ul>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p class='text-muted'>Belum ada log perubahan untuk kegiatan ini.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (collapse functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>