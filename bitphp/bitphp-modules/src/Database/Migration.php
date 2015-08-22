<?php
   
   namespace Bitphp\Modules\Database;

   use \Bitphp\Core\Globals;
   use \Bitphp\Modules\Utilities\File;

   class Migration {

      protected static function generateSeed($class) {
         $seed = '';

         // false in construct for prevent conection
         $object = new $class(false);

         $class_map = $object->map();
         $table_map = $object->tableMap();

         $migration_name  = ucwords($class_map['database'], '_');
         $migration_name .= '_' . ucwords($class_map['table'], '_');
         $migration_name .= '_Migration';

         if(!$class_map['provider']) {
            trigger_error("No se indico un proveedor de conexion en $class");
            return null;
         }

         $seed .= '<?php'. PHP_EOL;
         $seed .= PHP_EOL . 'namespace App\\Migrations;' . PHP_EOL . PHP_EOL;
         $seed .= "class $migration_name {" . PHP_EOL;
         $seed .= PHP_EOL . '  use \\Bitphp\\Modules\\Database\\Migration\\Seed;' . PHP_EOL . PHP_EOL;
         $seed .= '  protected $provider = "' . $class_map['provider'] . '";' . PHP_EOL;
         $seed .= '  protected $database_name = "' . $class_map['database'] . '";' . PHP_EOL;
         $seed .= '  protected $table_name = "' . $class_map['table'] . '";' . PHP_EOL;
         $seed .= '  protected $primary_keys = "' . $table_map['primary_keys'] . '";' . PHP_EOL;
         $seed .= '  protected $keys = "' . $table_map['keys'] . '";' . PHP_EOL;
         $seed .= PHP_EOL;

         foreach ($table_map['columns'] as $comlumn => $value) {
            $seed .= "  public \$$comlumn = \"$value\";" . PHP_EOL;
         }

         $seed .= '}';

         $migrations_path = Globals::get('base_path') . '/app/migrations';
         $seed_path = $migrations_path . "/$migration_name.php";
         File::write($seed_path, $seed);

         return $seed_path;
      }

      public static function seed($subject) {
         $seeds = array();

         list($database, $table) = explode('/', $subject);
         
         $database = ucwords($database, '_');
         $table = ucwords($table, '_');

         $database = ($database == 'All') ? '' : $database;
         $table = ($table == 'All') ? '' : "$table.php";

         $models_path = Globals::get('base_path') . "/app/models";
         $models = File::explore("$models_path/$database/$table");

         foreach ($models as $model) {
            if(is_file($model)) {
               //getting the class with namespace from path
               $class = str_replace($models_path, '', $model);
               $class = dirname($class) . '\\' . basename($class, '.php');
               $class = '\\App\\Models' . str_replace('/', '\\', $class);

               $seeds[] = self::generateSeed($class);
            }
         }

         return $seeds;
      }
   }