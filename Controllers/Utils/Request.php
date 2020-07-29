<?php
// Lecture et écriture des variables $_GET, $_POST, $_SESSION
class Request
{	
	// Attributs
	private $session = [];
	private $get = [];
	private $post = [];

	// Constructeur
	public function __construct()
	{
		// Lecture des variables $_GET, $_POST, $_SESSION
		foreach ($_GET as $name => $value) {
			$this->__save(GET, $name, $value);
		}

		// Lecture des variables $_GET, $_POST, $_SESSION
		foreach ($_POST as $name => $value) {
			$this->__save(POST, $name, $value);
		}

		// Lecture des variables $_GET, $_POST, $_SESSION
		foreach ($_SESSION as $name => $value) {
			$this->__save(SESSION, $name, $value);
		}
	}

	// Recherche de la valeur d'une variable
	public function value($type, $name)
	{
		// Sécurité
		if (!$this->exists($type, $name)) {
			return null;
		}

		switch ($type) {

			case GET:
				return $this->get[$name];
			break;

			case POST:
				return $this->post[$name];
			break;

			case SESSION:
				return $this->session[$name];
			break;
		}

		return null;
	}

	// Test de l'existence d'une variable
	public function exists($type, $name)
	{
		switch ($type) {

			case GET:
				return isset($this->get[$name]);
			break;

			case POST:
				return isset($this->post[$name]);
			break;

			case SESSION:
				return isset($this->session[$name]);
			break;
		}

		return false;
	}

	// Sauvegarde des informations
	private function __save($type, $name, $value)
	{
		switch ($type) {

			case GET:
				$this->get[$name] = $value;
			break;

			case POST:
				$this->post[$name] = $value;
			break;

			case SESSION:
				$this->session[$name] = $value;
			break;
		}
	}

	// Utilisable seulement pour les variables de Session
	public function write($name, $value)
	{
		$_SESSION[$name] = $value;
		$this->__save(SESSION, $name, $value);
	}

	// Utilisable seulement pour les variables de Session
	public function delete($name)
	{
		unset($_SESSION[$name]);
		unset($this->session[$name]);
	}
}