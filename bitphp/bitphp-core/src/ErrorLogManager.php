<?php

   namespace Bitphp\Core;

   use \Bitphp\Modules\Utilities\TimeDiff;
   use \Bitphp\Modules\Utilities\File;

   /**
    * Clase para la manipulacion y control del archivo de logeo de errores
    *
    * @author Eduardo B Romero
    */
   class ErrorLogManager {

      /**
       * Retorna un arreglo qué contiene las cadenas json
       * de los errores registrados
       *
       * @return array
       */
      private static function readErrorLog() {
         $errors  = file_get_contents('olimpus/log/errors.log');
         return explode("\n", $errors);
      }

      /**
       * Elimina el archivo de registro de errores
       *
       * @return bool Segun el caso de exito o fracaso en la accion
       */
      public static function dump() {
         return @unlink('olimpus/log/errors.log');
      }

      /**
       *
       */
      public static function search($search) {
         $errors = self::readErrorLog();
         $info = "\n";

         foreach ($errors as $error) {
            $error = json_decode($error, true);

            if(empty($error))
               continue;

            $id   = $error['id'];

            if($search == $id) {
               $date = TimeDiff::getTimeAgo($error['date']);
               $message = $error['message'];
               $file = $error['file'];
               $line = $error['line'];
               $url  = $error['request_uri'];

               $info .= "[bold_white] ~ Se produjo hace $date\n";
               $info .= "[bold_white]   Error [bold_blue]$id\n";
               $info .= "[bold_white]   Lanzado desde [bold_blue]$file ";
               $info .= "[bold_white]en la linea [bold_blue]$line\n";
               $info .= "[bold_white]   Url [bold_blue]$url\n\n";
               $info .= "[bold_red] ~ $message\n";

               if(!empty($error['trace'])) {

                  $info .= "\n[bold_white] ~ Stacktrace\n";

                  foreach ($error['trace'] as $trace) { 

                     if(!empty($trace['file'])) {
                        $file = $trace['file'];
                        $line = $trace['line'];

                        $info .= "[bold_white]   Dentro de [bold_blue]$file ";
                        $info .= "[bold_white]en la linea [bold_blue]$line\n";
                     }
                  }
               }

               return $info;
            }
         }

         $info = "[bold_red]   Registro $search no encontrado...";
         return $info;
      }

      public static function generateList() {
         $errors  = self::readErrorLog();

         $list = " ~ Registro de errores\n";

         $counter = 0;
         foreach ($errors as $error) {
            $error = json_decode($error, true);

            if(empty($error))
               continue;

            $id   = $error['id'];
            $date = TimeDiff::getTimeAgo($error['date']);
            $message = substr($error['message'], 0, 60);

            $list .= "[bold_white]   ($id) - Hace $date\n   [bold_red]$message...\n";
            $counter++;
         }

         $list .= "   [back_white] $counter Errores en total ";
         return $list;
      }
   }