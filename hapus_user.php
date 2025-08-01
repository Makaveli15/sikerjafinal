<?php
session_start();
include "config/koneksi.php";

// Cek apakah superadmin sudah login
if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

// Validasi ID user yang akan dihapus
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: superadmin_dashboard.php?error=id_tidak_valid");
    exit;
}

$id = intval($_GET['id']);

// Optional: Hapus data dari tabel lain jika ada relasi
// Misalnya: kegiatan, komentar, log_kegiatan, laporan (silakan sesuaikan nama tabel & kolom user_id)
// mysqli_query($host, "DELETE FROM kegiatan WHERE user_id = $id");
// mysqli_query($host, "DELETE FROM log_kegiatan WHERE user_id = $id");
// mysqli_query($host, "DELETE FROM komentar WHERE user_id = $id");
// mysqli_query($host, "DELETE FROM laporan WHERE user_id = $id");

// Hapus user dari tabel users
$hapus = mysqli_query($host, "DELETE FROM users WHERE id = $id");

// Redirect dengan notifikasi
if ($hapus) {
    header("Location: superadmin_dashboard.php?pesan=hapus_sukses");
} else {
    header("Location: superadmin_dashboard.php?pesan=hapus_gagal");
}
exit;
