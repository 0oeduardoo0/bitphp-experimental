<?php

   require 'bitphp/autoload.php';

   use \Bitphp\Base\MvcServer;

   $server = new MvcServer();
   $server->run();