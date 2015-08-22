<?php
   
   namespace Bitphp\Modules\Database\Migration;

   use \ReflectionProperty;

   /**
    * ¡Es importante qué el usuario de la conexion tenga privilegios para crear bases de datos y tablas!
    * 
    * @author Eduardo B Romero
    */
   trait Seed {

      private $database;

      /**
       * Determina si la tabla de la clase existe
       *
       * @return bool
       */
      private function tableExists($name) {
         $this->database->execute("SELECT 1 FROM $name LIMIT 1");
         if($this->database->error())
            return false;

         return true;
      }

      private function databaseExists($name) {
        $this->database->execute("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$name'");
        if(empty($this->database->result()))
          return false;

        return true;
      }

      private function createDatabase($name) {
        $query = "CREATE DATABASE IF NOT EXISTS $name DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;";
        $this->database->execute($query);
      }

      /**
       * Crea una tabla en base a las propiedades PUBLICAS de la clase
       * 
       * @return void
       */
      private function createTable($name) {
         $properties = get_class_vars(get_class($this));
         
         $primary = $properties['primary'];
         $engine  = isset($properties['engine'])  ? $properties['engine']  : 'innodb';
         $charset = isset($properties['charset']) ? $properties['charset'] : 'utf8';

         $query = "CREATE TABLE IF NOT EXISTS $name (";

         foreach ($properties as $property => $value) {
            $reflection = new ReflectionProperty(get_class($this), $property);
            if($reflection->isPublic())
              $query .= "$property $value, ";
         }

         if(isset($properties['keys'])) {
          $keys    = explode(',', $properties['keys']);
          
          foreach ($keys as $key) {
            $query .= "KEY $key ($key), ";
          }
         }

         $query .= "PRIMARY KEY ($primary) ) engine=$engine DEFAULT charset=$charset";

         $this->database->execute($query);

         if(false !== ($error = $this->database->error()))
          trigger_error($error);
      }

      /**
       * Crea la tabla y base de datos si no existen
       *
       * @return void
       */
      public function up() {
         $this->database = new $this->provider;

         $this->database_name = $this->database->realName($this->database_name);

         if(!$this->databaseExists($this->database_name))
            $this->createDatabase($this->database_name);

         $this->database->database($this->database_name);

         if($this->tableExists($this->table_name))
            $this->createTable($this->table_name);
      }
   }