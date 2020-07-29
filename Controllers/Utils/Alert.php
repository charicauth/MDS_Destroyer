<?php
/*
Une classe dédiée à la gestion de nos alertes
Qui va forcément travailler avec $Request

Méthodes ?
- Initialiser mes alertes (reset)
- Ajouter une alerte
- Récupérer mes alertes pour affichage
*/
class Alert
{
	// Pour manipuler les sessions
	private $Request;

	// Lors de la construction, est ce que je "reset" mes alertes
	public function __construct($reset = false)
	{
		$this->Request = new Request();

		if ($reset) {
			$this->reset();
		}
	}

	// On reset les alertes, en "vidant" l'endroit où elles sont stockées dans $_SESSION
	public function reset()
	{
		$this->Request->delete(SESSION_ALERTS);
	}

	// Ajout d'une alerte, par défaut "danger" (rouge)
	public function add($content, $color = DANGER)
	{
		$alerts = [];
		// 2 cas de figure :
			// Première alerte ajoutée
			// Nème alerte ajoutée
		if ($this->Request->exists(SESSION, SESSION_ALERTS)) {
			$alerts = $this->Request->value(SESSION, SESSION_ALERTS);
		}

		// Premier/Nouvel élément
		$alerts[] = [
				'content' => $content,
				'color' => $color
			];

		$this->Request->write(SESSION_ALERTS, $alerts);
		return true;
	}

	// Retourne mes alertes
	public function get()
	{
		// 2 cas de figure
		// J'ai des alertes
		if ($this->Request->exists(SESSION, SESSION_ALERTS)) {
			return $this->Request->value(SESSION, SESSION_ALERTS);
		}

		// Je n'ai pas d'alertes
		return [];
	}
}