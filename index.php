<?php

   require 'bitphp/autoload.php';

   use \Bitphp\Base\MicroServer;
   use \Bitphp\Modules\Auth\Token;
   use \Bitphp\Modules\Http\Input;
   use \Bitphp\Modules\Http\Response;
   use \App\Models\Applications;

   $app = new MicroServer();

   /**
    * Objetos de la aplicacion
    */
   $app->set('token', new Token());
   $app->set('model', new Applications());
   $app->set('response', new Response());
   $app->set('input', new Input());

   require 'app/functions.php';

   switch($app->method) 
   {
      case 'GET':
         require 'app/routes/get.php';
         break;
      case 'POST':
         require 'app/routes/post.php';
         break;
   }

   try {
      $app->run();
   } catch(Exception $e) {
      $data = [
           'status' => 404
         , 'result' => 'Not found'
         , 'error'  => $e->getMessage()
      ];

      $app->sendResponse($data);
   }