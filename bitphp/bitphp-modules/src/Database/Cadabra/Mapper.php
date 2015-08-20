<?php
   
   namespace Bitphp\Modules\Database\Cadabra;

   use \ReflectionProperty;
   use \PDOException;

   /**
    * Trait para el mapeo relacional
    *
    * ¡Es importante qué el usuario de la conexion tenga privilegios para crear bases de datos y tablas!
    * 
    * @author Eduardo B Romero
    */
   trait Mapper {

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
         
         if(!isset($properties['primary']))
          trigger_error("La tabla no existe y no se indico una llave primaria para crearla");

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
       * Determina el nombre de la bd en base nombre de espacio
       *
       * @return string Nombre de la base de datos
       */
      private static function databaseName() {
         $parts = explode('\\', __CLASS__);
         return strtolower($parts[2]);
      }

      /**
       * Determina el nombre de la tabla en base nombre de espacio
       *
       * @return string Nombre de la tabla
       */
      private static function tableName() {
         $parts = explode('\\', __CLASS__);
         return strtolower($parts[3]);
      }

      /**
       * Crea el objeto del proveedor en $this->database
       * Determina el nombre de la base de datos y la conecta a través del proveedor
       * Determina el nombre de la base de datos y la setea en $this->database
       * Crea la tabla si no existe
       *
       * @return void
       */
      public function map() {
         if(!isset($this->provider))
          trigger_error("No se indico el proveedor de base de datos");

         $this->database = new $this->provider;
         
         $database = $this->databaseName();
         $table = $this->tableName();

         if(isset($this->alias))
            $database = "alias.$db_name";


         $create = isset($this->dont_create) ? false : true;

         if($create && !$this->databaseExists($database))
            $this->createDatabase($database);

         $this->database->database($database);

         if($create && !$this->tableExists($table))
            $this->createTable($table);

         $this->table = $table;
      }
   }