<?php 

	namespace Bitphp\Core;
	
	use \Exception;
	use \Bitphp\Core\Globals;

	/**
	 *	Clase para registrar los error_handlers de bitphp
	 *
	 *	uso: $errorHandler = new \Bitphp\Core\Error();
	 *		 $errorHandler->registre();
	 *
	 *	requiere qué se haya registrado previamente la variable global
	 *	"base_path" con la clase \Bitphp\Core\Globals
	 *
	 *	@author Eduardo B <eduardo@root404.com>
	 */
	class Error {

		private $log_file;
		private $errors;
		public $debug;

		public function registre() {

			ini_set('display_errors', 0);
    		error_reporting(E_ALL);
    		# se definen las funciones para el manejo de errores
    		set_error_handler(array($this, 'globalErrorHandler'));
    		register_shutdown_function(array($this, 'fatalErrorHandler'));

    		#debug de errores
			$debug = Config::param('debug');
			$this->debug = ( null === $debug ) ? true : $debug;

			#archivo para el loggeo de errores
    		$this->log_file = Globals::get('base_path') . '/olimpus/log/errors.log';
    		$this->errors = array();
		}
		
		/**
		 *	Añade un registro de error al archivo de errores
		 *	en formato JSON, retorna id del error si el registro
		 *	fue satisfactorioo false si este falló
		 *
		 *	@param int $code indica el codigo de error
		 *	@param string $message mensaje de error
		 *	@param string $file archivo donde se produjo el error
		 *	@param int $line linea donde se produjo el error
		 *	@param array $trace trasa de pila
		 *	@return mixed false si no se pudo guardar el error, un string
		 *						con el identificador del error si este se guardo
		 */
		private function log($code, $message, $file, $line, $trace) {
			# id es un hash md5 formado por la fecha y un numero aleatorio
			$date = date('l jS \of F Y h:i:s A');
			$identifier =  md5($date . rand(0, 9999));

			$log = [
				  'date' => $date
				, 'message' => $message
				, 'id' => $identifier
				, 'trace' => $trace
				, 'code' => $code
				, 'file' => $file
				, 'line' => $line
			];

			$log = json_encode($log) . PHP_EOL;

			$done = @error_log($log, 3, $this->log_file);
			return $done ? $identifier : false;
		}

		/**
		 *	Bitphp gestiona todos los errores de php
		 *
		 *	@param int $code codigo de error
		 *	@param string $message mensaje del error
		 *	@param string $file archivo donde se produjo el error
		 *	@param int $line linea donde se produjo el error
		 *	@return void
		 */
		public function globalErrorHandler($code, $message, $file, $line) {
			$exception = new Exception();
			$trace = $exception->getTrace();

			$identifier = $this->log($code, $message, $file, $line, $trace);
			$this->errors[] = [
				  'code' => $code
				, 'message' => $message
				, 'file' => $file
				, 'line' => $line
				, 'identifier' => $identifier
				, 'trace' => $trace
			];
		}

		/**
		 *	Se ejecuta cuando el script finaliza y verifica si hubo errores
		 *	en caso de ser así carga la vista de error de bitphp
		 *
		 *	@return void
		 */
		public function fatalErrorHandler() {		
			$error = error_get_last();
			
			if(null !== $error) {
				 $this->globalErrorHandler(
				 	  E_ERROR
				 	, $error['message']
				 	, $error['file']
				 	, $error['line']
				 );
			}

			if (!empty($this->errors)) {
				#Si esta habilitado el debug lo muestra y si no manda 404
				if($this->debug) {
					$errors = $this->errors;
					require Globals::get('base_path') . '/olimpus/system/error_message.php';
				} else {
					require Globals::get('base_path') . '/olimpus/static_pages/404.php';
				}
			}
		}
	}