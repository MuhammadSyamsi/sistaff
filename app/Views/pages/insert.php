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

  <!-- Modal Input Tanggal -->
  <div
    x-show="showTanggalModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white w-full h-full p-6 flex flex-col justify-center">

      <h2 class="text-xl font-bold text-center mb-4">
        Pilih Tanggal Pembayaran
      </h2>

      <input
        type="date"
        x-model="tanggalBayar"
        class="border w-full p-3 rounded-lg mb-4 text-lg">

      <button
        @click="simpanTanggal()"
        class="w-full bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg text-lg">
        Simpan
      </button>

      <button
        @click="showTanggalModal=false"
        class="w-full mt-3 bg-gray-400 hover:bg-gray-500 text-white p-3 rounded-lg text-lg">
        Batal
      </button>

    </div>
  </div>

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
      class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-medium shadow-sm hover:bg-green-700 transition">
      Bayar
    </button>

    <button
      @click="edit(selectedSantri)"
      class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-sm hover:bg-yellow-600 transition">
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
  const santriData = <?= json_encode($cari); ?>;
  const bank = <?= json_encode(array_column($cari, 'nama')); ?>;

  function chatApp() {
    return {

      /* ==========================
          STATE
      ========================== */
      input: '',
      messages: [{
        id: 1,
        type: 'in',
        text: 'Masukkan Nama Santri'
      }],

      formBayar: [],
      saldoMasuk: [],
      suggestions: [],

      showActionButtons: false,
      showPaymentButtons: false,
      showTanggalModal: false,

      selectedSantri: null,
      tanggalBayar: '',


      /* ==========================
          FORMATTER
      ========================== */
      formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
      },

      format(n) {
        const x = Number(n);
        return isNaN(x) ? n : x.toLocaleString('id-ID');
      },


      /* ==========================
          TANGGAL (MODAL)
      ========================== */
      simpanTanggal() {
        if (!this.tanggalBayar) return;

        // simpan tanggal ke array formBayar
        this.formBayar.tanggal = this.tanggalBayar;

        // pesan out (user)
        this.messages.push({
          id: Date.now(),
          type: 'out',
          text: "Tanggal Pembayaran: " + this.tanggalBayar
        });

        this.showTanggalModal = false;
        this.showActionButtons = true;

        window.dispatchEvent(new CustomEvent('message-added'));
      },


      /* ==========================
          SUGGESTION NAMA
      ========================== */
      checkSuggestions() {
        if (!this.input || this.input.length < 2) {
          this.suggestions = [];
          return;
        }
        const q = this.input.toLowerCase();
        this.suggestions = bank
          .filter(n => n && n.toLowerCase().includes(q))
          .slice(0, 3);
      },

      applySuggestion(text) {
        this.input = text;
        this.suggestions = [];
        this.$nextTick(() => this.sendMessage());
      },


      /* ==========================
          PENGIRIMAN PESAN
      ========================== */
      sendMessage() {
        if (!this.input.trim()) return;

        let text = this.input.trim();

        this.messages.push({
          id: Date.now(),
          type: 'out',
          text: text
        });

        // reset
        this.input = '';
        this.suggestions = [];

        window.dispatchEvent(new CustomEvent('message-added'));

        // cari nama santri
        const match = santriData.find(
          s => (s.nama || '').toLowerCase() === text.toLowerCase()
        );

        setTimeout(() => {

          if (match) {

            const info =
              "Nama : " + match.nama + " --- " +
              "Jenjang : " + match.jenjang + " --- " +
              "Kelas : " + match.kelas + " --- " +
              "SPP : " + this.format(match.spp) + " --- " +
              "Tunggakan SPP : " + this.format(match.tunggakanspp) + " --- " +
              "Tunggakan DU 1 : " + this.format(match.tunggakandu) + " --- " +
              "Tunggakan DU 2 : " + this.format(match.tunggakandu2) + " --- " +
              "Tunggakan DU 3 : " + this.format(match.tunggakandu3) + " --- " +
              "Kontak Wali : " + (match.kontak1 || 'belum ada') + " --- lakukan pembayaran";

            this.messages.push({
              id: Date.now(),
              type: 'in',
              text: info
            });

            this.selectedSantri = match;
            this.showTanggalModal = true;
            this.showPaymentButtons = false;
            this.showActionButtons = false;

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


      /* ==========================
          EDIT DATA
      ========================== */
      edit(s) {
        this.messages.push({
          id: Date.now(),
          type: 'out',
          text: "Edit data " + s.nama
        });

        this.showActionButtons = false;
        this.showPaymentButtons = false;
        this.selectedSantri = null;

        window.dispatchEvent(new CustomEvent('message-added'));
      },


      /* ==========================
          TOMBOL BAYAR
      ========================== */
      bayar(s) {
        this.selectedSantri = s;
        this.showPaymentButtons = true;
        this.showActionButtons = false;
      },


      /* ==========================
          PILIH PEMBAYARAN
      ========================== */
      pilihPembayaran(jenis) {
        if (!this.selectedSantri) return;

        let judul = "";
        let nilai = 0;
        let tagReply = "";


        /* ---------- SPP ---------- */
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

          judul = "Pembayaran SPP : ";
          nilai = existing ? existing.spp : Number(this.selectedSantri.spp);
          tagReply = "reply_spp";
        }


        /* ---------- DAFTAR ULANG ---------- */
        else if (jenis === 'Daftar Ulang') {

          const nilaiDU =
            Number(this.selectedSantri.tunggakandu) +
            Number(this.selectedSantri.tunggakandu2) +
            Number(this.selectedSantri.tunggakandu3);

          let existing = this.formBayar.find(x => x.jenis === 'Daftar Ulang');

          if (existing) {
            existing.nilai = nilaiDU; // tetap 1
          } else {
            this.formBayar.push({
              jenis: 'Daftar Ulang',
              nama: this.selectedSantri.nama,
              nilai: nilaiDU
            });
          }

          judul = "Pembayaran Daftar Ulang : ";
          nilai = nilaiDU;
          tagReply = "reply_du";
        }


        /* ---------- UANG SAKU ---------- */
        else if (jenis === 'Uang Saku') {

          const nilaiSaku = 50000;

          let existing = this.formBayar.find(x => x.jenis === 'Uang Saku');

          if (existing) {
            existing.nilai = nilaiSaku;
          } else {
            this.formBayar.push({
              jenis: 'Uang Saku',
              nama: this.selectedSantri.nama,
              nilai: nilaiSaku
            });
          }

          judul = "Pembayaran Uang Saku : ";
          nilai = nilaiSaku;
          tagReply = "reply_saku";
        }


        /* ==========================
            HAPUS REPLY LAMA & PUSH BARU
        ========================== */
        this.messages = this.messages.filter(m => m.tag !== tagReply);

        this.messages.push({
          id: Date.now(),
          type: 'in',
          tag: tagReply,
          text: judul + "\n" + this.formatRupiah(nilai)
        });

        window.dispatchEvent(new CustomEvent('message-added'));
      },


      /* ==========================
          AUTO SCROLL CHAT
      ========================== */
      scrollBottom() {
        this.$nextTick(() => {
          if (this.$refs.chatBody) {
            this.$refs.chatBody.scrollTop =
              this.$refs.chatBody.scrollHeight;
          }
        });
      }

    };
  }
</script>

<?= $this->endSection(); ?>