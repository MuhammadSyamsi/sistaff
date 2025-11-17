<style>
  .selectable-card {
    transition: background-color 0.2s ease;
    border-radius: 0.25rem;
  }

  .selectable-card.checked {
    background-color: #d0ebff !important; /* biru muda seperti Bootstrap alert-success */
  }
</style>

<?php if (count($santri) > 0): ?>
  <div class="mb-3">
    <input type="checkbox" id="checkAll"> <label for="checkAll">Pilih Semua</label>

    <!-- Tombol Aksi Masal -->
    <button type="button" class="btn btn-sm btn-primary ml-3 btn-edit-masal" data-bs-toggle="modal" data-bs-target="#modalEditMasal" disabled>
      Edit massal
    </button>
  </div>
<?php endif; ?>

<div class="row">
  <?php foreach ($santri as $s): ?>
    <div class="col-12">
      <div class="card-body p-1 santri-card"
           data-jenjang="<?= strtolower($s['jenjang']) ?>"
           data-kelas="<?= strtolower($s['kelas']) ?>"
           data-status="<?= strtolower($s['status']) ?>"
           data-nama="<?= strtolower($s['nama']) ?>">

<div class="card position-relative mb-3">
  <!-- Checkbox absolute -->
  <div class="form-check position-absolute" style="top: 50%; left: 10px; transform: translateY(-50%);">
    <input class="form-check-input santri-check" type="checkbox"
           id="check<?= $s['id']; ?>"
           value="<?= $s['id']; ?>">
  </div>

  <!-- Bungkus dengan label agar bisa klik seluruh area -->
  <label for="check<?= $s['id']; ?>" class="card-body d-flex flex-column justify-content-between selectable-card ps-5" style="cursor: pointer;">
    
    <!-- Info Santri -->
    <div>
      <h6 class="fw-bold text-dark mb-2"><?= $s['nama']; ?></h6>

      <p class="text-muted small mb-0">
        <span class="mx-1"><strong>NISN:</strong> <?= $s['nisn']; ?></span>
        <span class="mx-1"><strong>Program:</strong> <?= $s['program']; ?></span>
        <span class="mx-1"><strong>Jenjang:</strong> <?= $s['jenjang']; ?></span>
        <span class="mx-1"><strong>Kelas:</strong> <?= $s['kelas']; ?></span>
        <span class="mx-1"><strong>Status:</strong> <?= $s['status']; ?></span>
      </p>
    </div>

  </label>
          <!-- Tombol Aksi -->
          <div class="d-flex flex-wrap mt-1">
<a href="#" class="btn btn-sm btn-outline-primary flex-fill btn-edit"
   data-id="<?= $s['id'] ?>"
   data-toggle="modal"
   data-target="#modalEditSantri">
  <i class="bi bi-pencil"></i> Edit
</a>
<a href="#" class="btn btn-sm btn-outline-danger flex-fill btn-keluar"
   data-id="<?= $s['id'] ?>">
  <i class="bi bi-box-arrow-right"></i> Mengundurkan Diri
</a>
<a href="#" class="btn btn-sm btn-outline-success flex-fill btn-arsip"
   data-id="<?= $s['id'] ?>">
  <i class="bi bi-archive"></i> Arsip
</a>
          </div>
</div>

</label>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php if (count($santri) === 0): ?>
  <div class="alert alert-warning">Tidak ada data santri ditemukan.</div>
<?php endif; ?>

