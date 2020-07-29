<?php
// Mes fonctions de mise en forme à la sauce Bootstrap

// Fonction qui permet de generé le debut de mon document HTML
function start_html($title)
{
	return '<html lang="fr">
			<head>
			<meta charset="utf-8">
			<title>' . $title . '</title>
			<link href="assets/css/bootstrap.min.css" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css?family=Permanent+Marker&display=swap" rel="stylesheet">
			<link href="assets/css/style.css" rel="stylesheet">
			</head>
			<body>';
}

// Fin de mon fichier HTML, avec appel de mes scripts JS
function end_html()
{
	// Appel Jquery
	$html =	'<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>';
    
	// Appel Bootstrap.js dans mon dossier assets
	$html .= '<script src="assets/js/bootstrap.min.js"></script>';
	
	// Fermeture body et html
	$html .='</body></html>';
	return $html;
}

// JUMBOTRON https://getbootstrap.com/docs/4.2/components/jumbotron/
function jumbotron($titre, $options = [])
{
	$html = '<div class="jumbotron">
				<h1 class="display-4">' . $titre . '</h1>';

	// sous_titre ?
	if (isset($options['sous_titre'])) {
		$html .= '<p class="lead">'.$options['sous_titre'].'</p>';
	}

	// paragraphe ou lien ? Pour le séparateur
	if (isset($options['paragraphe']) or isset($options['nom_lien'])) {
		$html .= '<hr class="my-4" />';
	}

	// paragraphe
	if (isset($options['paragraphe'])) {
		$html .= '<p>'. $options['paragraphe'] .'</p>';
	}

	// lien ?
	if (isset($options['nom_lien']) and isset($options['lien_lien'])) {
		$html .= '<a class="btn btn-primary btn-lg" href="'.$options['lien_lien'].'" role="button">'.$options['nom_lien'].'</a>';
	}

	$html .= '</div>';

	return $html;
}

// Création d'une route
function route($link)
{
	// Récupération Router
	global $Router;

	if (!isset($link[CONTROLLER])) {
		die('[BS 003] Controller manquant');
	}

	if (!isset($link[ACTION])) {
		die('[BS 004] Action manquante');
	}

	if (isset($link[PARAMS])) {
		return $Router->buildUrl($link[CONTROLLER], $link[ACTION], $link[PARAMS]);
	}

	return $Router->buildUrl($link[CONTROLLER], $link[ACTION]);
}

// Création d'un lien
function button($name, $link, $options = [])
{
	// Travail sur la classe de mon bouton
	$class = BTN;
	
		// Couleur
		if (isset($options['color'])) {
			$color = $options['color'];
		} else {
			$color = 'primary';
		}

		$class .= ' '. BTN .'-' . $color;
	
		// class personnalisée ?
		if (isset($options['class'])) {
			$class .= ' ' . $options['class'];
		}
	
	// Présence d'un title ?
	$title = '';
	if (isset($options['title'])) {
		$title = 'title="'. $options['title'] .'"';
	}
	
	// Gestion de l'ouverture du lien, via l'attribut target = '_blank'
	$target = '';
	if (isset($options['blank']) and $options['blank']) {
		$target = 'target="_blank"';
	}
	
	// Lien interne : button('Connexion', 'connect', ['color' => 'success']);
	// Lien externe : button('MDS', 'https//www.mds.com', ['externe' => true]);

	// Option pour un lien externe
	if (! (isset($options['externe']) and $options['externe']) ) {
		$link = route($link);
	}

	// Création et return de mon bouton final
	return '<a class="'. $class .'" href="'. $link .'" role="button" '. $title .' '. $target .'>'. $name .'</a>';
}




// Création d'une alert Bootstrap
// 2 options possibles : 
//	- color : (string) pour gérer la couleur via une class de Bootstrap
//	- dismiss : (bool) pour afficher ou non la petite croix pour fermer mon alert
function alert($content, $options = [])
{
	// Gestion de mon option "color"
	$class = ALERT;
	
	$color = PRIMARY; // Valeur par défaut
	if (isset($options['color'])) {
		$color = $options['color'];
	}
	
	$class .= ' '. ALERT .'-' . $color;
	
	if (isset($options['class'])) {
		$class .= ' ' . $options['class']; // Pour rajouter une classe personnalisée
	}
	
	// Gestion de mon option "dismiss"
	$dismiss = '';
	if (isset($options['dismiss']) and $options['dismiss']) {
		$dismiss = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	  </button>';
	}
	
	// Création et retour de mon html
	return '<div class="'. $class .'" role="alert">'. $content . $dismiss . '</div>';
}

