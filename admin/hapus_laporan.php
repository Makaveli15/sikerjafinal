<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$currentUser = $_SESSION['user'];
$role = $currentUser['role'];
$sub_bagian = $currentUser['sub_bagian'];

// Cek otorisasi
if ($role !== 'ketua_sub_bagian' && $role !== 'admin_sub_bagian') {
    echo "<script>alert('Akses ditolak!');window.location.href='laporan.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $file_laporan = $_POST['file_laporan'];

    // Validasi bahwa laporan milik sub bagiannya
    $cek = mysqli_query($host, "SELECT * FROM laporan WHERE id=$id AND sub_bagian='$sub_bagian'");
    if (mysqli_num_rows($cek) === 0) {
        echo "<script>alert('Laporan tidak ditemukan atau bukan milik Anda.');window.location.href='laporan.php';</script>";
        exit;
    }

    // Hapus dari folder
    $filePath = '../uploads/' . $file_laporan;
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Hapus dari DB
    mysqli_query($host, "DELETE FROM laporan WHERE id=$id");

    echo "<script>alert('Laporan berhasil dihapus.');window.location.href='laporan.php';</script>";
}
