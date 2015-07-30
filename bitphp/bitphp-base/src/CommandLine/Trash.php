<?php

   namespace Bitphp\Base\CommandLine;

   use \Bitphp\Core\Config;
   use \Bitphp\Core\Globals;
   use \Bitphp\Modules\Utilities\File;
   use \Bitphp\Modules\Cli\StandardIO;
   use \Bitphp\Modules\Utilities\Random;

   class Trash {
      private static function configSkip($files) {
         $skipeds = Config::param('trash.ignore');
         if($skipeds === null)
            return $files;
         
         foreach ($skipeds as $skiped) {
            $pattern = str_replace('/', '\\/', $skiped);
            $result = preg_grep('/^' . $pattern . '$/', $files);
            foreach ($result as $index => $name) {
               StandardIO::output("   [bold_purple]$name ignorado...");
               unset($files[$index]);
               usleep(25000);
            }
         }

         return $files;
      }

      private static function apply($files) {
         $meta = array();

         StandardIO::output("\n   Se almacenara un respaldo en papelera");
         StandardIO::output("   ingresa un nombre para dicho respaldo");
         StandardIO::output("\n ~ Nombre de respaldo (opcional): ", null, false);

         $input = StandardIO::input();

         $backup_name   = 'backup-' . ($input != '' ? $input : Random::string(6));
         $backup = Globals::get('base_path') . '/olimpus/bitphp-trash/' . $backup_name;

         StandardIO::output(" ~ Respaldo en papelera [bold_blue]$backup_name");

         foreach ($files as $file) {
            $hash = md5($file);
            $meta[$hash] = $file;
            File::write("$backup/$hash.bck", file_get_contents($file));
         }

         $meta['date']  = $date = date(DATE_ISO8601);
         $meta['count'] = count($files);

         File::write("$backup/meta.json", json_encode($meta, JSON_PRETTY_PRINT));
         StandardIO::output("[reset]   [back_white]Aplicacion removida");
      }

      private static function adjust($files) {

         $files = self::configSkip($files);
      
         $count = 0;
         foreach ($files as $file) {
            StandardIO::output("[bold_red]   $file");
            $count++;
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

         $from_config = Config::param('trash.scan');
         if($from_config !== null) {
            $dirs = array_merge($dirs, $from_config);
         }

         foreach ($dirs as $dir) {
            StandardIO::output("   [bold_blue]$dir...");
            $files = array_merge($files, File::explore($dir));
            usleep(25000);
         }

         self::adjust($files);
      }
   }