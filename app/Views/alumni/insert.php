<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<?php

use App\Models\DetailModel;
use LDAP\Result;

$TransferModel = new DetailModel();
$id = $TransferModel->orderBy('id', 'desc')->limit(1)->findColumn('id');

$today = date('Y-m-d');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Masukkan Transaksi Baru</h3>
            <form action="savealumni" method="post">
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
                  <label for="nisn" class="form-label">Nama</label>
                  <select class="form-select" id="nisn" name="nisn">
                    <option selected disabled value=""></option>
                    <?php foreach ($cari as $c) : ?>
                      <option value="<?= $c['nisn'] ?>"><?php echo $c['nama'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="spp" class="form-label">SPP</label>
                  <input type="number" disabled class="form-control" id="spp" name="spp" value="0">
                  <input type="text" hidden class="form-control" id="nama" name="nama" />
                  <input type="text" hidden class="form-control" id="kelas" name="kelas" />
                  <input type="text" hidden class="form-control" id="program" name="program" />
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="tunggakantl" class="form-label">TL</label>
                  <input type="number" disabled class="form-control" id="tunggakantl" name="tunggakantl" value="0">
                </div>
                <div class="mb-1 col-lg-2">
                  <label for="tunggakandu" class="form-label">DU</label>
                  <input type="number" disabled class="form-control" id="tunggakandu" name="tunggakandu" value="0">
                </div>
              </div>
              <div class="row">
                <div class="mb-1 col-lg-3">
                  <label for="saldomasuk" class="form-label">Nominal</label>
                  <input type="number" class="form-control" id="saldomasuk" name="saldomasuk">
                </div>
                <div class="mb-1 col-lg-3">
                  <label for="tanggal" class="form-label">tanggal</label>
                  <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $today; ?>">
                </div>
                <div class="mb-1 col-lg-3">
                  <label for="bukti" class="form-label">bukti</label>
                  <select type="text" class="form-control" id="bukti" name="bukti">
                    <option value="ada">Ada</option>
                    <option value="tidak ada">Tidak Ada</option>
                  </select>
                </div>
                <div class="mb-1 col-lg-3">
                  <label for="rekening" class="form-label">Rekening</label>
                  <select type="text" class="form-control" id="bukti" name="rekening">
                    <option value="Muamalat Salam">Muamalat Salam</option>
                    <option value="Jatim Syariah">Jatim Syariah</option>
                    <option value="BSI">BSI</option>
                    <option value="Uang Saku">Uang Saku</option>
                    <option value="Muamalat Yatim">Muamalat Yatim</option>
                    <option value="Tunai">Tunai</option>
                    <option value="lain-lain">Lain-lain</option>
                  </select>
                </div>
                <div class="mb-1">
                  <label for="ketarangan" class="form-label">Keterangan</label>
                  <input type="text" class="form-control" id="keterangan" name="keterangan">
                </div>
              </div>
              <div class="mb-1 col-lg-12">
                <label for="rekening" class="form-label">Transaksi Terakhir</label>
              </div>
              <div class="row mb-4">
                <div class="col-lg-2" id="lasttanggal1" name="lasttanggal1" disabled></div>
                <div class="col-lg-2" id="lastrek1" name="lastrek1" disabled></div>
                <div class="col-lg-2" id="lastnom1" name="lastnom1" disabled></div>
                <div class="col-lg-6" id="lastket1" name="lastket1" disabled></div>
                <div class="col-lg-2" id="lasttanggal2" name="lasttanggal2" disabled></div>
                <div class="col-lg-2" id="lastrek2" name="lastrek2" disabled></div>
                <div class="col-lg-2" id="lastnom2" name="lastnom2" disabled></div>
                <div class="col-lg-6" id="lastket2" name="lastket2" disabled></div>
                <div class="col-lg-2" id="lasttanggal3" name="lasttanggal3" disabled></div>
                <div class="col-lg-2" id="lastrek3" name="lastrek3" disabled></div>
                <div class="col-lg-2" id="lastnom3" name="lastnom3" disabled></div>
                <div class="col-lg-6" id="lastket3" name="lastket3" disabled></div>
              </div>
              <h4 class="card-title fw-semibold">Detail Pemasukan</h4>
              <div class="row">
                <div class="mb-1 col-lg-4">
                  <label for="tunggakandu" class="form-label">Bayar Daftar Ulang</label>
                  <input type="number" class="form-control" id="tunggakandu" name="tunggakandu" value="0">
                </div>
                <div class="mb-1 col-lg-4">
                  <label for="tunggakantl" class="form-label">Bayar Tunggakan</label>
                  <input type="number" class="form-control" id="tunggakantl" name="tunggakantl" value="0">
                </div>
                <div class="mb-1 col-lg-4">
                  <label for="tunggakanspp" class="form-label">Bayar SPP</label>
                  <input type="number" class="form-control" id="tunggakanspp" name="tunggakanspp" value="0">
                </div>
                <div class="mb-1 col-lg-4">
                  <label for="uangsaku" class="form-label">Uang Saku</label>
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
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="assets/dist/js/select2.min.js"></script>
<script>
  $('#nisn').select2({
    placeholder: "Nama Santri"
  });

  $('#nisn').on('change', (event) => {
    getIdentitas(event.target.value).then(AlumniModel => {
      $('#nama').val(AlumniModel.nama);
      $('#kelas').val(AlumniModel.kelas);
      $('#spp').val(AlumniModel.tunggakanspp);
      $('#tunggakantl').val(AlumniModel.tunggakantl);
      $('#tunggakandu').val(AlumniModel.tunggakandu);
      $('#program').val(AlumniModel.program);
    });
    getTransaksi(event.target.value).then(TransferModel => {
      $('#lasttanggal1').text(TransferModel.tanggal1);
      $('#lastket1').text(TransferModel.keterangan1);
      $('#lastrek1').text(TransferModel.rekening1);
      $('#lastnom1').text(TransferModel.nominal1);
      $('#lasttanggal2').text(TransferModel.tanggal2);
      $('#lastket2').text(TransferModel.keterangan2);
      $('#lastrek2').text(TransferModel.rekening2);
      $('#lastnom2').text(TransferModel.nominal2);
      $('#lasttanggal3').text(TransferModel.tanggal3);
      $('#lastket3').text(TransferModel.keterangan3);
      $('#lastrek3').text(TransferModel.rekening3);
      $('#lastnom3').text(TransferModel.nominal3);
    });
  });

  async function getIdentitas(id) {
    let response = await fetch('api/alumni/' + id)
    let data = await response.json();

    return data;
  }
  async function getTransaksi(id) {
    let response = await fetch('api/kedua/' + id)
    let data = await response.json();
    return data;
  }
</script>

<?= $this->endSection(); ?>