<?php

   /**
    * Rutas GET
    */
   $app->doGet('/test', function() use ($app) {
      
      $token = $app->input->get('token');
      if(null == $token) {
         $app->badToken();
         return;
      }

      $payload = $app->token->decode($token);
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