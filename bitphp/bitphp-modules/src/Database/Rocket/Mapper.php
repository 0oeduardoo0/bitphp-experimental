<?php
   
   namespace Bitphp\Modules\Database\Rocket;

   use \ReflectionProperty;

   /**
    * Trait para el mapeo relacional
    * 
    * @author Eduardo B Romero
    */
   trait Mapper {

      /**
       * Determina si la tabla de la clase existe
       *
       * @return bool
       */
      private function tableExists() {
         $this->database->execute("SELECT 1 FROM $this->table LIMIT 1");
         if($this->database->error())
            return false;

         return true;
      }

      /**
       * Crea una tabla en base a las propiedades PUBLICAS de la clase
       * 
       * @return void
       */
      private function createTable() {
         $properties = get_class_vars(get_class($this));
         
         if(!isset($properties['primary']))
          trigger_error("La tabla no existe y no se indico una llave primaria para crearla");

         $primary = $properties['primary'];
         $engine  = isset($properties['engine'])  ? $properties['engine']  : 'innodb';
         $charset = isset($properties['charset']) ? $properties['charset'] : 'utf8';

         $query = "CREATE TABLE IF NOT EXISTS $this->table (";

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
      }

      /**
       * Determina el nombre de la bd en base nombre de espacio
       *
       * @return string Nombre de la base de datos
       */
      private static function dataBaseName() {
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
         
         $db_name = $this->dataBaseName();
         if(isset($this->alias))
            $db_name = "alias.$db_name";

         $this->database->database($db_name);
         $this->table = $this->tableName();

         if(!$this->tableExists())
            $this->createTable();
      }
   }