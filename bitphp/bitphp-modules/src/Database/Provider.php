<?php

   namespace Bitphp\Modules\Database;

   use \Bitphp\Core\Config;

   /**
    *   Clase base para los módulos de conexion a bases de datos
    *   se encarga de setear los parametros como usuario, pass y host
    *
    *   @author Eduardo B Romero
    */
   abstract class Provider {
      /** Usuario para la conexión */
      public $user;
      /** Contraseña para la conexión */
      public $pass;
      /** Host para la conexión */
      public $host;

      /**
       *  Lee la configuracion en busca del user, host y pass
       *  si no los encuentra setea los valores por defecto  
       */
      public function __construct() {

         # Si no se encuentran en la configuración setea valores default
         $host = Config::param('database.host');
         if(null == $host)
            $host = 'localhost';

         $user = Config::param('database.user');
         if(null === $user)
            $user = 'root';

         $pass = Config::param('database.pass');
         if(null == $pass)
            $pass = '';

         $this->host = $host;
         $this->user = $user;
         $this->pass = $pass;
      }

      /**
       *   Detecta si la base de datos seleccionada es un
       *   alias, si es así trata de obtener el nombre real
       *   de la base para dicho alias, si no existe manda error
       *   y retorna nulo, si no es un alias retorna el valor original
       *
       *   @param string $alias Cadena a examinar
       *   @return string nombre real de la base de datos
       */
      protected function realName($alias) {
         $is_alias = strpos($alias, 'alias.');
         if(false === $is_alias)
            return $alias;

         $name = Config::param("database.$alias");
         if(null === $name) {
            $message  = "El '$alias' para la base de datos no esta definido. ";
            $message .= 'Antes de poder usarlo definelo en la configuración';
            trigger_error($message);
            return null;
         }

         return $name;
      }

      abstract public function database($name);
      abstract public function execute($query);
      abstract public function error();
      abstract public function result();
   }