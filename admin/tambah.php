<?php
session_start();
include("../config/koneksi.php"); // akses ke config
include("layout/sidebar.php");

$currentUser = $_SESSION['user'];
$currentRole = $currentUser['role'];
$currentSubBagian = $currentUser['sub_bagian'];

// Proses simpan
if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Tentukan sub_bagian sesuai role yang sedang login
    if ($currentRole === 'kepala_kantor' || $currentRole === 'kepala_umum' || $currentRole === 'ketua_sub_bagian' || $currentRole === 'admin sub_bagian') {
        // Role boleh tambah user hanya di sub_bagian mereka sendiri
        $sub_bagian = $currentSubBagian;
    } else {
        // Default fallback (tidak boleh tambah jika tidak punya hak akses)
        die("Anda tidak memiliki akses untuk menambah user.");
    }

    // Cek inputan: role baru tidak boleh lebih tinggi dari role saat ini
    if (($currentRole === 'kepala_kantor' || $currentRole === 'kepala_umum') && $role === 'Kepala Kantor') {
        die("Tidak bisa menambah user dengan role Kepala Kantor.");
    }

    // Simpan ke database
    mysqli_query($host, "INSERT INTO users (nama, username, password, role, sub_bagian) 
        VALUES ('$nama', '$username', '$password', '$role', '$sub_bagian')");

    header("Location: index.php");
}
?>

<div class="container mt-4">
    <h3>Tambah User</h3>
    <form method="POST">
        <div class="mb-2">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-2">
            <label>Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <?php if ($currentRole === 'kepala_kantor' || $currentRole === 'kepala_umum') : ?>
                    <option value="Ketua Sub Bagian">Ketua Sub Bagian</option>
                    <option value="Admin Sub Bagian">Admin Sub Bagian</option>
                <?php elseif ($currentRole === 'ketua_sub_bagian') : ?>
                    <option value="Admin Sub Bagian">Admin Sub Bagian</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Sub Bagian ditentukan otomatis -->
        <input type="hidden" name="sub_bagian" value="<?= $currentSubBagian ?>">

        <button class="btn btn-primary" name="simpan">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>