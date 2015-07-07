<?php 

	namespace Bitphp\Core;

	$_BITPHP_CONFIG = array();

	/**
	 *	Proporciona los metodos para leer el archivo de configuracion de
	 *	la aplicacion
	 *
	 *	@author Eduardo B <eduardo@root404.com>
	 */
	class Config {

		/**
		 *	verifica y carga el archivo
		 *	de configuracion de la aplicacion
		 *
		 *	@param string $file Ruta al archivo de configuracion
		 */
		public static function load($file) {
			global $_BITPHP_CONFIG;

			if( file_exists($file) ) {
				$content = file_get_contents($file);
				#usa la variable global para no cargar el archivo una y otra vez
				$_BITPHP_CONFIG = json_decode($content, true);
			}
		}

		/**
		 *	Lee un parametro de configuracion
		 *
		 *	@param string $index Nombre del parametro de configuracion a leer
		 *	@return mixed null si no existe el parametro de configuracion o su valor
		 *				  en caso de que este exista
		 */
		public static function param($index) {
			global $_BITPHP_CONFIG;
			return isset($_BITPHP_CONFIG[$index]) ? $_BITPHP_CONFIG[$index] : null;
		}

		/**
		 *	Retorna todos los parametros del archivo de configuracion leidos
		 *
		 *	@return array
		 */
		public static function all() {
			global $_BITPHP_CONFIG;
			return $_BITPHP_CONFIG;
		}
	}