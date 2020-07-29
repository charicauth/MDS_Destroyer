<div class="start">
	<h1>Détails Personnage</h1>
	<br/>
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-6">
			<?php include ('Views/Elements/alerts.php'); ?>

			<div class="personnage_detail">
				
				<br/>				
				<h3><?= $_personnage['nom'] ?></h3>
				<br/>
				
				<p>Classe <?= $_personnage['famille']['nom'] . ' :'?></p>
				<p class="text-muted"> <?= $_personnage['famille']['description']; ?> </p>
				<p>Puissance : <?= BR . badge($_personnage['puissance'], ['color'=>PRIMARY]);?></p>
				<p>Défense : <?= BR . badge($_personnage['defense'], ['color'=>PRIMARY]);?></p>
				<p>Agilité : <?= BR . badge($_personnage['agilite'], ['color'=>PRIMARY]);?></p>
				<p>Endurance : <?= BR . badge($_personnage['endurance'], ['color'=>PRIMARY]);?></p>
				<p>Magie : <?= BR . badge($_personnage['magie'], ['color'=>PRIMARY]);?></p>
				<p>Défense Magie : <?= BR . badge($_personnage['defense_magie'], ['color'=>PRIMARY]);?></p>
				<p>Points de vie : <?= BR . badge($_personnage['pv'], ['color'=>PRIMARY]) . BR . BR ;?></p>

				<div class="ml-auto">
					<?= button('Supprimer  Personnage', [
						CONTROLLER => 'personnages',
						ACTION => 'editionPerso',
					]);?>
				</div>

			</div>
		</div>
		
		<div class="col-sm-6">
			<br/>
			<div class="container-fluid bg-light">
				<h4> Equipements du personnage </h4>
				<br/>

				<B>Armes</B>

				<div class="row">
					<div class="col-sm-6">
						<?php
							$ligneIdArme = $EquipementsController->equipement->getEquipementByLigneEquipementId($_personnage['emplacement_arme_id']);		
							
							if(empty($ligneIdArme)){
								echo 'Pas d\'équipement';
							} else {
								$equipementArme = ($EquipementsController->equipement->getById($ligneIdArme['equipement_id']));
								?>
								<br/>
								<p>Nom : <?= $equipementArme['nom'] ?></p>
								<p><?= $equipementArme['effet_stat'] ?> : <?= badge($equipementArme['effet_valeur'], ['color'=>PRIMARY]) ?></p>
								<?php
							}
							?>
					</div>

					<div class="col-sm-6">
							<?php 

							$ArmesDisponibles = $EquipementsController->equipement->listeEquipementsParEmplacement($Auth->getId(), 'arme');
							$NomsArmesDisponibles = [];
							foreach($ArmesDisponibles as $ArmeDisponible) {
								if(!in_array($ArmeDisponible['nom'], $NomsArmesDisponibles)){
									$NomsArmesDisponibles[$ArmeDisponible['id']] =  $ArmeDisponible['nom'];
								}	
							}
							
							echo openForm([
								CONTROLLER => 'equipements',
								ACTION => 'equipement'
							]).
							select('id_equipement', $NomsArmesDisponibles,  ['label' => 'Armes à équipé']).
							hidden('personnage_id', $_personnage['id']).
							hidden('emplacement', 'arme').
							submit('Equipé').
							closeForm();

							?>
					</div>

				</div>
				<hr>

				<B>Tête</B>
				<div class="row">
					<div class="col-sm-6">
						<?php
							$ligneIdTete = $EquipementsController->equipement->getEquipementByLigneEquipementId($_personnage['emplacement_tete_id']);		
							
							if(empty($ligneIdTete)){
								echo 'Pas d\'équipement';
							} else {
								$equipementTete = ($EquipementsController->equipement->getById($ligneIdTete['equipement_id']));
								?>
								<br/>
								<p>Nom : <?= $equipementTete['nom'] ?></p>
								<p><?= $equipementTete['effet_stat'] ?> : <?= badge($equipementTete['effet_valeur'], ['color'=>PRIMARY]) ?></p>
								<?php
							}
							?>
					</div>

					<div class="col-sm-6">
							<?php 

							$TetesDisponibles = $EquipementsController->equipement->listeEquipementsParEmplacement($Auth->getId(), 'tete');
							$NomsTetesDisponibles = [];
							foreach($TetesDisponibles as $TeteDisponible) {
								if(!in_array($TeteDisponible['nom'], $NomsTetesDisponibles)){
									$NomsTetesDisponibles[$TeteDisponible['id']] =  $TeteDisponible['nom'];
								}	
							}
							
							echo openForm([
								CONTROLLER => 'equipements',
								ACTION => 'equipement'
							]).
							select('id_equipement', $NomsTetesDisponibles,  ['label' => 'Tetes à équipé']).
							hidden('personnage_id', $_personnage['id']).
							hidden('emplacement', 'tete').
							submit('Equipé').
							closeForm();

							?>
					</div>

				</div>
				<hr>
				<B>Torse</B>
				<div class="row">
					<div class="col-sm-6">
						<?php
							$ligneIdTorse = $EquipementsController->equipement->getEquipementByLigneEquipementId($_personnage['emplacement_torse_id']);		
							
							if(empty($ligneIdTorse)){
								echo 'Pas d\'équipement';
							} else {
								$equipementTorse = ($EquipementsController->equipement->getById($ligneIdTorse['equipement_id']));
								?>
								<br/>
								<p>Nom : <?= $equipementTorse['nom'] ?></p>
								<p><?= $equipementTorse['effet_stat'] ?> : <?= badge($equipementTorse['effet_valeur'], ['color'=>PRIMARY]) ?></p>
								<?php
							}
							?>
					</div>

					<div class="col-sm-6">
							<?php 

							$TorsesDisponibles = $EquipementsController->equipement->listeEquipementsParEmplacement($Auth->getId(), 'torse');
							$NomsTorsesDisponibles = [];
							foreach($TorsesDisponibles as $TorseDisponible) {
								if(!in_array($TorseDisponible['nom'], $NomsTorsesDisponibles)){
									$NomsTorsesDisponibles[$TorseDisponible['id']] =  $TorseDisponible['nom'];
								}	
							}
							
							echo openForm([
								CONTROLLER => 'equipements',
								ACTION => 'equipement'
							]).
							select('id_equipement', $NomsTorsesDisponibles,  ['label' => 'Torses à équipé']).
							hidden('personnage_id', $_personnage['id']).
							hidden('emplacement', 'torse').
							submit('Equipé').
							closeForm();

							?>
					</div>

				</div>
				
				<hr>
				<B>Jambes</B>
				<div class="row">
					<div class="col-sm-6">
						<?php
							$ligneIdJambes = $EquipementsController->equipement->getEquipementByLigneEquipementId($_personnage['emplacement_jambes_id']);		
							
							if(empty($ligneIdJambes)){
								echo 'Pas d\'équipement';
							} else {
								$equipementJambes = ($EquipementsController->equipement->getById($ligneIdJambes['equipement_id']));
								?>
								<br/>
								<p>Nom : <?= $equipementJambes['nom'] ?></p>
								<p><?= $equipementJambes['effet_stat'] ?> : <?= badge($equipementJambes['effet_valeur'], ['color'=>PRIMARY]) ?></p>
								<?php
							}
							?>
					</div>

					<div class="col-sm-6">
							<?php 

							$JambessDisponibles = $EquipementsController->equipement->listeEquipementsParEmplacement($Auth->getId(), 'jambes');
							$NomsJambessDisponibles = [];
							foreach($JambessDisponibles as $JambesDisponible) {
								if(!in_array($JambesDisponible['nom'], $NomsJambessDisponibles)){
									$NomsJambessDisponibles[$JambesDisponible['id']] =  $JambesDisponible['nom'];
								}	
							}
							
							echo openForm([
								CONTROLLER => 'equipements',
								ACTION => 'equipement'
							]).
							select('id_equipement', $NomsJambessDisponibles,  ['label' => 'Jambes à équipé']).
							hidden('personnage_id', $_personnage['id']).
							hidden('emplacement', 'jambes').
							submit('Equipé').
							closeForm();

							?>
					</div>

				</div>
				
				

				<br/>
			</div>

		</div>
	</div>						
</div>
