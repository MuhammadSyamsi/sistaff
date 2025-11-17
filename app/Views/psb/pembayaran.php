<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<?php
use App\Models\DetailModel;
$TransferModel = new DetailModel();
$id = $TransferModel->orderBy('id', 'desc')->limit(1)->findColumn('id');
$today = date('Y-m-d');
$i = ($id == null) ? 1 : max($id) + 1;
?>

<div class="container mx-auto p-4" x-data="psbBayar()">
  <div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-2xl font-semibold mb-6">Pembayaran Daftar Ulang PSB</h3>

    <form action="<?= base_url('bayar') ?>" method="post" @submit.prevent="submitForm">
      <?= csrf_field(); ?>

      <!-- Hidden Inputs -->
      <input type="hidden" name="saldomasuk" x-model="saldomasukRaw">
      <input type="hidden" name="tdu" x-model="tduRaw">
      <input type="hidden" name="infaq" x-model="infaqRaw">
      <input type="hidden" name="id" value="<?= $i; ?>" />
      <input type="hidden" name="nama" x-model="nama">
      <input type="hidden" name="kelas" x-model="kelas">

      <!-- Pilih Santri -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <div>
          <label class="block mb-1 font-medium">Nama</label>
          <select x-model="nisn" @change="changeSantri" class="w-full border rounded p-2">
            <option value="">-- Pilih Santri --</option>
            <?php foreach($cari as $c): ?>
              <option value="<?= $c['nisn']; ?>"><?= $c['nama']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block mb-1 font-medium">Daftar Ulang</label>
            <input type="text" class="w-full border rounded p-2 bg-gray-100" :value="duFormatted" disabled>
          </div>
          <div>
            <label class="block mb-1 font-medium">Sisa Daftar Ulang</label>
            <input type="text" class="w-full border rounded p-2 bg-gray-100" :value="tunggakanduFormatted" disabled>
          </div>
        </div>
      </div>

      <!-- Nominal, Tanggal, Rekening, Keterangan -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
        <div>
          <label class="block mb-1 font-medium">Nominal</label>
          <input type="text" class="w-full border rounded p-2" x-model="saldomasukFormatted" @input="formatSaldomasuk">
        </div>
        <div>
          <label class="block mb-1 font-medium">Tanggal</label>
          <input type="date" class="w-full border rounded p-2" id="tanggal" name="tanggal" value="<?= $today; ?>">
        </div>
        <div>
          <label class="block mb-1 font-medium">Rekening</label>
          <select id="rekening" name="rekening" class="w-full border rounded p-2">
            <option value="Muamalat Salam">Muamalat Salam</option>
            <option value="Jatim Syariah">Jatim Syariah</option>
            <option value="BSI">BSI</option>
            <option value="Uang Saku">Uang Saku</option>
            <option value="Muamalat Yatim">Muamalat Yatim</option>
            <option value="Tunai">Tunai</option>
            <option value="lain-lain">Lain-lain</option>
          </select>
        </div>
        <div>
          <label class="block mb-1 font-medium">Keterangan</label>
          <input type="text" class="w-full border rounded p-2" x-model="keterangan">
        </div>
      </div>

      <!-- Tombol Pelunasan / Angsuran -->
      <div class="flex gap-4 mb-6">
        <button type="button" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded flex items-center justify-center gap-2" @click="pelunasanDU">
          <span class="material-symbols-outlined">paid</span> Pelunasan DU
        </button>
        <button type="button" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded flex items-center justify-center gap-2" @click="angsuranDU">
          <span class="material-symbols-outlined">schedule</span> Angsuran DU
        </button>
      </div>

      <!-- Riwayat Transaksi -->
      <div class="bg-gray-50 p-4 rounded mb-6">
        <h5 class="font-semibold mb-2">Riwayat Transaksi Terakhir</h5>
        <template x-if="riwayat.length === 0">
          <p class="text-gray-500">Memuat data...</p>
        </template>
        <template x-for="item in riwayat" :key="item.id">
          <div class="flex justify-between border-b py-1">
            <div>
              <small class="text-gray-500" x-text="item.tanggal || '-'"></small><br>
              <strong x-text="item.keterangan || '-'"></strong>
            </div>
            <div class="text-right">
              <span class="inline-block bg-blue-500 text-white px-2 rounded text-sm" x-text="item.rekening || '-'"></span><br>
              <strong x-text="formatNumber(item.nominal || 0)"></strong>
            </div>
          </div>
        </template>
      </div>

      <!-- Detail Pemasukan -->
      <h5 class="font-semibold mb-2">Detail Pemasukan</h5>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <div>
          <label class="block mb-1 font-medium">Bayar Daftar Ulang</label>
          <input type="text" class="w-full border rounded p-2" x-model="tduFormatted" @input="formatTdu">
        </div>
        <div>
          <label class="block mb-1 font-medium">Infaq</label>
          <input type="text" class="w-full border rounded p-2" x-model="infaqFormatted" @input="formatInfaq">
        </div>
      </div>

      <button type="submit" :disabled="submitting" class="bg-gray-800 hover:bg-gray-900 text-white py-2 px-4 rounded flex items-center gap-2">
        <span class="material-symbols-outlined">receipt</span> Buat Kwitansi
      </button>
    </form>
  </div>
