<?php
// Appel de l'action d'un controller
function call_action($dir, $page)
{
	$controllerName = ucfirst($dir) . 'Controller';
	$action = 'view_' . $page;

	$controller = new $controllerName();

	if (!method_exists($controller, $action)) {
		return [];
	}

	return $controller->$action();
}