// Création d'un badge Bootstrap
// 1 option possible : color
function badge($content, $options = [])
{
	// Gestion de ma classe
	$class = BADGE;
	
	$color = SUCCESS; // Couleur par défaut
	if (isset($options['color'])) {
		$color = $options['color'];
	}
	
	$class .= ' ' . BADGE . '-' . $color;
	
	return '<span class="'. $class .'">'. $content .'</span>';
}

// Créer un "fil d'ariane"
// $links est un  tableau de tableau, avec à chaque fois les clés: "nom" et "lien"
function breadcrumbs($links)
{	
	// Début de mon breadcrumb
	$html = '<nav aria-label="breadcrumb">
  				<ol class="breadcrumb">';
		
	// Travail sur $links pour intégrer les liens
	foreach ($links as $link) {
		// 2 clés : "nom" et "lien"
		
		// Selon la valeur de "lien", je ne fais pas la même chose
		if ($link['lien'] != null) {
			$html .= '<li class="breadcrumb-item"><a href="'. $link['lien'] .'">'. $link['nom'] .'</a></li>';
		} else {
			$html .= '<li class="breadcrumb-item active" aria-current="page">'. $link['nom'] .'</li>';
		}
	}
  	
	// Fin de mon breadcrumb
	$html .= '</ol>
				</nav>';
		
	return $html;
}

// Gestion de la grille

// Permet de créer la div "container"
function container()
{
	return '<div class="container">';
}

// Permet de créer la div "row"
function row()
{
	return '<div class="row">';
}

// Permet de créer la div "col-X" avec X la largeur de cette div
function col($largeur = 1)
{
	return '<div class="col-'. $largeur .'">';
}

// Permet de fermer un certain nombre de div
function closeDiv($nbDivAFermer = 1)
{
	// Version + courte avec une fonction native de PHP
	return str_repeat('</div>', $nbDivAFermer);
}


// Formulaires => Aller voir dans le doc de bootstrap 'forms'

// Ouverture d'un formulaire
// options : method
function openForm($options = [])
{
	// Post par défaut
	$class = '';
	$method = 'post';
	if (isset($options['class'])) {
		$class = $options['class'];
	}

	if (isset($options['method'])) {
		$method = $options['method'];
	}
	
	// Pour gérer le routage correctement ----------
	if (!isset($options[CONTROLLER])) {
		die('[BS 001] Il manque le controller');
	}

	if (!isset($options[ACTION])) {
		die('[BS 002] Il manque l\'action');
	}

	// ---------------------------------------------

	// Génération automatique du name
	$name = $options[CONTROLLER] . '_' . $options[ACTION];

	$html = '<form class="'. $class .'" name="'. $name .'" action="controllers.php" method="'. $method .'">';

	// Champ caché pour gérer l'appel de la bonne action
	$html .= hidden(CONTROLLER, $options[CONTROLLER]);
	$html .= hidden(ACTION, $options[ACTION]);

	return $html;
}

// La création d'un input
// options : type, value, placeholder, label
function input($name, $options = [])
{
	// Gestion du type, par défaut 'text'
	$type = 'text';
	if (isset($options['type'])) {
		$type = $options['type'];
	}
	
	// Gestion de la value, par défaut rien ''
	$value = '';

	if (isset($options['value'])) {
		$value = $options['value'];
	}
	
	// Gestion du placeholder, par défaut rien ''
	$placeholder = '';
	if (isset($options['placeholder'])) {
		$placeholder = $options['placeholder'];
	}

	// Gestion d'un required
	$required = '';
	if (isset($options['required']) and $options['required']) {
		$required = ' required ';
	}

	// Création d'un identifiant unique pour le champ for
	$id = str_replace(' ', '', $name) . rand(0, 1000);

	// Gestion du label, par défaut égal à $name mais avec une majuscule (ucfirst)
	$label = ucfirst($name);
	if (isset($options['label'])) {
		$label = $options['label'];
	}

	$labelHtml = '<label for="'. $id .'">'. $label .'</label>';

	if (isset($options['no_label']) and $options['no_label']) {
		$labelHtml = '';
	}
		
	return '<div class="form-group">' .
		$labelHtml .
		'<input type="'. $type .'" class="form-control" id="'. $id .'" name="'. $name .'" placeholder="'. $placeholder .'" value="'. $value .'" '. $required .'/>
  	</div>';
}

