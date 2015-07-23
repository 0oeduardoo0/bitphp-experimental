<?php 

	namespace Bitphp\Base;

	use \Bitphp\Core\Error;
	use \Bitphp\Core\Config;
	use \Bitphp\Core\Globals;

	/**
	 *	Base para las aplicaciones de bitphp
	 *	carga las clases base de bitphp
	 */
	abstract class Server {

		/**
		 *	Crea una direccion base del servidor 
		 *	eg. http://foo.com/
		 *	    https://foo.com/test
		 *	Dependiendo de donde se encuentre
		 */
		private function getBaseUri() {
			$base_uri  = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
			$base_uri .= $_SERVER['SERVER_NAME'];
			$dirname = dirname($_SERVER['PHP_SELF']);
			$base_uri .= $dirname == '/' ? '' : $dirname;
			return $base_uri;
		}

		private function getRequestUri() {
			return filter_input(INPUT_GET, '_bitphp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}

		public function __construct() {

			Globals::registre([
				  'base_path' => realpath('')
				, 'base_uri' => $this->getBaseUri()
				, 'request_uri' => $this->getRequestUri()
			]);

			#se define archivo de configuración
			Config::load(Globals::get('base_path') . '/app/config.json');
			$errorHandler = new Error();
			$errorHandler->registre();
		}

		abstract public function run();
	}