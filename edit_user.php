<?php
session_start();
include "config/koneksi.php";
if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($host, "SELECT * FROM users WHERE id=$id"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $sub_bagian = $_POST['sub_bagian'];

    mysqli_query($host, "UPDATE users SET nama='$nama', username='$username', role='$role', sub_bagian='$sub_bagian' WHERE id=$id");
    header("Location: superadmin_dashboard.php");
}
?>

<h3>Edit Akun</h3>
<form method="post">
    Nama: <input type="text" name="nama" value="<?= $data['nama'] ?>"><br>
    Username: <input type="text" name="username" value="<?= $data['username'] ?>"><br>
    Role:
    <select name="role">
        <option <?= $data['role'] == 'Kepala Kantor' ? 'selected' : '' ?> value="Kepala Kantor">Kepala Kantor</option>
        <option <?= $data['role'] == 'Kepala Sub Bagian Umum' ? 'selected' : '' ?> value="Kepala Sub Bagian Umum">Kepala Umum</option>

        <option <?= $data['role'] == 'Admin Tim' ? 'selected' : '' ?> value="Admin Tim">Admin Sub Bagian</option>
    </select><br>
    Sub Bagian: <input type="text" name="sub_bagian" value="<?= $data['sub_bagian'] ?>"><br>
    <button type="submit">Simpan Perubahan</button>
</form>
<a href="superadmin_dashboard.php">Kembali</a>