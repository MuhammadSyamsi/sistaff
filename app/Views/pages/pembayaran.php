<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<!-- Messages -->
<div class="chat-messages" id="chatMessages">
  <div class="pin received">
    masukkan nama santri
    <div class="description">
      jika <b>data ditemukan</b>, pilih dengan cara <b>klik pada data tersebut</b>
    </div>
  </div>
  <div class="pin received">
    <div class="description">
      Ketik huruf <span class="badge badge-dark">C</span> untuk membersihkan chat<br />
      Ketik huruf <span class="badge badge-dark">R</span> untuk kembali ke halaman utama
    </div>
  </div>
</div>
<script>
  async function customFilterMessages() {
    const keyword = document.getElementById('filterInput').value.trim();
    const chatBox = document.getElementById("chatMessages");

    if (!keyword) return;
    if (keyword === "c") {
      window.location.href = window.location.origin + "/sistaff/public/pembayaran";
      return;
    }
    if (keyword === "r") {
      window.location.href = window.location.origin + "/sistaff/public";
      return;
    }

    try {
      const response = await fetch("<?= base_url('filter-santri') ?>?q=" + encodeURIComponent(keyword));
      const data = await response.json();

      // Membuat elemen baru untuk hasil pencarian
      const resultContainer = document.createElement('div');
      resultContainer.className = 'system-message';

      if (data.length > 0) {
        data.forEach(item => {
          const messageDiv = document.createElement('div');
          messageDiv.className = 'chat-bubble sent';
          messageDiv.id = 'pembayaran/' + item.sumber + '-' + item.nisn;
          messageDiv.setAttribute('onclick', 'goToRoute(this)');
          messageDiv.setAttribute('data-description', item.nisn);

          // Isi utama: nama
          messageDiv.textContent = item.nama;

          // Buat elemen <div class="description">
          const descDiv = document.createElement('div');
          descDiv.className = 'description';
          descDiv.textContent = 'dari data ';

          // Buat <span> untuk badge sumber
          const badgeSpan = document.createElement('span');
          badgeSpan.classList.add('badge');

          if (item.sumber === 'santri') {
            badgeSpan.classList.add('badge-success');
            badgeSpan.textContent = 'santri';
          } else if (item.sumber === 'psb') {
            badgeSpan.classList.add('badge-warning');
            badgeSpan.textContent = 'psb';
          } else if (item.sumber === 'alumni') {
            badgeSpan.classList.add('badge-danger');
            badgeSpan.textContent = 'alumni';
          } else {
            badgeSpan.classList.add('badge-secondary');
            badgeSpan.textContent = item.sumber || 'tidak diketahui';
          }

          // Masukkan <span> badge ke dalam <div class="description">
          descDiv.appendChild(badgeSpan);

          // Masukkan description ke dalam message
          messageDiv.appendChild(descDiv);

          // Tambahkan ke chatBox
          chatBox.appendChild(messageDiv);
        });

      } else {
        // Jika tidak ada hasil, tampilkan pesan tidak ditemukan
        resultContainer.textContent = 'nama "' + keyword + '" tidak ditemukan';
        chatBox.appendChild(resultContainer);
      }

    } catch (error) {
      console.error('Gagal mengambil data:', error);
    }

    // Hapus isian teks
    document.getElementById('filterInput').value = '';

  }
</script>

<?= $this->endSection(); ?>