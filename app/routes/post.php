<?php

   /**
    * Rutas para POST
    */
   $app->doPost('/auth', function() use ($app) {

      $app_id = $app->input->post('app_id');
      $secret = $app->input->post('secret');
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