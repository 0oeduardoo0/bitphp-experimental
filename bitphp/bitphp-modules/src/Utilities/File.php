<?php

   namespace Bitphp\Modules\Utilities;

   class File {
      public static function write( $file, $content ) {
         $dir = dirname($file);
         
         if(!is_dir($dir))
               mkdir($dir, 0777, true);

         $file = fopen( $file, 'w' );
         fwrite( $file, $content );
         fclose( $file );
      }
   }