<?php 
   
   namespace Bitphp\Modules\Layout;

   use \Bitphp\Core\Globals;
   use \Bitphp\Core\Config;
   use \Bitphp\Core\Cache;

   /**
    *   Modulo para el manejo de vistas
    *
    *   @author Eduardo B Romero
    */
   class View {

      protected $loaded;
      protected $variables;
      protected $mime;
      protected $output_buffer;
      public $source;

      /**
       *   Limpia todo para poder volver a usarlo con otras vistas
       */
      protected function clean() {
         $this->source = '';
         $this->loaded = array();
         $this->variables = array();
      }

      protected function render() {
         foreach ($this->loaded as $file) {
            $this->source .= file_get_contents($file);
         }
      }

      public function __construct() {
         $this->clean();
         $this->output_buffer = 'empty';
         $this->mime = '.php';

         //set cache angent
         Cache::$agent = 'views';
      }

      /**
       *   Lee y carga el contenido de una vista a $this->source
       *   solo si existe la vista
       */
      public function load($name) {

         $file = Globals::get('base_path') . "/app/views/$name" . $this->mime;
         if(false === file_exists($file)) {
            $message  = "No se pudo cargar la vista '$name.' ";
            $message .= "El fichero '$file' no existe";
            trigger_error($message);
            return false;
         }

         $this->loaded[] = $file;
         return $this;
      }

      /**
       *   Setea las variables qué se le pasaran a la vista
       */
      public function with($vars) {
         $this->variables = $vars;
         return $this;
      }

      /**
       * Imprime la vista
       */
      public function make() {
         if(empty($this->loaded)) {
            $message  = 'No se pudo mostrar la(s) vista(s) ';
            $message .= 'ya que no se han cargado ninguna';
            trigger_error($message);
            return;
         }

         $this->output_buffer = Cache::read([$this->loaded, $this->variables]);

         if(false !== $this->output_buffer)
            return;

         $this->render();
         $_ROUTE = Globals::all();

         ob_start();
         extract($this->variables);
         eval("?> $this->source <?php ");
         $this->output_buffer = ob_get_clean();

         Cache::save([$this->loaded, $this->variables], $this->output_buffer);
         $this->clean();
         return $this;
      }

      public function draw() {
        echo $this->output_buffer;
      }

      public function read() {
        return $this->output_buffer;
      }

      /**
       *   Metodo estatico para cargar, setear variables y mostar
       *   la vista en un solo paso
       */
      public static function quick($name, $vars = array()) {
         $loader = new View();
         $loader->load($name)->with($vars)->make()->draw();
         $loader = null;
      }
   }