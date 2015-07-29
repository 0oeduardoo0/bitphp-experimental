<?php
   
   namespace Bitphp\Base\CommandLine;

   class Pattern {

      public static function create($command) {
         $search = [
              '/(\s+)/'
            , '/\((\$\w+)?\)/'
         ];

         $replace = [
              '[\s+]'
            , '(\S+)'
         ];

         return '/^' . preg_replace($search, $replace, $command) . '/x';
      }
   }