// Pour un champ caché, on fait appel à la fonction input 
function hidden($name, $value = '')
{
	return input($name, ['value' => $value, 'type' => 'hidden', 'no_label' => true]);
}


// Liste "select"
// $name le nom de mon select
// $values un tableau de mes valeurs possibles, qui iront dans les <option>
// $options les options de mise en forme : 'label', 'selected' (valeur sélectionnée par défaut), 'empty' (message d'invitation, première <option value="">Message</option>)
function select($name, $values, $options = [])
{
	$html = '<div class="form-group">';
	
	// Gestion du label, par défaut égal à $name mais avec une majuscule (ucfirst)
	$label = ucfirst($name);
	if (isset($options['label'])) {
		$label = $options['label'];
	}
	
	// Création d'un identifiant unique pour le champ for
	$id = str_replace(' ', '', $name) . rand(0, 1000);
	
	$html .= '<label for="'. $id .'">'. $label .'</label>';
	
	// Ouverture de mon select
	$html .= '<select class="form-control" id="'. $id .'" name="'. $name .'">';
	
		// option 'empty'
		if (isset($options['empty'])) {
			$html .= '<option value="">'. $options['empty'] .'</option>';
		}
		
		// Vérification d'une option "format_values"
		if (isset($options['format_values']) and $options['format_values']) {
			// ['Fire','Ice']
			// ['Fire' => 'Fire', 'Ice' => 'Ice']
			$newValues = [];
			
			foreach ($values as $value) {
				$newValues[$value] = $value;
			}
			
			$values = $newValues;
		}
	
		// options issues de $values
		foreach ($values as $key => $value) {
			
			// gestion de 'selected'
			$selected = '';
			if (isset($options['selected']) and $options['selected'] == $key) {
				$selected = ' selected';
			}
			
			$html .= '<option value="'. $key .'" '. $selected .'>'. $value .'</option>';
		}
	
	// Fermeture de mon select et de ma div du départ
	$html .= '</select>';
	$html .= '</div>';
	
	return $html;
}

// La création du bouton submit
// options : color
function submit($value, $options = [])
{
	$class = BTN;
	$color = SUCCESS;
	
	if (isset($options['color'])){
		$color = $options['color'];
	}
	
	$class .= ' ' . BTN . '-' . $color;
	
	return '<input type="submit" class="'. $class .'" value="'. $value .'" />';
} 

// La fin du formulaire
function closeForm()
{
	return '</form>';
}

// Création d'une barre de progression
// Options :
// 	color
//  striped ?
//  animation ?
function progress_bar($pourcentage, $options = [])
{
	$html = '<div class="'. PROGRESS .'">';
	
	// Gestion de la class ----------------------
	$class = PROGRESS_BAR;
	
	// Couleur par défaut
	$color = PRIMARY;
	if (isset($options['color'])) {
		$color = $options['color'];
	}
	
	$class .= ' bg-' . $color; 
	
	// Striped ?
	if (isset($options['striped']) and $options['striped']) {
		$class .= ' ' . PROGRESS_BAR . '-striped';
	}
	
	// Animated ?
	if (isset($options['animated']) and $options['animated']) {
		$class .= ' ' . PROGRESS_BAR . '-animated';
	}
	// Fin de la gestion de la class -------------
	
	// div principale
    $html .='<div class="'. $class .'" role="progressbar" style="width: '. $pourcentage .'%" aria-valuenow="'. $pourcentage .'" aria-valuemin="0" aria-valuemax="100">'. $pourcentage .'%</div>';

	$html .= '</div>';
	return $html;
}


