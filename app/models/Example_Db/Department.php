<?php

   namespace App\Models\Example_Db;

   use \Bitphp\Modules\Database\Cadabra\Orm;

   class Department extends Orm {

      use \Bitphp\Modules\Database\Cadabra\Mapper;

      protected $provider = '\Bitphp\Modules\Database\MySql';
   }