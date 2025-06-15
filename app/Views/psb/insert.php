<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<?php
$today = date('Y-m-d');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Formulir Santri Baru</h3>
            <form action="formulir_psb" method="post">
              <?= csrf_field(); ?>
              <?php
              if ($id == null) {
                $i = 1;
              }
              if ($id != null) {
                foreach ($id as $a) : $i = $a + 1;
                endforeach;
              }
              ?>
              <div class="row">
                <div class="mb-1 col-lg-6">
                  <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $i; ?>" />
                  <input type="hidden" class="form-control" id="nisn" name="nisn" value="0" />
                  <input type="hidden" class="form-control" id="program" name="program" value="MANDIRI" />
                  <input type="hidden" class="form-control" id="status" name="status" value="formulir" />
                  <label for="nama" class="form-label">Nama</label>
                  <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pendaftar Baru">
                </div>
                <div class="mb-1 col-lg-3">
                  <label for="jenjang" class="form-label">Jenjang</label>
                  <select class="form-select" id="jenjang" name="jenjang">
                    <option value="MTs|7">MTs</option>
                    <option value="MA|10">MA</option>
                  </select>
                </div>
                <div class="mb-1 col-lg-3">
                  <label for="tanggal" class="form-label">Tanggal Daftar</label>
                  <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $today ?>" />
                </div>
                <div class="mb-4 col-lg-3">
                  <label for="formulir" class="form-label">Bayar Formulir</label>
                  <input type="number" class="form-control" id="formulir" name="formulir" value="0">
                </div>
                <div class="mb-1 col-lg-3">
                  <label for="rekening" class="form-label">Rekening</label>
                  <select class="form-select" id="rekening" name="rekening">
                    <option value="Muamalat Salam">Muamalat Salam</option>
                    <option value="Jatim Syariah">Jatim Syariah</option>
                    <option value="BSI">BSI</option>
                    <option value="Tunai">Tunai</option>
                    <option value="lain-lain">Lain-lain</option>
                  </select>
                </div>
                <div class="row">
                  <div class="mb-1 col-lg-3">
                    <button type="submit" class="form-control btn btn-dark m-1">Daftar</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>