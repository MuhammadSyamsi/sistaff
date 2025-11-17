<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm mb-4">
        <div class="card-body">

<!-- Statistik Santri (Gabung Jadi 1 Card) -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Statistik Santri</h5>
    <div class="row text-center">
      <div class="col-4 mb-3 mb-md-0">
        <div class="text-muted small">Total Calon Santri</div>
        <div class="h3 fw-bold text-warning"><?= $total ?></div>
      </div>
      <div class="col-4 mb-3 mb-md-0 border-start border-end">
        <div class="text-muted small">Santri MTs</div>
        <div class="h3 fw-bold text-secondary"><?= $mts ?></div>
      </div>
      <div class="col-4">
        <div class="text-muted small">Santri MA</div>
        <div class="h3 fw-bold text-danger"><?= $ma ?></div>
      </div>
    </div>
  </div>
</div>

          <!-- Filter Santri -->
          <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center mb-2">
              <h5 class="fw-bold mb-0">Data Santri</h5>
              
              <!-- Tombol Tambah -->
              <a href="<?= base_url('pendaftaran-formulir') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pendaftaran
              </a>
            </div>

            <div class="col-12">
            <form id="formFilter" class="row g-2 align-items-end">
              <!-- Jenjang -->
              <div class="col-md-2">
                <label for="filterJenjang" class="form-label mb-0">Jenjang</label>
                <select name="jenjang" id="filterJenjang" class="form-select">
                  <option value="">Pilih Jenjang</option>
                  <?php foreach ($filterJenjang as $fj): ?>
                    <option value="<?= $fj['jenjang']; ?>"><?= $fj['jenjang']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Kelas -->
              <div class="col-md-2">
                <label for="filterKelas" class="form-label mb-0">Kelas</label>
                <select name="kelas" id="filterKelas" class="form-select" disabled>
                  <option value="">Pilih Kelas</option>
                </select>
              </div>

              <!-- Status -->
              <div class="col-md-2">
                <label for="filterStatus" class="form-label mb-0">Status</label>
                <select name="status" id="filterStatus" class="form-select">
                  <option value="">Pilih Status</option>
                  <?php foreach ($statusList as $s): ?>
                    <option value="<?= $s['status'] ?>"><?= ucfirst($s['status']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Pencarian -->
              <div class="col-md-6">
                <label for="keyword" class="form-label mb-0">Pencarian Nama</label>
                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Cari Nama..." autocomplete="off">
              </div>
            </form>
          </div>
            <!-- Card AJAX -->
  <div class="col-12 mt-3">
    <div id="cardListSantri">
      <!-- Data akan muncul di sini -->
    </div>
  </div>
          </div>

        </div> <!-- card-body -->
      </div> <!-- card -->
    </div> <!-- col -->
  </div> <!-- row -->
</div> <!-- container-fluid -->

<!-- Script Filtering -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
  const kelasByJenjang = <?= json_encode($kelasByJenjang) ?>;
  const form = $('#formFilter');

  $('#filterJenjang').on('change', function () {
    const jenjang = $(this).val();
    let html = '<option value="">Pilih Kelas</option>';

    if (jenjang && kelasByJenjang[jenjang]) {
      kelasByJenjang[jenjang].forEach(k => {
        html += `<option value="${k}">${k}</option>`;
      });
      $('#filterKelas').html(html).prop('disabled', false);
    } else {
      $('#filterKelas').html(html).prop('disabled', true);
    }

    filterSantri();
  });

  $('#filterKelas, #filterStatus, #keyword').on('change keyup', function () {
    filterSantri();
  });

  function filterSantri() {
    const kelas = $('#filterKelas').val();
    const keyword = $('#keyword').val().trim();
    const jenjang = $('#filterJenjang').val();

    if (jenjang && kelas || keyword.length > 0) {
      $.ajax({
        type: 'GET',
        url: '<?= base_url('Santri/psb') ?>',
        data: form.serialize(),
        success: function (html) {
          $('#cardListSantri').html(html);
        }
      });
    } else {
      $('#cardListSantri').html('');
    }
  }

  filterSantri(); // initial load
});
</script>

<?= $this->endSection(); ?>
