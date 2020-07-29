<?php
class CombatsController extends Controller
{
	public $Combat;

	public function __construct()
	{
		parent::__construct();
		$this->Combat = new Combat();
	}

	public function view_deroule_combat($idCombat)
	{
		$this->actionSecurisse();
	}
}