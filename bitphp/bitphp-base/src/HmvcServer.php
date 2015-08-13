<?php

   namespace Bitphp\Base;

   use \Bitphp\Core\Globals;
   use \Bitphp\Core\Config;
   use \Bitphp\Base\MvcServer;
   use \Bitphp\Base\HmvcServer\Route;

   /**
    *   Algunas modificaciones sobre la clase para el Servidor
    *   de Mvc y poder implementar Hmvc
    *
    *   Mvc  -> http://foo.com/controller/action/p1/p2/pn...
    *   HMvc -> http://foo.com/aplication/controller/action/p1/p2/pn...
    *
    *   @author Eduardo B <eduardo@root404.com>
    */
   class HmvcServer extends MvcServer {

      /** aplicacion que se va a ejecutar */
      protected $application;

      /**
       *   Sobreescribe las variables de ruta, el cntrolador,
       *   la accion y los parametros y establece la app ejecutada
       */
      public function __construct() {
         global $loader;
         parent::__construct();

         $route = Route::parse(Globals::get('request_uri'));
         extract($route);

         Globals::registre([
              'uri_params' => $params
            , 'app_path' => Globals::get('base_path') . "/app/$application"
         ]);
         
         $this->controller_namespace = "\\$application\Controllers\\";
         $this->controller_file =  Globals::get('app_path') . "/controllers/$controller.php";

         $this->controller =  $controller;
         $this->action = $action;

         Config::load(Globals::get('app_path') . '/config.json');
         $loader->add("$application\\Models", "app/$application/models");
         $loader->add("$application\\Controllers", "app/$application/controllers");
      }
   }