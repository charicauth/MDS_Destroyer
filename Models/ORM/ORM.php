<?php
class ORM
{	
	// ------ ATTRIBUTS -------------------------------------------------

	/**
	 * Identifiants de connexion à la base de données
	 */

	// Dev (en ligne)
	private $host = 'localhost';
	private $db = 'ffrekbrzkn';
	private $user = 'ffrekbrzkn';
	private $pass = 'MR4qkR2zFT';

	// Local
	// private $host = 'localhost';
	// private $db = 'mds_game';
	// private $user = 'root';
	// private $pass = 'root';

	/**
	 * Connexion PDO stockée
	 */
	private $connexionPDO = null;

	/**
	 * Attributs pour gérer une requête SELECT
	 */
	private $selectTable = '';
	private $selectFields = '*';
	private $selectWhere = [];
	private $selectOrder = [];

	/**
	 * Attributs pour gérer une requête INSERT
	 */
	private $insertTable = '';
	private $insertFieldsAndValues = [];

	/**
	 * Attributs pour gérer une requête UPDATE
	 */
	private $updateTable = '';
	private $updateFieldsAndValues = [];
	private $updateWhere = [];

	/**
	 * Attributs pour gérer une requête DELETE
	 */
	private $deleteTable = '';
	private $deleteWHERE = [];

	// ------ CONSTRUCTEUR ET METHODES ASSOCIEES ------------------------

	/**
	 * Constructeur, paramétrage de la connexion
	 */
	public function __construct($table = null)
	{	
		$this->connect();

		// L'idée ici était de paramétrer tout de suite la table qui serait concernée
		if ($table != null) {
			$this->setGlobalTable($table); // On utilise la méthode ci-dessous
		}
	}

	/**
	 * Initialisation de la connexion PDO
	 */
	protected function connect()
	{
		// On se connecte et on stocke la connexion dans l'objet
		try {
			$this->connexionPDO = new PDO('mysql:host='. $this->host .';dbname='. $this->db .';charset=utf8', $this->user, $this->pass);
		} catch (Exception $e) {
		    die('[ORM 1] Erreur à la connexion : ' . $e->getMessage());
		}
	}

	/**
	 * Paramétrage de la table sur laquelle on va travailler
	 */
	public function setGlobalTable($table)
	{
		// Dans cette méthode là, pas besoin de tester si $table est null
		// Et on met à jour les attributs qui doivent contenir la table concernée
		$this->selectTable = $table;
		$this->insertTable = $table;
		$this->updateTable = $table;
		$this->deleteTable = $table;
	}

	/**
	 * Destructeur, fermeture de la connexion
	 */
	public function __destruct()
	{	
		$this->connexionPDO = null;
	}

	// ------- METHODES POUR REQUETE SELECT -------------------------------

	/**
	 * Initialisation d'une requete SELECT
	 */
	public function selectInit($table = null)
	{
		// On attribue la table si besoin
		if ($table != null) {
			$this->selectTable = $table;
		}

		// On peut vérifier que selectTable est bien complétée, c'est à dire que la fonction setGlobalTable a été utilisée si $table n'était pas renseigné
		if ($this->selectTable == '') {
			die ('[ORM 2] Aucune table pour la requête select');
		}

		// On reset les attributs, on reprend donc les valeurs définies au début
		$this->selectFields = '*';
		$this->selectWhere = [];
		$this->selectOrder = [];
	}

	/**
	 * Paramétrage des champs à sélectionner
	 */
	public function selectFields()
	{
		// Récupération des valeurs passées en paramètres
		$args = func_get_args();
		
		// $args va lire directement les paramètres passés à la fonction
		// Si j'appelle selectFields(), $args vaut []
		// Si j'appelle selectFields('param1', 'param2'), $args vaut ['param1', 'param2']

		// On travaille sur l'attribut selectFields qui contiendra : 
		// soit '*'
		// soit un tableau des champs souhaités
		if (!empty($args)) {
			$this->selectFields = $args;
		}

		// Pas de else, puisque par défaut selectFields vaut déjà "*"
	}

