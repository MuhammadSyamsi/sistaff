<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/dh.png" />

    <title>Sistaff</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .chat-container {
            max-width: 100%;
            margin: 0 auto;
            height: 100vh;
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            background-color: #e5ddd5;
            border-radius: 10px;
            overflow: hidden;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .message {
            margin-bottom: 15px;
        }

        .message.user .text {
            background-color: #d1ecf1;
            text-align: right;
        }

        .message.bot .text {
            background-color: #e2e3e5;
            text-align: left;
        }

        .text {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 15px;
            max-width: 80%;
        }

        .chat-input {
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .welcome-screen {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            text-align: center;
        }

        .badges span {
            margin: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php if (session('errors.login')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('welcomeText').innerText = '<?= esc(session('errors.login')) ?>';
            });
        </script>
    <?php endif; ?>

    <?php if (session('errors.password')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('welcomeText').innerText = '<?= esc(session('errors.password')) ?>';
            });
        </script>
    <?php endif; ?>

    <main role="main" class="chat-container">
        <?= $this->renderSection('main') ?>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let stage = 0;
        let loginData = {
            username: '',
            password: ''
        };

        function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('userInput');
            const message = input.value.trim();
            if (message === '') return;

            const chat = document.getElementById('chatMessages');
            const welcome = document.getElementById('welcomeScreen');

            if (stage === 0) {
                loginData.username = message;
                stage = 1;
                input.placeholder = 'Masukkan password';
                document.getElementById('welcomeText').innerText = `Hai, ${message}`;

            } else if (stage === 1) {
                loginData.password = message;
                stage = 2;
                input.placeholder = 'Ketik sesuatu...';
                // Isi input form tersembunyi
                document.getElementById('inputLogin').value = loginData.username;
                document.getElementById('inputPassword').value = loginData.password;

                // Submit form login
                document.getElementById('loginForm').submit();

            } else {
                setTimeout(() => {
                    chat.innerHTML += `
            <div class="message bot">
              <div class="text">Anda sudah login sebagai <b>${loginData.username}</b>. Ketik perintah atau pertanyaan Anda.</div>
            </div>
          `;
                    chat.scrollTop = chat.scrollHeight;
                }, 300);
            }

            input.value = '';
            chat.scrollTop = chat.scrollHeight;
        }
    </script>

    <?= $this->renderSection('pageScripts') ?>
</body>

</html>