<?php

   namespace Bitphp\Modules\Utilities;

   use \Exception;

   class JsonWebToken {

      private static function b64UrlEncode($input) {
         $base64 = strtr(base64_encode($input), '+/', '-_');
         //to trim
         return str_replace('=', '', $base64);
      }

      private static function b64UrlDecode($input) {
         $length = strlen($input) % 4;
         if($length) {
            $padding = 4 - $length;
            $input .= str_repeat('=', $padding);
         }

         return base64_decode(strtr($input, '-_', '+/-'));
      }

      private static function sing($data, $key, $alg = 'HS256') {
         $algorithms = [
              'HS256' => 'sha256'
            , 'HS384' => 'sha384'
            , 'HS512' => 'sha512'
         ];

         if(!isset($algorithms[$alg]))
            throw new Exception("Wrong algorithm '$alg'");

         return hash_hmac($algorithms[$alg], $data, $key, true);
      }

      public static function encode(array $payload, $key, $alg = 'HS256') {
         $token = array();

         $header = [
              'typ' => 'JWT'
            , 'alg' => $alg
         ];

         $token[] = self::b64UrlEncode(json_encode($header));
         $token[] = self::b64UrlEncode(json_encode($payload));

         $sing = self::sing(implode('.', $token), $key, $alg);
         $token[] = self::b64UrlEncode($sing);

         return implode('.', $token);
      }

      public static function decode($token, $key, $verify = true) {
         $token = explode('.', $token);
         if(3 !== count($token))
            throw new Exception("Invalid number of segments");
         
         $header = self::b64UrlDecode($token[0]);
         if(null === ($header = json_decode($header, true)))
            throw new Exception("Invalid header encoding");

         $payload = self::b64UrlDecode($token[1]);
         if(null === ($payload = json_decode($payload, true)))
            throw new Exception("Invalid payload encoding");

         if($verify) {
            if(empty($header['alg']))
               throw new Exception("Empty algorithm");
            
            $token_sing = self::b64UrlDecode($token[2]);
            $sing = self::sing("$token[0].$token[1]", $key, $header['alg']);
            if($token_sing != $sing)
               throw new Exception("Invalid token signature");
         }

         return $payload;
      }
   }