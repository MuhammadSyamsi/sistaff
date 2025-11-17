<?php
if (in_groups('superadmin')) {
    echo $this->extend('template');
} else {
    echo $this->extend('template_sekolah');
}
?>
<?= $this->section('konten') ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="row mx-2 py-4">
<!-- Accordion: Atur Jadwal Guru -->
<div class="accordion mb-4" id="accordionFormJadwal">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingForm">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
        <i class="bi bi-check-square me-2"></i> Atur Jadwal Guru
      </button>
    </h2>
    <div id="collapseForm" class="accordion-collapse collapse" aria-labelledby="headingForm" data-bs-parent="#accordionFormJadwal">
      <div class="accordion-body">

      <form id="formJadwal" action="<?= base_url('jadwal/simpanChecklist') ?>" method="post">
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label for="guru_id" class="form-label">Guru</label>
            <select name="guru_id" id="guru_id" class="form-select" required>
              <option value="">Pilih Guru</option>
              <?php foreach ($guruList as $guru): ?>
                <option value="<?= $guru['id'] ?>"><?= esc($guru['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label for="mapel_id" class="form-label">Mata Pelajaran</label>
            <select name="mapel_id" id="mapel_id" class="form-select" required>
              <option value="">Pilih Mapel</option>
              <?php foreach ($matpel as $mapel): ?>
                <option value="<?= $mapel['id'] ?>"><?= esc($mapel['nama_mapel']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="accordion" id="accordionJadwalKelas">
          <?php foreach ($kelasChecklist as $index => $kelas): ?>
            <?php
              $accordionId = 'kelasAccordion' . $kelas['id'];
              $collapseId = 'collapse' . $kelas['id'];
            ?>
            <div class="accordion-item">
              <h2 class="accordion-header" id="<?= $accordionId ?>">
                <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="<?= $index == 0 ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">
                  <?= esc($kelas['nama_kelas']) ?> - <?= esc($kelas['tingkat']) ?>
                </button>
              </h2>
              <div id="<?= $collapseId ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" aria-labelledby="<?= $accordionId ?>" data-bs-parent="#accordionJadwalKelas">
                <div class="accordion-body">
                  <input type="hidden" name="kelas_id[]" value="<?= $kelas['id'] ?>">

                  <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                      <thead class="table-light">
                        <tr>
                          <th>Hari / Jam ke-</th>
                          <?php for ($i = 1; $i <= 8; $i++): ?>
                            <th class="text-center">Ke-<?= $i ?></th>
                          <?php endfor; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($hari as $h): ?>
                          <tr>
                            <th><?= esc($h['nama_hari']) ?></th>
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                              <td class="text-center">
<?php
$isTerisi = isset($slotTerisi[$kelas['id']][$h['id']][$i]);
?>

<input 
<input 
  type="checkbox" 
  class="form-check-input"
  name="slots[<?= $kelas['id'] ?>][<?= $h['id'] ?>][]"
  value="<?= $i ?>"
  <?= $isTerisi ? 'checked disabled title="Sudah terisi di jadwal"' : '' ?>>
                              </td>
                            <?php endfor; ?>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="mt-3 text-end">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Simpan Jadwal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

                    <!-- Jadwal Tabel -->
<div class="col-md-12 mb-4">
  <div class="card shadow-sm">
    <div class="card-body">

      <!-- Header dan Tombol -->
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h5 class="mb-0">
          <i class="bi bi-calendar-week me-1"></i> Jadwal Pelajaran
        </h5>
        <form action="<?= base_url('jadwal/generate/proses') ?>" method="post" class="mb-0">
          <button class="btn btn-warning" type="submit">
            <i class="bi bi-shuffle me-1"></i> Generate Otomatis
          </button>
        </form>
        <form action="<?= base_url('jadwal/reset') ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus SEMUA jadwal? Tindakan ini tidak bisa dibatalkan.')">
  <?= csrf_field() ?>
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-trash"></i> Reset Semua Jadwal
  </button>
</form>
      </div>

      <!-- Konten Jadwal -->
      <?php if (empty($jadwalGrouped)): ?>
        <div class="text-center text-muted py-5">
          <i class="bi bi-exclamation-circle me-2"></i> Belum ada jadwal tersedia.
        </div>
      <?php else: ?>
        <?php foreach ($jadwalGrouped as $hari => $jamData): ?>
          <div class="mb-4">
            <h6 class="fw-bold mb-3">
              <i class="bi bi-calendar3 me-1"></i> Hari <?= esc($hari) ?>
            </h6>
            <div class="table-responsive">
              <table class="table table-bordered table-sm align-middle text-center">
                <thead class="table-light">
                  <tr>
                    <th style="width: 80px;">Jam Ke</th>
                    <?php foreach ($kelasList as $kelas): ?>
                      <th><?= esc($kelas) ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($jamData as $jam => $kelasRow): ?>
                    <tr>
                      <td><?= $jam ?></td>
                      <?php foreach ($kelasList as $kelas): ?>
                        <td>
<?php if (isset($kelasRow[$kelas])): ?>
  <div class="jadwal-cell" data-id="<?= $kelasRow[$kelas]['id'] ?>">
    <div class="jadwal-view">
      <div><?= esc($kelasRow[$kelas]['nama_mapel']) ?></div>
      <small class="text-muted"><?= esc($kelasRow[$kelas]['nama_guru']) ?></small>
      <div class="mt-1">
        <button class="btn btn-sm btn-outline-primary btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-outline-danger btn-hapus" title="Hapus"><i class="bi bi-trash"></i></button>
      </div>
    </div>
  </div>
<?php else: ?>
  <span class="text-muted">-</span>
<?php endif; ?>
                        </td>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>

<template id="edit-template">
  <form class="edit-form d-flex flex-column gap-1">
    <select name="mapel_id" class="form-select form-select-sm" required>
      <?php foreach ($matpel as $m): ?>
        <option value="<?= $m['id'] ?>"><?= esc($m['nama_mapel']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="guru_id" class="form-select form-select-sm" required>
      <?php foreach ($guruList as $g): ?>
        <option value="<?= $g['id'] ?>"><?= esc($g['nama']) ?></option>
      <?php endforeach; ?>
    </select>
    <div class="d-flex justify-content-end gap-1 mt-1">
      <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-save"></i></button>
      <button type="button" class="btn btn-secondary btn-sm btn-cancel"><i class="bi bi-x"></i></button>
    </div>
  </form>
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const template = document.getElementById('edit-template');

  function attachEditAndDeleteListeners() {
    document.querySelectorAll('.btn-edit').forEach(button => {
      button.addEventListener('click', () => {
        const cell = button.closest('.jadwal-cell');
        const id = cell.dataset.id;
        const view = cell.querySelector('.jadwal-view');

        // Simpan konten asli hanya jika belum ada
        if (!view.dataset.original) {
          view.dataset.original = view.innerHTML;
        }

        // Tampilkan form
        const form = template.content.cloneNode(true).querySelector('.edit-form');
        view.innerHTML = '';
        view.appendChild(form);

        // Handle cancel
        form.querySelector('.btn-cancel').addEventListener('click', () => {
          view.innerHTML = view.dataset.original;
          delete view.dataset.original;
          attachEditAndDeleteListeners(); // Re-attach tombol
        });

        // Handle submit
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          const formData = new FormData(form);
          const res = await fetch(`<?= base_url('jadwal/update') ?>/${id}`, {
            method: 'POST',
            body: formData
          });

          if (res.ok) {
            const json = await res.json();
            view.innerHTML = `
              <div>${json.nama_mapel}</div>
              <small class="text-muted">${json.nama_guru}</small>
              <div class="mt-1">
                <button class="btn btn-sm btn-outline-primary btn-edit"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger btn-hapus"><i class="bi bi-trash"></i></button>
              </div>
            `;
            delete view.dataset.original;
            attachEditAndDeleteListeners(); // Re-attach tombol
          } else {
            alert('Gagal update jadwal.');
          }
        });
      });
    });

    document.querySelectorAll('.btn-hapus').forEach(button => {
      button.addEventListener('click', async () => {
        if (!confirm('Yakin hapus jadwal ini?')) return;
        const cell = button.closest('.jadwal-cell');
        const id = cell.dataset.id;

        const res = await fetch(`<?= base_url('jadwal/hapus') ?>/${id}`, {
          method: 'DELETE'
        });

        if (res.ok) {
          cell.innerHTML = `
  <div class="jadwal-view">
    <span class="text-muted">-</span>
    <div class="mt-1">
      <button class="btn btn-sm btn-outline-primary btn-edit"><i class="bi bi-pencil"></i></button>
    </div>
  </div>
`;
const newEditBtn = cell.querySelector('.btn-edit');
if (newEditBtn) {
  newEditBtn.addEventListener('click', () => {
    // Sama seperti fungsi klik edit awal
    const id = cell.dataset.id;
    const view = cell.querySelector('.jadwal-view');
    const originalContent = view.innerHTML;
    const form = template.content.cloneNode(true).querySelector('.edit-form');
    view.innerHTML = '';
    view.appendChild(form);

    form.querySelector('.btn-cancel').addEventListener('click', () => {
      view.innerHTML = originalContent;
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const res = await fetch(`<?= base_url('jadwal/update') ?>/${id}`, {
        method: 'POST',
        body: formData
      });

      if (res.ok) {
        const json = await res.json();
        view.innerHTML = `
          <div>${json.nama_mapel}</div>
          <small class="text-muted">${json.nama_guru}</small>
          <div class="mt-1">
            <button class="btn btn-sm btn-outline-primary btn-edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-outline-danger btn-hapus"><i class="bi bi-trash"></i></button>
          </div>
        `;
        // Tambahkan event listener ulang
        setupButtonEvents(cell);
      } else {
        alert('Gagal update jadwal.');
      }
    });
  });
}
        } else {
          alert('Gagal hapus jadwal.');
        }
      });
    });
  }

  // Inisialisasi awal
  attachEditAndDeleteListeners();
});
</script>

<?= $this->endSection() ?>
