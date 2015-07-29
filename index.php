<?php

   require 'bitphp/autoload.php';

    use \Bitphp\Base\MicroServer;
    use \Bitphp\Core\Globals;

    $app = new MicroServer();

    $app->doGet('/', function() use ($app) {
       var_dump(Globals::all());
    });

    try {
      $app->run();
    } catch (Exception $e) {
      trigger_error($e->getMessage());
    }
