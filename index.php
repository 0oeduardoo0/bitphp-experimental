<?php

  /**
   *  Bitphp Framework
   */

  require 'bitphp/autoload.php';

  use \Bitphp\Base\MicroServer;
  use \Bitphp\Modules\Layout\Medusa;

  $server   = new MicroServer();
  $template = new Medusa();

  $server->doGet('/', function() use ($template) {
    $template->load('welcome')
             ->draw();
  });

  $server->run();