<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: ../index.php");
  exit;
}
include "../config/koneksi.php";

// Ambil data user yang login
$currentUser = $_SESSION['user'];
$userPIC = $currentUser['sub_bagian'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kro = $_POST['kro'];
  $nama_kegiatan = $_POST['nama_kegiatan'];

  $details = $_POST['detail_kegiatan'];
  $target_anggaran_list = $_POST['target_anggaran'];
  $progres_list = $_POST['progres'];
  $mitra_ids_list = $_POST['mitra_id_detail'];
  $tanggal_mulai_list = $_POST['tanggal_mulai_detail'];
  $tanggal_selesai_list = $_POST['tanggal_selesai_detail'];

  $success = true;

  for ($i = 0; $i < count($details); $i++) {
    $detail = $details[$i];
    $target = !empty($target_anggaran_list[$i]) ? $target_anggaran_list[$i] : null;
    $prog = $progres_list[$i];
    $tanggal_mulai = $tanggal_mulai_list[$i];
    $tanggal_selesai = $tanggal_selesai_list[$i];

    $insert = mysqli_query($host, "INSERT INTO kegiatan (kro, nama_kegiatan, detail_kegiatan, target_anggaran, progres, pic, tanggal_mulai, tanggal_selesai)
      VALUES ('$kro', '$nama_kegiatan', '$detail', " . ($target !== null ? "'$target'" : "NULL") . ", '$prog', '$userPIC', '$tanggal_mulai', '$tanggal_selesai')");

    if ($insert) {
      $kegiatan_id = mysqli_insert_id($host);
      if (!empty($mitra_ids_list[$i]) && is_array($mitra_ids_list[$i])) {
        foreach ($mitra_ids_list[$i] as $mitra_id) {
          mysqli_query($host, "INSERT INTO kegiatan_mitra (kegiatan_id, mitra_id) VALUES ('$kegiatan_id', '$mitra_id')");
        }
      }
    } else {
      $success = false;
      echo "Gagal menyimpan data: " . mysqli_error($host);
      break;
    }
  }

  if ($success) {
    header("Location: kegiatan.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah Kegiatan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <style>
    .select2-container .select2-selection--multiple {
      height: auto;
    }

    .selected-mitra {
      font-size: 0.9em;
      color: #555;
      margin-top: 5px;
    }
  </style>
</head>

<body>
  <?php include 'layout/sidebar.php'; ?>
  <div class="container mt-4">
    <h4 class="mb-4">Tambah Kegiatan</h4>
    <form method="POST" id="formKegiatan">
      <div class="mb-3">
        <label class="form-label">KRO</label>
        <select name="kro" class="form-select" required>
          <option value="">-- Pilih KRO --</option>
          <?php
          $kro_result = mysqli_query($host, "SELECT * FROM kro ORDER BY kode_kro ASC");
          while ($row = mysqli_fetch_assoc($kro_result)) {
            echo '<option value="' . htmlspecialchars($row['kode_kro']) . '">' . htmlspecialchars($row['kode_kro']) . '</option>';
          }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Nama Kegiatan</label>
        <input type="text" name="nama_kegiatan" class="form-control" required>
      </div>

      <div id="detailContainer">
        <div class="detail-item border rounded p-3 mb-3 position-relative">
          <label class="form-label">Detail Kegiatan</label>
          <textarea name="detail_kegiatan[]" class="form-control mb-2" required></textarea>

          <label class="form-label">Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai_detail[]" class="form-control tanggal mb-2" required>

          <label class="form-label">Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai_detail[]" class="form-control tanggal mb-2" required>

          <label class="form-label">Target Anggaran</label>
          <div class="input-group mb-2">
            <span class="input-group-text">Rp</span>
            <input type="number" name="target_anggaran[]" class="form-control">
          </div>

          <label class="form-label">Progres</label>
          <input type="range" name="progres[]" class="form-range" min="0" max="100" value="0" oninput="this.nextElementSibling.innerText = this.value + '%'">
          <div>0%</div>

          <label class="form-label mt-2">Pilih Mitra</label>
          <select name="mitra_id_detail[0][]" class="form-select mitra-select" multiple></select>
          <div class="selected-mitra mt-1"></div>
        </div>
      </div>

      <button type="button" class="btn btn-outline-primary mb-3" onclick="tambahDetail()">+ Tambah Detail Kegiatan</button>

      <div class="d-flex justify-content-between">
        <button class="btn btn-primary">Simpan</button>
        <a href="kegiatan.php" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    let detailCount = 1;
    let mitraOptions = '';
    let mitraTerpilihGlobal = [];

    function initSelect2() {
      $('.mitra-select').select2({
        placeholder: "Pilih Mitra...",
        allowClear: true,
        width: '100%'
      }).off('change').on('change', function() {
        updateMitraTerpilih();
        updateMitraText(this);
      });

      $('.mitra-select').each(function() {
        updateMitraText(this);
      });
    }

    function updateMitraText(selectElem) {
      const selectedOptions = $(selectElem).select2('data');
      const mitraText = selectedOptions.map(opt => opt.text).join(', ');
      $(selectElem).siblings('.selected-mitra').text(mitraText);
    }

    function updateMitraTerpilih() {
      mitraTerpilihGlobal = [];
      $('.mitra-select').each(function() {
        const selected = $(this).val();
        if (selected) {
          mitraTerpilihGlobal.push(...selected);
        }
      });
    }

    $(document).ready(function() {
      initSelect2();
      loadAvailableMitra();
    });

    $('.tanggal').on('change', function() {
      loadAvailableMitra();
    });

    function loadAvailableMitra() {
      const mulai = $('input[name="tanggal_mulai_detail[]"]').first().val();
      const selesai = $('input[name="tanggal_selesai_detail[]"]').first().val();

      if (!mulai || !selesai) return;

      $.ajax({
        url: 'get_mitra_tersedia.php',
        method: 'POST',
        data: {
          tanggal_mulai: mulai,
          tanggal_selesai: selesai
        },
        success: function(response) {
          mitraOptions = response;
          updateAllSelectOptions();
        }
      });
    }

    function updateAllSelectOptions() {
      $('.mitra-select').each(function() {
        const currentSelect = $(this);
        const selected = currentSelect.val() || [];
        const filteredOptions = $(mitraOptions).filter(function() {
          return !mitraTerpilihGlobal.includes(this.value) || selected.includes(this.value);
        });
        currentSelect.empty().append(filteredOptions).val(selected);
      });
      initSelect2();
    }

    function tambahDetail() {
      updateMitraTerpilih();

      const container = document.getElementById('detailContainer');
      const index = detailCount;

      const filteredOptions = $(mitraOptions).filter(function() {
        return !mitraTerpilihGlobal.includes(this.value);
      });

      const optionHtml = $('<div>').append(filteredOptions.clone()).html();

      const detailHtml = `
        <div class="detail-item border rounded p-3 mb-3 position-relative">
          <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="hapusDetail(this)">Hapus</button>

          <label class="form-label">Detail Kegiatan</label>
          <textarea name="detail_kegiatan[]" class="form-control mb-2" required></textarea>

          <label class="form-label">Tanggal Mulai</label>
          <input type="date" name="tanggal_mulai_detail[]" class="form-control tanggal mb-2" required>

          <label class="form-label">Tanggal Selesai</label>
          <input type="date" name="tanggal_selesai_detail[]" class="form-control tanggal mb-2" required>

          <label class="form-label">Target Anggaran</label>
          <div class="input-group mb-2">
            <span class="input-group-text">Rp</span>
            <input type="number" name="target_anggaran[]" class="form-control">
          </div>

          <label class="form-label">Progres</label>
          <input type="range" name="progres[]" class="form-range" min="0" max="100" value="0"
            oninput="this.nextElementSibling.innerText = this.value + '%'">
          <div>0%</div>

          <label class="form-label mt-2">Pilih Mitra</label>
          <select name="mitra_id_detail[${index}][]" class="form-select mitra-select" multiple>
            ${optionHtml}
          </select>
          <div class="selected-mitra mt-1"></div>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', detailHtml);
      detailCount++;
      initSelect2();
    }

    function hapusDetail(button) {
      $(button).closest('.detail-item').remove();
      updateMitraTerpilih();
      updateAllSelectOptions();
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