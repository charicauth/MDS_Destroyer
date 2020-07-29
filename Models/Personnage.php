<?php
class Personnage extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table
	public $table = 'personnages';
	public $Lignes_Objets;
    public $Lignes_Equipements;

	// Modèle(s) lié(s)
	public $Famille;
	public $Equipement;
	
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

		// Modèle(s) lié(s)
		$this->Famille = new Famille();
		$this->Equipement = new Equipement();
		$this->Lignes_Equipements = new Lignes_Equipements();
		$this->Lignes_Objets = new Lignes_Objets();
	}

	// ------- METHODES SPECIFIQUES --------------------------------------
	public function add($values)
	{
		$this->insertInit();

		$this->Famille->get($values['famille_id']);

		// Infos du joueur et de la famille
		$this->insertFieldsAndValues('famille_id', $values['famille_id']);
		$this->insertFieldsAndValues('nom', $values['nom']); // Champ unique
		$this->insertFieldsAndValues('joueur_id', $values['joueur_id']);

		// Infos liées à la famille
		$this->insertFieldsAndValues('pv', rand($this->Famille->pv_min, $this->Famille->pv_max));
		$this->insertFieldsAndValues('mp', rand($this->Famille->mp_min, $this->Famille->mp_max));
		$this->insertFieldsAndValues('endurance', rand($this->Famille->endurance_min, $this->Famille->endurance_max));
		$this->insertFieldsAndValues('puissance', rand($this->Famille->puissance_min, $this->Famille->puissance_max));
		$this->insertFieldsAndValues('defense', rand($this->Famille->defense_min, $this->Famille->defense_max));
		$this->insertFieldsAndValues('agilite', rand($this->Famille->dagilite_min, $this->Famille->agilite_max));
		$this->insertFieldsAndValues('magie', rand($this->Famille->magie_min, $this->Famille->magie_max));
		$this->insertFieldsAndValues('defense_magie', rand($this->Famille->defense_magie_min, $this->Famille->defense_magie_max));

		return $this->insert();
	}

	// Liste des personnages d'un joueur
	public function liste($idJoueur)
	{
		$this->selectInit();
		$this->selectFields('id', 'nom', 'famille_id');
		$this->selectWhere('joueur_id', $idJoueur, '=', 'integer');

		return $this->select();
	}

	// Récupération complète d'un personnage, avec données liées
	public function getFull($idPersonnage)
	{
		// Infos du personnage
		$personnage = $this->getObject($idPersonnage);

		// Si le personnage n'existe pas, les récupérations suivantes ne marcheront pas
		// On sort donc tout de suite
		if (empty($personnage)) {
			return $personnage;
		}

		// Infos de la famille
		$personnage['famille'] = $this->Famille->getObject($personnage['famille_id']);
		
		// Infos des équipements
		$personnage['tete'] = $this->Equipement->getObject($personnage['emplacement_tete_id']);
		$personnage['torse'] = $this->Equipement->getObject($personnage['emplacement_torse_id']);
		$personnage['jambes'] = $this->Equipement->getObject($personnage['emplacement_jambes_id']);
		$personnage['arme'] = $this->Equipement->getObject($personnage['emplacement_arme_id']);

		return $personnage;
	}

	public function getById($idPersonnage)
	{
		$this->selectInit('personnages');
		$this->selectFields();
		$this->selectWhere('id', $idPersonnage, '=', 'integer');

		return $this->select('first');
	}
}