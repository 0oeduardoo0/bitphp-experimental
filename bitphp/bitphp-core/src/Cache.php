<?php

	namespace Bitphp\Core;

	use \Bitphp\Core\Config;
	use \Bitphp\Core\Globals;

	/**
	 *	Clase para manipular el cache de bitphp.
	 *
	 *	por ejemplo, los templates se cachean en base a su nombre de archivo y a 
	 *	los parametros qué estos reciban, en base a estos 2 se crea un hash que
	 *	se usa para identificarlos en los archivos de cache.
	 *
	 *	@author Eduardo B <eduardo@root404.com>
	 */
	class Cache {

		/**
		 *	Crea el nombre de un archivo de cache en base a un arreglo.
		 *
		 *	@param array $dada Parametros que se toman para general el cache
		 *					   por ejemplo el nombre de una vista y los parametros
		 *					   que esta recibe.
		 *	@return string ruta del archivo del cache
		 */
		protected static function generateName($data) {
			$label = json_encode($data);
			$dir = Globals::get('base_path') . '/olimpus/cache/';
			return $dir . md5($label) . '.lock';
		}

		/**
		 *	Verifica si los datos pasados estan en el cache, si estos sobrepasan el tiempo
		 *	de vida del cache se eliminan y retorna false
		 *
		 *	@param array $dad Parametros que se usan para verificar si estan en el cache
		 *	@return mixed false si no esta en cache, o este esta desabilitado, retorna su contenido
		 *					    si este existe
		 */
		public static function isCached($data) {
			if(false === Config::param('cache'))
				return false;

			$file = self::generateName($data);
			$cachetime = Config::param('cache.time');

			if( null === $cachetime || !is_numeric($cachetime) )
				$cachetime = 300; //senconds

			return self::read($file);
		}

		/**
		 *	Lee el contenido de un archivo en cache, si este existe
		 *	y no ha sobrepasado el tiempo de vida
		 *
		 *	@param string $file Ruta del archivo a leer
		 *	@return mixed contenido del archivo si extiste y no a expirado
		 *				  false de lo contrario
		 */
		public static function read($file) {
			if(file_exists($file)) {
				if((fileatime($file) + $cachetime) >= time()) {
					return file_get_contents($file);
				}

				unlink($file);
			}

			return false;
		}

		/**
		 *	Guarda algun contenido en el cache identificandolo en base a 
		 *	un arreglo de datos especifico
		 *
		 *	@param array $data arreglo qué servira como identificador
		 *	@param string $content contenido para guardar en cache
		 *	@return void
		 */
		public static function save($data, $content) {
			if(false === Config::param('cache'))
				return false;
			
			$file = self::generateName($data);
			$writer = fopen($file, 'w');
			fwrite($writer, $content);
			fclose($writer);
		}
	}