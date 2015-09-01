<?php

/**
 * Bitphp Framework
 */

require 'bitphp/autoload.php';

use \Bitphp\Base\MicroServer;
use \Bitphp\Modules\Layout\Medusa;

$server = new MicroServer();

$server->doGet('/', function() {
   Medusa::quick('welcomes');
});

$server->run();