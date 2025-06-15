<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Sistaff</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/dh.png" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 4 CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    .chat-box {
      display: flex;
      flex-direction: column;
      height: 100vh;
      max-width: 100%;
    }

    .chat-header {
      background-color: #075E54;
      color: white;
      padding: 15px;
      font-size: 1.25rem;
      font-weight: bold;
      border-bottom: 1px solid #ddd;
    }

    .chat-header a {
      color: white;
      text-decoration: none;
    }

    .chat-messages {
      flex: 1;
      padding: 15px;
      overflow-y: auto;
      background-color: #e5ddd5;
      display: flex;
      flex-direction: column;
    }

    /* Bubble umum */
    .chat-bubble {
      max-width: 70%;
      padding: 10px 15px;
      margin-bottom: 10px;
      border-radius: 10px;
      word-wrap: break-word;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    /* Pesan yang dikirim user */
    .sent {
      background-color: #dcf8c6;
      align-self: flex-end;
    }

    /* Pesan yang diterima system-message, pin, received*/
    .system-message {
      text-align: center;
      background-color: #e1f3fb;
      /* Biru muda atau abu */
      color: #4a4a4a;
      font-size: 12px;
      padding: 6px 12px;
      margin: 15px auto;
      border-radius: 10px;
      max-width: 70%;
      font-style: italic;
      box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
    }

    .pin {
      max-width: 70%;
      padding: 10px 15px;
      margin-bottom: 10px;
      border-radius: 10px;
      word-wrap: break-word;
    }

    .received {
      background-color: #fff;
      align-self: flex-start;
      border: 1px solid #ddd;
    }

    .message:hover {
      background-color: #cdebb5;
    }

    .description {
      font-size: 0.75rem;
      color: #555;
    }

    .chat-footer {
      background-color: #f1f1f1;
      padding: 10px;
      border-top: 1px solid #ddd;
    }
  </style>
</head>

<body>

  <div class="container-fluid p-0">
    <div class="chat-box">

      <!-- Header -->
      <?php

      use CodeIgniter\HTTP\URI;


      $uri = service('uri');
      $segments = $uri->getSegments();
      ?>

      <div class="chat-header" id="chatHeader">
        <a href="<?= base_url() ?>" class="text-white text-decoration-none">Sistaff</a>
        <?php foreach ($segments as $segment): ?>
          &nbsp;&gt;&nbsp;<?= ucwords(str_replace('-', ' ', esc($segment))) ?>
        <?php endforeach; ?>
      </div>

      <?= $this->renderSection('konten'); ?>

      <!-- Footer (Filter) -->
      <div class="chat-footer d-flex">
        <input type="text" class="form-control mr-2" id="filterInput" placeholder="Tuliskan Sesuatu" autocomplete="off" onkeypress="if(event.key==='Enter') filterMessages()">
        <button class="btn btn-primary" onclick="filterMessages()">Kirim</button>
      </div>

    </div>
  </div>

  <!-- JS -->
  <script>
    // agar selalu fokus di chat input
    // document.getElementById("filterInput").focus();
    const baseUrl = "<?= base_url() ?>";

    function goToRoute(element) {
      const route = element.id;

      // Ubah header
      window.location.href = `${baseUrl}${route}`;
    }

    function filterMessages() {
      // Cek apakah ada fungsi lokal di halaman yang override filterMessages
      if (typeof customFilterMessages === 'function') {
        return customFilterMessages(); // pakai fungsi lokal halaman ini
      }

      // Default behavior (dari template utama)
      const filter = document.getElementById("filterInput").value.trim().toLowerCase();
      if (filter === "") return; // Tidak melakukan apapun
      if (filter === "r") {
        window.location.href = window.location.origin + "/sistaff/public";
        return;
      }
      if (filter === "bayar") {
        window.location.href = window.location.origin + "/sistaff/public/pembayaran";
        return;
      }
      if (filter === "tunggakan") {
        window.location.href = window.location.origin + "/sistaff/public/tunggakan";
        return;
      }
      if (filter === "santri") {
        window.location.href = window.location.origin + "/sistaff/public/santri";
        return;
      }
      if (filter === "psb") {
        window.location.href = window.location.origin + "/sistaff/public/psb";
        return;
      }
      if (filter === "laporan") {
        window.location.href = window.location.origin + "/sistaff/public/laporan";
        return;
      }
      if (filter === "berkas") {
        window.location.href = window.location.origin + "/sistaff/public/berkas";
        return;
      }
      if (filter === "alumni") {
        window.location.href = window.location.origin + "/sistaff/public/alumni";
        return;
      }
      if (filter === "mutasi") {
        window.location.href = window.location.origin + "/sistaff/public/mutasi";
        return;
      }
      const chatBox = document.getElementById("chatMessages");
      const messages = document.querySelectorAll(".chat-bubble");

      // Membuat elemen baru untuk hasil pencarian
      const resultContainer = document.createElement('div');
      resultContainer.className = 'chat-bubble sent';
      const messageDiv = document.createElement('div');
      messageDiv.className = 'chat-bubble sent';
      // Isi tulisan kita
      messageDiv.textContent = filter;
      chatBox.appendChild(messageDiv);

      messages.forEach(msg => {
        const msgId = msg.id.toLowerCase();
        msg.style.display = msgId.includes(filter) ? "block" : "none";

      });

      // Hapus isian teks
      document.getElementById('filterInput').value = '';
    }
  </script>

  <!-- Bootstrap 4 JS + dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>