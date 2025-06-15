<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<!-- Messages -->
<div class="chat-messages" id="chatMessages">
  <div class="pin received" data-description="deskripsi">
    <?php foreach ($cari as $cari): echo ($cari['nama']); ?>
      <div class="description"><b><?= ($cari['jenjang']); ?> Kelas <?= ($cari['kelas']); ?> </b><br />
        SPP : <?= format_rupiah($cari['spp']); ?> <br>
        <?php if ($cari['tunggakanspp'] != 0): ?>
          Tunggakan SPP : <?= format_rupiah($cari['tunggakanspp']); ?> <br>
        <?php endif;
        if ($cari['tunggakandu'] != 0): ?>
          Tunggakan Daftar Ulang : <?= format_rupiah($cari['tunggakandu']); ?> <br />
        <?php endif;
        if ($cari['tunggakantl'] != 0): ?>
          Tunggakan Lainnya : <?= format_rupiah($cari['tunggakantl']); ?>
      <?php endif;
      endforeach; ?>
      </div>
  </div>
  <div class="pin received" data-description="deskripsi">
    <div class="description">Riwayat Pembayaran<br />
      <ul>
        <?php foreach ($histori as $histori): ?>
          <li><?= date('d M Y', strtotime($histori['tanggal'])); ?> : Rp<?= format_rupiah($histori['saldomasuk']); ?> <br /> <?= ($histori['keterangan']); ?>
          </li><?php endforeach; ?>
      </ul>
    </div>
  </div>
  <div class="pin received">
    masukkan saldo diterima
    <div class="description">Masukkan hanya angka dan tanda titik (.)
      Contoh: <span class="badge badge-dark">2.000.000</span> atau <span class="badge badge-dark">2000000</span></div>
  </div>
