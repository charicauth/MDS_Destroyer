<?php
// Affichage des erreurs ?
if (!empty($Alert->get())) {
	foreach ($Alert->get() as $alert) {
		echo alert($alert['content'], ['color' => $alert['color']]);
	}

	echo BR . HR . BR;
	// J'ai affiché mes alerts, donc je les vide
	$Alert->reset();
}