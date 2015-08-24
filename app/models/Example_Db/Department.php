<?php

   namespace App\Models\Example_Db;

   use \Bitphp\Modules\Database\Cadabra\Orm;

   class Department extends Orm {

      use \Bitphp\Modules\Database\Cadabra\Mapper;

      protected $provider = '\Bitphp\Modules\Database\MySql';
      protected $primary_key = 'id';
      protected $keys = '(owner_id) references person(id)';
      protected $index = '(name)';

      public $id = 'int(11) not null auto_increment';
      public $name  = 'varchar(80) not null';
      public $owner_id   = 'int(2) not null';
   }