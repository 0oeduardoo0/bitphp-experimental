<?php

   namespace Bitphp\Modules\Auth;

   use \Exception;
   use \Bitphp\Core\Config;
   use \Bitphp\Modules\Utilities\JsonWebToken as JWT;

   class Token {

      protected $signature_key;
      protected $life;
      protected $error;

      public function __construct() {
         $this->signature_key = Config::param('token.signature');
         if(null === $this->signature_key)
            $this->signature_key = 'R4nd0mStr1ng_';

         $this->life = Config::param('token.life');
         if(null === $this->life)
            $this->life = 300; //seconds

         $this->error = null;
      }

      public function create($payload) {
         $payload['iat'] = time();
         $payload['exp'] = time() + $this->life;
         $jwt = JWT::encode($payload, $this->signature_key);
         return $jwt;
      }

      public function decode($token) {
         try {
            $payload = JWT::decode($token, $this->signature_key);
         } catch (Exception $e) {
            $this->error = $e->getMessage();
            return null;
         }

         return $payload;
      }

      public function error() {
         if(null !== $this->error)
            return $this->error;

         return false;
      }
   }