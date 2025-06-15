<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<!-- Messages -->
<div class="chat-messages" id="chatMessages">
  <div class="message" id="keuangan" onclick="goToRoute(this)" data-description="tunggakan">
    Pembayaran
    <div class="description">Pembayaran SPP | Daftar Ulang | Infaq | dll</div>
  </div>
  <div class="message" id="tunggakan" onclick="goToRoute(this)" data-description="tunggakan">
    Tunggakan (Rp ,-)
    <div class="description">Detail Tunggakan Santri</div>
  </div>
  <div class="message" id="santri" onclick="goToRoute(this)" data-description="santri">
    Data Santri Darul Hijrah 2 Pasuruan
    <div class="description">Detail Jumlah dan Data Santri santri</div>
  </div>
  <div class="message" id="psb" onclick="goToRoute(this)" data-description="psb">
    Data Calon Santri Baru
    <div class="description">Data Santri Inden dan Pendaftar Baru</div>
  </div>
  <div class="message" id="laporan" onclick="goToRoute(this)" data-description="laporan">
    File Laporan
    <div class="description">Laporan Dalam Bentuk Chart, Pivot, xlsx</div>
  </div>
  <div class="message" id="berkas-santri" onclick="goToRoute(this)" data-description="berkas-santri">
    Berkas Santri
    <div class="description">Berita Acara Pengambilan Berkas Santri</div>
  </div>
  <div class="message" id="alumni" onclick="goToRoute(this)" data-description="alumni">
    Data Alumni dan Santri Keluar
    <div class="description">Detail Alumni dan Data Santri Keluar</div>
  </div>
</div>

<?= $this->endSection(); ?>