<div class="start">
	<h1>Connexion</h1>

	<div class="container">
		<div class="row">
			<div class="col-sm-6 offset-sm-3">

				<p>
					Connectez-vous avec votre compte ci-dessous.
				</p>

				<br />
				<hr />
				<br />

				<?php include ('Views/Elements/alerts.php'); ?>

				<?php
				// On va utiliser les fonctions de bootstrap pour créer le formulaire
				echo openForm([CONTROLLER => 'joueurs', ACTION => 'connexion']) .

					 // Valeurs à saisir
					 input('username', ['label' => 'Adresse mail', 'required' => true, 'type' => 'email']) .
					 input('password', ['label' => 'Mot de passe', 'type' => 'password', 'required' => true]) .

					 submit('Je me connecte') .
					 closeForm();
				?>
				<br />
				<hr />
				<br />
				<p>Pas encore de compte ? Inscrivez-vous :<br /><br />
					<?= button('Inscription', [ACTION => 'signin', CONTROLLER => 'joueurs'], ['color' => 'primary']); ?>
				</p>
			</div>
		</div>
	</div>
</div>