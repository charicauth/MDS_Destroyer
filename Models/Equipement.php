<?php
class Equipement extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table
	public $table = 'equipements';
	
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


		$this->selectInit('equipements');
		$this->selectFields();
		$this->selectWhere('niveau_min_accessible', $niveau_joueur['niveau'], '<=');

		return $this->select();
	}

	public function getById($equipement_id){
		
		$this->selectInit('equipements');
		$this->selectFields();
		$this->selectWhere('id', $equipement_id);

		return $this->select('first');
	}

	public function addequipement($equipement_id, $joueur_id)
	{
		$this->insertInit('lignes_equipements');
		$this->insertFieldsAndValues('joueur_id', $joueur_id, 'integer');
		$this->insertFieldsAndValues('equipement_id', $equipement_id, 'integer');
		$this->insert();
	}

	public function getEquipementByLigneEquipementId($idLigne)
    {
		$this->selectInit('lignes_equipements');
		$this->selectFields('equipement_id');
		$this->selectWhere('id', $idLigne);
		return $this->select('first');
	}
	
	public function listeEquipementsParEmplacement($idJoueur, $emplacement) {
		
		$return = [];

		$this->selectInit('lignes_equipements');
		$this->selectFields();
		$this->selectWhere('joueur_id', $idJoueur);
		$listeEquipementsJoueur = $this->select();


		foreach($listeEquipementsJoueur as $equipementJoueur) {
			if($equipementJoueur['equipe'] == 0){
				$equipementDetailsJoueur = $this->getById($equipementJoueur['equipement_id']);
				if($equipementDetailsJoueur['emplacement'] == $emplacement){
					$return[] = $equipementDetailsJoueur;
				}
			}
		}
		return $return;
	}

	public function equipementPossede($equipement_id, $joueur_id)
	{
		$this->selectInit('lignes_equipements');
		$this->selectWhere('joueur_id', $joueur_id);
		$this->selectWhere('equipement_id', $equipement_id);
		$existe = $this->select('first');
		if(empty($existe))
		{
			return false;
		}
		return true;
	}

	public function equipementDisponible($equipement_id, $joueur_id)
	{
		$this->selectInit('lignes_equipements');
		$this->selectFields();
		$this->selectWhere( 'joueur_id', $joueur_id);
		$this->selectWhere('equipement_id', $equipement_id);
		$equipements = $this->select();
		if(empty($equipements))
		{
			return false;
		}
		foreach($equipements as $equipement){
			if($equipement['equipe'] == 0){
				return $equipement['id'];
				die();
			}
		}
		return false;
	}

	public function equipementBonEmplacement($equipement_id, $emplacement)
	{
		$this->selectInit('equipements');
		$this->selectFields('emplacement');
		$this->selectWhere('id', $equipement_id);
		$equipement = $this->select('first');
		if(empty($equipement))
		{
			return false;
		} elseif ($equipement['emplacement'] != $emplacement) {
			return false;
		}
		
		return true;
	}

	public function updateEquipement($personnage_id, $id_ligne_equipement, $emplacement)
	{
		// ajouter l'id de la ligne equipement au personnage
		$this->updateInit('personnages');
		$this->updateFieldsAndValues('emplacement_'.$emplacement.'_id', $id_ligne_equipement, 'integer');
		$this->updateWhere('id', $personnage_id);
		$this->update();
		
		// update equipe de ligne_equipement
		$this->updateInit('lignes_equipements');
		$this->updateFieldsAndValues('equipe', 1, 'integer');
		$this->updateWhere('id', $id_ligne_equipement);
		$this->update();
	}

	public function deleteEquipement($personnage_id, $id_ligne_equipement, $emplacement)
	{
		// ajouter l'id de la ligne equipement au personnage
		$this->updateInit('personnages');
		$this->updateFieldsAndValues('emplacement_'.$emplacement.'_id', 0, 'integer');
		$this->updateWhere('id', $personnage_id);
		$this->update();
		
		// update equipe de ligne_equipement
		$this->updateInit('lignes_equipements');
		$this->updateFieldsAndValues('equipe', 0, 'integer');
		$this->updateWhere('id', $id_ligne_equipement);
		$this->update();
	}

		
}