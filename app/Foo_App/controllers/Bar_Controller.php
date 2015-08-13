<?php
   
   namespace Foo_App\Controllers;

   use \Bitphp\Modules\Layout\Medusa;

   class Bar_Controller {
      public function baz() {
         Medusa::quick('welcome');
      }
   }