<?php

	require 'bitphp/autoload.php';

    use \Bitphp\Base\MicroServer;
    use \App\Models\Personas;

    $app = new MicroServer();

    $app->set('personas', new Personas());

    $app->doGet('/', function() use ($app) {
    	print_r($app->personas->all());
    });

    $app->run();
