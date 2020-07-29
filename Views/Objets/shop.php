<div class="start">
	<h1>SHOP Objets</h1>
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
							<th scope="col">Objet</th>
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
						foreach ($_objets as $objet) {
							echo '<tr>';
							echo '<td style="vertical-align:middle;">' . $objet['image'] . '</td>';
							echo '<th scope="row" style="vertical-align:middle;">' . $objet['nom'] . '</th>';
							echo '<td style="vertical-align:middle;">' . $objet['description'] . '</td>';
							echo '<td style="vertical-align:middle;">' . $objet['effet_stat'] . '</td>';
							echo '<td style="vertical-align:middle;">' . $objet['effet_valeur'] . '</td>';
							echo '<td style="vertical-align:middle;">' . $objet['prix'] . '</td>';
							echo '<td style="vertical-align:middle;">'.
							openForm([
								CONTROLLER => 'objets',
								ACTION => 'achat'
								]).
							input('quantite', [
								'type' => 'number',
								'no_label' => true,
								'required' => true,
							]).
							hidden('objet_id', $objet['id']).
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
