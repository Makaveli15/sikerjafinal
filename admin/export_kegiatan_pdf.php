<?php
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;

session_start();
include "../config/koneksi.php";

// Ambil bulan dari GET
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

// Ambil sub_bagian dari session user
if (!isset($_SESSION['user'])) {
    die("Akses ditolak. Silakan login.");
}
$sub_bagian = $_SESSION['user']['sub_bagian'] ?? '';

// Validasi format bulan
if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
    die("Format bulan tidak valid. Gunakan format YYYY-MM.");
}

// Header HTML awal
$html = '
<h3 style="text-align:center;">Laporan Data Kegiatan SIKERJA<br>Bulan ' . htmlspecialchars($bulan) . ' - Sub Bagian: ' . htmlspecialchars($sub_bagian) . '</h3>
<style>
    body {
        font-family: DejaVu Sans, sans-serif;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 10px;
    }
    th, td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
        vertical-align: top;
    }
    th {
        background-color: #f0f0f0;
    }
</style>
<table>
<thead>
<tr>
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
</tr>
</thead>
<tbody>';

// Ambil data kegiatan berdasarkan tanggal_mulai & sub_bagian
$no = 1;
$query = mysqli_query($host, "SELECT * FROM kegiatan 
    WHERE DATE_FORMAT(tanggal_mulai, '%Y-%m') = '$bulan' 
    AND pic = '$sub_bagian' 
    ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($query)) {
    // Ambil mitra dari relasi kegiatan_mitra
    $kegiatan_id = $row['id'];
    $mitra_list = [];
    $mitra_query = mysqli_query($host, "SELECT m.nama 
        FROM kegiatan_mitra dk
        JOIN mitra m ON dk.mitra_id = m.id
        WHERE dk.kegiatan_id = '$kegiatan_id'");
    while ($m = mysqli_fetch_assoc($mitra_query)) {
        $mitra_list[] = $m['nama'];
    }
    $mitra_str = implode(', ', $mitra_list);

    // Konversi tanggal
    $tanggal_mulai = !empty($row['tanggal_mulai']) ? date('d-m-Y', strtotime($row['tanggal_mulai'])) : '';
    $tanggal_selesai = !empty($row['tanggal_selesai']) ? date('d-m-Y', strtotime($row['tanggal_selesai'])) : '';
    $batas_waktu = !empty($row['batas_waktu']) ? date('d-m-Y', strtotime($row['batas_waktu'])) : '';

    $html .= '<tr>
        <td>' . $no++ . '</td>
        <td>' . htmlspecialchars($row['nama_kegiatan']) . '</td>
        <td>' . htmlspecialchars($row['kro']) . '</td>
        <td>' . nl2br(htmlspecialchars($row['detail_kegiatan'])) . '</td>
        <td>' . number_format($row['target_anggaran'], 0, ',', '.') . '</td>
        <td>' . number_format($row['realisasi'], 0, ',', '.') . '</td>
        <td>' . htmlspecialchars($row['progres']) . '</td>
        <td>' . nl2br(htmlspecialchars($row['kendala'])) . '</td>
        <td>' . nl2br(htmlspecialchars($row['solusi'])) . '</td>
        <td>' . nl2br(htmlspecialchars($row['tindak_lanjut'])) . '</td>
        <td>' . $batas_waktu . '</td>
        <td>' . htmlspecialchars($row['pic']) . '</td>
        <td>' . htmlspecialchars($mitra_str) . '</td>
        <td>' . $tanggal_mulai . '</td>
        <td>' . $tanggal_selesai . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Render PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("laporan_kegiatan_sikerja_{$bulan}_{$sub_bagian}.pdf", ["Attachment" => false]);
exit;
