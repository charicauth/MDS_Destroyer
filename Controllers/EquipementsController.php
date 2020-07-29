<?php
class EquipementsController extends Controller
{
	public $equipement;

	// Constructeur
	public function __construct()
	{
		$this->equipement = new Equipement();
		parent::__construct();
    }

    public function view_shop()
    {
        //il faut être connecté
		$this->actionSecurisee();

		$idJoueur = $this->Authentification->getId();

         // Récupération de l'id passé en paramètre
      
        return [
            '_equipements' => $this->equipement->liste($idJoueur)
            ];
    }
    
    public function process_achat()
    {
		$idJoueur = $this->Authentification->getId();
		$Joueur = new Joueur();
        $erreurs = [];
        $success = '';

        
        $quantite = 1 * clean($this->Request->value(POST, 'quantite'));
        //verification que quantite n'est pas vide
        if(strlen($quantite) <= 0) {
			$erreurs[] = 'Pas de quantite rentré';
		}
        //verifivation que quantite est un integer
        if(!is_int($quantite)){
            $erreurs[] = 'quantité doit être un chiffre';
        }

        $equipement_id = clean($this->Request->value(POST, 'equipement_id'));

		//verification que l'equipement existe
	
		$equipement_exist = $this->equipement->getById($equipement_id);

		if(empty($equipement_exist))
		{
			$erreurs[] = 'l\'equipement n\'existe pas ';
			
		}

		//verification que le solde est ok pour l'achat
		$equipement_prix = $equipement_exist['prix'];
		$solde_actuel = $this->Authentification->getJoueurConnecte()['solde'];
		if($equipement_prix * $quantite > $solde_actuel)
		{
			$erreurs[] = 'Pas assez d\'argent';
		}

		//si une erreur on sort
		if (!empty($erreurs)) {
			$this->Alert->reset();
			foreach ($erreurs as $erreur) {
				$this->Alert->add($erreur);
			}

			$this->Router->redirect('equipements', 'shop');
		}

		//verification que le joueur possède déjà l'equipement
		
		for($i = 0; $i < $quantite; $i++){
			$this->equipement->addequipement($equipement_id, $idJoueur);
		}
		
		$valeur = $equipement_prix * $quantite;

		$Joueur->updateSolde($idJoueur, $valeur);
		$success = 'Èquipements acheter';

		//redirection shop
		if ($success != '') {
			$this->Alert->reset();
			$this->Alert->add($success, SUCCESS);

			$this->Router->redirect('equipements', 'shop');
		}
	}

	public function process_equipement() 
	{

		//clean les posts
		$personnage_id = clean($this->Request->value(POST, 'personnage_id'));
		$equipement_id = clean($this->Request->value(POST, 'id_equipement'));
		$emplacement = clean($this->Request->value(POST, 'emplacement'));
		$Personnage = new Personnage();
		$idJoueur = $_SESSION[SESSION_JOUEUR_CONNECTE]['id'];
        $erreurs = [];
		$success = '';
		
		//vérification que l'objet existe
		$equipement_exist = $this->equipement->getById($equipement_id);
		if(empty($equipement_exist))
		{
			$erreurs[] = 'l\'equipement n\'existe pas ';
		}

		//verifie que c'est le bon emplacement
		if (!$this->equipement->equipementBonEmplacement($equipement_id, $emplacement)){
			$erreurs[] = 'L\'équipement choisi n\'est pas à la bonne place ';
		}

		//vérification que le joueur possède l'objet
		/*debug($this->equipement->equipementPossede($equipement_id, $idJoueur));
		die();*/
		if ($this->equipement->equipementPossede($equipement_id, $idJoueur) == false){
			$erreurs[] = 'Vous n\'avez pas cet équipement dans votre inventaire ';
		}
		

		//vérification que l'objet est disponible
		if ($this->equipement->equipementDisponible($equipement_id, $idJoueur) == false){
			$erreurs[] = 'Tout les équipements de ce type sont utilisés ';
		}

		//si erreur redirection page détails personnage
		if (!empty($erreurs)) {
			$this->Alert->reset();
			foreach ($erreurs as $erreur) {
				$this->Alert->add($erreur);
			}

			$this->Router->redirect('personnages', 'details', ['id' => $personnage_id]);
		}

		//si un objet est déjà équipé 
		
		$id_ligne_equipement = $this->equipement->equipementDisponible($equipement_id, $idJoueur);
		$idEquipementActuel = $Personnage->getById($personnage_id)['emplacement_'.$emplacement.'_id'];
		if($idEquipementActuel != 0){
			//on dégage l'objet et on met le nouveau
			$this->equipement->deleteEquipement($personnage_id, $idEquipementActuel, $emplacement);
			$this->equipement->updateEquipement($personnage_id, $id_ligne_equipement, $emplacement);
			
			//sinon
		} else {
			$this->equipement->updateEquipement($personnage_id, $id_ligne_equipement, $emplacement);
		}

		$success = 'Equipement équipé';

		//redirection personnage détails
		if ($success != '') {
			$this->Alert->reset();
			$this->Alert->add($success, SUCCESS);

			$this->Router->redirect('personnages', 'details', ['id' => $personnage_id]);
		}
	}
}