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
            <h4 class="card-title fw-semibold">Detail Calon Santri</h4>
            <div class="row">
              <div class="mb-1 col-lg-12">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="tunggakantl" name="tunggakantl" placeholder="Masukkan Alamat Rumah">
              </div>
              <div class="mb-1 col-lg-2">
                <label for="ttl" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="ttl" name="ttl" value="<?= $today ?>">
              </div>
              <div class="mb-1 col-lg-5">
                <label for="ayah" class="form-label">Nama Ayah</label>
                <input type="text" class="form-control" id="ayah" name="ayah" placeholder="Masukkan Nama Ayah">
              </div>
              <div class="mb-1 col-lg-5">
                <label for="ibu" class="form-label">Nama Ibu</label>
                <input type="text" class="form-control" id="ibu" name="ibu" placeholder="Masukkan Nama Ibu">
              </div>
              <div class="mb-1 col-lg-5">
                <label for="tk" class="form-label">Asal TK</label>
                <input type="text" class="form-control" id="tk" name="tk" placeholder="Kosongi Jika Tidak Ada">
              </div>
              <div class="mb-1 col-lg-5">
                <label for="uangsaku" class="form-label">Asal SD / MI</label>
                <input type="number" class="form-control" id="uangsaku" name="uangsaku" value="0">
              </div>
              <div class="mb-1 col-lg-4">
                <label for="infaq" class="form-label">Infaq</label>
                <input type="number" class="form-control" id="infaq" name="infaq" value="0">
              </div>
              <div class="mb-4 col-lg-4">
                <label for="formulir" class="form-label">Formulir</label>
                <input type="number" class="form-control" id="formulir" name="formulir" value="0">
              </div>
            </div>
            <button type="submit" class="btn btn-dark m-1">Buat Kwitansi</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>