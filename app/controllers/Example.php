<?php

   namespace App\Controllers;

   use \Bitphp\Modules\Http\Input;

   class Example {

      /* con filtro */
      public function foo() {
         $baz = Input::get('baz');
         echo $baz;
      }

      /* sin filtro */
      public function bar() {
         $baz = Input::get('baz', false);
         echo $baz;
      }
   }