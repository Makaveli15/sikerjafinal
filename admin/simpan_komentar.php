<?php
session_start();
include "../config/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

// Ambil data user dari session
$currentUser = $_SESSION['user'];
$user_id = $currentUser['id'];
$dibuat_oleh = $currentUser['nama'];

// Ambil data dari form
$kegiatan_id = $_POST['kegiatan_id'] ?? null;
$komentar = trim($_POST['komentar'] ?? '');

// Validasi input
if (!$kegiatan_id || empty($komentar)) {
    header("Location: kepala_kegiatan.php?status=gagal");
    exit;
}

// Ambil sub_bagian dari kolom 'pic' di tabel kegiatan
$subResult = mysqli_query($host, "SELECT pic FROM kegiatan WHERE id = '$kegiatan_id'");
if (!$subResult || mysqli_num_rows($subResult) == 0) {
    header("Location: kepala_kegiatan.php?status=gagal");
    exit;
}
$subRow = mysqli_fetch_assoc($subResult);
$sub_bagian = $subRow['pic'];

// Buat tanggal saat ini
$tanggal = date('Y-m-d H:i:s');

// Simpan komentar ke database
$query = "INSERT INTO komentar_kegiatan 
    (kegiatan_id, sub_bagian, user_id, komentar, tanggal, dibuat_oleh)
    VALUES ('$kegiatan_id', '$sub_bagian', '$user_id', '$komentar', '$tanggal', '$dibuat_oleh')";

// Redirect sesuai hasil query
if (mysqli_query($host, $query)) {
    header("Location: kepala_kegiatan.php?sub=" . urlencode($sub_bagian) . "&status=sukses");
} else {
    header("Location: kepala_kegiatan.php?sub=" . urlencode($sub_bagian) . "&status=gagal");
}
exit;
