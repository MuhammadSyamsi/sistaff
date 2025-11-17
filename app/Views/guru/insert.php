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
          <!-- Data Guru -->
          <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h5 class="fw-semibold mb-3"><i class="bi bi-journal-bookmark-fill me-1"></i> Data Guru</h5>
                <?php if (!empty($guruList)): ?>
                  <div class="accordion" id="accordionGuru">
                    <?php foreach ($guruList as $i => $guru): ?>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $i ?>">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>" aria-expanded="false" aria-controls="collapse<?= $i ?>">
                            Ustadz <?= esc($guru['nama']) ?>
                          </button>
                        </h2>
                        <div id="collapse<?= $i ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $i ?>" data-bs-parent="#accordionGuru">
                          <div class="accordion-body small">
                            <p class="mb-1"><strong>NIP:</strong> <?= esc($guru['nip']) ?></p>
                            <p class="mb-1"><strong>Jabatan:</strong> <?= esc($guru['jabatan']) ?></p>
                            <p class="mb-1"><strong>Wali Kelas:</strong> <?= esc($guru['kelas']) ?></p>
                            <p class="mb-2"><strong>Pendidikan:</strong> <?= esc($guru['pendidikan_akhir']) ?></p>
                            <div class="d-flex gap-2">
                              <a href="<?= site_url('guru/edit/' . $guru['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil-square"></i> Edit
                              </a>
                              <form action="<?= site_url('guru/delete/' . $guru['id']) ?>" method="post" onsubmit="return confirm('Hapus data ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                  <i class="bi bi-trash"></i> Hapus
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach ?>
                  </div>
                <?php else: ?>
                  <div class="text-center text-muted">Belum ada data guru.</div>
                <?php endif ?>
                <div class="position-relative text-center my-3">
                  <hr>
                  <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted small">
                    Tambah Guru
                  </span>
                </div>
                <form action="<?= base_url('guru/save') ?>" method="post">
                  <div class="input-group">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Guru" required>
                    <button class="btn btn-primary" type="submit">Tambah</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

                    <!-- Distribusi Jam Guru -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="mb-3"><i class="bi bi-person-lines-fill me-1"></i>Distribusi Jam Guru / Minggu</h5>

                                <?php
                                $grouped = [];
                                foreach ($guruMapel as $item) {
                                    $grouped[$item['nama_guru']][] = $item;
                                }
                                ?>

                                <?php if (!empty($grouped)): ?>
                                    <div class="accordion" id="accordionMapel">
                                        <?php foreach ($grouped as $mapel => $daftarGuru): ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading<?= md5($mapel) ?>">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#mapel<?= md5($mapel) ?>" aria-expanded="false" aria-controls="mapel<?= md5($mapel) ?>">
                                                        Ustadz <?= esc($mapel) ?>
                                                    </button>
                                                </h2>
                                                <div id="mapel<?= md5($mapel) ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= md5($mapel) ?>" data-bs-parent="#accordionMapel">
                                                    <div class="accordion-body small">
                                                        <?php foreach ($daftarGuru as $guru): ?>
                                                            <div class="mb-3 border-bottom pb-2">
                                                                <p class="mb-1"><strong>Nama Mapel:</strong> <?= esc($guru['nama_mapel']) ?></p>
                                                                <p class="mb-1"><strong>Jumlah Jam:</strong> <?= esc($guru['jumlah_jam']) ?></p>
                                                                <div class="d-flex gap-2">
                                                                    <a href="<?= site_url('guru/edit/' . $guru['id']) ?>" class="btn btn-sm btn-warning" title="Edit">
                                                                        <i class="bi bi-pencil-square"></i> Edit
                                                                    </a>
                                                                    <form action="<?= site_url('guru/delete/' . $guru['id']) ?>" method="post" onsubmit="return confirm('Hapus data ini?')">
                                                                        <?= csrf_field() ?>
                                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                            <i class="bi bi-trash"></i> Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        <?php endforeach ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center text-muted">Belum ada data jam mengajar guru.</div>
                                <?php endif ?>

                                <div class="position-relative text-center my-4">
                                    <hr>
                                    <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-muted small">Tambah Jam Mengajar
                                    </span>
                                </div>

                                <form action="<?= base_url('jadwal/distribusi/simpan') ?>" method="post" class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Guru</label>
                                        <select name="guru_id" class="form-select form-select-sm" required>
                                            <option value="">Pilih Guru</option>
                                            <?php foreach ($guruList as $g): ?>
                                                <option value="<?= $g['id'] ?>"><?= $g['nama'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Mapel</label>
                                        <select name="mapel_id" class="form-select form-select-sm" required>
                                            <option value="">Pilih Mapel</option>
                                            <?php foreach ($matpel as $m): ?>
                                                <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Jam</label>
                                        <input type="number" name="jumlah_jam" class="form-control form-control-sm" placeholder="0" min="1" required>
                                    </div>

                                    <div class="col-md-12">
                                        <button class="btn btn-success w-100 btn-md" type="submit">
                                            <i class="bi bi-save me-1"></i> Simpan
                                        </button>
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
