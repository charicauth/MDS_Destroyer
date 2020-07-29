<?php
class Competence extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table (nouveau)
	public $table = 'competences';

	// ------ CONSTRUCTEUR ET METHODES ASSOCIEES ------------------------
	
	/**
	 * Constructeur
	 */
	public function __construct($id = null)
	{	
		// Connexion ORM
		$this->connect();

		// Table utilisée
		$this->setGlobalTable($this->table); // Modification

		if ($id != null) {
			$this->id = $id;
			$this->get($id);	
		}
	}
}