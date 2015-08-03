<?php

   namespace Bitphp\Base;

   use \Bitphp\Base\MvcServer;

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

      }

      /**
       *   Para ejecutar una aplicacion/controlador desde otra
       *   aplicaion/controlador
       *
       *   @param string $data aplicacion controlador y accion a ejecutar
       *                       en el formato <b>aplication@controller.action</b>
       */
      public function execute($data)
   }