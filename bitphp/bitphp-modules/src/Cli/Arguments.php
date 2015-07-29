<?php

   namespace Bitphp\Modules\Cli;

   class Arguments {

      public static function command() {
         return implode(' ', $_SERVER['argv']);
      }

      public function get($index) {
         $arguments = $_SERVER['argv'];
         array_shift($arguments);

         if(!is_numeric($index)) {
            $index = array_search($index, $arguments);
            return self::get($index + 1);
         }

         if(isset($arguments[$index])) {
            return $arguments[$index];
         }

         return null;
      }

      public function flag($flag) {
         $arguments = $_SERVER['argv'];
         array_shift($arguments);

         $large_flag = array_search("--$flag", $arguments);
         $short_flag = array_search("-$flag[0]", $arguments);

         if($large_flag !== false)
            return "--$flag";

         if($short_flag !== false)
            return "-$flag[0]";

         return false;
      }
   }