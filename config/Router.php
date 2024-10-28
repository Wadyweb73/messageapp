<?php

class Router {
	private $routes;

	public function __construct() {
		$this->routes = [];	
	}

	public function get($url, $callback) {
		$this->routes['GET'][$url] = $callback;
	}

	public function post($url, $callback) {
		$this->routes['POST'][$url] = $callback;
	}

	public function run() {
		$url        = $_SERVER['REQUEST_URI'];
		$req_method = $_SERVER['REQUEST_METHOD'];
		$matched    = false;

		foreach($this->routes[$req_method] as $route => $callback) {
            $routePattern = preg_replace('/{[^\/]+}/', '([^\/]+)', $route);

            if (preg_match('#^' . $routePattern . '$#', $url, $matches)) {

                array_shift($matches);
                call_user_func_array($callback, $matches);
                $matched = true;

                break;
            }
        }

        if (!$matched) {
            http_response_code(404);
            //Redirect::to('/edulib/pagenotfound');
            echo json_encode(['result' => 'page not found']);
		}
	}
}

?>
