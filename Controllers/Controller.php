<?php
class Controller
{
	public $Request;
	public $Alert;
	public $Authentification;
	public $Router;

	public function __construct()
	{
		$this->Request = new Request();
		$this->Alert = new Alert();
		$this->Authentification = new Authentification();
		$this->Router = new Router();
	}

	// Sécurité, si je ne suis pas connecté, je retourne sur la page de connexion
	public function actionSecurisee()
	{
		if (!$this->Authentification->isConnected()) {

			$this->Alert->reset();
			$this->Alert->add('Vous devez être connecté pour accéder à cette page');

			$this->Router->redirect('joueurs', 'connect');
		}
	}

	// Si déjà connecté, je renvoie tout de suite sur le dashboard
	public function dejaConnecte()
	{
		if ($this->Authentification->isConnected()) {

			$this->Alert->reset();
			$this->Alert->add('Vous êtes déjà connecté !', SUCCESS);

			$this->Router->redirect('joueurs', 'dashboard');
		}
	}
}