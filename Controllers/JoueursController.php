<?php
class JoueursController extends Controller
{
	public $Joueur;

	// Constructeur
	public function __construct()
	{
		$this->Joueur = new Joueur();
		parent::__construct();
	}

	// Vue Inscription
	public function view_signin()
	{
		$this->dejaConnecte();

		return [
			'_familles' => $this->Joueur->Personnage->Famille->liste()
		];
	}

	// Vue Connexion
	public function view_connect()
	{
		$this->dejaConnecte();
		
		return [];
	}

	// Traitement
	public function process_inscription()
	{
		// Pour le moment, aucune erreur
		$erreurs = [];

		// Traitement des données
		$prenom = clean($this->Request->value(POST, 'prenom'));

		// Le prenom ne doit pas être vide
		if (strlen($prenom) <= 2) {
			$erreurs[] = 'Le prénom ne doit pas être vide';
		}

		// Le nom ne doit pas être vide
		$nom = clean($this->Request->value(POST, 'nom'));
		if (strlen($nom) <= 2) {
			$erreurs[] = 'Le nom ne doit pas être vide';
		}

		// Le username ne doit pas être vide et doit être une adresse mail valide ET unique
		$username = clean($this->Request->value(POST, 'username'));
		$testUniciteMail = true;

		if (strlen($username) <= 4) {
			$erreurs[] = 'L\'adresse mail ne doit pas être vide';
			$testUniciteMail = false;
		}

		// filter_var
		if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
			$erreurs[] = 'L\'adresse mail n\'est pas au bon format';
			$testUniciteMail = false;
		}

		// Je teste l'unicité de mon mail seulement si le format du mail est bon
		if ($testUniciteMail and $this->Joueur->exist('username', $username, 'string')) {
			$erreurs[] = 'L\'adresse mail est déjà utilisée';
		}

		// Le mot de passe doit faire 8 caractères minimum
		$password = clean($this->Request->value(POST, 'password'));
		if (strlen($password) < 8) {
			$erreurs[] = 'Le mot de passe doit faire au moins 8 caractères';
		}

		// Le pseudo doit être unique
		$pseudo = clean($this->Request->value(POST, 'pseudo'));
		$testUnicitePseudo = true;

		if (strlen($pseudo) < 4) {
			$erreurs[] = 'Le pseudo doit faire au moins 4 caractères';
			$testUnicitePseudo = false;
		}

		// Je teste l'unicité de mon pseudo seulement si le format du pseudo est bon
		if ($testUnicitePseudo and $this->Joueur->exist('pseudo', $pseudo, 'string')) {
			$erreurs[] = 'Le pseudo est déjà utilisé';
		}

		// La classe doit correspondre à une famille niveau_min_accessible = 1
		$famille_id = clean($this->Request->value(POST, 'famille_id'));

		if (!$this->Joueur->Personnage->Famille->verifNiveau($famille_id, 1)) {
			$erreurs[] = 'Famille non disponible à l\'inscription';
		}

		if (empty($erreurs)) {
			// Aucune erreur donc je peux créer l'utilisateur (Joueur)
			// Et récupérer l'id de ce joueur nouvellement créé
			$idNouveauJoueur = $this->Joueur->add([
				'prenom' => $prenom,
				'nom' => $nom,
				'username' => $username,
				'password' => $password,
				'pseudo' => $pseudo
			]);

			// Une fois l'utilisateur créé, je peux créer son premier Personnage
			$nom_personnage = clean($this->Request->value(POST, 'nom_personnage'));

			$this->Joueur->Personnage->add([
				'joueur_id' => $idNouveauJoueur,
				'famille_id' => $famille_id,
				'nom' => $nom_personnage,
			]);

			// On initialise tous les champs sauf les équipements
			header('Location: index.php?page=dashboard&dir=joueurs');
			exit;
		}

		// Il y a des erreurs donc je retourne sur l'inscription

			// Je vais envoyer mes alerts en SESSION, d'une page à l'autre
			$this->Alert->reset();
			foreach ($erreurs as $erreur) {
				$this->Alert->add($erreur);
			}

		// Redirection
		header('Location: index.php?page=signin&dir=joueurs');
		exit;
	}

	public function process_connexion()
	{
		// Récupération des valeurs passées dans mon formulaire
		$username = clean($this->Request->value(POST, 'username'));
		$password = clean($this->Request->value(POST, 'password'));

		// Vérification du format des données
		$erreurs = [];

		if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
			$erreurs[] = 'Mauvais format pour l\'adresse mail';
		}

		if (strlen($password) < 8) {
			$erreurs[] = 'Mot de passe trop court';
		}

		if (!empty($erreurs)) {
			$this->Alert->reset();
			foreach ($erreurs as $erreur) {
				$this->Alert->add($erreur);
			}

			$this->Router->redirect('joueurs', 'connect');
		}

		// Faut que je vérifie que ces informations correspondent bien à un joueur existant

			// Controler que username et password vont bien ensemble
			$testConnexion = $this->Joueur->controleUsernamePassword($username, $password);

				// Si c'est OK 
				if ($testConnexion['success']) {
					// Je dois récupérer le joueur
					$idJoueur = $testConnexion['id'];

					// Et le mettre en session pour pouvoir le passer d'une page à une autre
					$joueur = $this->Joueur->getObject($idJoueur);

					$this->Authentification->setJoueurConnecte($joueur);

					// Se rediriger vers le dashboard
					$this->Router->redirect('joueurs', 'dashboard');
				}	

				// Si c'est pas OK
					// Alerte et redirection
					$this->Alert->reset();
					$this->Alert->add('Mauvaise combinaison Adresse Mail / Mot de passe');

					// Redirection
					$this->Router->redirect('joueurs', 'connect');
	}

	public function view_dashboard()
	{	
		$this->actionSecurisee();

		return [
			'_joueurConnecte' => $this->Authentification->getJoueurConnecte(),
			'_personnages' => $this->Joueur->Personnage->liste($this->Authentification->getId())
		];
	}

	public function process_deconnexion()
	{
		$this->Authentification->deconnexion();
		// Plus rien dans l'objet, plus rien en session

		// Redirection
		$this->Alert->reset();
		$this->Alert->add('Vous avez bien été déconnecté', SUCCESS);
		$this->Router->redirect('joueurs', 'connect');
	}

	public function view_inventaire()
	{	
		$this->actionSecurisee();

		return [
			'_joueurConnecte' => $this->Authentification->getJoueurConnecte(),
			'_inventaireObjets' => $this->Joueur->liste_inventaire_objets($this->Authentification->getId()),
			'_inventaireEquipements' => $this->Joueur->liste_inventaire_equipements($this->Authentification->getId())
		];
	}
}









