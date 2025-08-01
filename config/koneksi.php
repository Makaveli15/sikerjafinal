<?php
$host = mysqli_connect("localhost", "root", "", "simonev");
if (!$host) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
