<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

include "../config/koneksi.php";
$user = $_SESSION['user'];

if (!in_array($user['role'], ['Kepala Sub Bagian Umum', 'Kepala Kantor'])) {
    header("Location: dashboard.php");
    exit;
}

$sub_bagian = isset($_GET['sub']) ? $_GET['sub'] : null;

$subBagianList = [];
$result = mysqli_query($host, "SELECT DISTINCT sub_bagian FROM users WHERE sub_bagian != '' LIMIT 10");
while ($row = mysqli_fetch_assoc($result)) {
    $subBagianList[] = $row['sub_bagian'];
}

function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Monitoring Kegiatan - Kepala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-subbagian {
            border: 1px solid #dee2e6;
            background: #ffffff;
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .card-subbagian:hover {
            background: #0d6efd;
            color: #fff;
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .card-subbagian:hover i,
        .card-subbagian:hover h6 {
            color: #fff !important;
        }

        .card-subbagian.active {
            background: #0d6efd;
            color: #fff;
        }


        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <?php include 'layout/sidebar_kepala.php'; ?>

    <div class="container mt-4 mb-5">
        <h4 class="mb-4"><i class="bi bi-clipboard-check-fill"></i> Monitoring Kegiatan</h4>

        <?php if (!$sub_bagian) : ?>
            <div class="mb-4">
                <h5 class="text-muted mb-3"><i class="bi bi-diagram-3"></i> Pilih Tim untuk Dilihat</h5>
                <div class="row">
                    <?php foreach ($subBagianList as $sb) : ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                            <a href="?sub=<?= urlencode($sb) ?>" class="text-decoration-none">
                                <div class="card card-subbagian shadow text-center p-3 h-100 <?= ($sub_bagian === $sb) ? 'active' : '' ?>">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <div class="mb-2 text-primary" style="font-size: 2rem;">
                                            <i class="bi bi-person-badge-fill"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($sb) ?></h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>


        <?php if ($sub_bagian) : ?>
            <div class="mb-3">
                <a href="kepala_kegiatan.php" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left-circle"></i> Kembali
                </a>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'sukses') : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> Komentar berhasil dikirim!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-header bg-primary text-white fw-semibold">
                    <i class="bi bi-list-task me-2"></i> Kegiatan - Tim : <?= htmlspecialchars($sub_bagian) ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelKegiatan" class="table table-bordered table-striped">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kegiatan</th>
                                    <th>KRO</th>
                                    <th>Detail</th>
                                    <th>Target Anggaran</th>
                                    <th>Progres</th>
                                    <th>Komentar</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $q = mysqli_query($host, "SELECT * FROM kegiatan WHERE pic = '" . mysqli_real_escape_string($host, $sub_bagian) . "' ORDER BY id DESC");
                                if (mysqli_num_rows($q) > 0) :
                                    while ($data = mysqli_fetch_assoc($q)) :
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($data['nama_kegiatan']) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($data['kro']) ?></td>
                                            <td><?= htmlspecialchars($data['detail_kegiatan']) ?></td>
                                            <td class="text-end"><?= rupiah($data['target_anggaran']) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= ($data['progres'] >= 80 ? 'success' : ($data['progres'] >= 50 ? 'warning' : 'danger')) ?>">
                                                    <?= htmlspecialchars($data['progres']) ?>%
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#komentarModal" data-id="<?= $data['id'] ?>">
                                                    <i class="bi bi-chat-left-text-fill"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <a href="detail_kepala.php?id=<?= $data['id'] ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye-fill"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else : ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada kegiatan untuk Tim ini.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <footer class="text-center py-3 border-top mt-5">
            <div class="container">
                <small class="text-muted">© <?php echo date('Y'); ?> <strong>Marthin Juan</strong>. All rights reserved.</small>
            </div>
        </footer>
    </div>

    <!-- Modal Komentar -->
    <div class="modal fade" id="komentarModal" tabindex="-1" aria-labelledby="komentarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="simpan_komentar.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="komentarModalLabel"><i class="bi bi-chat-square-dots-fill"></i> Tambahkan Komentar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="kegiatan_id" id="kegiatan_id">
                        <input type="hidden" name="sub_bagian" value="<?= htmlspecialchars($sub_bagian) ?>">
                        <div class="mb-3">
                            <label for="komentar" class="form-label">Komentar</label>
                            <textarea class="form-control" name="komentar" id="komentar" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        const komentarModal = document.getElementById('komentarModal');
        komentarModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const kegiatanId = button.getAttribute('data-id');
            komentarModal.querySelector('#kegiatan_id').value = kegiatanId;
        });

        $(document).ready(function() {
            $('#tabelKegiatan').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    },
                    zeroRecords: "Tidak ditemukan data yang sesuai"
                },
                pageLength: 5
            });
        });
    </script>
</body>

</html>