</div>
<script>
  let saldomasuk = null; // Menyimpan angka asli tanpa format
  let tanggal = null; // Menyimpan tanggal
  let isThirdDivAppended = false; // Boolean agar create div hanya sekali
  let rekening = null; // Menyimpan rekening
  let keterangan = null; // Menyimpan keterangan

  let detailDaftarulang = 0; // Menyimpan detail daftar ulang
  let detailTunggakan = 0; // Menyimpan detail tunggakan sebelumnya
  let detailSpp = 0; // Menyimpan detail spp
  let detailUangsaku = 0; // Menyimpan detail uang saku
  let detailInfaq = 0; // Menyimpan detail infaq
  let detailFormulir = 0; // Menyimpan detail formulir

  function checkSaldomasukStatus() {
    const filterInput = document.getElementById("filterInput");

    if (saldomasuk !== null && !isThirdDivAppended) {
      filterInput.disabled = true;
    } else {
      filterInput.disabled = false;
    }
  }

  function customFilterMessages() {
    const input = document.getElementById("filterInput").value.trim();
    const chatBox = document.getElementById("chatMessages");

    // Simpan angka asli ke saldomasuk
    if (!isThirdDivAppended) {
      const inputRaw = input.replace(/\./g, '');
      const isValid = /^[0-9]+$/.test(inputRaw);

      if (!isValid || inputRaw === "") {
        // Tampilkan pesan kesalahan sebagai bubble
        const errorDiv = document.createElement("div");
        errorDiv.className = "system-message";
        errorDiv.textContent = "Input hanya boleh berisi angka dan tanda titik (.)";
        chatBox.appendChild(errorDiv);
        document.getElementById("filterInput").value = '';
        return;
      }

      saldomasuk = parseFloat(inputRaw);
      if (isNaN(saldomasuk)) {
        const errorDiv = document.createElement("div");
        errorDiv.className = "system-message";
        errorDiv.textContent = "Saldo diterima hanya boleh berisi angka dan tanda titik.";
        chatBox.appendChild(errorDiv);
        return;
      }

      // Format tampilan angka
      formattedValue = saldomasuk.toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      });
    }

    // Tampilkan di chat box
    const messageDiv = document.createElement("div");
    messageDiv.className = "chat-bubble sent";
    messageDiv.id = 'saldomasuk';
    messageDiv.setAttribute('onclick', 'editSaldomasuk(this)');
    messageDiv.textContent = `${formattedValue}`;
    chatBox.appendChild(messageDiv);

    const descDiv = document.createElement("div");
    descDiv.className = 'description';
    descDiv.innerHTML = 'saldo masuk, <i>ketuk untuk mengganti</i>';
    messageDiv.appendChild(descDiv);

    const secondDiv = document.createElement("div");
    secondDiv.className = 'chat-bubble received';
    secondDiv.textContent = 'masukkan tanngal';
    chatBox.appendChild(secondDiv);
    const dateInput = document.createElement("input");
    dateInput.type = "date";
    dateInput.className = "chat-bubble sent";
    dateInput.style.marginTop = "5px";
    // Tambahkan ke chatBox
    chatBox.appendChild(dateInput);
    // Simpan nilai tanggal ketika user memilihnya
    dateInput.addEventListener("change", function() {
      tanggal = this.value;

      if (!isThirdDivAppended) {
        const thirdDiv = document.createElement("div");
        const descThird = document.createElement("div");
        const rekeningInput = document.createElement("input");
        thirdDiv.className = 'chat-bubble received';
        descThird.className = 'description';
        thirdDiv.textContent = 'rekening yayasan yang ditransfer';
        descThird.innerHTML = '<i>tag </i><span class="badge badge-dark">M</span> Muamalat Salam<br /><i>tag </i><span class="badge badge-dark">J</span> Jatim Syariah<br /><i>tag </i><span class="badge badge-dark">B</span> BSI<br /><i>tag </i><span class="badge badge-dark">S</span> Uang Saku<br /><i>tag </i><span class="badge badge-dark">Y</span> Muamalat Yatim<br /><i>tag </i><span class="badge badge-dark">L</span> lain-lain<br />';
        chatBox.appendChild(thirdDiv);
        thirdDiv.appendChild(descThird);
        isThirdDivAppended = true;
        rekeningInput.type = "text";
        rekeningInput.className = "chat-bubble received";
      }
      checkSaldomasukStatus();
    });

    // Kosongkan input
    document.getElementById("filterInput").value = "";
    checkSaldomasukStatus();

  }

  function editSaldomasuk(elem) {
    // Cegah duplikasi input
    if (elem.querySelector("input")) return;

    const currentValue = saldomasuk || 0;

    // Buat input baru
    const inputEdit = document.createElement("input");
    inputEdit.type = "text";
    inputEdit.value = currentValue;
    inputEdit.style.width = "100%";
    inputEdit.style.border = "none";
    inputEdit.style.background = "transparent";
    inputEdit.style.fontSize = "16px";

    // Kosongkan teks lama (kecuali deskripsi)
    const desc = elem.querySelector('.description');
    elem.innerHTML = "";
    elem.appendChild(inputEdit);
    if (desc) elem.appendChild(desc);

    inputEdit.focus();

    // Validasi saat blur
    inputEdit.addEventListener("blur", () => {
      const val = inputEdit.value.trim();
      const isValid = /^[0-9.]+$/.test(val);
      if (!isValid || val === "") {
        inputEdit.value = currentValue;
        // Test input
        console.log(saldomasuk);

        const chatBox = document.getElementById("chatMessages");
        const errorDiv = document.createElement("div");
        errorDiv.className = "system-message";
        errorDiv.textContent = "Saldo masuk hanya boleh berisi angka dan tanda titik.";
        chatBox.appendChild(errorDiv);
        const formatted = saldomasuk.toLocaleString('id-ID', {
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
        });
        inputEdit.value = formatted;
        console.log(saldomasuk)
      } else {
        const newValue = parseFloat(val);
        const formatted = saldomasuk.toLocaleString('id-ID', {
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
        });
        inputEdit.value = formatted;
        saldomasuk = newValue;
        console.log(saldomasuk)
      }

      // elem.innerHTML = formatted;
      // if (desc) elem.appendChild(desc);
    });
  }
</script>

<?= $this->endSection(); ?>