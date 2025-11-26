<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<?php

use App\Models\DetailModel;

$TransferModel = new DetailModel();
$id = $TransferModel->orderBy('id', 'desc')->limit(1)->findColumn('id');
$today = date('Y-m-d');
$i = ($id == null) ? 1 : ($id[0] + 1);
?>

<!-- Chat Area Container -->
<div x-data="chatApp()" class="mt-0 flex flex-col h-[calc(100vh-120px)] w-full bg-white rounded-xl shadow p-4">
  <!-- Header -->
  <div class=" flex items-center justify-between border-b pb-3 mb-3">
    <div class="flex items-center gap-3">
      <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center">S</div>
      <div>
        <h2 class="text-lg font-semibold">Pembayaran Kewajiban</h2>
        <p class="text-xs text-gray-500">Sistaff • Online</p>
      </div>
    </div>
    <button class="text-gray-500 hover:text-gray-700">
      <i class="bi bi-three-dots-vertical text-xl"></i>
    </button>
  </div>

  <!-- Messages -->
  <div class="flex-1 overflow-y-auto space-y-4 pr-2" x-ref="chatBody" x-init="scrollBottom()" @message-added.window="scrollBottom()">

    <!-- Incoming Message -->
    <template x-for="msg in messages" :key="msg.id">
      <div>
        <!-- Incoming -->
        <div x-show="msg.type === 'in'" class="flex items-start gap-2">
          <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm">A</div>
          <div>
            <div class="bg-gray-100 px-4 py-2 rounded-2xl rounded-tl-none max-w-xs" x-text="msg.text"></div>
          </div>
        </div>

        <!-- Outgoing -->
        <div x-show="msg.type === 'out'" class="flex items-end justify-end gap-2">
          <div>
            <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-tr-none max-w-xs" x-text="msg.text"></div>
          </div>
          <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-sm">S</div>
        </div>
      </div>
    </template>
  </div>

  <!-- Action Buttons (Bayar / Edit) -->
  <div x-show="showActionButtons" class="flex gap-2 mb-3">

    <button
      @click="bayar(selectedSantri)"
      class="flex-1 bg-green-600 text-white px-4 py-2 rounded-xl">
      Bayar
    </button>

    <button
      @click="edit(selectedSantri)"
      class="flex-1 bg-yellow-500 text-white px-4 py-2 rounded-xl">
      Edit
    </button>

  </div>

  <!-- Payment Options -->
  <div
    x-show="showPaymentButtons"
    class="flex flex-wrap gap-2 mb-3">

    <button
      @click="pilihPembayaran('SPP')"
      class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
      Bayar SPP
    </button>

    <button
      @click="pilihPembayaran('Daftar Ulang')"
      class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
      Bayar Daftar Ulang
    </button>

    <button
      @click="pilihPembayaran('Uang Saku')"
      class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
      Bayar Uang Saku
    </button>

    <button
      @click="pilihPembayaran('Infaq')"
      class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-medium">
      Bayar Infaq
    </button>

    <button
      @click="pilihPembayaran('Lainnya')"
      class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
      Bayar Lainnya
    </button>

  </div>

  <!-- Input Box -->
  <div class="mt-3 flex flex-col gap-2 border-t pt-3 relative">

    <!-- Suggestion Box -->
    <div x-show="suggestions.length > 0" class="absolute -top-24 left-0 w-full bg-white shadow rounded-xl p-3 z-10">
      <template x-for="(s, index) in suggestions" :key="index">
        <button @click="applySuggestion(s)" class="block w-full text-left px-3 py-2 hover:bg-gray-100 rounded-lg text-sm"> <span x-text="s"></span> </button>
      </template>
    </div>

    <div class="flex items-center gap-2">
      <input
        type="text"
        placeholder="Ketik pesan..."
        x-model="input"
        @input="checkSuggestions()"
        @keydown.enter="sendMessage()"
        class="flex-1 border rounded-full px-4 py-2 ml-12 focus:outline-none focus:ring-2 focus:ring-blue-500">
      <button @click="sendMessage()" class="bg-blue-600 text-white rounded-full p-3 hover:bg-blue-700">
        <span class="material-symbols-outlined text-xl">send</span>
      </button>
    </div>
  </div>
</div>

