<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<!-- Welcome screen -->
<div class="welcome-screen" id="welcomeScreen">
	<h2 class="mb-4" id="welcomeText">Ada yang bisa saya bantu?</h2>
	<div class="badges mb-4">
		<span class="badge badge-pill badge-light p-2 px-4">Lupa password</span>
		<span class="badge badge-pill badge-light p-2 px-4">Register baru</span>
	</div>
</div>

<!-- Chat area -->
<div class="chat-messages d-none" id="chatMessages"></div>

<!-- Chat input -->
<form class="chat-input" onsubmit="sendMessage(event)">
	<div class="input-group">
		<input type="text" id="userInput" class="form-control" placeholder="Masukkan username" autocomplete="off">
		<div class="input-group-append">
			<button class="btn btn-primary" type="submit">Kirim</button>
		</div>
	</div>
</form>

<form id="loginForm" action="<?= url_to('login') ?>" method="post" style="display:none;">
	<?= csrf_field() ?>
	<input type="hidden" name="login" id="inputLogin">
	<input type="hidden" name="password" id="inputPassword">
</form>

<?= $this->endSection() ?>