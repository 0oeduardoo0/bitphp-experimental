<?php

   require 'bitphp/autoload.php';

   use \Bitphp\Base\MicroServer;
   use \Bitphp\Modules\Auth\Token;
   use \Bitphp\Modules\Http\Response;
   use \Bitphp\Modules\Http\Input;
   use \App\Models\Applications;

   $app = new MicroServer();

   /**
    * Objetos de la aplicacion
    */
   $app->set('token', new Token());
   $app->set('model', new Applications());

   /**
    * Funciones de la aplicacion
    */
   $app->set('sendResponse', function(array $array) {
      // Set status header
      Response::status($array['status']);

      $data = json_encode($array, JSON_PRETTY_PRINT);
      Response::json($data);
   });

   $app->set('authFailure', function() use ($app) {
      $result = [
           'status' => 401
         , 'result' => 'Invalid app_id/secret'
      ];

      $app->sendResponse($result);
   });

   $app->set('badToken', function() use ($app){
      $result = [
           'status' => 400
         , 'result' => 'Invalid or null access token'
      ];

      $app->sendResponse($result);
   });

   $app->set('requestError', function($msg) use ($app) {
      $result = [
           'status' => 400
         , 'result' => 'Bad request'
         , 'error' => $msg
      ];

      $app->sendResponse($result);
   });

   /**
    * Rutas GET
    */
   $app->doGet('/test', function() use ($app) {
      $token = Input::get('token');
      if(null == $token) {
         $app->badToken();
         return;
      }

      $payload = $app->token->check($token);
      if(false !== ($error = $app->token->error())) {
         $app->requestError($error);
         return;
      }

      $result = [
           'status' => 200
         , 'result' => $payload
      ];

      $app->sendResponse($result);
   });

   /**
    * Rutas para POST
    */
   $app->doPost('/auth', function() use ($app) {
      $app_id = Input::post('app_id');
      $secret = Input::post('secret');
      $result = array();

      if($app_id == null || $secret == null) {
         $app->authFailure();
         return;
      }

      $payload = $app->model->login($app_id, md5($secret));
      if(false === $payload) {
         $app->authFailure();
         return;
      }

      $token = $app->token->create($payload);

      $result = [
           'status' => 200
         , 'result' => [
               'token' => $token
            ]
      ];

      $app->sendResponse($result);
   });

   
   try {
      $app->run();
   } catch(Exception $e) {
      $message = $e->getMessage();

      if($message == 'Invalid request method') {
         $data = [
              'status' => 405
            , 'result' => 'Method not allowed'
         ];
      } else {
         $data = [
              'status' => 404
            , 'result' => 'Not found'
         ];
      }

      $app->sendResponse($data);
   }