	/**
	 * Paramétrage des conditions WHERE de la requete
	 * $field => champ sur lequel s'applique la condition
	 * $value => valeur à tester
	 * $operator => opérateur de comparaison
	 * $type => pour préparer les requêtes indiquer le type de valeur attendue
	 */
	public function selectWhere($field, $value, $operator = '=', $type = 'string')
	{
		// On travaille sur l'attribut selectWhere qui sera donc un tableau associatif
		
		// Il y avait ici une subtilité sur $type
		// Puisque PDO attend plutot des choses genre PDO::PARAM_STR pour un type
		// On peut donc créer une méthode qui controle le type et retourne sa "valeur PDO"
		$typeControle = $this->controlType($type);

		// A chaque appel, j'incrémente mon tableau
		$this->selectWhere[] = [
			'field' => $field,
			'value' => $value,
			'operator' => $operator,
			'type' => $typeControle
		];
	}

	
	/**
	 * Paramétrage de l'ordre de sortie des champs
	 * $field => champ sur lequel s'applique le tri
	 * $direction => ASC ou DESC
	 */
	public function selectOrder($field, $direction = 'ASC')
	{
		// On travaille sur l'attribut selectOrder qui sera donc un tableau associatif
		
		// On peut avoir, comme pour le type, un controle sur la direction
		$directions = ['ASC', 'DESC'];
		if (!in_array($direction, $directions)) {
			die('[ORM 4] Mauvaise direction');
		}

		// A chaque appel, j'incrémente mon tableau
		$this->selectOrder[] = [
			'field' => $field,
			'direction' => $direction
		];
	}

	/**
	 * On génère la requête de manière sécurisée et on retourne les résultats
	 * 3 types possibles :
	 * all => fetchAll => tous les résultats
	 * first => fetch => le premier résultat
	 * count => rowCount => le nombre de résultat
	 */
	public function select($type = 'all')
	{
		// Bien structurer la création de la requête et enfin son appel
		// Il faut faire appel aux méthodes et attributs liés

		// Méthode qui doit faire facile une cinquantaine de lignes

		// Je commence à construire ma requête ---------------------
		$sql = 'SELECT ';

		// Quels champs je veux ------------------------------------
		if (is_array($this->selectFields)) {
			$sql .= implode(', ', $this->selectFields); // Je transforme [c1, c2] en "c1, c2"
		} else {
			$sql .= '*'; // Si ce n'est pas un tableau, ça vaut alors forcément "*"
		}

		// Dans quelle table ---------------------------------------
		$sql .= ' FROM ' . $this->selectTable;

		// Conditions WHERE ----------------------------------------
		if (!empty($this->selectWhere)) {
			$sql .= $this->writeWhere($this->selectWhere);
		}

		// Direction ORDER -----------------------------------------
		if (!empty($this->selectOrder)) {
			$sql .= ' ORDER BY ';

			// Je vais stocker mes directions dans un tableau
			$directions = [];

			foreach ($this->selectOrder as $order) {
				$directions[] = $order['field'] . ' ' . $order['direction'];
			}

			$sql .= implode(', ', $directions);
		}

		// Préparation de la requête -------------------------------
		$query = $this->connexionPDO->prepare($sql);

		// Re-boucle sur les conditions where pour "binder" les valeurs proprement
		if (!empty($this->selectWhere)) {
			foreach ($this->selectWhere as $where) {
				$query->bindValue(':' . $where['field'], $where['value'], $where['type']);
			}
		}

		// Execution de la requete ---------------------------------
		$success = $query->execute();

		if (!$success) {
			echo '<pre>';
			print_r($query->errorInfo());
			echo '</pre>';
			die('[ORM 5] Erreur à l\'execution de la requête => ' . $sql);
		}

		// Retour selon le type choisi en paramètre ----------------
		switch ($type) {

			case 'first':
				$result = $query->fetch();

				if ($result === false) {
					return [];
				}

				return $result;
			break;

			case 'count':
				return $query->rowCount();
			break;

			case 'all':
				return $query->fetchAll();
			break;

			default:
				die('[ORM 6] Erreur type de sortie SELECT');
			break;
		}
	}

	// ------- METHODES POUR REQUETE INSERT -------------------------------

	/**
	 * Initialisation d'une requete INSERT
	 */
	public function insertInit($table = null)
	{
		if ($table != null) {
			$this->insertTable = $table;
		}

		if ($this->insertTable == '') {
			die ('[ORM 10] Aucune table pour la requête insert');
		}

		// Reset
		$this->insertFieldsAndValues = [];
	}

	/**
	 * Gestion des champs à ajouter et leur valeur
	 */
	public function insertFieldsAndValues($field, $value, $type = 'string')
	{
		$typeControle = $this->controlType($type);

		$this->insertFieldsAndValues[] = [
			'field' => $field,
			'value' => $value,
			'type' => $typeControle
		];
	}

