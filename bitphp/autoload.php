<?php 
// Si existe el cargador de composer lo utiliza
if(file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
	//si no, usa su propio cargador
    require 'bitphp/self_autoload.php';
}