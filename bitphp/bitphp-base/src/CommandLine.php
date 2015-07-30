<?php

   namespace Bitphp\Base;

   use \Bitphp\Core\Globals;
   use \Bitphp\Base\CommandLine\Pattern;
   use \Bitphp\Modules\Cli\Arguments;

   class CommandLine {

      protected $commands;
      protected $binded;
      public $standard;

      private function runnigInWebServer() {
         if( !empty($_SERVER['SERVER_NAME']) ) {
            return true;
         }

         return false;
      }

      public function doCommand($command, $callback) {
         $command = Pattern::create($command);
         $this->commands[$command] = $callback;
      }

      public function set( $item, $value ) {
         if(is_callable($value)) {
            $this->binded[$item] = Closure::bind($value, $this, get_class());
            return;
         }

         $this->$item = $value;
      }

      public function run() {
         if($this->runnigInWebServer())
            return false;

         $executed = Arguments::command();

         foreach ($this->commands as $command => $callback) {
            if(@preg_match($command, $executed, $arguments)) {
               array_shift($arguments);
               call_user_func_array($callback, $arguments);
               return true;
            }
         }

         $default = Pattern::create('default');

         if(isset($this->commands[$default])) {
            call_user_func($this->commands[$default]);
            return true;
         }

         return false;
      }

      public function __call($method, array $args) {
         if(isset($this->binded[$method])) {
            return call_user_func_array($this->binded[$method], $args);
         }

         throw new Exception('La clase ' . __CLASS__ . " no contiene el metodo $method", 1);
      }

      public function __construct() {
         Globals::registre([
              'base_path' => realpath('')
         ]);

         $this->commands = array();
      }
   }