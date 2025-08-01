<?php
include 'config/koneksi.php';

// Ganti username dan password sesuai keinginan
$username = 'superadmin';
$password_plain = 'admin123'; // password asli
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

$query = mysqli_query($host, "INSERT INTO superadmin (nama, username, password) VALUES ('Super Admin', '$username', '$password_hash')");

if ($query) {
    echo "Akun superadmin berhasil dibuat!";
} else {
    echo "Gagal membuat akun: " . mysqli_error($host);
}
