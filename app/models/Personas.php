<?php

   namespace App\Models;

   use \Bitphp\Modules\Database\MySql;

   class Personas extends MySql {

      public function __construct() {
         parent::__construct();
         $this->database('exampledb');
      }

      public function all() {
         $query = 'SELECT * FROM personas';
         $this->execute($query);
         return $this->result();
      }
   }