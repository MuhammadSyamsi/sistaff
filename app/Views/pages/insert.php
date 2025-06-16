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
  let booleanSaldo = true; // Boolean untuk menampilkan chat input saldo
  let booleanTanggal = false; // Boolean untuk menampilkan chat input tanggal
  let booleanRekening = false; // Boolean untuk menampilkan chat input rekening
  let booleanKeterangan = false; // Boolean untuk menampilkan chat input keterangan
  // Data untuk tabel transfer
  let saldomasuk = null;
  let tanggal = null;
  let rekening = null;
  let keterangan = null;
  // Data untuk tabel detail
  let detailDaftarulang = 0;
  let detailTunggakan = 0;
  let detailSpp = 0;
  let detailUangsaku = 0;
  let detailInfaq = 0;
  let detailFormulir = 0;

  function checkSaldomasukStatus() {
    const filterInput = document.getElementById("filterInput");

    if (booleanSaldo) {
      filterInput.oninput = () => {
        const raw = filterInput.value.replace(/[^0-9]/g, '');
        if (raw === '') return;
        saldomasuk = parseFloat(raw);
        if (isNaN(saldomasuk)) return;

        const div = document.createElement("div");
        div.className = "chat-bubble received";
        div.id = "saldomasuk";
        div.textContent = saldomasuk.toLocaleString('id-ID');
        div.onclick = editSaldo;
        document.getElementById("chatMessages").appendChild(div);

        booleanSaldo = false;
        booleanTanggal = true;
        filterInput.value = '';
        checkSaldomasukStatus();
      };
    }

    if (booleanTanggal) {
      const chatBox = document.getElementById("chatMessages");
      const promptDiv = document.createElement("div");
      promptDiv.className = "chat-bubble received";
      promptDiv.textContent = "Masukkan tanggal pembayaran";

      const dateDiv = document.createElement("div");
      dateDiv.className = "chat-bubble sent";
      const dateInput = document.createElement("input");
      dateInput.type = "date";
      dateInput.onchange = () => {
        tanggal = dateInput.value;
        booleanTanggal = false;
        booleanRekening = true;
        filterInput.disabled = false;

        const rekeningPrompt = document.createElement("div");
        rekeningPrompt.className = "chat-bubble received";
        rekeningPrompt.textContent = "Pilih rekening yayasan penerima";
        chatBox.appendChild(rekeningPrompt);

        checkSaldomasukStatus();
      };
      dateDiv.appendChild(dateInput);

      chatBox.appendChild(promptDiv);
      chatBox.appendChild(dateDiv);
      filterInput.disabled = true;
    }

    if (booleanRekening) {
      filterInput.oninput = () => {
        const val = filterInput.value.trim().toLowerCase();
        if (val === 'm') rekening = 'muamalat salam';
        else if (val === 'j') rekening = 'jatim syariah';
        else if (val === 'b') rekening = 'bsi';
        else if (val === 's') rekening = 'uang saku';
        else if (val === 'y') rekening = 'muamalat yatim';
        else if (val === 'l') rekening = 'lain-lain';
        else {
          const sys = document.createElement("div");
          sys.className = "system-messages";
          sys.textContent = "Pilihan tidak ada";
          document.getElementById("chatMessages").appendChild(sys);
          filterInput.value = '';
          return;
        }

        const rekeningDiv = document.createElement("div");
        rekeningDiv.className = "chat-bubble sent";
        rekeningDiv.textContent = rekening;
        document.getElementById("chatMessages").appendChild(rekeningDiv);

        booleanRekening = false;
        booleanKeterangan = true;
        filterInput.value = '';
        checkSaldomasukStatus();
      };
    }

    if (booleanKeterangan) {
      const chatBox = document.getElementById("chatMessages");
      const ketPrompt = document.createElement("div");
      ketPrompt.className = "chat-bubble received";
      ketPrompt.textContent = "Masukkan keterangan";
      chatBox.appendChild(ketPrompt);

      filterInput.oninput = () => {
        keterangan = filterInput.value.trim();
        if (!keterangan) return;

        const ketDiv = document.createElement("div");
        ketDiv.className = "chat-bubble sent";
        ketDiv.id = "keterangan";
        ketDiv.textContent = keterangan;
        ketDiv.onclick = editKeterangan;
        chatBox.appendChild(ketDiv);

        const detailPrompt = document.createElement("div");
        detailPrompt.className = "chat-bubble received";
        detailPrompt.textContent = "Cek juga detailnya";
        chatBox.appendChild(detailPrompt);

        filterInput.value = '';
        booleanKeterangan = false;
      };
    }
  }

  function editSaldo() {
    const current = document.getElementById("saldomasuk");
    const val = saldomasuk.toString();
    const inputEdit = document.createElement("input");
    inputEdit.type = "text";
    inputEdit.value = val;
    inputEdit.onblur = () => {
      const newVal = inputEdit.value.replace(/[^0-9]/g, '');
      if (!/^[0-9]+$/.test(newVal)) {
        current.textContent = saldomasuk.toLocaleString('id-ID');
        return;
      }
      saldomasuk = parseFloat(newVal);
      current.textContent = saldomasuk.toLocaleString('id-ID');
      current.onclick = editSaldo;
    };
    current.replaceWith(inputEdit);
    inputEdit.focus();
  }

  function editKeterangan() {
    const current = document.getElementById("keterangan");
    const inputEdit = document.createElement("input");
    inputEdit.type = "text";
    inputEdit.value = keterangan;
    inputEdit.onblur = () => {
      keterangan = inputEdit.value.trim();
      current.textContent = keterangan;
      current.onclick = editKeterangan;
      inputEdit.replaceWith(current);
    };
    current.replaceWith(inputEdit);
    inputEdit.focus();
  }

  function customFilterMessages() {
    const input = document.getElementById("filterInput").value.trim();
    const chatBox = document.getElementById("chatMessages");

    // Simpan angka asli ke saldomasuk
    if (!booleanTanggal) {
      const inputRaw = input.replace(/\./g, '');
      const isValid = /^[0-9]+$/.test(inputRaw);

      if (!isValid || inputRaw === "") {
        // Tampilkan pesan kesalahan sebagai bubble
        const errorDiv = document.createElement("div");
        errorDiv.className = "system-message";
        errorDiv.textContent = "Saldo hanya boleh berisi angka";
        chatBox.appendChild(errorDiv);
        document.getElementById("filterInput").value = '';
        return;
      }

      saldomasuk = parseFloat(inputRaw);

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

      if (!booleanTanggal) {
        const thirdDiv = document.createElement("div");
        const descThird = document.createElement("div");
        const rekeningInput = document.createElement("input");
        thirdDiv.className = 'chat-bubble received';
        descThird.className = 'description';
        thirdDiv.textContent = 'rekening yayasan yang ditransfer';
        descThird.innerHTML = '<i>tag </i><span class="badge badge-dark">M</span> Muamalat Salam<br /><i>tag </i><span class="badge badge-dark">J</span> Jatim Syariah<br /><i>tag </i><span class="badge badge-dark">B</span> BSI<br /><i>tag </i><span class="badge badge-dark">S</span> Uang Saku<br /><i>tag </i><span class="badge badge-dark">Y</span> Muamalat Yatim<br /><i>tag </i><span class="badge badge-dark">L</span> lain-lain<br />';
        chatBox.appendChild(thirdDiv);
        thirdDiv.appendChild(descThird);
        booleanTanggal = true;
        rekeningInput.type = "text";
        rekeningInput.className = "chat-bubble received";
      }
      checkSaldomasukStatus();
    });

    // Kosongkan input
    document.getElementById("filterInput").value = "";
    checkSaldomasukStatus();
  }
</script>

<?= $this->endSection(); ?>