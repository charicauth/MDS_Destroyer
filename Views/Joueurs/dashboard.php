<div class="start">
	<h1>Dashboard</h1>
</div>

<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<?php include ('Views/Elements/alerts.php'); ?>

				<div class="dashboard">
					<h3>Vos personnages</h3>
					<br/>
					<ul class="list-group">
						<?php
							foreach ($_personnages as $personnage) {

								echo '<a href="' . $Router->buildUrl('personnages', 'details', ['id' => $personnage['id']]) . '" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">	
											<img src="assets/img/archer.png" width="30" height="30" class="d-inline-block align-top" alt="">
											' . $personnage['nom'] . '
											<span></span>
									</a>';
							}					
						?>
						<a href="#" class="list-group-item list-group-item-success d-flex justify-content-center align-items-center">
							<img src="assets/img/person.png" width="30" height="30" class="d-inline-block" alt=""> 
							<span class="pl-2">Nouveau personnage</span>
						</a>
					</ul>					
			</div>
		</div>
	</div>
</div>