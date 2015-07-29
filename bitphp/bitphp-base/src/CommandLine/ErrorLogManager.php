<?php

   namespace Bitphp\Base\CommandLine;

   use \Bitphp\Modules\Utilities\TimeDiff;
   use \Bitphp\Modules\Utilities\File;

   class ErrorLogManager {

      private static function readErrorLog() {
         $errors  = file_get_contents('olimpus/log/errors.log');
         return explode("\n", $errors);
      }

      public static function dump() {
         File::write('olimpus/log/errors.log', '');
      }

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

               $info .= "[bold_white]   $date\n";
               $info .= "[bold_white]   Error [bold_blue]$id\n";
               $info .= "[bold_white]   Lanzado desde [bold_blue]$file ";
               $info .= "[bold_white]en la linea [bold_blue]$line\n";
               $info .= "[bold_white]   Url [bold_blue]$url\n\n";
               $info .= "   [bold_red]$message...\n";

               if(!empty($error['trace'])) {

                  $info .= "\n[bold_white]   ~ Stacktrace\n";

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

            $list .= "[bold_white]   ($id) - $date\n   [bold_red]$message...\n";
            $counter++;
         }

         $list .= "   [back_white] $counter Errores en total ";
         return $list;
      }
   }