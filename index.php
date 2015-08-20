<?php

  require 'bitphp/autoload.php';

  use \Bitphp\Base\MicroServer;
  use \Bitphp\Modules\Layout\Medusa;

  $server = new MicroServer();

  $foo = new \App\Models\Example_Db\Person();

  $server->doGet('/', function() {
    Medusa::quick('welcome');
  });

  $server->run();