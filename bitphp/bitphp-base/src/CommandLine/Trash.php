<?php

   namespace Bitphp\Base\CommandLine;

   use \Bitphp\Core\Config;
   use \Bitphp\Core\Globals;
   use \Bitphp\Modules\Utilities\File;
   use \Bitphp\Modules\Cli\StandardIO;
   use \Bitphp\Modules\Utilities\Random;

   class Trash {

      private static function apply($files) {
         $meta = array();
         $meta['files'] = array();

         StandardIO::output("\n   Se almacenara un respaldo en papelera");
         StandardIO::output("   ingresa un nombre para dicho respaldo");
         StandardIO::output("\n ~ Nombre de respaldo (opcional): ", null, false);

         $input = StandardIO::input();

         $backup_name   = $input != '' ? $input : Random::string(6);
         $backup = Globals::get('base_path') . '/olimpus/bitphp-trash/' . $backup_name;

         if(is_dir($backup))
            $backup = $backup . '_' . Random::number(3);

         StandardIO::output(" ~ Respaldo en papelera [bold_blue]$backup");

         foreach ($files as $file) {
            $hash = md5($file);
            $meta['files'][$hash] = $file;
            File::write("$backup/$hash", file_get_contents($file));
            unlink($file);
         }

         $meta['date']  = $date = date(DATE_ISO8601);
         $meta['count'] = count($files);

         File::write("$backup/meta.json", json_encode($meta, JSON_PRETTY_PRINT));
         StandardIO::output(" ~ Ficheros de aplicacion removidos");
      }

      private static function indentifySkipeds($files) {
         $skipeds = Config::param('trash.ignore');
         if($skipeds === null)
            return $files;

         StandardIO::output(" ~ Identificando ficheros para ignorar...");
         
         foreach ($skipeds as $skiped) {
            $pattern = str_replace('/', '\\/', $skiped);
            $result = preg_grep('/^' . $pattern . '$/', $files);
            foreach ($result as $index => $name) {
               StandardIO::output("   [bold_blue]$name ignorado...");
               unset($files[$index]);
               usleep(25000);
            }
         }

         return $files;
      }

      private static function removeFiles($files) {
         $files = self::indentifySkipeds($files);
      
         foreach ($files as $file) {
            # File::explore() devuelve en la lista tambien los 
            # directorios escaneados, solo se requieren los archivos
            if(is_dir($file)) {
               $index = array_search($file, $files);
               unset($files[$index]);
               continue;
            }
            
            StandardIO::output("[bold_red]   $file");
            usleep(25000);
         }

         StandardIO::output(' ~ ' . count($files) . " archivos para mover a la papelera");
         if(empty($files))
            return;

         StandardIO::output(" ~ Mover a papelera? [bold_blue][s/N] ", null, false);

         $option = strtolower(StandardIO::input());
         if($option != 's')
            return;

         self::apply($files);
      }

      public static function getBackupsList() {
         $trash_dir = Globals::get('base_path') . '/olimpus/bitphp-trash/';
         $list = File::explore($trash_dir, false);
         array_shift($list);
         
         $backups = array();
         foreach ($list as $backup) {
            $name = basename($backup);
            $meta = file_get_contents($backup . '/meta.json');

            $backups[$name] = [
                 'meta' => json_decode($meta, true)
               , 'path' => $backup
            ];
         }

         return $backups;
      }

      public static function restore($name, $force_restore = null) {
         $files = array();
         $backups = self::getBackupsList();
         foreach ($backups as $backup => $data) {
            if($backup == $name) {
               $total_restore = true;
               $count = $data['meta']['count'];
               StandardIO::output("   $count archivos para restaurar...");

               foreach ($data['meta']['files'] as $file => $original_path) {
                  $file_trash_path = $data['path'] . "/$file";
                  if(!file_exists($file_trash_path)) {
                     $message = "   No se pudo restaurar [bold_red]$original_path [bold_white]";
                     $message .= "el archivo [bold_red]$file_trash_path [bold_white]";
                     $message .= "de respalfo fue eliminado.";
                     StandardIO::output($message);
                     continue;
                  }

                  if(file_exists($original_path)) {
                     $message = "   El archivo $original_path ya existe, desea reemplazarlo? [y/N] ";
                     StandardIO::output($message, null, false);
                     $option = StandardIO::input();
                     if(strtolower($option) !== 'y') {
                        $total_restore = false;
                        continue;
                     }
                  }

                  File::write($original_path, file_get_contents($file_trash_path));
                  StandardIO::output("   $original_path restaurado");
                  unlink($file_trash_path);
               }

               if($total_restore) {
                  StandardIO::output(" ~ Backup restaurado por completo");
                  unlink($data['path'] . '/meta.json');
                  rmdir($data['path']);
               } else {
                  StandardIO::output(" [bold_green]~ Algunos de los ficheros no fueron ser restaurados");
               }

               return;
            }
         }

         StandardIO::output(" [back_red]~ El respaldo $name no existe");
      }

      public static function remove() {
         $files = array();
         $base = Globals::get('base_path');

         Config::load($base . '/app/config.json');
         StandardIO::output(' ~ Analizando directorios...');

         $dirs  = [
              $base . '/app'
            , $base . '/public'
            , $base . '/index.php'
         ];

         $configured_dirs = Config::param('trash.scan');
         if(
                 $configured_dirs !== null
              && is_array($configured_dirs)
           ) 
         {
            $dirs = array_merge($dirs, $configured_dirs);
         }

         foreach ($dirs as $dir) {
            StandardIO::output("   [bold_blue]$dir...");
            $files = array_merge($files, File::explore($dir));
            usleep(25000);
         }

         self::removeFiles($files);
      }
   }