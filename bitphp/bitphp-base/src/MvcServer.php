<?php 
   
   namespace Bitphp\Base;

   use \Bitphp\Core\Globals;
   use \Bitphp\Base\Server;
   use \Bitphp\Base\MvcServer\Route;

   /**
    *   Implementacion del servidor base para crear 
    *   un servicio MVC
    *
    *   @author Eduardo B <eduardo@root404.com>
    */
   class MvcServer extends Server {

      /** Controlador solicitado */
      protected $controller;
      /** Accion del controlador a ejecutar */
      protected $action;

      /**
       *    Durante el contructor se parsea la ruta de la
       *   url solicitada para obtener el controlador, la
       *   accion y los parametros.
       */
      public function __construct() {
         parent::__construct();

         $route = Route::parse(Globals::get('request_uri'));
         # MVC
         Globals::registre('uri_params', $route['params']);
         $this->controller = $route['controller'];
         $this->action = $route['action'];
      }

      /**
       *   Se verifica y carga el archivo del controlador
       *   se crea un objeto de este y se retorna
       *
       *   @return Object instancia del controlador cargado
       */
      public function getController() {
         # El formato del nombre del archivo debe de ser
         # example.com/controlador -> app/controllers/Controlador.php
         $file_name = ucfirst($this->controller);
         $file = Globals::get('base_path') . '/app/controllers/' . $file_name . '.php';
         if(false === file_exists($file)){
            $message  = "Error al cargar el controlador '$this->controller.' ";
            $message .= "El archivo del controlador '$file' no existe";
            trigger_error($message);
            return false;
         }
         
         require $file;

         $fullClassName = '\App\Controllers\\' . $this->controller;
         return  new $fullClassName;
      }

      /**
       *   Se verifica y carga el archivo del controlador
       *   y se ejecuta la accion (metodo) solicitado
       *
       *   @return void
       */
      public function run() {
         $controller = $this->getController();
         if($controller === false)
            return;

         if(!method_exists($controller, $this->action)) {
            $message  = "La clase del controlador '$this->controller' ";
            $message .= "no contiene el metodo '$this->action'";
            trigger_error($message);
            return;
         }

         call_user_func(array($controller, $this->action));
      }
   }