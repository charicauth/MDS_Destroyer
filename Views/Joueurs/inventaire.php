<div class="start">
	<h1>Inventaire</h1>
</div>

<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php include ('Views/Elements/alerts.php'); ?>

				<div class="dashboard">

					<!-- Inventaire Objets --->
					<h3>Objets</h3>
					<br/>

					<table class="table">
						<thead class="thead-light">
							<tr>
								<th scope="col">Objet</th>
								<th scope="col">Nom</th>
								<th scope="col">Description</th>
								<th scope="col">Statut</th>
								<th scope="col">Valeur</th>
								<th scope="col">Quantité</th>
							</tr>
						</thead>
						<tbody>
						<?php
							foreach($_inventaireObjets as $inventaireObjet){
								$objet = ($ObjetsController->objet->getById($inventaireObjet['objet_id'])) ;

								echo '<tr>';
								echo '<td style="vertical-align:middle;">' . $objet['image'] . '</td>';
								echo '<th scope="row" style="vertical-align:middle;">' . $objet['nom'] . '</th>';
								echo '<td style="vertical-align:middle;">' . $objet['description'] . '</td>';
								echo '<td style="vertical-align:middle;">' . $objet['effet_stat'] . '</td>';
								echo '<td style="vertical-align:middle;">' . $objet['effet_valeur'] . '</td>';
								echo '<td style="vertical-align:middle;">' . $inventaireObjet['quantite'] . '</td>';

							}								
						?>
						</tbody>
					</table>				
					<br/>

					<!-- Inventaire Equipements --->

					<h3>Equipements</h3>
					<br/>
					
					<table class="table">
						<thead class="thead-light">
							<tr>
								<th scope="col">Equipement</th>
								<th scope="col">Nom</th>
								<th scope="col">Description</th>
								<th scope="col">Emplacement</th>
								<th scope="col">Statut</th>
								<th scope="col">Valeur</th>
								<th scope="col">Equipé</th>
							</tr>
						</thead>
						<tbody>
						<?php
							foreach($_inventaireEquipements as $inventaireEquipement){
								$equipement = ($EquipementsController->equipement->getById($inventaireEquipement['equipement_id'])) ;
								$equipe = ($inventaireEquipement['equipe'] == 0 ) ? 'Pas équipé' : 'Equipé';

								echo '<tr>';
								echo '<td style="vertical-align:middle;">' . $equipement['image'] . '</td>';
								echo '<th scope="row" style="vertical-align:middle;">' . $equipement['nom'] . '</th>';
								echo '<td style="vertical-align:middle;">' . $equipement['description'] . '</td>';
								echo '<td style="vertical-align:middle;">' . $equipement['emplacement'] . '</td>';
								echo '<td style="vertical-align:middle;">' . $equipement['effet_stat'] . '</td>';
								echo '<td style="vertical-align:middle;">' . $equipement['effet_valeur'] . '</td>';
								echo '<td style="vertical-align:middle;">' . $equipe . '</td>';

							}								
						?>
						</tbody>
					</table>
			</div>
		</div>
	</div>
</div>