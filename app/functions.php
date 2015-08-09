<?php

   /**
    * Funciones de la aplicacion
    */
   $app->set('sendResponse', function(array $array) use ($app) {
      // Set status header
      $app->response->status($array['status']);
      
      $data = json_encode($array, JSON_PRETTY_PRINT);
      $app->response->json($data);
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