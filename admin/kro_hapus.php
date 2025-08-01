<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($host, "DELETE FROM kro WHERE id = $id");
}

header("Location: kro.php");
exit;
