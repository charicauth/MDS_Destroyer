<?php
// Divers fonctions utilitaires pour sécuriser les données

// Clean, nettoie les données saisies par l'utilisateur
function clean($input)
{
	return htmlspecialchars($input);
}