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

      public function createDatabaseQuery($name) {
        $query = "CREATE DATABASE IF NOT EXISTS $name DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;";
        return $query;
      }

      /**
       * Crea una tabla en base a las propiedades PUBLICAS de la clase
       * 
       * @return void
       */
      public function createTableQuery($name) {
         $properties = get_class_vars(get_class($this));

         $query = PHP_EOL . "CREATE TABLE IF NOT EXISTS $name (" . PHP_EOL;

         $qkeys = array();

         foreach ($properties as $property => $value) {
            $reflection = new ReflectionProperty(get_class($this), $property);
            if($reflection->isPublic())
              $qkeys[] = "  $property $value";
         }

         if(!empty($properties['keys'])) {
          $keys = explode(',', $properties['keys']);
          
          foreach ($keys as $key) {
            $qkeys[] = "  KEY $key ($key)";
          }
         }

         if(!empty($properties['primary_key'])) {
          $keys    = explode(',', $properties['primary_key']);
          
          foreach ($keys as $key) {
            $qkeys[] = "  PRIMARY KEY ($key)";
          }
         }

         $query .= implode(", " . PHP_EOL, $qkeys);
         $query .= PHP_EOL . ") engine=innodb DEFAULT charset=utf8;";
         return $query;
      }

      /**
       * Crea la tabla y base de datos si no existen
       *
       * @return void
       */
      public function up() {
         $this->database = new $this->provider;
         $this->database_name = $this->database->realName($this->database_name);

         if(!$this->databaseExists($this->database_name)) {
            $query = $this->createDatabaseQuery($this->database_name);
            $this->database->execute($query);

            if(false !== ($error = $this->database->error()))
              trigger_error($error);
         }

         $this->database->database($this->database_name);

         if(!$this->tableExists($this->table_name)) {
            $query = $this->createTableQuery($this->table_name);
            $this->database->execute($query);
            
            if(false !== ($error = $this->database->error()))
              trigger_error($error);
         }
      }

      public function down() {
        $this->database = new $this->provider;
        $this->database_name = $this->database->realName($this->database_name);
        
        $this->database->database($this->database_name);
        $this->database->execute("DROP TABLE $this->table_name");

        if(false !== ($error = $this->database->error()))
              trigger_error($error);
      }
   }