	/**
	 * Construction et Execution de la requête INSERT
	 */
	public function insert()
	{
		// Détection d'erreurs ------------------------------------------
		if ($this->insertTable == '') {
			die('[ORM 11] Table non précisée');
		}

		if (empty($this->insertFieldsAndValues)) {
			die('[ORM 12] Rien à insérer');
		}
		// --------------------------------------------------------

		// Construction de la requête
		$sql = 'INSERT INTO ' . $this->insertTable . ' (';

		$fields = [];
		$bindFields = [];
		foreach ($this->insertFieldsAndValues as $fav) {
			$fields[] = $fav['field'];
			$bindFields[] = ':' . $fav['field'];
		}

		$sql .= implode(', ', $fields);
		$sql .= ') VALUES (';
		$sql .= implode(', ', $bindFields);
		$sql .= ')';

		// Préparation de la requête -------------------------------
		$query = $this->connexionPDO->prepare($sql);

		// Re-boucle sur les conditions where pour "binder" les valeurs proprement
		foreach ($this->insertFieldsAndValues as $fav) {
			$query->bindValue(':' . $fav['field'], $fav['value'], $fav['type']);
		}

		// Execution de la requete ---------------------------------
		$success = $query->execute();

		if (!$success) {
			echo '<pre>';
			print_r($query->errorInfo());
			echo '</pre>';
			die('[ORM 13] Erreur à l\'execution de la requête => ' . $sql);
		}

		return true;
	}

	// ------- METHODES POUR REQUETE UPDATE -------------------------------

	/**
	 * Initialisation d'une requete UPDATE
	 */
	public function updateInit($table = null)
	{
		if ($table != null) {
			$this->updateTable = $table;
		}

		if ($this->updateTable == '') {
			die ('[ORM 20] Aucune table pour la requête update');
		}

		// Reset
		$this->updateFieldsAndValues = [];
		$this->updateWhere = [];
	}

	/**
	 * Champs à changer et valeurs associées
	 */
	public function updateFieldsAndValues($field, $value, $type)
	{
		$typeControle = $this->controlType($type);

		$this->updateFieldsAndValues[] = [
			'field' => $field,
			'value' => $value,
			'type' => $typeControle
		];
	}

	/**
	 * Gestion du Where
	 */
	public function updateWhere($field, $value, $operator = '=', $type = 'string')
	{
		$typeControle = $this->controlType($type);

		$this->updateWhere[] = [
			'field' => $field,
			'value' => $value,
			'operator' => $operator,
			'type' => $typeControle
		];
	}

	/**
	 * Construction de la requête UPDATE
	 */
	public function update($forceNoWhere = false)
	{
		// Sécurité pour ne pas oublier le WHERE
		if (empty($this->updateWhere) and !$forceNoWhere) {
			die ('[ORM 22] Il manque le Where');
		}

		$sql = 'UPDATE ' . $this->updateTable . ' SET ';

		$updates = [];
		foreach ($this->updateFieldsAndValues as $fav) {
			$updates[] = $fav['field'] . ' = ' .  ':' . $fav['field'];
		}
		// effet_valeur = :effet_valeur ....

		$sql .= implode(', ', $updates);

		// Conditions WHERE ----------------------------------------
		if (!empty($this->updateWhere)) {
			$sql .= $this->writeWhere($this->updateWhere, 'where_');
		}

		// Préparation de la requête -------------------------------
		$query = $this->connexionPDO->prepare($sql);

		// "binder" les valeurs de l'update
		foreach ($this->updateFieldsAndValues as $fav) {
			$query->bindValue(':' . $fav['field'], $fav['value'], $fav['type']);
		}

		// Re-boucle sur les conditions where pour "binder" les valeurs proprement
		if (!empty($this->updateWhere)) {
			foreach ($this->updateWhere as $where) {
				$query->bindValue(':where_' . $where['field'], $where['value'], $where['type']);
			}
		}

		// Execution de la requete ---------------------------------
		$success = $query->execute();

		if (!$success) {
			echo '<pre>';
			print_r($query->errorInfo());
			echo '</pre>';
			die('[ORM 21] Erreur à l\'execution de la requête => ' . $sql);
		}

		return true;
	}



	// ------- METHODES POUR REQUETE DELETE -------------------------------

	/**
	 * Initialisation d'une requete DELETE
	 */
	public function deleteInit($table = null)
	{
		if ($table != null) {
			$this->deleteTable = $table;
		}

		if ($this->deleteTable == '') {
			die ('[ORM 30] Aucune table pour la requête delete');
		}

		// Reset
		$this->deleteWhere = [];
	}

	/**
	 * Gestion du Where
	 */
	public function deleteWhere($field, $value, $operator = '=', $type = 'string')
	{
		$typeControle = $this->controlType($type);

		$this->deleteWhere[] = [
			'field' => $field,
			'value' => $value,
			'operator' => $operator,
			'type' => $typeControle
		];
	}

