<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

$errors = [];
$success = '';

if (isset($_POST['simpan'])) {
    $nama     = mysqli_real_escape_string($host, $_POST['nama']);
    $posisi   = mysqli_real_escape_string($host, $_POST['posisi']);
    $alamat   = mysqli_real_escape_string($host, $_POST['alamat']);
    $jk       = mysqli_real_escape_string($host, $_POST['jk']);
    $no_telp  = mysqli_real_escape_string($host, $_POST['no_telp']);
    $sobat_id = mysqli_real_escape_string($host, $_POST['sobat_id']);
    $email    = mysqli_real_escape_string($host, $_POST['email']);

    if (!$nama || !$posisi || !$alamat || !$jk || !$no_telp || !$sobat_id || !$email) {
        $errors[] = "Semua field wajib diisi.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO mitra (nama, posisi, alamat, jk, no_telp, sobat_id, email) 
                  VALUES ('$nama', '$posisi', '$alamat', '$jk', '$no_telp', '$sobat_id', '$email')";

        if (mysqli_query($host, $query)) {
            $success = "Mitra berhasil ditambahkan.";
        } else {
            $errors[] = "Gagal menambahkan mitra: " . mysqli_error($host);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Mitra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php include 'admin/layout/sidebar_superadmin.php'; ?>
    <div id="main-content" style="margin-left: 260px; padding: 20px;">
        <div class="container mt-4">
            <h4>Tambah Data Mitra</h4>

            <a href="kelola_mitra.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

            <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error) : ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($success) : ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="post" class="bg-white p-4 rounded shadow-sm">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Posisi</label>
                    <select name="posisi" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Mitra Pendataan">Mitra Pendataan</option>
                        <option value="Mitra Pengolahan">Mitra Pengolahan</option>
                        <option value="Mitra (Pendataan dan Pengolahan)">Mitra (Pendataan dan Pengolahan)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jk" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" name="no_telp" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sobat ID</label>
                    <input type="text" name="sobat_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <button type="submit" name="simpan" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>