<?php
	require 'bitphp/autoload.php';

    use \Bitphp\Base\MicroServer;

    $app = new MicroServer();
    
    $app->doGet('/hello/(str $name)', function($name){
    	echo "Hola $name";
    });

    try
    {
		$app->run();    
	} catch (Exception $e) {
		trigger_error($e->getMessage());
	}
