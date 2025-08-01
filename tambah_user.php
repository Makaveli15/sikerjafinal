<?php
session_start();
include "config/koneksi.php";

// Cek apakah superadmin sudah login
if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

// Proses simpan data jika form dikirim
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($host, $_POST['nama']);
    $username = mysqli_real_escape_string($host, $_POST['username']);
    $password = password_hash("BPS5305", PASSWORD_DEFAULT); // Password default otomatis
    $role = mysqli_real_escape_string($host, $_POST['role']);
    $sub_bagian = isset($_POST['sub_bagian']) ? mysqli_real_escape_string($host, $_POST['sub_bagian']) : '';

    $error = null;

    // Cek apakah username sudah digunakan
    $cek = mysqli_query($host, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Username sudah digunakan. Silakan pilih username lain.";
    }

    // Cek apakah role Kepala Kantor atau Kepala Sub Bagian Umum sudah ada
    if (!$error && ($role == 'Kepala Kantor' || $role == 'Kepala Sub Bagian Umum')) {
        $cekRole = mysqli_query($host, "SELECT * FROM users WHERE role = '$role' AND status = 'Aktif'");
        if (mysqli_num_rows($cekRole) > 0) {
            $error = "Akun dengan role <strong>$role</strong> sudah ada dan aktif. Tidak bisa menambahkan lagi.";
        }
    }

    // Simpan data jika tidak ada error
    if (!$error) {
        $query = "INSERT INTO users (nama, username, password, role, sub_bagian, status) 
                  VALUES ('$nama', '$username', '$password', '$role', '$sub_bagian', 'Aktif')";
        mysqli_query($host, $query);
        header("Location: superadmin_dashboard.php?pesan=berhasil");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        #main-content {
            margin-left: 240px;
            padding: 40px 20px;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            max-width: 500px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #444;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: 0.3s;
        }

        input:focus,
        select:focus {
            border-color: #007bff;
            outline: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .error {
            color: red;
            margin-top: 10px;
            font-weight: bold;
        }

        button {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: inline-block;

            color: #007bff;
            text-decoration: none;
        }

        #subBagianDiv {
            display: none;
        }

        .info {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>

<body>
    <?php include 'admin/layout/sidebar_superadmin.php'; ?>
    <div id="main-content" style="margin-left: 260px; padding: 20px;">
        <div class="container">
            <h2>Tambah Akun Pengguna</h2>
            <a href="superadmin_dashboard.php">← Kembali ke Dashboard</a>

            <?php if (isset($error)) : ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nama:</label>
                    <input type="text" name="nama" required>
                </div>

                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>Password (default):</label>
                    <input type="text" value="BPS5305" disabled>
                    <div class="info">Password akan otomatis diset ke <strong>BPS5305</strong>.</div>
                </div>

                <div class="form-group">
                    <label>Role:</label>
                    <select name="role" id="role" onchange="toggleSubBagian()" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="Kepala Kantor">Kepala Kantor</option>
                        <option value="Kepala Sub Bagian Umum">Kepala Sub Bagian Umum</option>
                        <option value="Admin Tim">Admin Tim</option>
                    </select>
                </div>

                <div class="form-group" id="subBagianDiv">
                    <label>Tim:</label>
                    <select name="sub_bagian">
                        <option value="">Pilih Tim</option>
                        <option value="Produksi">Produksi</option>
                        <option value="Distribusi">Distribusi</option>
                        <option value="Nerwilis">Nerwilis</option>
                        <option value="IPDS">IPDS</option>
                        <option value="Sosial">Sosial</option>
                        <option value="Umum">Umum</option>
                        <option value="PSS">PSS</option>
                        <option value="Desa Cantik">Desa Cantik</option>
                        <option value="IPM">IPM</option>
                        <option value="ZI">ZI</option>
                    </select>
                </div>

                <button type="submit" name="simpan">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        function toggleSubBagian() {
            const role = document.getElementById("role").value;
            const subBagianDiv = document.getElementById("subBagianDiv");

            if (role === "Admin Tim") {
                subBagianDiv.style.display = "block";
            } else {
                subBagianDiv.style.display = "none";
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>