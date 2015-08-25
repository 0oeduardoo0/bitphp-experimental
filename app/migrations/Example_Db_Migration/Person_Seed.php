<?php

namespace App\Migrations\Example_Db_Migration;

use \Bitphp\Modules\Database\Migration\Seed;

class Person_Seed extends Seed {

  protected $provider = "\Bitphp\Modules\Database\MySql";
  protected $database_name = "example_db";
  protected $table_name = "person";

  public function setup() {
    
    $this->field('id int(11) not null auto_increment');
    $this->field('email varchar(80) not null');
    $this->field('address varchar(80) not null');
    $this->field('name varchar(80) not null');
    $this->field('age int(2) not null');

    $this->primaryKey('id');
  }
}