<script>
  // data dari PHP
  const santriData = <?= json_encode($cari); ?>;
  const bank = <?= json_encode(array_column($cari, 'nama')); ?>;

  function chatApp() {
    return {
      // state
      input: '',
      messages: [{
        id: 1,
        type: 'in',
        text: 'Masukkan Nama Santri'
      }],

      formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
      },

      formBayar: [],
      suggestions: [],
      showActionButtons: false,
      showPaymentButtons: false,
      selectedSantri: null,

      // helper format ribuan
      format(n) {
        if (n === null || n === undefined || n === '') return '0';
        // pastikan angka valid sebelum format
        const num = Number(n);
        return Number.isNaN(num) ? n : num.toLocaleString('id-ID');
      },

      // ===== events / actions =====
      sendMessage() {
        if (!this.input || !this.input.trim()) return;

        const text = this.input.trim();

        // push outgoing message
        this.messages.push({
          id: Date.now(),
          type: 'out',
          text: text
        });

        // reset input + suggestions
        this.input = '';
        this.suggestions = [];
        window.dispatchEvent(new CustomEvent('message-added'));

        // cari match (exact, case-insensitive)
        const match = santriData.find(s => (s.nama || '').toLowerCase() === text.toLowerCase());

        // bot reply setelah delay singkat
        setTimeout(() => {
          if (match) {
            const info =
              "Nama : " + (match.nama) + " --- " +
              "Jenjang : " + (match.jenjang) + " --- " +
              "Kelas : " + (match.kelas) + " --- " +
              "SPP : " + this.format(match.spp) + " --- " +
              "Tunggakan SPP : " + this.format(match.tunggakanspp) + " --- " +
              "Tunggakan DU 1 : " + this.format(match.tunggakandu) + " --- " +
              "Tunggakan DU 2 : " + this.format(match.tunggakandu2) + " --- " +
              "Tunggakan DU 3 : " + this.format(match.tunggakandu3) + " --- " +
              "Kontak Wali : " + (match.kontak1 || 'belum ada');

            this.messages.push({
              id: Date.now(),
              type: 'in',
              text: info
            });

            // tampilkan tombol Bayar / Edit
            this.showActionButtons = true;
            this.showPaymentButtons = false;
            this.selectedSantri = match;

          } else {
            this.messages.push({
              id: Date.now(),
              type: 'in',
              text: 'Masukkan sesuai data yang ada'
            });

            this.showActionButtons = false;
            this.showPaymentButtons = false;
            this.selectedSantri = null;
          }

          window.dispatchEvent(new CustomEvent('message-added'));
        }, 300);
      },

      applySuggestion(text) {
        // langsung isi dan kirim pesan
        this.input = text;
        this.suggestions = [];
        // beri sedikit waktu supaya x-model ter-update sebelum dikirim
        this.$nextTick(() => this.sendMessage());
      },

      checkSuggestions() {
        if (!this.input || this.input.length < 2) {
          this.suggestions = [];
          return;
        }
        const q = this.input.toLowerCase().trim();
        this.suggestions = bank
          .filter(x => x && x.toLowerCase().includes(q))
          .slice(0, 3);
      },

      // edit action
      edit(s) {
        // contoh: kirim pesan out atau bisa buka modal edit
        this.messages.push({
          id: Date.now(),
          type: 'out',
          text: "Edit data " + (s.nama || '')
        });

        // sembunyikan action buttons
        this.showActionButtons = false;
        this.showPaymentButtons = false;
        this.selectedSantri = null;

        window.dispatchEvent(new CustomEvent('message-added'));
      },

      // tampilkan opsi payment badge
      bayar(s) {
        this.selectedSantri = s;
        this.showPaymentButtons = true;
        this.showActionButtons = false;
      },

      // pilih salah satu badge pembayaran
      pilihPembayaran(jenis) {
        if (!this.selectedSantri) return;

        // --- KHUSUS JENIS SPP ---
        if (jenis === 'SPP') {

          let existing = this.formBayar.find(x => x.jenis === 'SPP');

          if (existing) {
            existing.spp += Number(this.selectedSantri.spp);
          } else {
            this.formBayar.push({
              jenis: 'SPP',
              nama: this.selectedSantri.nama,
              spp: Number(this.selectedSantri.spp)
            });
          }

          var judul = "Pembayaran SPP : ";
          var nilai = existing ? existing.spp : Number(this.selectedSantri.spp);

          var tagReply = "reply_spp";
        }



        // --- DAFTAR ULANG ---
        else if (jenis === 'Daftar Ulang') {

          const nilaiDU =
            Number(this.selectedSantri.tunggakandu) +
            Number(this.selectedSantri.tunggakandu2) +
            Number(this.selectedSantri.tunggakandu3);

          let existingDU = this.formBayar.find(x => x.jenis === 'Daftar Ulang');

          if (existingDU) {
            existingDU.nilai = nilaiDU; // tidak menambah
          } else {
            this.formBayar.push({
              jenis: 'Daftar Ulang',
              nama: this.selectedSantri.nama,
              nilai: nilaiDU
            });
          }

          var judul = "Pembayaran Daftar Ulang : ";
          var nilai = nilaiDU;

          var tagReply = "reply_du";
        }



        // --- UANG SAKU ---
        else if (jenis === 'Uang Saku') {

          const nilaiSaku = 50000;

          let existingSaku = this.formBayar.find(x => x.jenis === 'Uang Saku');

          if (existingSaku) {
            existingSaku.nilai = nilaiSaku; // tidak bertambah
          } else {
            this.formBayar.push({
              jenis: 'Uang Saku',
              nama: this.selectedSantri.nama,
              nilai: nilaiSaku
            });
          }

          var judul = "Pembayaran Uang Saku : ";
          var nilai = nilaiSaku;

          var tagReply = "reply_saku";
        }



        // --- Hapus reply chat lama ---
        this.messages = this.messages.filter(m => m.tag !== tagReply);

        // --- Hanya 1 reply messages.push ---
        this.messages.push({
          id: Date.now(),
          type: 'in',
          tag: tagReply,
          text: judul + "\n" + this.formatRupiah(nilai)
        });
        window.dispatchEvent(new CustomEvent('message-added'));
      },

      // scroll helper
      scrollBottom() {
        this.$nextTick(() => {
          if (this.$refs && this.$refs.chatBody) {
            this.$refs.chatBody.scrollTop = this.$refs.chatBody.scrollHeight;
          }
        });
      }
    };
  }
</script>

<?= $this->endSection(); ?>