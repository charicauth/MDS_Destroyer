<?php
class Joueur extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table
	public $table = 'joueurs';

	// Modèle(s) lié(s)
	public $Personnage;
	
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
		$this->Personnage = new Personnage();
	}

	// ------- METHODES SPECIFIQUES --------------------------------------
	public function add($values)
	{
		$this->insertInit();

		$this->insertFieldsAndValues('prenom', $values['prenom']);
		$this->insertFieldsAndValues('nom', $values['nom']);
		$this->insertFieldsAndValues('username', $values['username']); // Champ unique

		// Le mot de passe doit être enregistré crypté => utiliser la méthode "md5"
		$this->insertFieldsAndValues('password', md5($values['password']));

		$this->insertFieldsAndValues('pseudo', $values['pseudo']); // Champ unique
		$this->insertFieldsAndValues('created', date('Y-m-d H:i:s'));

		if (!$this->insert()) {
			return false; // S'il y a un problème à l'insertion
		}

		// Récupération id nouvellement créé
		$this->getByField('username', $values['username']);
		return $this->id;
	}

	/**
	 * Retourne un tableau de résultat
	 * Si c'est bon je retourne l'id, sinon je retourne false
	 */
	public function controleUsernamePassword($username, $password)
	{
		// Postulat de départ
		$success = false;
		$id = null;

		// Controle par une seule requête
		$this->selectInit($this->table);
		$this->selectWhere('username', $username, '=', 'string');
		$this->selectWhere('password', md5($password), '=', 'string');
		$this->selectFields('id');

		$joueur = $this->select('first');

		if (!empty($joueur)) {
			$success = true;
			$id = $joueur['id'];
		}

		return [
			'success' => $success,
			'id' => $id // si true, alors on a l'id du joueur, null sinon
		];
	}

	public function updateSolde($joueur_id, $valeur){
		
		$this->selectInit();
		$this->selectFields('solde');
		$this->selectWhere('id', $joueur_id);
		$solde = $this->select('first');

		$solde = $solde['solde'] - $valeur;
		$this->updateInit();
		$this->updateFieldsAndValues('solde', $solde, 'integer');
		$this->updateWhere('id', $joueur_id);
		$this->update();

		$_SESSION[SESSION_JOUEUR_CONNECTE]['solde'] = $solde;

		return true;
	}

	public function liste_inventaire_objets($joueur_id){
		$this->selectInit('lignes_objets');
		$this->selectFields();
		$this->selectWhere('joueur_id', $joueur_id);
		return $this->select();
	}

	public function liste_inventaire_equipements($joueur_id){
		$this->selectInit('lignes_equipements');
		$this->selectFields();
		$this->selectWhere('joueur_id', $joueur_id);
		return $this->select();
	}
}