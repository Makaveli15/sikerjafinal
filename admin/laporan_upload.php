<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$currentUser = $_SESSION['user'];
$uploaded_by = $currentUser['nama'];
$sub_bagian = $currentUser['sub_bagian'];

// Validasi input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_laporan = mysqli_real_escape_string($host, $_POST['nama_laporan']);
    $tanggal_upload = date('Y-m-d H:i:s');

    // Handle file
    $file = $_FILES['file_laporan'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validasi ekstensi
    $allowedExt = ['pdf', 'doc', 'docx'];
    if (!in_array($fileExt, $allowedExt)) {
        echo "<script>alert('Ekstensi file tidak valid. Hanya PDF, DOC, dan DOCX yang diperbolehkan.');history.back();</script>";
        exit;
    }

    // Rename dan simpan file
    $newFileName = uniqid('lap_') . '.' . $fileExt;
    $uploadPath = '../uploads/' . $newFileName;

    if (move_uploaded_file($fileTmp, $uploadPath)) {
        // Simpan ke database
        $query = "INSERT INTO laporan (nama_laporan, file_laporan, sub_bagian, tanggal_upload, uploaded_by)
                  VALUES ('$nama_laporan', '$newFileName', '$sub_bagian', '$tanggal_upload', '$uploaded_by')";
        $insert = mysqli_query($host, $query);

        if ($insert) {
            echo "<script>alert('Laporan berhasil diupload.');window.location.href='laporan.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan ke database.');history.back();</script>";
        }
    } else {
        echo "<script>alert('Gagal mengunggah file.');history.back();</script>";
    }
}
