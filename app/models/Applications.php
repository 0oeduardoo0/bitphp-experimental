<?php

   namespace App\Models;

   use \Bitphp\Modules\Database\MySql;

   class Applications  extends MySql {

      public function __construct() {
         parent::__construct();
         $this->database('example_db');
      }

      public function login($app_id, $secret) {
         $query = "SELECT id, name, description
                   FROM application 
                   WHERE id='$app_id' AND secret='$secret'";
         
         $this->execute($query);

         if(false !== ($error = $this->error())) {
            trigger_error($error);
            return false;
         }

         $result = $this->result();
         return empty($result) ? false : $result[0];
      }
   }