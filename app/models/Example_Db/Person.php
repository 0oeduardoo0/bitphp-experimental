<?php

   namespace App\Models\Example_Db;

   use \Bitphp\Modules\Database\Cadabra\Orm;

   class Person extends Orm {

      use \Bitphp\Modules\Database\Cadabra\Mapper;

      protected $provider = '\Bitphp\Modules\Database\MySql';
      protected $primary_key = 'id';
      protected $keys = 'email';

      public $id = 'int(11) not null auto_increment';
      public $email   = 'varchar(80) not null';
      public $address = 'varchar(80) not null';
      public $name  = 'varchar(80) not null';
      public $age   = 'int(2) not null';
   }