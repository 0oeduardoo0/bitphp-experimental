<?php

namespace App\Migrations\Example_Db_Migration;

class Person_Seed {

  use \Bitphp\Modules\Database\Migration\Seed;

  protected $provider = "\Bitphp\Modules\Database\MySql";
  protected $database_name = "example_db";
  protected $table_name = "person";
  protected $primary_key = "id";
  protected $keys = "";

  public $id = "int(11) not null auto_increment";
  public $email = "varchar(80) not null";
  public $address = "varchar(80) not null";
  public $name = "varchar(80) not null";
  public $age = "int(2) not null";
}