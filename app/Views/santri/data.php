<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch mb-2">
      <p class="btn btn-light me-2">Total <span class="badge bg-warning rounded-3 fw-semibold"><?= $total;
                                                                                                echo (' santri'); ?></span></p>
    </div>
    <div class="row">
      <div class="col-6">
        <div class="card w-100">
          <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">MTs <?= $mts ?> Santri</h5>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Kelas</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Jumlah</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($kelasmts as $rek) : ?>
                    <tr>
                      <td class="border-bottom-0">
                        <h6 class="fw-semibold mb-1"><?= $rek['kelas']; ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <p class="mb-0 fw-normal"><?= $rek['hitung']; ?> santri</p>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 float-left">
        <div class="card w-100">
          <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">MA <?= $ma ?> Santri</h5>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Kelas</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Jumlah</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($kelasma as $rekma) : ?>
                    <tr>
                      <td class="border-bottom-0">
                        <h6 class="fw-semibold mb-1"><?= $rekma['kelas']; ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <p class="mb-0 fw-normal"><?= $rekma['hitung']; ?> santri</p>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12 d-flex align-items-stretch">
        <table class="table">
          <tr>
            <th>NISN</th>
            <th>Nama</th>
            <th>tempat, tanggal lahir</th>
            <th>setting</th>
          </tr>
          <?php foreach ($santri as $santri): ?>
            <tr>
              <td><?= $santri['nisn']; ?></td>
              <td><?= $santri['nama']; ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
  <?= $this->endSection(); ?>