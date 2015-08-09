<?php

   namespace Bitphp\Base\MicroServer;

   class Route {

      private static function requestMethod() {
         $method = $_SERVER['REQUEST_METHOD'];
      
            if( !(   $method == 'GET'
                  || $method == 'POST'
                  || $method == 'PUT'
                  || $method == 'DELETE'
              ) )
            {
                $method = 'invalid';
            }

            return $method;
      }

      private static function action( $uri ) {
         if(empty($uri))
            return '/';

         return '/' . rtrim($uri, '/');
      }

      private static function uriParams( $uri ) {
         # /parametro1/parametro2/etc
         if(!empty($uri)) {
            return $uri;
         }

         # si no hay parametros retorna un array vacio
         return array();
      }

      public static function parse( $request_uri ) {
         $array = trim($request_uri, '/');
         $array = explode('/', $array);

         $result = [
              'action' => self::action($request_uri)
            , 'params' => self::uriParams($array)
            , 'method' => self::requestMethod()
         ];

         return $result;
      }
   }