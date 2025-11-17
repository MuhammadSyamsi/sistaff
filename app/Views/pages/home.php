<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="px-4 py-4">
  
  <!-- Judul Utama -->
  <h4 class="text-xl font-semibold mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined text-blue-600">list_alt</span>
    Penerimaan Santri Baru
  </h4>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <?php
    function badgeStyle($status) {
      return match (strtolower($status)) {
        'diterima' => 'bg-green-100 text-green-700',
        'baru' => 'bg-yellow-100 text-yellow-700',
        'mengundurkan diri' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-600'
      };
    }
    ?>

    <?php foreach ($psb as $p): ?>
      <div class="w-full">
        <div class="bg-white shadow rounded-xl p-4 h-full">

          <!-- Badge Status -->
          <h5 class="text-lg font-medium flex items-center gap-2">
            <span class="px-3 py-1 text-sm rounded-full <?= badgeStyle($p['status']); ?>">
              <?php
              echo match (strtolower($p['status'])) {
                'diterima' => '<span class="material-symbols-outlined text-green-600 align-middle">check_circle</span>',
                'baru' => '<span class="material-symbols-outlined text-yellow-600 align-middle">schedule</span>',
                'mengundurkan diri' => '<span class="material-symbols-outlined text-red-600 align-middle">cancel</span>',
                default => '<span class="material-symbols-outlined text-gray-600 align-middle">info</span>',
              };
              ?>
              <?= ucfirst($p['status']); ?>
            </span>
          </h5>

          <!-- Detail -->
          <ul class="mt-3 space-y-1 text-gray-700">
            <li><strong>Jumlah:</strong> <?= $p['jumlah']; ?></li>
            <li><strong>Kewajiban:</strong> <?= format_rupiah($p['kewajiban']); ?></li>
            <li><strong>Pembayaran:</strong> <?= format_rupiah($p['pembayaran']); ?></li>
            <li><strong>Tunggakan:</strong> <?= format_rupiah($p['totaltunggakan']); ?></li>
          </ul>

        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Grafik PSB -->
  <?php if (!empty($psb)): ?>
    <div class="mt-4 border rounded-xl overflow-hidden">
      <button class="w-full flex items-center justify-between px-4 py-3 bg-gray-100 text-left"
              data-bs-toggle="collapse" data-bs-target="#collapsePsbChart">
        <span class="flex items-center gap-2">
          <span class="material-symbols-outlined text-blue-600">pie_chart</span>
          Lihat Grafik Penerimaan Santri Baru
        </span>
        <span class="material-symbols-outlined">expand_more</span>
      </button>

      <div id="collapsePsbChart" class="collapse">
        <div class="p-4 bg-white">
          <canvas id="chartPsbPie" height="200"></canvas>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <hr class="my-6">

  <!-- Judul Keuangan -->
  <h4 class="text-xl font-semibold mb-3 flex items-center gap-2">
    <span class="material-symbols-outlined text-green-600">payments</span>
    Pemasukan & Ringkasan Tunggakan
  </h4>

  <div class="bg-white shadow rounded-xl p-4">
    <p><strong>Pemasukan Bulan Ini:</strong> <?= format_rupiah($jumlah[0]['sum'] ?? 0); ?></p>

    <p class="mt-3 font-semibold">Detail Tunggakan:</p>

    <ul class="mt-1 space-y-1 text-gray-700">
      <li>Daftar Ulang:</li>
      <li>- Mandiri: <?= format_rupiah(array_sum(array_column($detailtung, 'tungdu'))); ?></li>
      <li>- Beasiswa: <?= format_rupiah(array_sum(array_column($detailbea, 'tungdu'))); ?></li>

      <li><strong>Total DU:</strong> 
        <?= format_rupiah(
              array_sum(array_column($detailtung, 'tungdu')) + 
              array_sum(array_column($detailbea, 'tungdu'))
            ); ?>
      </li>

      <li><strong>Tahun Lalu:</strong> 
        <?= format_rupiah(
              array_sum(array_column($detailtung, 'tungtl')) + 
              array_sum(array_column($detailbea, 'tungtl'))
            ); ?>
      </li>

      <li><strong>SPP:</strong> <?= format_rupiah(array_sum(array_column($detailtung, 'tungspp'))); ?></li>
    </ul>
  </div>

  <!-- Grafik Tunggakan -->
  <?php if (!empty($detailtung)): ?>
    <div class="mt-4 border rounded-xl overflow-hidden">
      <button class="w-full flex items-center justify-between px-4 py-3 bg-gray-100 text-left"
              data-bs-toggle="collapse" data-bs-target="#collapseTunggakanChart">
        <span class="flex items-center gap-2">
          <span class="material-symbols-outlined text-purple-600">donut_large</span>
          Lihat Grafik Tunggakan & Pemasukan
        </span>
        <span class="material-symbols-outlined">expand_more</span>
      </button>

      <div id="collapseTunggakanChart" class="collapse">
        <div class="p-4 bg-white">
          <canvas id="chartTunggakanPie" height="200"></canvas>
        </div>
      </div>
    </div>
  <?php endif; ?>

</div>

<?= $this->endSection(); ?>