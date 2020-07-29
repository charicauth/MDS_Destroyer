<?php
// Démarrage de la Session
session_start();
include('loader.php');

// $JoueursController = new JoueursController();
// $JoueursController existe, car créé dans loader.php
// $JoueursController contient bien une instance de JoueursController

// Traitement de TOUS les formulaires POST
if ($Request->exists(POST, CONTROLLER) and $Request->exists(POST, ACTION)) {

	$controllerName = ucfirst($Request->value(POST, CONTROLLER)) . 'Controller';
	// Formulaire : joueurs 
	// $controllerName => 'JoueursController'
	// $controllerName contient une chaine de caractères
	
	$action = 'process_' . $Request->value(POST, ACTION);
	// Formulaire : inscription
	// $action => process_inscription

	// Lancement de l'action, grâce aux variables-variables
	${$controllerName}->$action();
	// Prend la variable qui s'appelle $controllerName, qui s'appelle 'JoueursController', tu prends $JoueursController

	// ${$controllerName} => ${'JoueursController'} => $JoueursController

	// $JoueursController->process_inscription();
}
// ------------------------------------------------------------