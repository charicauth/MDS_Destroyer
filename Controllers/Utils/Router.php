<?php
class Router
{
	public function __construct()
	{

	}

	public function redirect($controller, $action, $params = [])
	{	
		header('Location: ' . $this->buildUrl($controller, $action, $params));
		exit;
	}

	public function buildUrl($controller, $action, $params = [])
	{
		$url = 'index.php?dir=' . $controller . '&page=' . $action;

		// Gestion de params
		if (!empty($params)) {
			foreach ($params as $key => $value) {
				$url .= '&' . $key . '=' . urlencode($value);
			}
		}

		return $url;
	}
}