	/**
	 * Construction de la requête DELETE
	 */
	public function delete($forceNoWhere = false)
	{
		// Sécurité pour ne pas oublier le WHERE
		if (empty($this->deleteWhere) and !$forceNoWhere) {
			die ('[ORM 31] Il manque le Where');
		}

		$sql = 'DELETE FROM ' . $this->deleteTable;

		// Conditions WHERE ----------------------------------------
		if (!empty($this->deleteWhere)) {
			$sql .= $this->writeWhere($this->deleteWhere);
		}

		// Préparation de la requête -------------------------------
		$query = $this->connexionPDO->prepare($sql);

		// Re-boucle sur les conditions where pour "binder" les valeurs proprement
		if (!empty($this->deleteWhere)) {
			foreach ($this->deleteWhere as $where) {
				$query->bindValue(':' . $where['field'], $where['value'], $where['type']);
			}
		}

		// Execution de la requete ---------------------------------
		$success = $query->execute();

		if (!$success) {
			echo '<pre>';
			print_r($query->errorInfo());
			echo '</pre>';
			die('[ORM 32] Erreur à l\'execution de la requête => ' . $sql);
		}

		return true;
	}

	// ------- METHODES GENERIQUES ----------------------------------------

	/**
	 * Controle du type
	 */
	private function controlType($type)
	{	
		// Tableau des types PDO autorisés
		$pdoMap = [ 
			'integer' => PDO::PARAM_INT,
			'float' => PDO::PARAM_STR,
			'boolean' => PDO::PARAM_BOOL,
			'string' => PDO::PARAM_STR,
			'text' => PDO::PARAM_STR
		];

		if (!isset($pdoMap[$type])) {
			die('[ORM 3] Ce type n\'existe pas');
		}

		return $pdoMap[$type];
	}

	/*
	 * Génération de la condition WHERE
	 */
	private function writeWhere($wheres, $prefix = '')
	{
		$part = ' WHERE ';

		// Je vais stocker mes conditions écrites dans un tableau
		$conditions = [];

		foreach ($wheres as $where) {
			$conditions[]= $where['field'] . ' ' . $where['operator'] . ' :' . $prefix . $where['field'];
			// Ca peut me donner "name = :name"
			// Notez les ":"
			// J'utiliserais value et type + tard
		}

		// Mes conditions seront séparées par des AND, on pourrait rajouter des options à la fonction pour pouvoir faire des OR
		$part .= implode(' AND ', $conditions); // Même principe que pour selectFields

		return $part;
	}

	// ------- METHODES LIEES AUX MODELES ----------------------------------------

	/**
	 * Populate des modèles
	 */
	public function populate($object)
	{
		foreach ($object as $field => $value) {
			if (is_numeric($field)) {
				continue;
			}

			$this->$field = $value;
		}
	}

	/**
	 * Récupération du modèle et populate des attributs
	 */
	public function get($id)
	{
		// On utilise les méthodes de l'ORM
		return $this->getByField('id', $id, 'integer');
	}

	/**
	 * Récupération du modèle selon un champ précis
	 */
	public function getByField($field, $value, $type = 'string')
	{
		// On utilise les méthodes de l'ORM
		$this->selectInit();
		$this->selectWhere($field, $value, '=', $type);

		$object = $this->select('first');

		if (!empty($object)) {
			$this->populate($object);
			return true;
		}

		return false;
	}

	/**
	 * Récupération du modèle (sans le populate)
	 * Retour du tableau complet
	 */
	public function getObject($id)
	{
		// On utilise les méthodes de l'ORM
		return $this->getObjectByField('id', $id, 'integer');
	}

	/**
	 * Récupération du modèle selon un champ précis
	 */
	public function getObjectByField($field, $value, $type = 'string')
	{
		// On utilise les méthodes de l'ORM
		$this->selectInit();
		$this->selectWhere($field, $value, '=', $type);

		return $this->select('first');
	}

	/**
	 * Retourne true si une ligne correspondante existe
	 */
	public function exist($field, $value, $type, $table = '')
	{
		// On redéfinit la table si besoin
		if ($table != '') {
			$this->selectInit($table);
		}

		// On va faire un select
		$this->selectWhere($field, $value, '=', $type);

		// Retourne true s'il y a au moins 1 valeur
		$resultat = $this->select('count');
		if ($resultat >= 1) {
			return true;
		}

		// Retourne false sinon
		return false;
	}


}