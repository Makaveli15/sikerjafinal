<?php
session_start();
include "../config/koneksi.php";

// Validasi login
if (!isset($_SESSION['user'])) {
  die("Anda belum login.");
}

// Ambil data dari GET
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$sub_bagian = isset($_GET['sub_bagian']) ? $_GET['sub_bagian'] : '';

// Validasi parameter
if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
  die("Format bulan tidak valid. Gunakan format YYYY-MM.");
}
if (empty($sub_bagian)) {
  die("Sub bagian harus dipilih.");
}

// Set header untuk Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Kegiatan_{$sub_bagian}_{$bulan}.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Query kegiatan
$query = mysqli_query($host, "SELECT * FROM kegiatan 
  WHERE DATE_FORMAT(tanggal_mulai, '%Y-%m') = '$bulan'
  AND pic = '$sub_bagian'
  ORDER BY id DESC");

// Output table
echo "<table border='1'>
  <tr style='background-color:#f2f2f2; font-weight:bold;'>
    <th>No</th>
    <th>Nama Kegiatan</th>
    <th>KRO</th>
    <th>Detail Kegiatan</th>
    <th>Target Anggaran</th>
    <th>Realisasi Anggaran</th>
    <th>Progres (%)</th>
    <th>Kendala</th>
    <th>Solusi</th>
    <th>Tindak Lanjut</th>
   
    <th>Batas Waktu Tindak Lanjut</th>
    <th>PIC</th>
    <th>Mitra</th>
    <th>Tanggal Mulai</th>
    <th>Tanggal Selesai</th>
  </tr>";

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
  $kegiatan_id = $row['id'];

  // Ambil mitra
  $mitra_list = [];
  $mitra_q = mysqli_query($host, "SELECT m.nama 
                                  FROM kegiatan_mitra dk
                                  JOIN mitra m ON dk.mitra_id = m.id
                                  WHERE dk.kegiatan_id = '$kegiatan_id'");
  while ($m = mysqli_fetch_assoc($mitra_q)) {
    $mitra_list[] = $m['nama'];
  }
  $mitra_str = implode(', ', $mitra_list);

  // Format tanggal
  $tanggal_mulai   = !empty($row['tanggal_mulai']) ? date('d-m-Y', strtotime($row['tanggal_mulai'])) : '';
  $tanggal_selesai = !empty($row['tanggal_selesai']) ? date('d-m-Y', strtotime($row['tanggal_selesai'])) : '';
  $batas_waktu     = !empty($row['batas_waktu']) ? date('d-m-Y', strtotime($row['batas_waktu'])) : '';

  echo "<tr>
    <td>{$no}</td>
    <td>" . htmlspecialchars($row['nama_kegiatan']) . "</td>
    <td>" . htmlspecialchars($row['kro']) . "</td>
    <td>" . nl2br(htmlspecialchars($row['detail_kegiatan'])) . "</td>
    <td>" . number_format($row['target_anggaran'], 0, ',', '.') . "</td>
    <td>" . number_format($row['realisasi'], 0, ',', '.') . "</td>
    <td>" . htmlspecialchars($row['progres']) . "</td>
    <td>" . nl2br(htmlspecialchars($row['kendala'])) . "</td>
    <td>" . nl2br(htmlspecialchars($row['solusi'])) . "</td>
    <td>" . nl2br(htmlspecialchars($row['tindak_lanjut'])) . "</td>
    
    <td>" . $batas_waktu . "</td>
    <td>" . htmlspecialchars($row['pic']) . "</td>
    <td>" . htmlspecialchars($mitra_str) . "</td>
    <td>" . $tanggal_mulai . "</td>
    <td>" . $tanggal_selesai . "</td>
  </tr>";
  $no++;
}

echo "</table>";
