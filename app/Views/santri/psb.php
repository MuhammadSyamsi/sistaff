<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch mb-2">
      <p class="btn btn-light me-2">Total <span class="badge bg-warning rounded-3 fw-semibold"><?= $total ?></span></p>
      <p class="btn btn-light me-2">MTs <span class="badge bg-secondary rounded-3 fw-semibold"><?= $mts ?></span></p>
      <p class="btn btn-light me-2">MA <span class="badge bg-danger rounded-3 fw-semibold"><?= $ma ?></span></p>
    </div>
    <div class="row">
      <div class="col-lg-8 d-flex align-items-stretch">
        <div class="card w-100">
          <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Database Santri</h5>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Nama</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Kelas</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">pindah</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($psb as $rek) :  ?>
                    <form action="mig<?= $rek['nisn'] ?>" method="post">
                      <tr>
                        <td class="border-bottom-0">
                          <h6 class="fw-semibold mb-1"><?= $rek['nama']; ?></h6>
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal"><?= $rek['kelas']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <input type="hidden" class="form-control" id="id" name="id" value="<?= $rek['id']; ?>" />
                          <input type="hidden" class="form-control" id="nisn" name="nisn" value="<?= $rek['nisn']; ?>" />
                          <input type="hidden" class="form-control" id="nama" name="nama" value="<?= $rek['nama']; ?>" />
                          <input type="hidden" class="form-control" id="program" name="program" value="<?= $rek['program']; ?>" />
                          <input type="hidden" class="form-control" id="jenjang" name="jenjang" value="<?= $rek['jenjang']; ?>" />
                          <input type="hidden" class="form-control" id="kelas" name="kelas" value="<?= $rek['kelas']; ?>" />
                          <input type="hidden" class="form-control" id="tunggakandu" name="tunggakandu" value="<?= $rek['tdu']; ?>" />
                          <input type="hidden" class="form-control" id="tunggakantl" name="tunggakantl" value=0 />
                          <input type="hidden" class="form-control" id="tunggakanspp" name="tunggakanspp" value=0 />
                          <input type="hidden" class="form-control" id="du" name="du" value="<?= $rek['daftarulang']; ?>" />
                          <input type="hidden" class="form-control" id="spp" name="spp" value="<?= $rek['spp']; ?>" />
                          <input type="hidden" class="form-control" id="tempatlahir" name="tempatlahir" value="<?= $rek['tempatlahir']; ?>" />
                          <input type="hidden" class="form-control" id="tanggallahir" name="tanggallahir" value="<?= $rek['tanggal']; ?>" />
                          <input type="hidden" class="form-control" id="asalsekolah" name="asalsekolah" value="<?= $rek['asalsekolah']; ?>" />
                          <input type="hidden" class="form-control" id="tahunmasuk" name="tahunmasuk" value="<?= $rek['tahunmasuk']; ?>" />
                          <input type="hidden" class="form-control" id="ayah" name="ayah" value="<?= $rek['ayah']; ?>" />
                          <input type="hidden" class="form-control" id="alamatayah" name="alamatayah" value="<?= $rek['alamatayah']; ?>" />
                          <input type="hidden" class="form-control" id="pekerjaanayah" name="pekerjaanayah" value="<?= $rek['pekerjaanayah']; ?>" />
                          <input type="hidden" class="form-control" id="ibu" name="ibu" value="<?= $rek['ibu']; ?>" />
                          <input type="hidden" class="form-control" id="pekerjaanibu" name="pekerjaanibu" value="<?= $rek['pekerjaanibu']; ?>" />
                          <input type="hidden" class="form-control" id="kontak1" name="kontak1" value="<?= $rek['kontak1']; ?>" />
                          <input type="hidden" class="form-control" id="kontak2" name="kontak2" value="<?= $rek['kontak2']; ?>" />
                          <input type="hidden" class="form-control" id="berkas" name="berkas" value="<?= $rek['berkas']; ?>" />
                          <button type="submit" class="btn btn-info m-1">
                            <span>
                              <i class="ti ti-logout"></i>
                            </span>
                          </button>
                        </td>
                      </tr>
                    </form>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 d-flex align-items-stretch">
        <div class="card w-100">
          <div class="card-body p-4">
            <h5 class="card-title fw-semibold mb-4">Database Kelas</h5>
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
                  <?php foreach ($santri as $rekma) : ?>
                    <tr>
                      <td class="border-bottom-0">
                        <h6 class="fw-semibold mb-1"><?= $rekma['kelas']; ?></h6>
                      </td>
                      <td class="border-bottom-0">
                        <p class="mb-0 fw-normal"><?= $rekma['total']; ?></p>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= $this->endSection(); ?>