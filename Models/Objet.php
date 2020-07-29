<?php
class Objet extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table
	public $table = 'objets';
	
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

	public function liste($joueur_id)
	{
		$this->selectInit('joueurs');
		$this->selectFields('niveau');
		$this->selectWhere('id', $joueur_id);
		$niveau_joueur = $this->select('first');


		$this->selectInit('objets');
		$this->selectFields();
		$this->selectWhere('niveau_min_accessible', $niveau_joueur['niveau'], '<=');

		return $this->select();
	}

	public function getById($objet_id){
		
		$this->selectInit();
		$this->selectFields();
		$this->selectWhere('id', $objet_id);

		return $this->select('first');
	}

	public function objetPossede($objet_id, $joueur_id)
	{
		$this->selectInit('lignes_objets');
		$this->selectFields('quantite');
		$this->selectWhere( 'joueur_id', $joueur_id);
		$this->selectWhere('objet_id', $objet_id);
		$quantite_existante = $this->select('first');
		if(empty($quantite_existante))
		{
			return false;
		}
		return $quantite_existante['quantite'];
	}

	public function achatUpdate($objet_id, $joueur_id, $quantite, $quantite_existante)
	{
		$this->updateInit('lignes_objets');
		$this->updateFieldsAndValues('quantite', $quantite_existante + $quantite, 'integer');
		$this->updateWhere('joueur_id', $joueur_id);
		$this->updateWhere('objet_id', $objet_id);
		$this->update();
	}

	public function addObjet($quantite, $objet_id, $joueur_id)
	{
		$this->insertInit('lignes_objets');
		$this->insertFieldsAndValues('joueur_id', $joueur_id, 'integer');
		$this->insertFieldsAndValues('objet_id', $objet_id, 'integer');
		$this->insertFieldsAndValues('quantite', $quantite, 'integer');
		$this->insert();
	}
}