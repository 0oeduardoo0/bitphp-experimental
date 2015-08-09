<?php

   namespace Bitphp\Base;

   use \Closure;
   use \Exception;
   use \Bitphp\Base\Server;
   use \Bitphp\Core\Globals;
   use \Bitphp\Base\MicroServer\Route;
   use \Bitphp\Base\MicroServer\Pattern;

   /**
    *   Implementacion del servidor base para crear 
    *   un servicio de clausulas (funciones) basadas
    *   en rutas
    *
    *   Para la ruta "/say/hello" ejecutar funcion X
    *
    *   @author Eduardo B <eduardo@root404.com>
    */
   class MicroServer extends Server {

      /** Ruta solicitada */
      protected $action;
      /** Metodo http de la solicitud */
      protected $method;
      /** Rutas registradas */
      protected $routes;
      /** Metodos agregados dinamicamente a la clase */
      protected $binded;

      /**
       *   Durante el contructor se obtiene informacion
       *   de la ruta, la ruta en si, el metodo http que
       *   se solicita
       */
      public function __construct() {
         parent::__construct();

         $route = Route::parse( Globals::get('request_uri') );

         Globals::registre('uri_params', $route['params']);
         $this->action = $route['action'];
         $this->method = $route['method'];            
         
         $this->routes = array(
              'GET' => array()
            , 'POST' => array()
            , 'DELETE' => array()
            , 'PUT' => array()
         );

         $this->binded = array();
      }

      /**
       *   Al ejecutar un metodo que en principio no
       *   existe en la clase, se verifica si este fue
       *   generado dinamicamente y si es asi se llama
       *
       *   @throw Exception cuando el metodo llamado definitivamente no existe
       */
      public function __call($method, array $args) {
         if(isset($this->binded[$method])) {
            return call_user_func_array($this->binded[$method], $args);
         }

         throw new Exception('La clase ' . __CLASS__ . " no contiene el metodo $method", 1);
      }

      /**
       *   Se usa para definir la clausula (funcion)
       *   que responda a la ruta indicada, si esta
       *   es solicitada a traves del metodo GET
       *
       *   $app->doGet('/say/hello', function() {
       *      echo "Hello world!";
       *   });
       *
       *   @param string $route ruta a la que respondera la funcion
       *   @param Clousure $callback funcion anonima a ejecutar para la ruta
       *   @return void
       */
      public function doGet($route, $callback) {
         $pattern = Pattern::create($route);
         $this->routes['GET'][$pattern] = $callback;
      }

      /**
       *   Se usa para definir rutas que responden
       *   al metodo PUT
       *
       *   @param string $route ruta a la que respondera la funcion
       *   @param Clousure $callback funcion anonima a ejecutar para la ruta
       *   @return void
       */
      public function doPut($route, $callback) {
         $pattern = Pattern::create($route);
         $this->routes['PUT'][$pattern] = $callback;
      }

      /**
       *   Se usa para definir rutas que responden
       *   al metodo POST
       *
       *   @param string $route ruta a la que respondera la funcion
       *   @param Clousure $callback funcion anonima a ejecutar para la ruta
       *   @return void
       */
      public function doPost($route, $callback) {
         $pattern = Pattern::create($route);
         $this->routes['POST'][$pattern] = $callback;
      }

      /**
       *   Se usa para definir rutas que responden
       *   al metodo DELETE
       *
       *   @param string $route ruta a la que respondera la funcion
       *   @param Clousure $callback funcion anonima a ejecutar para la ruta
       *   @return void
       */
      public function doDelete($route, $callback) {
         $pattern = Pattern::create($route);
         $this->routes['DELETE'][$pattern] = $callback;
      }

      /**
       *   Agrega metodos o propiedades dinamicamente
       *   a la clase MicroServer
       *
       *   @param string $item nombre del metodo o propiedad
       *   @param mixed $value valor, ya sea como el de una variable o un metodo
       *   @return void
       */
      public function set($item, $value) {
         if(is_callable($value)) {
            $this->binded[$item] = Closure::bind($value, $this, get_class());
            return;
         }

         $this->$item = $value;
      }

      /**
       *   Obtiene las rutas definidas para el metodo solicitado
       *   la compara mediante un patron regular previamente generado
       *   y si la ruta soolicitada a sido definida ejecuta su callback
       *   
       *   @throw Exception cuando la ruta solicitada no esta definida
       *   @return void
       */
      public function run() {
         if($this->method == 'invalid')
            throw new Exception('Invalid request method');

         $routes = $this->routes[$this->method];

         foreach ($routes as $route => $callback) {
            if(preg_match($route, $this->action, $args)) {
               array_shift($args);
               call_user_func_array($callback, $args);
               return;
            }
         }

         throw new Exception('Invalid request route');         
      }
   }