<?php
class ObjetsController extends Controller
{
	public $objet;

	// Constructeur
	public function __construct()
	{
		$this->objet = new Objet();
		parent::__construct();
    }

    public function view_shop()
    {
        //il faut être connecté
        $this->actionSecurisee();

		 // Récupération de l'id passé en paramètre
		 
		 $idJoueur = $this->Authentification->getId();
      
        return [
            '_objets' => $this->objet->liste($idJoueur)
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

        $objet_id = clean($this->Request->value(POST, 'objet_id'));

		//verification que l'objet existe
	
		$objet_exist = $this->objet->getById($objet_id);

		if (empty($objet_exist)){
			$erreurs[] = 'l\'objet n\'existe pas ';
			
		}

		//verification que le solde est ok pour l'achat
		$objet_prix = $objet_exist['prix'];
		$solde_actuel = $_SESSION[SESSION_JOUEUR_CONNECTE]['solde'];
		if($objet_prix * $quantite > $solde_actuel)
		{
			$erreurs[] = 'Pas assez d\'argent';
		}

		//si une erreur on sort
		if (!empty($erreurs)){
			$this->Alert->reset();
			foreach ($erreurs as $erreur) {
				$this->Alert->add($erreur);
			}

			$this->Router->redirect('objets', 'shop');
		}

		//verification que le joueur possède déjà l'objet
		

		//si oui
		if ($this->objet->objetPossede($objet_id, $idJoueur) != false){
			$this->objet->achatUpdate($objet_id, $idJoueur, $quantite, $this->objet->objetPossede($objet_id, $idJoueur));
			
		}
		//sinon
		else {
			$this->objet->addObjet($quantite, $objet_id, $idJoueur);
		}
		
		$valeur = $objet_prix * $quantite;

		$Joueur->updateSolde($idJoueur, $valeur);
		$success = 'Objets acheter';

		//redirection shop
		if ($success != '') {
			$this->Alert->reset();
			$this->Alert->add($success, SUCCESS);

			$this->Router->redirect('objets', 'shop');
		}
	}
}