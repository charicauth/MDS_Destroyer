<?php
class Authentification
{
	// Par défaut, je n'ai pas de joueur connecté
	private $connecte = false;

	// Par défaut, je n'ai pas de joueur connecté, donc pas d'info
	private $joueurConnecte = [];

	private $Request;

	// Constructeur
	public function __construct()
	{
		// Je vais aller checker mes sessions
		$this->Request = new Request();

		// A la construction, je vérifie que j'ai un joueur connecté
		$this->checkJoueurConnecte();
	}

	// CE QUI SE PASSE A LA CONNEXION ------------

	// 
	public function setJoueurConnecte($joueur)
	{	
		foreach ($joueur as $field => $value) {
			if (is_numeric($field)) {
				continue;
			}

			if ($field == 'password') {
				continue;
			}

			$this->joueurConnecte[$field] = $value;
		}

		// On met tout en Session
		$this->connecte = true;
		$this->write();
	}

	// J'écris les valeurs en session
	private function write()
	{
		$this->Request->write(SESSION_JOUEUR_CONNECTE, $this->joueurConnecte);
		$this->Request->write(SESSION_CONNECTE, $this->connecte);
	}

	// -----------

	// CE QUI SE PASSE POUR TOUTES LES ACTIONS

	private function checkJoueurConnecte()
	{
		if ($this->Request->exists(SESSION, SESSION_CONNECTE) and $this->Request->value(SESSION, SESSION_CONNECTE) === true) {

			// J'ai bien un joueur de connecté
			$this->connecte = true;
			$this->joueurConnecte = $this->Request->value(SESSION, SESSION_JOUEUR_CONNECTE);
		}
	}

	// Méthodes publiques
	public function isConnected()
	{
		return $this->connecte;
	}

	public function getJoueurConnecte()
	{
		return $this->joueurConnecte;
	}

	public function getId()
	{
		return $this->joueurConnecte['id'];
	}

	public function getSolde()
	{
		return $this->joueurConnecte['solde'];
	}

	public function getNiveau()
	{
		return $this->joueurConnecte['niveau'];
	}

	public function getPseudo()
	{
		return $this->joueurConnecte['pseudo'];
	}

	// Déconnexion - détruire les données en session
	public function deconnexion()
	{	
		// Je reset mes attributs
		$this->connecte = false;
		$this->joueurConnecte = [];

		// Je détruis mes sessions
		$this->Request->delete(SESSION_CONNECTE);
		$this->Request->delete(SESSION_JOUEUR_CONNECTE);
	}
}