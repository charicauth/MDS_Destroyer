<?php
class Carte extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table
	public $table = 'cartes';
	
	// ------ CONSTRUCTEUR ET METHODES ASSOCIEES ------------------------

	/**
	 * Constructeur
	 */
	public function __construct($id = null)
	{	
		// Connexion ORM
		$this->connect();

		// Table utilisée
		$this->setGlobalTable($this->table);

		if ($id != null) {
			$this->get($id);	
		}
	}

	// ------- METHODES SPECIFIQUES --------------------------------------
}