<?php
class PersonnagesController extends Controller
{
    public $Personnage;
    

	// Constructeur
	public function __construct()
	{
        $this->Personnage = new Personnage();

		parent::__construct();
    }
    
    // Vue Détails d'un personnage
	public function view_details()
	{
        $joueur_id = $_SESSION[SESSION_JOUEUR_CONNECTE]['id'];
        // Il faut être connecté
		$this->actionSecurisee();

        // Récupération de l'id passé en paramètre
        if (!$this->Request->exists(GET, 'id')) {
            $this->Alert->reset();
			$this->Alert->add('Paramètre manquant', DANGER);

			$this->Router->redirect('joueurs', 'dashboard');
        }

        $id = $this->Request->value(GET, 'id');

        // Récupération du personnage
        $personnage = $this->Personnage->getFull($id);

        // Controle de sécurité sur le personnage
        $this->securitePersonnage($personnage);

        // Envoi du personnage à la vue
		return [
            '_personnage' => $personnage,
		];
    }
    
    // Sécurité de l'accès à un Personnage
    private function securitePersonnage($personnage)
    {
        // Il faut que le personnage existe
        if (empty($personnage)) {
            $this->Alert->reset();
			$this->Alert->add('Ce personnage n\'existe pas', DANGER);

			$this->Router->redirect('joueurs', 'dashboard');
        }

        // Il faut que ce soit mon personnage
        if ($personnage['joueur_id'] != $this->Authentification->getId()) {
            $this->Alert->reset();
			$this->Alert->add('Ce personnage n\'est pas le votre !', DANGER);

			$this->Router->redirect('joueurs', 'dashboard');
        }
    }
}