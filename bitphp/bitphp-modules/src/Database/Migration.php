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

         $migration_name  = ucwords($class_map['table'], '_');
         $migration_name .= '_Seed';

         $database_migration_name = ucwords($class_map['database'], '_')  . '_Migration';

         if(!$class_map['provider']) {
            trigger_error("No se indico un proveedor de conexion en $class");
            return null;
         }

         $seed .= '<?php'. PHP_EOL;
         $seed .= PHP_EOL . "namespace App\\Migrations\\$database_migration_name;" . PHP_EOL . PHP_EOL;
         $seed .= "class $migration_name {" . PHP_EOL;
         $seed .= PHP_EOL . '  use \\Bitphp\\Modules\\Database\\Migration\\Seed;' . PHP_EOL . PHP_EOL;
         $seed .= '  protected $provider = "' . $class_map['provider'] . '";' . PHP_EOL;
         $seed .= '  protected $database_name = "' . $class_map['database'] . '";' . PHP_EOL;
         $seed .= '  protected $table_name = "' . $class_map['table'] . '";' . PHP_EOL;
         $seed .= '  protected $primary_key = "' . $table_map['primary_key'] . '";' . PHP_EOL;
         $seed .= '  protected $keys = "' . $table_map['keys'] . '";' . PHP_EOL;
         $seed .= PHP_EOL;

         foreach ($table_map['columns'] as $comlumn => $value) {
            $seed .= "  public \$$comlumn = \"$value\";" . PHP_EOL;
         }

         $seed .= '}';

         $migrations_path = Globals::get('base_path') . "/app/migrations/$database_migration_name";
         $seed_path = $migrations_path . "/$migration_name.php";
         File::write($seed_path, $seed);
         return $seed_path;
      }

      protected function upOrDown($subject, $action) {
         $tables = array();

         list($database, $table) = explode('/', $subject);
         
         $database = ucwords($database, '_');
         $table = ucwords($table, '_');

         $database = ($database == 'All') ? '' : $database . '_Migration';
         $table = ($table == 'All') ? '' : $table . '_Seed.php';

         $migrations_path = Globals::get('base_path') . "/app/migrations";
         $seeds = File::explore("$migrations_path/$database/$table");

         foreach ($seeds as $seed) {
            if(is_file($seed)) {
               //getting the class with namespace from path
               $class = str_replace($migrations_path, '', $seed);
               $class = dirname($class) . '\\' . basename($class, '.php');
               $class = '\\App\\Migrations' . str_replace('/', '\\', $class);

               $seed = new $class();

               if($action == 'up') {
                  $seed->up();
                  $tables[] = "Up $class...";
               } else {
                  $seed->down();
                  $tables[] = "Down $class...";
               }
            }
         }

         return $tables;
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

      public static function up($subject) {
         return self::upOrDown($subject, 'up');
      }

      public static function down($subject) {
         return self::upOrDown($subject, 'down');
      }
   }