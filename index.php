<?php
// Démarrage de la Session
session_start();
include('loader.php');

// Page par défaut
$page = 'start';
$dir = '';

// Layout par défaut
$layout = 'default';

// Si une page est passée en paramètre, alors on prend celle la
if ($Request->exists(GET, 'page')) {
	$page = $Request->value(GET, 'page');
}

if ($Request->exists(GET, 'dir')) {
	$dir = $Request->value(GET, 'dir');
}

// Sécurité sur l'existence de la vue
if (!file_exists('Views' . '/' . ucfirst($dir) . '/' . $page . '.php')) {
	die('Erreur fatale, page inexistante');
}

// Appel de l'action correspondante dans le controller, pour charger les éventuelles variables
if ($dir != '') {
	$data = call_action($dir, $page);
	foreach ($data as $name => $value) {
		${$name} = $value;
	}
}

// Haut de page
include('Views/Layouts/' . $layout . '_top.php');

// Contenu de la page
include('Views' . '/' . ucfirst($dir) . '/' . $page . '.php');

// Bas de la page
include('Views/Layouts/' . $layout . '_bottom.php');