</div>

<script>
function psbBayar() {
  return {
    nisn: '',
    nama: '',
    kelas: '',
    du: 0,
    tunggakandu: 0,
    saldomasukRaw: 0,
    tduRaw: 0,
    infaqRaw: 0,
    saldomasukFormatted: '0',
    tduFormatted: '0',
    infaqFormatted: '0',
    keterangan: '',
    riwayat: [],
    submitting: false,

    changeSantri() {
      if(!this.nisn) {
        this.nama = this.kelas = '';
        this.du = this.tunggakandu = 0;
        this.riwayat = [];
        this.duFormatted = '0';
        this.tunggakanduFormatted = '0';
        return;
      }

      fetch(`api/psb/${this.nisn}`)
        .then(res => res.json())
        .then(data => {
          this.nama = data.nama;
          this.kelas = data.kelas;
          this.du = Number(data.daftarulang);
          this.tunggakandu = Number(data.tunggakandu);
          this.duFormatted = this.formatNumber(this.du);
          this.tunggakanduFormatted = this.formatNumber(this.tunggakandu);
          this.tduRaw = 0;
          this.saldomasukRaw = 0;
          this.tduFormatted = '0';
          this.saldomasukFormatted = '0';
          this.loadRiwayat();
        });
    },

    formatNumber(raw) {
      return String(raw).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    },
    parseNumber(str) {
      return Number(str.replace(/\D/g,''));
    },
    formatSaldomasuk() {
      this.saldomasukRaw = this.parseNumber(this.saldomasukFormatted);
      this.saldomasukFormatted = this.formatNumber(this.saldomasukRaw);
    },
    formatTdu() {
      this.tduRaw = this.parseNumber(this.tduFormatted);
      this.tduFormatted = this.formatNumber(this.tduRaw);
    },
    formatInfaq() {
      this.infaqRaw = this.parseNumber(this.infaqFormatted);
      this.infaqFormatted = this.formatNumber(this.infaqRaw);
    },

    pelunasanDU() {
      this.tduRaw = this.tunggakandu;
      this.tduFormatted = this.formatNumber(this.tduRaw);
      this.saldomasukRaw = this.tduRaw + this.infaqRaw;
      this.saldomasukFormatted = this.formatNumber(this.saldomasukRaw);
      this.keterangan = 'Pelunasan Daftar Ulang';
    },

    angsuranDU() {
      this.tduRaw = Math.floor(this.tunggakandu / 2);
      this.tduFormatted = this.formatNumber(this.tduRaw);
      this.saldomasukRaw = this.tduRaw + this.infaqRaw;
      this.saldomasukFormatted = this.formatNumber(this.saldomasukRaw);
      this.keterangan = 'Angsuran Daftar Ulang';
    },

    loadRiwayat() {
      fetch(`/api/kedua/${this.nisn}`)
        .then(res => res.json())
        .then(data => {
          this.riwayat = Array.isArray(data) ? data : [];
        })
        .catch(()=> this.riwayat = []);
    },

    submitForm() {
      this.submitting = true;
      this.$el.submit();
    }
  }
}
</script>

<?= $this->endSection(); ?>