<div class="start">
	<h1><?= GAME_NAME; ?></h1>

	<div class="container">
		<div class="row">
			<div class="col-sm-6 offset-sm-3">

				<p>
					<img src="assets/img/home.jpg" alt="Home" class="img-fluid rounded"/>
				</p>

				<p>
					<?= button('Connexion', [ACTION => 'connect', CONTROLLER => 'joueurs'], ['color' => 'success']); ?>
					<?= button('Inscription', [ACTION => 'signin', CONTROLLER => 'joueurs'], ['color' => 'primary']); ?>
				</p>
			</div>
		</div>
	</div>
</div>