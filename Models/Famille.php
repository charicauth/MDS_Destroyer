<?php
class Famille extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table
	public $table = 'familles'; // !! Renommer la table classes en familles !!
	
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

	/**
	 * Liste des familles
	 */
	public function liste($niveau = 1)
	{
		// Préparation requête
		$this->selectFields('id', 'nom');
		$this->selectWhere('niveau_min_accessible', $niveau, '=', 'integer');
		$this->selectOrder('nom', 'ASC');

		$familles = $this->select();

		$return = [];
		foreach ($familles as $famille) {
			$return[$famille['id']] = $famille['nom'];
		}

		// On veut un tableau sous la forme [1 => 'Nom Famille 1', 2 => 'Nom famille 2' ...]
		return $return;
	}

	/**
	 * Vérification du niveau d'une famille
	 */
	public function verifNiveau($familleId, $niveau)
	{
		$famillesDuNiveau = $this->liste($niveau);
		return isset($famillesDuNiveau[$familleId]);
	}

}