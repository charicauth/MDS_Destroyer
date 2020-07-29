<div class="start">
	<h1>SHOP Equipement</h1>
	<br/>
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<?php include ('Views/Elements/alerts.php'); ?>

			<div class="Shop">
				<table class="table">
					<thead class="thead-light">
						<tr>
							<th scope="col">Equipement</th>
							<th scope="col">Nom</th>
							<th scope="col">Description</th>
							<th scope="col">Statut</th>
							<th scope="col">Valeur</th>
							<th scope="col">Prix</th>
							<th scope="col">Achat</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($_equipements as $equipement) {
							echo '<tr>';
							echo '<td style="vertical-align:middle;">' . $equipement['image'] . '</td>';
							echo '<th scope="row" style="vertical-align:middle;">' . $equipement['nom'] . '</th>';
							echo '<td style="vertical-align:middle;">' . $equipement['description'] . '</td>';
							echo '<td style="vertical-align:middle;">' . $equipement['effet_stat'] . '</td>';
							echo '<td style="vertical-align:middle;">' . $equipement['effet_valeur'] . '</td>';
							echo '<td style="vertical-align:middle;">' . $equipement['prix'] . '</td>';
							echo '<td style="vertical-align:middle;">'.
							openForm([
								CONTROLLER => 'equipements',
								ACTION => 'achat'
								]).
							input('quantite', [
								'type' => 'number',
								'no_label' => true,
								'required' => true,
							]).
							hidden('equipement_id', $equipement['id']).
							submit('Acheter').
							closeForm().'
							</td>';
							echo '</tr>';
						} 
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>