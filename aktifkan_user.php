<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['superadmin'])) {
    header("Location: superadmin_login.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($host, "UPDATE users SET status='Aktif' WHERE id=$id");
header("Location: superadmin_dashboard.php");
exit;
