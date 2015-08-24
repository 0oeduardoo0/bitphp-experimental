<?php

namespace App\Migrations\Example_Db_Migration;

class Department_Seed {

  use \Bitphp\Modules\Database\Migration\Seed;

  protected $provider = "\Bitphp\Modules\Database\MySql";
  protected $database_name = "example_db";
  protected $table_name = "department";
  protected $primary_key = "id";
  protected $keys = "(owner_id) references person(id)";

  public $id = "int(11) not null auto_increment";
  public $name = "varchar(80) not null";
  public $owner_id = "int(2) not null";
}