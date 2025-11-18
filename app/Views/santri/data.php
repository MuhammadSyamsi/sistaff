<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="container mx-auto p-4" x-data="santriApp()">
  <!-- Top Cards -->
  <div class="grid lg:grid-cols-2 gap-4 mb-6">
    <!-- Data Santri Toggle -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="flex justify-between items-center mb-4">
        <h5 class="font-semibold text-lg">Data Santri</h5>
        <div class="flex space-x-2">
          <button @click="active='mts'" :class="active==='mts' ? 'bg-green-500 text-white' : 'bg-white border border-green-500 text-green-500'"
            class="px-3 py-1 rounded-md text-sm font-medium">MTs</button>
          <button @click="active='ma'" :class="active==='ma' ? 'bg-red-500 text-white' : 'bg-white border border-red-500 text-red-500'"
            class="px-3 py-1 rounded-md text-sm font-medium">MA</button>
        </div>
      </div>

      <!-- MTs -->
      <div x-show="active==='mts'" x-transition>
        <div class="mb-2">
          <span class="text-4xl text-gray-500 font-bold"><?= $mts ?></span>
          <span class="text-gray-400">santri</span>
        </div>
        <p class="text-gray-500 mb-2">Distribusi per kelas:</p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($kelasmts as $rek): ?>
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
              <?= $rek['kelas']; ?>: <i><?= $rek['hitung']; ?> santri</i>
            </span>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- MA -->
      <div x-show="active==='ma'" x-transition>
        <div class="mb-2">
          <span class="text-4xl text-red-500 font-bold"><?= $ma ?></span>
          <span class="text-gray-400">santri</span>
        </div>
        <p class="text-gray-500 mb-2">Distribusi per kelas:</p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($kelasma as $rekma): ?>
            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
              <?= $rekma['kelas']; ?>: <i><?= $rekma['hitung']; ?> santri</i>
            </span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Total Santri -->
    <div class="bg-yellow-100 text-yellow-800 shadow rounded-lg flex items-center justify-center p-6 text-center">
      <div>
        <h6 class="text-gray-500 mb-1">Total Santri Semua Jenjang</h6>
        <h2 class="text-3xl font-bold"><?= $total ?></h2>
      </div>
    </div>
  </div>

  <!-- Filter Section -->
  <div class="mb-4">
    <div class="flex justify-between items-center mb-3">
      <h5 class="font-semibold text-lg">Data Santri</h5>
      <a href="<?= base_url('Santri/download') ?>"
        class="flex items-center gap-1 text-blue-600 border border-blue-600 px-3 py-1 rounded hover:bg-blue-600 hover:text-white transition">
        <span class="material-symbols-outlined">download</span> Download
      </a>
    </div>

    <!-- Form Filter -->
    <form id="formFilter" class="grid md:grid-cols-3 gap-4 items-end">
      <div>
        <label for="filterJenjang" class="block mb-1 text-gray-600">Jenjang</label>
        <select name="jenjang" id="filterJenjang" class="w-full border rounded px-3 py-2" x-model="filter.jenjang" @change="updateKelas">
          <option value="">Pilih Jenjang</option>
          <?php foreach ($filterJenjang as $fj): ?>
            <option value="<?= $fj['jenjang']; ?>"><?= $fj['jenjang']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="filterKelas" class="block mb-1 text-gray-600">Kelas</label>
        <select name="kelas" id="filterKelas" class="w-full border rounded px-3 py-2" x-model="filter.kelas" :disabled="!kelasOptions.length">
          <option value="">Pilih Kelas</option>
          <template x-for="k in kelasOptions" :key="k">
            <option :value="k" x-text="k"></option>
          </template>
        </select>
      </div>

      <div>
        <label for="keyword" class="block mb-1 text-gray-600">Pencarian Nama</label>
        <input type="text" id="keyword" name="keyword" placeholder="Cari Nama..."
          class="w-full border rounded px-3 py-2" x-model="filter.keyword" @input.debounce.500ms="filterSantri">
      </div>
    </form>
  </div>

  <!-- Card AJAX -->
  <div id="cardListSantri" class="grid gap-4 mt-4">
    <!-- Data AJAX akan muncul di sini -->
  </div>
</div>

<script>
  $(document).ready(function() {
    const kelasByJenjang = <?= json_encode($kelasByJenjang) ?>;
    const form = $('#formFilter');

    $('#filterJenjang').on('change', function() {
      const jenjang = $(this).val();
      let html = '<option value="">Pilih Kelas</option>';

      if (jenjang && kelasByJenjang[jenjang]) {
        kelasByJenjang[jenjang].forEach(k => {
          html += `<option value="${k}">${k}</option>`;
        });
        $('#filterKelas').html(html).prop('disabled', false);
      } else {
        $('#filterKelas').html(html).prop('disabled', true);
      }

      filterSantri();
    });

    $('#filterKelas, #keyword').on('change keyup', function() {
      filterSantri();
    });

    function filterSantri() {
      const kelas = $('#filterKelas').val();
      const keyword = $('#keyword').val().trim();
      const jenjang = $('#filterJenjang').val();

      if (jenjang && kelas || keyword.length > 0) {
        $.ajax({
          type: 'GET',
          url: '<?= base_url('Santri/data') ?>',
          data: form.serialize(),
          success: function(html) {
            $('#cardListSantri').html(html);
          }
        });
      } else {
        $('#cardListSantri').html('');
      }
    }

    filterSantri(); // initial load
  });

  function santriApp() {
    return {
      active: 'mts',

      // Data filter
      filter: {
        jenjang: '',
        kelas: '',
        keyword: ''
      },

      // Data kelas berdasarkan jenjang (dari PHP)
      kelasByJenjang: <?= json_encode($kelasByJenjang) ?>,

      // pilihan kelas
      kelasOptions: [],

      // loading
      loading: false,

      // Update kelas saat pilihan jenjang berubah
      updateKelas() {
        if (this.filter.jenjang && this.kelasByJenjang[this.filter.jenjang]) {
          this.kelasOptions = this.kelasByJenjang[this.filter.jenjang];
        } else {
          this.kelasOptions = [];
          this.filter.kelas = '';
        }
        this.filterSantri();
      },

      // Debounce manual 500ms
      timer: null,
      debounceFilter() {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
          this.filterSantri();
        }, 500);
      },

      // Load data AJAX
      filterSantri() {
        const {
          jenjang,
          kelas,
          keyword
        } = this.filter;

        // Tidak load kalau kosong semua
        if (!(jenjang && kelas) && keyword.trim().length === 0) {
          document.getElementById('cardListSantri').innerHTML = '';
          return;
        }

        this.loading = true;

        fetch(`<?= base_url('Santri/data') ?>?jenjang=${jenjang}&kelas=${kelas}&keyword=${keyword}`)
          .then(res => res.text())
          .then(html => {
            document.getElementById('cardListSantri').innerHTML = html;
            this.loading = false;
          });
      },

      // initial load
      init() {
        this.filterSantri();
      }
    }
  }
</script>

<?= $this->endSection(); ?>