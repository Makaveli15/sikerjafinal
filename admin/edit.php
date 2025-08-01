<?php
include("../config/koneksi.php");
include("layout/sidebar.php");

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($host, "SELECT * FROM users WHERE id = $id"));

if (isset($_POST['update'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];

    // Ambil password hash dari DB
    $result = mysqli_query($host, "SELECT password FROM users WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    $hash = $row['password'];

    // Verifikasi password lama
    if (password_verify($password_lama, $hash)) {
        // Password lama benar
        if (!empty($password_baru)) {
            $newHash = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($host, "UPDATE users SET password='$newHash' WHERE id=$id");
            echo "<script>alert('Password berhasil diupdate'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Password baru tidak boleh kosong');</script>";
        }
    } else {
        echo "<script>alert('Password lama salah!');</script>";
    }
}
?>

<div class="container mt-4">
    <h3>Edit Password User</h3>
    <form method="POST">
        <div class="mb-2">
            <label>Nama</label>
            <input type="text" class="form-control" value="<?= $data['nama'] ?>" readonly>
        </div>
        <div class="mb-2">
            <label>Username</label>
            <input type="text" class="form-control" value="<?= $data['username'] ?>">
        </div>
        <div class="mb-2">
            <label>Role</label>
            <input type="text" class="form-control" value="<?= $data['role'] ?>" readonly>
        </div>
        <div class="mb-2">
            <label>Sub Bagian</label>
            <input type="text" class="form-control" value="<?= $data['sub_bagian'] ?>" readonly>
        </div>
        <div class="mb-3">
            <label>Password Lama</label>
            <input type="password" name="password_lama" class="form-control" required placeholder="Masukkan password lama">
        </div>
        <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" name="password_baru" class="form-control" required placeholder="Masukkan password baru">
        </div>
        <button class="btn btn-success" name="update">Update Password</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>