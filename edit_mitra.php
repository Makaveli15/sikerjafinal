<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kelola_mitra.php");
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($host, "SELECT * FROM mitra WHERE id = $id");
$mitra = mysqli_fetch_assoc($result);

if (!$mitra) {
    echo "<script>alert('Data mitra tidak ditemukan!');window.location='kelola_mitra.php';</script>";
    exit;
}

$errors = [];
$success = '';

if (isset($_POST['update'])) {
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
        $query = "UPDATE mitra SET 
                    nama='$nama',
                    posisi='$posisi',
                    alamat='$alamat',
                    jk='$jk',
                    no_telp='$no_telp',
                    sobat_id='$sobat_id',
                    email='$email'
                  WHERE id = $id";

        if (mysqli_query($host, $query)) {
            $success = "Data mitra berhasil diperbarui.";
            $mitra = mysqli_fetch_assoc(mysqli_query($host, "SELECT * FROM mitra WHERE id = $id"));
        } else {
            $errors[] = "Gagal update: " . mysqli_error($host);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Mitra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        .content-wrapper {
            margin-left: 260px;
            padding: 2rem;
        }

        .card-form {
            background: #ffffff;
            border: none;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        }

        .form-label {
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php include 'admin/layout/sidebar_superadmin.php'; ?>

    <div class="content-wrapper">
        <div class="container">
            <h4 class="mb-3">Edit Data Mitra</h4>
            <a href="kelola_mitra.php" class="btn btn-outline-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

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

            <form method="post" class="card-form">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($mitra['nama']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Posisi</label>
                        <select name="posisi" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Mitra Pendataan" <?= $mitra['posisi'] == 'Mitra Pendataan' ? 'selected' : '' ?>>Mitra Pendataan</option>
                            <option value="Mitra Pengolahan" <?= $mitra['posisi'] == 'Mitra Pengolahan' ? 'selected' : '' ?>>Mitra Pengolahan</option>
                            <option value="Mitra (Pendataan dan Pengolahan)" <?= $mitra['posisi'] == 'Mitra (Pendataan dan Pengolahan)' ? 'selected' : '' ?>>Mitra (Pendataan dan Pengolahan)</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" required><?= htmlspecialchars($mitra['alamat']) ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jk" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki" <?= $mitra['jk'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $mitra['jk'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($mitra['no_telp']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sobat ID</label>
                        <input type="text" name="sobat_id" class="form-control" value="<?= htmlspecialchars($mitra['sobat_id']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($mitra['email']) ?>" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" name="update" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>