<?php

	namespace Bitphp\Base;

	use \Closure;
	use \Exception;
	use \Bitphp\Base\Server;
	use \Bitphp\Core\Globals;
	use \Bitphp\Base\MicroServer\Route;
	use \Bitphp\Base\MicroServer\Pattern;

	class MicroServer extends Server {

		protected $action;
		protected $method;
		protected $routes;
		protected $binded;

		public function __construct() {
			parent::__construct();

			$route = Route::parse( Globals::get('request_uri') );

			Globals::registre('uri_params', $route['params']);
			$this->action = $route['action'];
			$this->method = $route['method'];
			$this->routes = array();
			$this->binded = array();
		}

		public function __call($method, array $args) {
			if(isset($this->binded[$method])) {
				return call_user_func_array($this->binded[$method], $args);
			}

			throw new Exception('La clase ' . __CLASS__ . " no contiene el metodo $method", 1);
		}

		public function doGet($route, $callback) {
			$pattern = Pattern::create($route);
			$this->routes['GET'][$pattern] = $callback;
		}

		public function doPut($route, $callback) {
			$pattern = Pattern::create($route);
			$this->routes['PUT'][$pattern] = $callback;
		}

		public function doPost($route, $callback) {
			$pattern = Pattern::create($route);
			$this->routes['POST'][$pattern] = $callback;
		}

		public function doDelete( $route, $callback ) {
			$pattern = Pattern::create($route);
			$this->routes['DELETE'][$pattern] = $callback;
		}

		public function set( $item, $value ) {
			if(is_callable($value)) {
				$this->binded[$item] = Closure::bind($value, $this, get_class());
				return;
			}

			$this->$item = $value;
		}

		public function run() {

			$routes = $this->routes[$this->method];

			foreach ($routes as $route => $callback) {
				if(preg_match($route, $this->action, $args)) {
					array_shift($args);
					call_user_func_array($callback, $args);
					return;
				}
			}

			throw new Exception("Accion no definida para la ruta '$this->action'", 1);
			
		}
	}