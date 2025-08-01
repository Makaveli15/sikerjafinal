<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: ../index.php");
  exit;
}
include "../config/koneksi.php";

// Validasi ID kegiatan
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
$data = mysqli_query($host, "SELECT * FROM kegiatan WHERE id='$id'");
$kegiatan = mysqli_fetch_assoc($data);

if (!$kegiatan) {
  echo "Data kegiatan tidak ditemukan.";
  exit;
}

// Dropdown PIC manual
$picOptions = ['Produksi', 'Distribusi', 'Nerwilis', 'Sosial', 'IPDS', 'Umum'];

// KRO dari tabel kro
$kro_result = mysqli_query($host, "SELECT * FROM kro ORDER BY kode_kro ASC");

// Ambil mitra yang sudah terkait
$mitra_terpilih = [];
$getMitra = mysqli_query($host, "SELECT mitra_id FROM kegiatan_mitra WHERE kegiatan_id = '$id'");
while ($m = mysqli_fetch_assoc($getMitra)) {
  $mitra_terpilih[] = $m['mitra_id'];
}

// Ambil semua mitra yang belum digunakan di kegiatan lain
$mitra_ids_terpilih = implode(",", array_map('intval', $mitra_terpilih)); // untuk digunakan dalam IN clause

$mitra_query = "
  SELECT * FROM mitra 
  WHERE id NOT IN (
    SELECT mitra_id FROM kegiatan_mitra WHERE kegiatan_id != '$id'
  )
  " . (!empty($mitra_ids_terpilih) ? " OR id IN ($mitra_ids_terpilih)" : "") . "
  ORDER BY nama ASC
";
$mitra_result = mysqli_query($host, $mitra_query);


