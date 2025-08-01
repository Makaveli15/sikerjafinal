<?php
include "../config/koneksi.php";

$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];

// Ambil mitra yang sudah terlibat di kegiatan yang belum selesai dalam rentang waktu
$query = "
  SELECT DISTINCT km.mitra_id 
  FROM kegiatan_mitra km
  JOIN kegiatan k ON km.kegiatan_id = k.id 
  WHERE (
    (k.tanggal_mulai <= '$tanggal_selesai' AND k.tanggal_selesai >= '$tanggal_mulai')
  )
";

$mitra_terpakai = [];
$result = mysqli_query($host, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $mitra_terpakai[] = $row['mitra_id'];
}

$mitra_result = mysqli_query($host, "SELECT * FROM mitra ORDER BY nama ASC");
while ($mitra = mysqli_fetch_assoc($mitra_result)) {
    if (!in_array($mitra['id'], $mitra_terpakai)) {
        $display = $mitra['nama'] . ' | ' . $mitra['posisi'] . ' | ' . $mitra['no_telp'] . ' | ' . $mitra['alamat'];
        echo '<option value="' . $mitra['id'] . '">' . htmlspecialchars($display) . '</option>';
    }
}
