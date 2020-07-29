<div class="start">
	<h1>Inscription</h1>

	<div class="container">
		<div class="row">
			<div class="col-sm-6 offset-sm-3">

				<p>
					Bienvenue dans l'aventure !
					<br />
					Complétez les champs ci-dessous pour vous inscrire :
				</p>

				<br />
				<hr />
				<br />

				<?php include ('Views/Elements/alerts.php'); ?>

				<?php
				// On va utiliser les fonctions de bootstrap pour créer le formulaire
				echo openForm([CONTROLLER => 'joueurs', ACTION => 'inscription']) .

					 // Valeurs à saisir
					 input('prenom', ['label' => 'Prénom *']) .
					 input('nom', []) .
					 input('username', ['label' => 'Adresse mail']) .
					 input('password', ['label' => 'Mot de passe', 'placeholder' => '8 caractères minimum', 'type' => 'password']) .
					 input('pseudo') .
					 select('famille_id', $_familles, ['label' => 'Famille de départ']) .
					 input('nom_personnage', ['label' => 'Nom de mon premier personnage']) .
					 BR .
					 submit('Je m\'inscris !') .
					 closeForm();
				?>
			<p>* champ obligatoire</p>
			</div>
		</div>
	</div>
</div>