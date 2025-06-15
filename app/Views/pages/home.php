<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<!-- Messages -->
<div class="chat-messages" id="chatMessages">
  <div class="chat-bubble received" id="pembayaran" onclick="goToRoute(this)" data-description="pembayaran">
    Pembayaran
    <div class="description">
      Pembayaran SPP | Daftar Ulang | Infaq | dll<br />
      <i>tag </i> <span class="badge badge-dark">bayar</span>
    </div>
  </div>
  <div class="chat-bubble received" id="tunggakan" onclick="goToRoute(this)" data-description="tunggakan">
    Tunggakan (Rp <?= format_rupiah($sumtung) ?>,-)
    <div class="description">
      Detail Tunggakan dan Tagihan Santri<br />
      <i>tag </i> <span class="badge badge-dark">tunggakan</span>
    </div>
  </div>
  <div class="chat-bubble received" id="santri" onclick="goToRoute(this)" data-description="santri">
    Data <?php foreach ($santri as $santri): echo ($santri['nisn']);
          endforeach ?> Santri Darul Hijrah 2 Pasuruan
    <div class="description">
      Detail Jumlah dan Data Santri<br />
      <i>tag </i> <span class="badge badge-dark">santri</span>
    </div>
  </div>
  <div class="chat-bubble received" id="santri-psb" onclick="goToRoute(this)" data-description="santri-psb">
    Data <?php foreach ($psb as $psb): echo ($psb['nisn']);
          endforeach ?> Calon Santri Baru
    <div class="description">
      Data Santri Inden dan Pendaftar Baru<br />
      <i>tag </i> <span class="badge badge-dark">psb</span>
    </div>
  </div>
  <div class="chat-bubble received" id="laporan" onclick="goToRoute(this)" data-description="laporan">
    File Laporan
    <div class="description">
      Laporan Dalam Bentuk Chart, Pivot, dan xlsx<br />
      <i>tag </i> <span class="badge badge-dark">laporan</span>
    </div>
  </div>
  <div class="chat-bubble received" id="berkas-santri" onclick="goToRoute(this)" data-description="berkas-santri">
    Berkas Santri
    <div class="description">
      Berita Acara Pengambilan Berkas Santri<br />
      <i>tag </i> <span class="badge badge-dark">berkas</span>
    </div>
  </div>
  <div class="chat-bubble received" id="alumni" onclick="goToRoute(this)" data-description="alumni">
    Data <?php foreach ($alumni as $alumni): echo ($alumni['nisn']);
          endforeach ?> Alumni dan Santri Keluar
    <div class="description">
      Detail Alumni dan Data Santri Keluar<br />
      <i>tag </i> <span class="badge badge-dark">alumni</span>
    </div>
  </div>
  <div class="chat-bubble received" id="mutasi-pembayaran" onclick="goToRoute(this)" data-description="mutasi-pembayaran">
    Mutasi
    <div class="description">
      Cek Riwayat Pembayaran Santri<br />
      <i>tag </i> <span class="badge badge-dark">mutasi</span>
    </div>
  </div>
  <div class="pin received">
    <div class="description">
      Ketik huruf <span class="badge badge-dark">R</span> untuk memuat ulang halaman ini
    </div>
  </div>
</div>
<?= $this->endSection(); ?>