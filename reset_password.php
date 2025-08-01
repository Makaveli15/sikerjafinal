<?php
session_start();
include "config/koneksi.php";

// Cek apakah superadmin sudah login
if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

// Ambil ID user
if (!isset($_GET['id'])) {
    header("Location: superadmin_dashboard.php");
    exit;
}
$id = intval($_GET['id']);

// Ambil data user untuk ditampilkan
$user = mysqli_query($host, "SELECT * FROM users WHERE id = $id");
$data = mysqli_fetch_assoc($user);

// Jika form reset dikonfirmasi
if (isset($_POST['konfirmasi'])) {
    $password_baru = password_hash("BPS5305", PASSWORD_DEFAULT);
    mysqli_query($host, "UPDATE users SET password = '$password_baru' WHERE id = $id");
    $sukses = "Password berhasil direset ke default: BPS5305";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
        }

        .info {
            margin-top: 20px;
            font-size: 16px;
        }

        .success {
            color: green;
            font-weight: bold;
            margin-top: 15px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            margin: 10px 5px 0 0;
            font-size: 14px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-secondary {
            background-color: gray;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reset Password</h2>

        <p class="info">
            Apakah Anda yakin ingin mereset password akun <strong><?= $data['nama']; ?></strong> (username: <strong><?= $data['username']; ?></strong>)?
            <br>Password akan diubah menjadi <strong>BPS5305</strong>.
        </p>

        <?php if (isset($sukses)) : ?>
            <div class="success"><?= $sukses; ?></div>
            <a href="superadmin_dashboard.php" class="btn">Kembali ke Dashboard</a>
        <?php else : ?>
            <form method="POST">
                <button type="submit" name="konfirmasi" class="btn btn-danger">Ya, Reset Sekarang</button>
                <a href="superadmin_dashboard.php" class="btn btn-secondary">Batal</a>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>