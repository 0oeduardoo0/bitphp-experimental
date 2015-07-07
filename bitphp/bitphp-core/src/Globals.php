<?php
	
	namespace Bitphp\Core;

	$_BITPHP = array();

	/**
	 *	Registra y menja las variables globales de bitphp
	 *
	 *	@author Eduardo B <eduardo@root404.com>
	 */
	class Globals {

		/**
		 *	Registra una variable en el ambito global
		 *	tambien puede registrar varias variables pasando
		 * 	$var como un arreglo asosiativo de ["var" => "val"]
		 *
		 *	@param mixed $var nombre de la variable
		 *	@param mixed $val valor de la variable
		 */
		public static function registre($var, $val = null) {
			global $_BITPHP;

			if(is_array($var)) {
				foreach ($var as $name => $value) {
					self::registre($name, $value);
				}
				return;
			}

			$_BITPHP[$var] = $val;
		}

		/** 
		 *	Retorna el valor de una variable global
		 *
		 *	@param string $val nombre de la variable
		 *	@return mixed null si la variable no esta registrada y su valor si existe
		 */
		public static function get($var) {
			global $_BITPHP;
			return isset($_BITPHP[$var]) ? $_BITPHP[$var] : null;
		}

		/**
		 *	Retorna todas las variables globales y sus valores
		 *
		 *	@return array
		 */
		public static function all() {
			global $_BITPHP;
			return $_BITPHP;
		}
	}