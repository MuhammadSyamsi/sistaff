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
          <!-- Input Kelas -->
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h5 class="fw-semibold mb-3"><i class="bi bi-house-door me-1"></i> Kelas</h5>
                <?php if (!empty($kelas)): ?>
                  <div class="list-group">
                    <?php foreach ($kelas as $guru): ?>
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?= esc($guru['nama_kelas']) ?> - <?= esc($guru['tingkat']) ?></span>
                        <div class="d-flex gap-2">
                          <a href="<?= site_url('guru/edit/' . $guru['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                          </a>
                          <form action="<?= site_url('kelas/delete/' . $guru['id']) ?>" method="post" onsubmit="return confirm('Hapus data ini?')" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </div>
                      </div>
                    <?php endforeach ?>
                  </div>
                <?php else: ?>
                  <div class="text-center text-muted">Belum ada Kelas.</div>
                <?php endif ?>
                <div class="position-relative text-center my-3">
                  <hr>
                  <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted small">
                    Tambah Kelas
                  </span>
                </div>
                <form action="<?= base_url('jadwal/kelas/simpan') ?>" method="post">
                  <div class="input-group">
                    <input type="text" name="nama_kelas" class="form-control" placeholder="Kelas ( 7, 8, 9, ... )" required>
                    <input type="text" name="tingkat" class="form-control" placeholder="Rombel ( A, B, C, ... )" required>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal Tambah Guru -->
<div class="modal fade" id="modalGuru" tabindex="-1" aria-labelledby="modalGuruLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-sm">
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title" id="modalGuruLabel">Tambah Guru</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?= $this->include('guru/_form') ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
