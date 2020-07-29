<?php
// Chargement de tous les fichiers 

// Constantes
	include('constantes.php');

// Controllers
	// Utils
	include('Controllers/Utils/dev_tools.php'); // Fonctions
	include('Controllers/Utils/security_tools.php'); // Fonctions
	include('Controllers/Utils/Request.php'); // Request
	include('Controllers/Utils/Alert.php'); // Alertes
	include('Controllers/Utils/Authentification.php'); // Authentification
	include('Controllers/Utils/Router.php'); // Router

// Models
	// ORM
	include('Models/ORM/ORM.php'); // Objet / Classe

	// Modèles
	include('Models/Famille.php');
	include('Models/Competence.php');
	include('Models/Personnage.php');
	include('Models/Combat.php');
	include('Models/Carte.php');
	include('Models/Joueur.php');
	include('Models/Objet.php');
	include('Models/Equipement.php');
	include('Models/Lignes_Equipements.php');
	include('Models/Lignes_Objets.php');

// Controllers
	// Controller générique
	include('Controllers/Controller.php');

	// Méthode permettant d'appeler l'action d'un controller
	include('Controllers/Utils/call_action.php');

	// Controllers
	include('Controllers/JoueursController.php');
	include('Controllers/PersonnagesController.php');
	include('Controllers/ObjetsController.php');
	include('Controllers/EquipementsController.php');

// Vues
	// Builders
	include('Views/Builders/bootstrap.php'); // Fonctions

// Pré-chargement des objets Utils
$Alert = new Alert();
$Request = new Request();
$Router = new Router();
$Auth = new Authentification();

// Pré-chargement des controllers
$JoueursController = new JoueursController();
$ObjetsController = new ObjetsController();
$EquipementsController = new EquipementsController();
$PersonnagesController = new PersonnagesController();