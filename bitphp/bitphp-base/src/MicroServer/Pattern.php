<?php

   namespace Bitphp\Base\MicroServer;

   class Pattern {

      public static function create($route) {
         $search = [
              '/\//'
            , '/\((int|integer)(\s+\$\w+)?\)/'
            , '/\((dbl|double)(\s+\$\w+)?\)/'
            , '/\((str|string)(\s+\$\w+)?\)/'
            , '/\((any|anything)(\s+\$\w+)?\)/'
         ];

         $replace = [
              '\/'
            , '([0-9]+)'
            , '([0-9]+\.[0-9]+)'
            , '(\w+)'
            , '(.*)'
         ];

         return '/^' . preg_replace($search, $replace, $route) . '$/';
      }
   }