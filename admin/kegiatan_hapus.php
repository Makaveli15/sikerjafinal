<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

// Validasi ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Eksekusi query penghapusan
    $query = mysqli_query($host, "DELETE FROM kegiatan WHERE id = $id");

    if ($query) {
        header("Location: kegiatan.php");
        exit;
    } else {
        echo "Gagal menghapus kegiatan. Error: " . mysqli_error($host);
    }
} else {
    echo "ID tidak valid.";
}
