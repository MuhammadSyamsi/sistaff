<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>
<?php $today = date('Y-m-d'); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between mb-3">
            <h3 class="card-title fw-semibold">Formulir Santri Baru</h3>
            <!-- Tombol View Data -->
            <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#psbModal">
              <i class="bi bi-table"></i> View Data
            </button>
          </div>

          <form action="<?= base_url('formulir_psb') ?>" method="post">
            <?= csrf_field(); ?>

            <div class="table-responsive">
              <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                  <tr>
                    <th>Nama</th>
                    <th>Jenjang</th>
                    <th>Tanggal Daftar</th>
                    <th>Bayar Formulir</th>
                    <th>Rekening</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="form-container">
                  <!-- Start of repeatable row -->
                  <tr class="repeatable-row">
                    <input type="hidden" name="santri[0][id]" value="<?= $id ? max($id)+1 : 1; ?>">
                    <input type="hidden" name="santri[0][nisn]" value="0">
                    <input type="hidden" name="santri[0][program]" value="MANDIRI">
                    <input type="hidden" name="santri[0][status]" value="formulir">

                    <td>
                      <input type="text" class="form-control" name="santri[0][nama]" placeholder="Nama" required>
                    </td>
                    <td>
                      <select class="form-select" name="santri[0][jenjang]">
                        <option value="MTs|7">MTs</option>
                        <option value="MA|10">MA</option>
                      </select>
                    </td>
                    <td>
                      <input type="date" class="form-control" name="santri[0][tanggal]" value="<?= $today ?>">
                    </td>
                    <td>
                      <input type="number" class="form-control" name="santri[0][formulir]" value="0">
                    </td>
                    <td>
                      <select class="form-select" name="santri[0][rekening]">
                        <option value="Muamalat Salam">Muamalat Salam</option>
                        <option value="Jatim Syariah">Jatim Syariah</option>
                        <option value="BSI">BSI</option>
                        <option value="Tunai">Tunai</option>
                        <option value="lain-lain">Lain-lain</option>
                      </select>
                    </td>
                    <td>
                      <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="bi bi-dash-circle"></i>
                      </button>
                    </td>
                  </tr>
                  <!-- End of repeatable row -->
                </tbody>
              </table>
            </div>

            <button type="button" id="add-row" class="btn btn-success btn-sm mb-3">
              <i class="bi bi-plus-circle"></i> Tambah Baris
            </button>

            <div>
              <button type="submit" class="btn btn-dark">Simpan Semua</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal View Data -->
<div class="modal fade" id="psbModal" tabindex="-1" aria-labelledby="psbModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Data Pendaftaran Santri</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <!-- Filter -->
        <div class="row mb-3">
          <div class="col-lg-6">
            <select id="filter-status" class="form-select">
              <option value="">-- Semua Status --</option>
              <option value="formulir">Formulir</option>
              <option value="diterima">Diterima</option>
              <option value="lulus">Lulus</option>
            </select>
          </div>
          <div class="col-lg-6">
            <button id="btn-filter" class="btn btn-primary w-100">Filter</button>
          </div>
        </div>

        <div id="table-container">
          <p class="text-muted">Silakan pilih filter untuk melihat data...</p>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script>
let rowIndex = 1;

// Tambah baris baru
document.getElementById('add-row').addEventListener('click', function() {
  let container = document.getElementById('form-container');
  let clone = container.querySelector('.repeatable-row').cloneNode(true);

  clone.querySelectorAll('input, select').forEach(el => {
    let name = el.getAttribute('name');
    if (name) {
      el.setAttribute('name', name.replace(/\[\d+\]/, '[' + rowIndex + ']'));
      if (el.type !== 'hidden') {
        el.value = (el.tagName === 'SELECT') ? el.options[0].value : '';
      }
    }
  });

  container.appendChild(clone);
  rowIndex++;
});

// Hapus baris
document.addEventListener('click', function(e) {
  if (e.target.closest('.remove-row')) {
    let row = e.target.closest('.repeatable-row');
    if (document.querySelectorAll('.repeatable-row').length > 1) {
      row.remove();
    } else {
      alert('Minimal satu baris harus ada!');
    }
  }
});

// Filter data via AJAX
document.getElementById('btn-filter').addEventListener('click', function() {
  let status = document.getElementById('filter-status').value;

  fetch("<?= base_url('psb/filter') ?>", {
    method: "POST",
    headers: { "Content-Type": "application/json", "X-Requested-With": "XMLHttpRequest" },
    body: JSON.stringify({status: status})
  })
  .then(res => res.text())
  .then(html => {
    document.getElementById('table-container').innerHTML = html;
  });
});
</script>

<?= $this->endSection(); ?>