<!--modal edit santri massal-->
<div class="modal fade" id="modalEditMasal" tabindex="-1" aria-labelledby="modalEditMasalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
    <div class="modal-content shadow">
      <form id="formEditMasal" class="needs-validation" novalidate>
        <div class="modal-header bg-light border-bottom">
          <h5 class="modal-title fw-semibold" id="modalEditMasalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Masal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info small mb-4">
            Perubahan yang Anda lakukan akan diterapkan ke <strong class="jumlah-cek">0</strong> santri yang dipilih.
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label for="edit_program" class="form-label">Program</label>
              <select id="edit_program" name="program" class="form-select" required>
                <option value="">- Pilih -</option>
                <option value="MANDIRI">MANDIRI</option>
                <option value="BEASISWA">BEASISWA</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="edit_status" class="form-label">Status</label>
              <select id="edit_status" name="status" class="form-select" required>
                <option value="">- Pilih -</option>
                <option value="formulir">formulir</option>
                <option value="observasi">observasi</option>
                <option value="diterima">diterima</option>
                <option value="mengundurkan diri">mengundurkan diri</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light border-top d-flex justify-content-between">
          <button type="button" id="btnMigrasiSantri" class="btn btn-primary">
            <i class="bi bi-arrow-right-circle me-1"></i>Konfirmasi Telah Diterima
          </button>
          <div>
            <button type="submit" class="btn btn-success">
              <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-lg me-1"></i>Tutup
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Santri -->
<div class="modal fade" id="modalEditSantri" tabindex="-1" aria-labelledby="modalEditSantriLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content shadow">
      <form id="formEditSantri">
        <input type="hidden" id="editId" name="id">

        <div class="modal-header bg-light border-bottom">
          <h5 class="modal-title fw-semibold" id="modalEditSantriLabel"><i class="bi bi-person-lines-fill me-2"></i>Edit Data Santri</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          <div class="accordion" id="accordionEditSantri">
            <!-- Biodata -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingBiodata">
                <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBiodata" aria-expanded="true">
                  Biodata
                </button>
              </h2>
              <div id="collapseBiodata" class="accordion-collapse collapse show">
                <div class="accordion-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="editNisn" class="form-label">NISN</label>
                      <input type="text" class="form-control" id="editNisn" name="nisn" required>
                    </div>
                    <div class="col-md-6">
                      <label for="editNama" class="form-label">Nama Lengkap</label>
                      <input type="text" class="form-control" id="editNama" name="nama" required>
                    </div>
                    <div class="col-md-6">
                      <label for="editTempatlahir" class="form-label">Tempat Lahir</label>
                      <input type="text" class="form-control" id="editTempatlahir" name="tempatlahir">
                    </div>
                    <div class="col-md-6">
                      <label for="editTanggallahir" class="form-label">Tanggal Lahir</label>
                      <input type="date" class="form-control" id="editTanggallahir" name="tanggallahir">
                    </div>
                    <div class="col-md-6">
                      <label for="editAsalsekolah" class="form-label">Asal Sekolah</label>
                      <input type="text" class="form-control" id="editAsalsekolah" name="asalsekolah">
                    </div>
                    <div class="col-md-6">
                      <label for="editTahunMasuk" class="form-label">Tahun Masuk</label>
                      <input type="number" class="form-control" id="editTahunMasuk" name="tahunmasuk">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Akademik -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingAkademik">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAkademik">
                  Akademik & Program
                </button>
              </h2>
              <div id="collapseAkademik" class="accordion-collapse collapse">
                <div class="accordion-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="editJenjang" class="form-label">Jenjang</label>
                      <select class="form-select" id="editJenjang" name="jenjang">
                        <option value="">- Pilih -</option>
                        <option value="MTs">MTs</option>
                        <option value="MA">MA</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="editKelas" class="form-label">Kelas</label>
                      <select class="form-select" id="editKelas" name="kelas">
                        <option value="">- Pilih -</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="editProgram" class="form-label">Program</label>
                      <select class="form-select" id="editProgram" name="program">
                        <option value="">- Pilih -</option>
                        <option value="MANDIRI">MANDIRI</option>
                        <option value="BEASISWA">BEASISWA</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="editStatus" class="form-label">Status</label>
                      <select class="form-select" id="editStatus" name="status">
                        <option value="">- Pilih -</option>
                        <option value="formulir">formulir</option>
                        <option value="observasi">observasi</option>
                        <option value="diterima">diterima</option>
                        <option value="mengundurkan diri">mengundurkan diri</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Komitmen Keuangan -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingKeuangan">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKeuangan">
                  Komitmen Keuangan
                </button>
              </h2>
              <div id="collapseKeuangan" class="accordion-collapse collapse">
                <div class="accordion-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="editDaftarulangView" class="form-label">Daftar Ulang</label>
                      <input type="text" class="form-control" id="editDaftarulangView">
                      <input type="hidden" id="editDaftarulang" name="daftarulang">
                      <input type="hidden" id="tdu" name="tdu">
                    </div>
                    <div class="col-md-6">
                      <label for="editSPPView" class="form-label">SPP</label>
                      <input type="text" class="form-control" id="editSPPView">
                      <input type="hidden" id="editSPP" name="spp">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Orang Tua -->
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingOrangtua">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrangtua">
                  Orang Tua / Wali
                </button>
              </h2>
              <div id="collapseOrangtua" class="accordion-collapse collapse">
                <div class="accordion-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="editAyah" class="form-label">Nama Ayah</label>
                      <input type="text" class="form-control" id="editAyah" name="ayah">
                    </div>
                    <div class="col-md-6">
                      <label for="editPekerjaanayah" class="form-label">Pekerjaan Ayah</label>
                      <input type="text" class="form-control" id="editPekerjaanayah" name="pekerjaanayah">
                    </div>
                    <div class="col-md-6">
                      <label for="editAlamatayah" class="form-label">Alamat Ayah</label>
                      <input type="text" class="form-control" id="editAlamatayah" name="alamatayah">
                    </div>
                    <div class="col-md-6">
                      <label for="editKontak1" class="form-label">Kontak Ayah</label>
                      <input type="text" class="form-control" id="editKontak1" name="kontak1">
                    </div>
                    <div class="col-md-6">
                      <label for="editIbu" class="form-label">Nama Ibu</label>
                      <input type="text" class="form-control" id="editIbu" name="ibu">
                    </div>
                    <div class="col-md-6">
                      <label for="editPekerjaanibu" class="form-label">Pekerjaan Ibu</label>
                      <input type="text" class="form-control" id="editPekerjaanibu" name="pekerjaanibu">
                    </div>
                    <div class="col-md-6">
                      <label for="editAlamatibu" class="form-label">Alamat Ibu</label>
                      <input type="text" class="form-control" id="editAlamatibu" name="alamatibu">
                    </div>
                    <div class="col-md-6">
                      <label for="editKontak2" class="form-label">Kontak Ibu</label>
                      <input type="text" class="form-control" id="editKontak2" name="kontak2">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- end accordion -->
        </div>

        <div class="modal-footer bg-light border-top">
          <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!--js-->
