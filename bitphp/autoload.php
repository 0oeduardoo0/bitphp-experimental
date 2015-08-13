<?php

$loader = null;

// Si existe el cargador de composer lo utiliza
if(file_exists('vendor/autoload.php')) {
    $loader = require 'vendor/autoload.php';
} else {
   //si no, usa su propio cargador
    require 'bitphp/self_autoload.php';
}