// Ambil mitra yang sudah terkait
$mitra_terpilih = [];
$getMitra = mysqli_query($host, "SELECT mitra_id FROM kegiatan_mitra WHERE kegiatan_id = '$id'");
while ($m = mysqli_fetch_assoc($getMitra)) {
  $mitra_terpilih[] = $m['mitra_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $old_data = json_encode($kegiatan);

  $kro = $_POST['kro'] === 'custom' ? $_POST['kro_baru'] : $_POST['kro'];
  $nama = mysqli_real_escape_string($host, $_POST['nama_kegiatan']);
  $detail = mysqli_real_escape_string($host, $_POST['detail_kegiatan']);
  $realisasi = intval($_POST['realisasi']);
  $target_anggaran = mysqli_real_escape_string($host, $_POST['target_anggaran']);
  $progres = intval($_POST['progres']);
  $pic = mysqli_real_escape_string($host, $_POST['pic']);
  $kendala = mysqli_real_escape_string($host, $_POST['kendala']);
  $solusi = mysqli_real_escape_string($host, $_POST['solusi']);
  $tindaklanjut = mysqli_real_escape_string($host, $_POST['tindak_lanjut']);
  $bataswaktu = mysqli_real_escape_string($host, $_POST['batas_waktu']);
  $mitra_terpilih_post = isset($_POST['mitra']) ? $_POST['mitra'] : [];

  // Tambahkan KRO baru jika dipilih custom
  if ($_POST['kro'] === 'custom') {
    $cek_kro = mysqli_query($host, "SELECT * FROM kro WHERE kode_kro = '$kro'");
    if (mysqli_num_rows($cek_kro) == 0) {
      mysqli_query($host, "INSERT INTO kro (kode_kro) VALUES ('$kro')");
    }
  }

  $update = mysqli_query($host, "UPDATE kegiatan SET 
    kro='$kro',
    nama_kegiatan='$nama',
    detail_kegiatan='$detail',
    target_anggaran='$target_anggaran',
    realisasi='$realisasi',
    progres='$progres',
    pic='$pic',
    kendala='$kendala',
    solusi='$solusi',
    tindak_lanjut='$tindaklanjut',
    batas_waktu='$bataswaktu'
    WHERE id='$id'");

  if ($update) {
    // Simpan mitra: hapus dulu lalu insert baru
    mysqli_query($host, "DELETE FROM kegiatan_mitra WHERE kegiatan_id = '$id'");
    foreach ($mitra_terpilih_post as $mitra_id) {
      $mitra_id_safe = intval($mitra_id);
      mysqli_query($host, "INSERT INTO kegiatan_mitra (kegiatan_id, mitra_id) VALUES ('$id', '$mitra_id_safe')");
    }

    // Simpan log
    $new_data = [
      'kro' => $kro,
      'nama_kegiatan' => $nama,
      'detail_kegiatan' => $detail,
      'target_anggaran' => $target_anggaran,
      'realisasi' => $realisasi,
      'progres' => $progres,
      'pic' => $pic,
      'kendala' => $kendala,
      'solusi' => $solusi,
      'tindak_lanjut' => $tindaklanjut,
      'batas_waktu' => $bataswaktu
    ];
    $new_data_json = json_encode($new_data);
    $username = $_SESSION['user'];

    mysqli_query($host, "INSERT INTO log_kegiatan (kegiatan_id, updated_by, old_data, new_data) VALUES (
      '$id', '$username', '" . mysqli_real_escape_string($host, $old_data) . "', '" . mysqli_real_escape_string($host, $new_data_json) . "'
    )");

    header("Location: kegiatan.php");
    exit;
  } else {
    echo "Gagal update kegiatan: " . mysqli_error($host);
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Kegiatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tambahkan di bagian <head> -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    #customKRO {
      display: none;
    }
  </style>
</head>

<body class="bg-light">
  <?php include 'layout/sidebar.php'; ?>
  <div class="container mt-4">
    <div class="card shadow p-4">
      <h4 class="mb-4">Edit Kegiatan</h4>
      <form method="POST">
        <!-- KRO -->
        <div class="mb-3">
          <label class="form-label">KRO</label>
          <select name="kro" class="form-select" id="kroSelect" onchange="toggleCustomKRO()" required>
            <option value="">-- Pilih KRO --</option>
            <?php while ($row = mysqli_fetch_assoc($kro_result)) : ?>
              <option value="<?= $row['kode_kro'] ?>" <?= ($kegiatan['kro'] === $row['kode_kro']) ? 'selected' : '' ?>>
                <?= $row['kode_kro'] ?>
              </option>
            <?php endwhile; ?>
            <option value="custom">+ Tambah KRO Baru</option>
          </select>
        </div>

        <div class="mb-3" id="customKRO">
          <label class="form-label">KRO Baru</label>
          <input type="text" name="kro_baru" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Kegiatan</label>
          <input type="text" name="nama_kegiatan" class="form-control" value="<?= htmlspecialchars($kegiatan['nama_kegiatan']) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Detail Kegiatan</label>
          <textarea name="detail_kegiatan" class="form-control" rows="3" required><?= htmlspecialchars($kegiatan['detail_kegiatan']) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Target Anggaran</label>
          <input type="number" name="target_anggaran" class="form-control" value="<?= $kegiatan['target_anggaran'] ?>" min="0" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Realisasi Anggaran (Rp)</label>
          <input type="number" name="realisasi" class="form-control" value="<?= $kegiatan['realisasi'] ?>" min="0" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Progres Kegiatan (%)</label>
          <input type="range" name="progres" class="form-range" min="0" max="100" value="<?= intval($kegiatan['progres']) ?>" id="progresRange">
          <span id="rangeValue"><?= intval($kegiatan['progres']) ?></span>%
        </div>

        <div class="mb-3">
          <label class="form-label">PIC</label>
          <select class="form-select" disabled>
            <option value="">-- Pilih PIC --</option>
            <?php foreach ($picOptions as $option) : ?>
              <option value="<?= $option ?>" <?= ($kegiatan['pic'] === $option) ? 'selected' : '' ?>><?= $option ?></option>
            <?php endforeach; ?>
          </select>
          <input type="hidden" name="pic" value="<?= $kegiatan['pic'] ?>">
        </div>

        <!-- Ganti bagian dropdown Mitra ini -->

        <div class="mb-3">
          <label class="form-label">Mitra Terkait</label>
          <select name="mitra[]" class="form-select" id="mitraSelect" multiple>
            <?php while ($mitra = mysqli_fetch_assoc($mitra_result)) : ?>
              <option value="<?= $mitra['id'] ?>" <?= in_array($mitra['id'], $mitra_terpilih) ? 'selected' : '' ?>>
                <?= htmlspecialchars($mitra['nama']) ?>
              </option>
            <?php endwhile; ?>
          </select>
          <small class="text-muted">Pilih satu atau beberapa mitra</small>
        </div>


        <div class="mb-3">
          <label class="form-label">Kendala</label>
          <textarea name="kendala" class="form-control"><?= htmlspecialchars($kegiatan['kendala'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Solusi</label>
          <textarea name="solusi" class="form-control"><?= htmlspecialchars($kegiatan['solusi'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Tindak Lanjut</label>
          <textarea name="tindak_lanjut" class="form-control"><?= htmlspecialchars($kegiatan['tindak_lanjut'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Batas Waktu Tindak Lanjut</label>
          <input type="date" name="batas_waktu" class="form-control" value="<?= htmlspecialchars($kegiatan['batas_waktu'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label>Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($kegiatan['tanggal_mulai'] ?? '') ?>" readonly>
        </div>
        <div class="mb-3">
          <label>Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($kegiatan['tanggal_selesai'] ?? '') ?>" readonly>
        </div>
        <div class="d-flex justify-content-between">
          <button class="btn btn-warning">Update</button>
          <a href="kegiatan.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>

  <!-- Tambahkan sebelum penutup </body> -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#mitraSelect').select2({
        placeholder: "Pilih mitra...",
        width: '100%',
        allowClear: true
      });
    });
  </script>


  <script>
    const range = document.getElementById('progresRange');
    const rangeValue = document.getElementById('rangeValue');
    range.addEventListener('input', () => {
      rangeValue.textContent = range.value;
    });

    function toggleCustomKRO() {
      const kroSelect = document.getElementById('kroSelect');
      const customInput = document.getElementById('customKRO');
      customInput.style.display = kroSelect.value === 'custom' ? 'block' : 'none';
    }
  </script>

  <script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      const icon = toggleBtn.querySelector('i');
      icon.classList.toggle('bi-chevron-double-left');
      icon.classList.toggle('bi-chevron-double-right');
    });
  </script>
</body>

</html>