<script>
  $('#btnMigrasiSantri').on('click', function () {
    const ids = [];

    $('.santri-check:checked').each(function () {
      ids.push($(this).val());
    });

    if (ids.length === 0) {
      alert('Pilih minimal satu santri untuk migrasi.');
      return;
    }

    if (!confirm('Yakin ingin migrasi data yang dipilih ke tabel santri?')) {
      return;
    }

    $.ajax({
      url: '<?= base_url('psb/migrasiKeSantri') ?>',
      method: 'POST',
      data: { ids: ids },
      dataType: 'json',
      success: function (res) {
        if (res.status) {
          alert(res.message);
          $('#modalEditMasal').modal('hide');
          location.reload(); // atau refresh list santri
        } else {
          alert(res.message);
        }
      },
      error: function () {
        alert('Terjadi kesalahan saat migrasi.');
      }
    });
  });
</script>
<script>
  // Format angka ribuan
  function formatRibuan(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function unformatRibuan(nilai) {
    return nilai.replace(/\./g, '');
  }

  // Update tombol edit masal dan jumlah tercentang
  function updateEditMasalButton() {
    const selected = $('.santri-check:checked').length;
    $('.btn-edit-masal').prop('disabled', selected === 0);
    $('.jumlah-cek').text(selected);
  }

  // Checkbox individual berubah
  $(document).on('change', '.santri-check', function () {
    const label = $(`label[for="${this.id}"]`);
    label.toggleClass('checked', this.checked);
    updateEditMasalButton();
  });

  // Checkbox "Pilih Semua"
  $(document).on('change', '#checkAll', function () {
    const isChecked = this.checked;

    $('.santri-check').each(function () {
      $(this).prop('checked', isChecked);
      const label = $(`label[for="${this.id}"]`);
      label.toggleClass('checked', isChecked);
    });

    updateEditMasalButton();
  });

  // Kurangi kewajiban massal
  $('#modalEditMasal').on('click', '.btn-kurangi-spp', function () {
    const selected = $('.santri-check:checked').map(function () {
      return $(this).val();
    }).get();

    $.post('<?= base_url('Santri/kurangiSPPMasal') ?>', { ids: selected }, function (res) {
      alert(res.msg);
      if (res.status) {
        $('#modalEditMasal').modal('hide');
        location.reload();
      }
    });
  });

  // Migrasi kelas massal
  $('#modalEditMasal').on('click', '.btn-migrasi-masal', function () {
    const kelasBaru = $('#kelasTujuan').val();
    if (!kelasBaru) return alert("Kelas tujuan harus diisi.");

    const selected = $('.santri-check:checked').map(function () {
      return $(this).val();
    }).get();

    $.post('<?= base_url('Santri/migrasiMasal') ?>', { ids: selected, kelas_baru: kelasBaru }, function (res) {
      alert(res.msg);
      if (res.status) {
        $('#modalEditMasal').modal('hide');
        location.reload();
      }
    });
  });

  // Arsip massal
  $('#modalEditMasal').on('click', '.btn-arsip-masal', function () {
    if (!confirm('Yakin ingin mengarsipkan santri terpilih?')) return;

    const selected = $('.santri-check:checked').map(function () {
      return $(this).val();
    }).get();

    $.post('<?= base_url('Santri/arsipMasal') ?>', { ids: selected }, function (res) {
      alert(res.msg);
      if (res.status) {
        $('#modalEditMasal').modal('hide');
        location.reload();
      }
    });
  });

  // Ganti tahun masuk massal
  $('#modalEditMasal').on('click', '.btn-angkatan', function () {
    const tahun = $('#TahunMasuk').val();
    if (!tahun || isNaN(tahun)) return alert("Masukkan tahun masuk yang valid.");

    const selected = $('.santri-check:checked').map(function () {
      return $(this).val();
    }).get();

    $.post('<?= base_url('Santri/gantiTahunMasuk') ?>', { ids: selected, tahun }, function (res) {
      alert(res.msg);
      if (res.status) {
        $('#modalEditMasal').modal('hide');
        location.reload();
      }
    });
  });

  // Format angka saat mengisi input
  $('#editSPPView').on('input', function () {
    const nilai = $(this).val().replace(/[^\d]/g, '');
    $(this).val(formatRibuan(nilai));
    $('#editSPP').val(unformatRibuan($(this).val()));
  });

  $('#editDaftarulangView').on('input', function () {
    const nilai = $(this).val().replace(/[^\d]/g, '');
    $(this).val(formatRibuan(nilai));
    $('#editDaftarulang').val(unformatRibuan($(this).val()));
  });

  // Buka modal edit individual
  $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id');

    $.get('<?= base_url('psb/getSantriById/') ?>' + id, function (res) {
      if (res.status) {
        const s = res.data;
        $('#editId').val(s.id);
        $('#editNisn').val(s.nisn);
        $('#editNama').val(s.nama);
        $('#editJenjang').val(s.jenjang);
        $('#editKelas').val(s.kelas);
        $('#editProgram').val(s.program);
        $('#editStatus').val(s.status);
        $('#editTempatlahir').val(s.tempatlahir);
        $('#editTanggallahir').val(s.tanggallahir);
        $('#editAsalsekolah').val(s.asalsekolah);
        $('#editTahunMasuk').val(s.tahunmasuk);
        $('#editAyah').val(s.ayah);
        $('#editPekerjaanayah').val(s.pekerjaanayah);
        $('#editAlamatayah').val(s.alamatayah);
        $('#editIbu').val(s.ibu);
        $('#editPekerjaanibu').val(s.pekerjaanibu);
        $('#editAlamatibu').val(s.alamatibu);
        $('#editKontak1').val(s.kontak1);
        $('#editKontak2').val(s.kontak2);
        $('#editDaftarulang').val(s.daftarulang);
        $('#tdu').val(s.tdu);
        $('#editDaftarulangView').val(formatRibuan(s.daftarulang));
        $('#editSPP').val(s.spp);
        $('#editSPPView').val(formatRibuan(s.spp));

        $('#modalEditSantri').modal('show');
      } else {
        alert(res.msg || 'Data tidak ditemukan.');
      }
    });
  });

  // Submit edit individual
  $(document).on('submit', '#formEditSantri', function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.post('<?= base_url('psb/updateSantri') ?>', formData, function (res) {
      alert(res.msg);
      if (res.status) {
        $('#modalEditSantri').modal('hide');
        location.reload();
      }
    });
  });

  // Arsip individu
  $(document).on('click', '.btn-arsip', function (e) {
    e.preventDefault();
    const nisn = $(this).data('id');
    const ids = [nisn];

    if (!confirm('Yakin ingin mengarsipkan santri ini?')) return;

    $.post('<?= base_url('Santri/arsipMasal') ?>', { ids }, function (res) {
      alert(res.msg);
      if (res.status) {
        location.reload();
      }
    });
  });

  // Santri keluar
  $(document).on('click', '.btn-keluar', function (e) {
    e.preventDefault();
    const nisn = $(this).data('id');

    if (!confirm('Yakin ingin menandai santri ini sebagai keluar?')) return;

    $.post('<?= base_url('Santri/tandaiKeluar') ?>', { nisn }, function (res) {
      alert(res.msg);
      if (res.status) {
        location.reload();
      }
    });
  });
</script>
