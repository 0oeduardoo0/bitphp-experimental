<?php 

   namespace Bitphp\Core;
   
   use \Exception;
   use \Bitphp\Core\Globals;
   use \Bitphp\Core\Config;
   use \Bitphp\Modules\Layout\Medusa;
   use \Bitphp\Modules\Utilities\File;

   /**
    *   Clase para registrar los error_handlers de bitphp
    *
    *   uso: $errorHandler = new \Bitphp\Core\Error();
    *       $errorHandler->registre();
    *
    *   requiere qué se haya registrado previamente la variable global
    *   "base_path" con la clase \Bitphp\Core\Globals
    *
    *   @author Eduardo B <eduardo@root404.com>
    */
   class Error {
      
      private $errors;
      private $file;
      private $medusa;

      /**
       *  Registra las funciones para el manejo de errores
       *
       */
      public function registre() {
          ini_set('display_errors', 0);
          error_reporting(E_ALL);
          
          set_error_handler(array($this, 'globalErrorHandler'));
          register_shutdown_function(array($this, 'fatalErrorHandler'));

          $this->errors = array();
          $this->file = new File();
          $this->medusa = new Medusa();
          $this->medusa->views_path = Globals::get('base_path') . '/olimpus/system/pages';
      }
      
      /**
       *   Añade un registro de error al archivo de errores
       *   en formato JSON, retorna id del error si el registro
       *   fue satisfactorioo false si este falló
       *
       *   @param int $code indica el codigo de error
       *   @param string $message mensaje de error
       *   @param string $file archivo donde se produjo el error
       *   @param int $line linea donde se produjo el error
       *   @param array $trace trasa de pila
       *   @return mixed false si no se pudo guardar el error, un string
       *                  con el identificador del error si este se guardo
       */
      private function log($code, $message, $file, $line, $trace) {
         $save_log = Config::param('errors.log');
         if(false === $save_log)
          return false;

         # id es un hash md5 formado por la fecha y un numero aleatorio
         $date = date(DATE_ISO8601);
         $identifier  = md5($date . rand(0, 9999));
         $request_uri = Globals::get('base_url') . '/' . Globals::get('request_uri');

         $log = [
              'date' => $date
            , 'message' => $message
            , 'id' => $identifier
            , 'trace' => $trace
            , 'code' => $code
            , 'file' => $file
            , 'line' => $line
            , 'request_uri' => $request_uri
         ];

         $log = json_encode($log) . PHP_EOL;

         $log_file = Globals::get('base_path') . '/olimpus/log/errors.log';
         if(false === $this->file->append($log_file, $log))
          return false;

         return $identifier;
      }

      /**
       *   Bitphp gestiona todos los errores de php
       *
       *   @param int $code codigo de error
       *   @param string $message mensaje del error
       *   @param string $file archivo donde se produjo el error
       *   @param int $line linea donde se produjo el error
       *   @return void
       */
      public function globalErrorHandler($code, $message, $file, $line) {
         $exception = new Exception();
         $trace = $exception->getTrace();

         $identifier = $this->log($code, $message, $file, $line, $trace);
         $this->errors[] = [
              'code' => $code
            , 'message' => $message
            , 'file' => $file
            , 'line' => $line
            , 'identifier' => $identifier
            , 'trace' => $trace
         ];
      }

      /**
       *   Se ejecuta cuando el script finaliza y verifica si hubo errores
       *   en caso de ser así carga la vista de error de bitphp
       *
       *   @return void
       */
      public function fatalErrorHandler() {      
         $error = error_get_last();
         
         if(null !== $error) {
             $this->globalErrorHandler(
                  E_ERROR
                , $error['message']
                , $error['file']
                , $error['line']
             );
         }

         if (!empty($this->errors)) {
            $display = Config::param('errors.debug');
            if(false !== $display)
              $display = true;

            if($display) {
               $this->medusa->load('Error')
                            ->with([
                              'errors' => $this->errors
                            ])
                            ->draw();
            } else {
               header('HTTP/1.1 404 Not Found');
               $this->medusa->load('NotFound')
                            ->draw();
            }
         }
      }
   }