<?php

namespace App\Migrations\Example_Db_Migration;

use \Bitphp\Modules\Database\Migration\Seed;

class Department_Seed extends Seed {

  protected $provider = "\Bitphp\Modules\Database\MySql";
  protected $database_name = "example_db";
  protected $table_name = "department";

  public function setup() {
    $this->field('id int(11) not null auto_increment');
    $this->field('name varchar(80) not null');
    $this->field('owner_id int(2) not null');

    $this->foreignKey('(owner_id) references person(id)');
    $this->primaryKey('id');
  }
}