<?php

   namespace App\Models\Example_Db;

   use \Bitphp\Modules\Database\Rocket\Orm;

   class Person extends Orm {

      use \Bitphp\Modules\Database\Rocket\Mapper;

      protected $provider = '\Bitphp\Modules\Database\MySql';
      protected $primary = 'id';
      protected $keys = 'email';
      protected $engine = 'innodb';
      protected $charset = 'utf8';

      public $id = 'int(11) not null auto_increment';
      public $email   = 'varchar(80) not null';
      public $address = 'varchar(80) not null';
      public $name  = 'varchar(80) not null';
      public $age   = 'int(2) not null';

      public function foo() {
         echo $this->builder->insert([
                                'foo' => 'faa'
                              , 'lel' => 'ja'
                            ])
                            ->make();
      }
   }