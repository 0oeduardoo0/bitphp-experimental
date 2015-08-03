<?php

   namespace Bitphp\Modules\Utilities;

   class File {

      public static function explore($dir, $recursive = true) {
         if(!is_dir($dir)) {
            if(is_file($dir)) {
               return [$dir];
            }

            return [];
         }

         $dir = realpath($dir);
         $list = array($dir);
         $dir_obj = dir($dir);
         while (false !== ($item = $dir_obj->read())) {
            if($item == '.' || $item == '..')
               continue;

            $complete_path = "$dir/$item";
            if(is_dir($complete_path)) {
               if($recursive) {
                  $list = array_merge($list, self::explore($complete_path));
               } else {
                  $list[] = $complete_path;
               }

               continue;
            }

            $list[] = $complete_path;
         }

         $dir_obj->close();
         return $list;
      }

      public static function write( $file, $content ) {
         $dir = dirname($file);
         
         if(!is_dir($dir))
               mkdir($dir, 0777, true);

         $file = fopen( $file, 'w' );
         fwrite( $file, $content );
         fclose( $file );
      }
   }