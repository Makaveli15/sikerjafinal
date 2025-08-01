<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: kelola_mitra.php");
    exit;
}

$id = (int)$_GET['id'];

$hapus = mysqli_query($host, "DELETE FROM mitra WHERE id = $id");

if ($hapus) {
    header("Location: kelola_mitra.php?message=Mitra berhasil dihapus.");
} else {
    header("Location: kelola_mitra.php?message=Gagal menghapus mitra.");
}
