<?php 

	namespace Bitphp\Base\MvcServer;

	/**
	 *	Recibe como parametro en el contructor el arreglo 
	 *	de la url solicitada y en base a ella identifica 
	 *	el controlador, la accion, y los parametros
	 */
	class Route {

		private static function controller($uri) {
			#main es el controlador por defecto
			if(empty($uri[0]))
				return 'main';

			return $uri[0];
		}

		private static function action($uri) {
			# __index es la accion por defecto
			if(empty($uri[1]))
				return '__index';

			return $uri[1];
		}

		private static function uriParams($uri) {
			# /controlador/accion/parametro1/parametro2/etc
			# |                  | <- si solo existen estos 2
			#						  quiere decir qué no hay parametros :v
			if(2 < count($uri)) {
				$params = $uri;
				unset($params[0], $params[1]);
				return array_values($params);
			}

			# si no hay parametros retorna un array vacio
			return array();
		}

		public static function parse($request_uri) {
			$request_uri = trim($request_uri, '/');
			$request_uri = explode('/', $request_uri);

			$result = [
				  'controller' => self::controller($request_uri)
				, 'action' => self::action($request_uri)
				, 'params' => self::uriParams($request_uri)
			];

			return $result;
		}
	}