<?php
class Lignes_Equipements extends ORM
{
	// ------ ATTRIBUTS -------------------------------------------------
	
	// Nom de la table
	public $table = 'lignes_equipements';
	
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
    public function liste_inventaire($id_joueur)
	{
        //Je vais chercher la listes des id des objets de mon joueur
        $ids = $this->get_Equipements_id($id_joueur);

        //J'initialise mon tableau vide
        $return = [];

        /**je parcours le tableau et je vais chercher dans ma base de donnée tous les objets correspondants
         * Ensuite je les rangent dans un tableau
        **/
        foreach($ids as $id){

            // Préparation de ma requête
            $this->selectInit('equipements');
            $this->selectFields('id', 'nom');
            $this->selectWhere('id', $id, '=', 'integer');

            $select = $this->select();
            $return[$id] = $select['nom'];
        }

        return $return;
    }
    
    public function get_Equipements_id($id)
    {
        //Préparation de ma requête
        $this->selectInit();
        $this->selectFields('equipement_id');
        $this->selectWhere('joueur_id', $id, '=', 'integer');
        
        $return = $this->select();
        return $return;
    }

    public function liste(){
        
        $this->selectInit();
		$this->selectFields('id', 'nom', 'famille_id');
		$this->selectWhere('joueur_id', $idJoueur, '=', 'integer');

		return $this->select();
    }
}