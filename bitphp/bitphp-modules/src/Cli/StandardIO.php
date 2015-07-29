<?php

   namespace Bitphp\Modules\Cli;

   use \Bitphp\Modules\Cli\Colors;

   class StandardIO {

      public static function output( $string, $type = null, $new_line = "\n" ) {
         
         $color = '';
         switch ( $type ) {
            case 'ERROR':
               $color = '[back_red]';
               break;

            case 'INFO':
               $color = '[bold_cyan]';
               break;

            case 'SUCCESS':
               $color = '[bold_green]';
               break;

            case 'EMPASIS':
               $color = '[back_green]';
               break;

            case 'WARNING':
               $color = '[bold_yellow]';
               break;

            case 'FINAL':
               $color = '[back_white]';
               break;

            default:
               $color = '[bold_white]';
               break; 
         }

         //error_log(  );
         echo Colors::paint( $color . $string . '[reset]' . $new_line );
      }

      public static function input() {
         echo " ";
         return trim( fgets( STDIN ) );
      }
   }