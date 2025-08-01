<?php
include("../config/koneksi.php");      // akses ke config di luar admin

$id = $_GET['id'];
mysqli_query($host, "DELETE FROM users WHERE id = $id");

header("Location: index.php");
