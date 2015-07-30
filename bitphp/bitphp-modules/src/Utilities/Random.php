<?php

   namespace Bitphp\Modules\Utilities;

   class Random {
      public static function string($length) {
         $pool    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678901234567899_';
         $limit = (strlen($pool) - 1);
         $out = '';

         for ($i = 1;$i <= $length; $i++) {
            $out .= $pool[rand(0,$limit)];
         }

         return $out;
      }
   }