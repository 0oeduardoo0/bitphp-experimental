<?php

  require 'bitphp/autoload.php';

  use \Bitphp\Base\MicroServer;
  use \Bitphp\Modules\Layout\Medusa;
  use \Bitphp\Modules\Http\Response;

  $server = new MicroServer();

  $server->doGet('/', function() {
    Medusa::quick('welcome');
  });

  $server->doGet('/old', function(){
    Response::redir('/', 10);
  });

  $server->run();