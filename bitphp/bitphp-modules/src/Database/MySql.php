<?php 

   namespace Bitphp\Modules\Database;

   use \PDO;
   use \Bitphp\Modules\Database\Provider;

   /**
    *   Proporciona una capa de abstracción para una conexión 
    *   a basesde datos mysql a través de PDO
    */   
   class MySql extends Provider {
      
      private $statement;
      public $pdo;

      public function __construct() {
         parent::__construct();
      }

      public function database($name) {
         # obtiene el nombre real, por si es un alias
         $name = $this->realName($name);
         $params = 'mysql:host='.$this->host.';dbname='.$name.';charset=utf8';
         $this->pdo = new PDO($params, $this->user, $this->pass);
         return $this;
      }

      public function execute($query) {
         $this->statement = $this->pdo->query($query);
         return $this;
      }

      public function error() {
         $error = $this->pdo->errorInfo()[2];

         if($error === null)
            return false;

         return $error;
      }

      public function result() {
         if(false !== ($error = $this->error())) {
            trigger_error($error);
            return false;
         }
         
         return $this->statement->fetchAll(PDO::FETCH_ASSOC);
      }
   }