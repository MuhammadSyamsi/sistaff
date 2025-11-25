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
  const santriData = <?= json_encode($cari); ?>;

  function chatApp() {
    return {
      input: '',
      messages: [{
        id: 1,
        type: 'in',
        text: 'Masukkan Nama Santri',
        showActionButtons: false,
        showPaymentButtons: false,
        selectedSantri: null,

        bayar(s) {
          this.selectedSantri = s;
          this.showPaymentButtons = true;
          this.showActionButtons = false;
        },

        edit(s) {
          this.messages.push({
            id: Date.now(),
            type: 'out',
            text: "Edit data " + s.nama
          });

          this.showActionButtons = false;
        },

        pilihPembayaran(jenis) {
          this.messages.push({
            id: Date.now(),
            type: 'out',
            text: "Pembayaran " + jenis + " untuk " + this.selectedSantri.nama
          });

          this.showPaymentButtons = false;

          window.dispatchEvent(new CustomEvent('message-added'));
        }

      }],

      suggestions: [],

      sendMessage() {
        if (!this.input.trim()) return;

        const text = this.input.trim();

        // kirim pesan out
        this.messages.push({
          id: Date.now(),
          type: 'out',
          text: text
        });

        this.input = '';
        this.suggestions = [];
        window.dispatchEvent(new CustomEvent('message-added'));

        // cek apakah input cocok dengan nama santri
        const match = santriData.find(s =>
          s.nama.toLowerCase() === text.toLowerCase()
        );

        // --- BOT REPLY ---
        setTimeout(() => {
          if (match) {
            const info =
              "Nama : " + match.nama + ", " +
              "Jenjang : " + match.jenjang + ", " +
              "Kelas : " + match.kelas + ", " +
              "DU : " + Number(match.du).toLocaleString('id-ID') + ", " +
              "SPP : " + Number(match.spp).toLocaleString('id-ID') + ", " +
              "Tunggakan SPP : " + Number(match.tunggakanspp).toLocaleString('id-ID') + ", " +
              "Tunggakan DU 1 : " + Number(match.tunggakandu).toLocaleString('id-ID') + ", " +
              "Tunggakan DU 2 : " + Number(match.tunggakandu2).toLocaleString('id-ID') + ", " +
              "Tunggakan DU 3 : " + Number(match.tunggakandu3).toLocaleString('id-ID') +
              ", " +
              "Kontak Wali : " + match.kontak1;

            this.messages.push({
              id: Date.now(),
              type: 'in',
              text: info
            });

            // munculkan tombol Bayar + Edit
            this.showActionButtons = true;
            this.selectedSantri = match;

          } else {
            // TIDAK COCOK
            this.messages.push({
              id: Date.now(),
              type: 'in',
              text: 'Masukkan sesuai data yang ada'
            });

            this.showActionButtons = false;
            this.selectedSantri = null;
          }

          window.dispatchEvent(new CustomEvent('message-added'));
        }, 500);
      },

      scrollBottom() {
        this.$nextTick(() => {
          this.$refs.chatBody.scrollTop = this.$refs.chatBody.scrollHeight;
        });
      },

      checkSuggestions() {
        const bank = <?= json_encode(array_column($cari, 'nama')); ?>;
        if (this.input.length < 2) {
          this.suggestions = [];
          return;
        }
        this.suggestions = bank.filter(x => x.toLowerCase().includes(this.input.toLowerCase())).slice(0, 3);
      },

      applySuggestion(text) {
        this.input = text;
        this.suggestions = [];
        this.sendMessage();
      }
    };
  }
</script>

<?= $this->endSection(); ?>