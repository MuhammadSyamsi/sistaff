<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<?php
$today = date('Y-m-d');
?>
<div class="container-fluid">
  <!-- row 1 -->
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4 navbar-light bg-light">
          <a href="tambahpsb" type="button" class="btn btn-light me-2">Tambah</a>
          <a href="pembayaran" type="button" class="btn btn-light me-2">Pembayaran</a>
        </div>
      </div>
    </div>
  </div>
  <!-- row 2 -->
  <div class="row">

    <!-- pilih data -->
    <div class="col-lg-6 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Data Calon Santri</h3>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <thead class="text-dark fs-4">
                  <tr>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">No</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Nama</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Kelas</h6>
                    </th>
                    <th class="border-bottom-0">
                      <h6 class="fw-semibold mb-0">Checklist</h6>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 0;
                  foreach ($data as $form) : $i++; ?>
                    <form action="formulir<?= $form['id']; ?>" method="post">
                      <tr>
                        <td class="border-bottom-0">
                          <p class="fw-normal mb-0"><?php echo $i; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <p class="fw-normal mb-0"><?php echo $form['nama']; ?></p>
                        </td>
                        <td class="border-bottom-0">
                          <input type="text" value="<?php echo $form['kelas']; ?>" class="form-control" />
                        </td>
                        <td class="border-bottom-0">
                          <label class="list-group-item">
                            <input class="form-check-input me-1" type="checkbox" id="cek[]" name="cek[]" value="<?= $form['id'] ?>"> Checklist
                          </label>
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
    </div>

  </div>
</div>

<?= $this->endSection(); ?>