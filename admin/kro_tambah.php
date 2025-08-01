<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_kro = trim($_POST['kode_kro']);

    $insert = mysqli_query($host, "INSERT INTO kro (kode_kro) VALUES ('$kode_kro')");
    if ($insert) {
        header("Location: kro.php");
        exit;
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($host);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah KRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'layout/sidebar.php'; ?>
    <div class="container mt-4">
        <h4 class="mb-3">Tambah KRO</h4>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Kode KRO</label>
                <input type="text" name="kode_kro" class="form-control" required>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="kro.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>