<?php

  namespace Bitphp\Modules\Http;

  use \Bitphp\Core\Globals;

  /**
   *  Obtiene una entrada limpia de los metodos de entrada
   *
   *  @author Eduardo B Romero
   */
  class Input {

    /**
     *   Filtra el indice del metodo HTTP indicado
     *
     *   @param string $index Indice del metodo
     *   @param int $method Metodo HTTP se pueden usar las consatantes INPUT_POST, etc.
     *   @param bool $filter Activa/Desactiva el filtro completo
     *   @return string valor leido o null si no existe
     */
    private static function filter($index, $method, $filter) {
      $filter = $filter ? FILTER_SANITIZE_FULL_SPECIAL_CHARS : FILTER_DEFAULT;
      return filter_input($method, $index, $filter);
    }

    /**
     *   Obtiene la entrada (estrictamente limpia) de los parametros de la url
     *
     *   @param mixed $index NOmbre o posicion de la variable a leer
     *   @return string Valor leido o null si no existe
     */
    public static function url($index) {
      $parms = Globals::get('uri_params');

      if(is_numeric($index)) {
        if(!isset($parms[$index]))
          return null;
        
        $result = $parms[$index];
      } else {
        $index = array_search($index, $parms);
        $result = self::url($index + 1);
      }

      return $result;
    }

    /**
     *   Obtiene una entrada limpia de $_POST[$index]
     *   el segundo parametro en false desactiva el filtro
     *
     *   @param string $index Indice a leer ($_POST['indice'])
     *   @param bool $filter Activa/desactiva el filtro, true por defecto
     *   @return string Valor leido o null si no existe
     */
    public static function post($index, $filter = true) {
      return self::filter($index, INPUT_POST, $filter);
    }

    /**
     *  Obtiene una entrada limpia de $_GET[$index]
     *
     *  @param string $index Indice a leer ($_GET['index>'])
     *  @param bool $filter Indica si se debe aplicar o no un filtro, por defecto en true
     *  @return string Valor leido o null si el indice no existe
     */
    public static function get($index, $filter = true) {
      return self::filter($index, INPUT_GET, $filter);
    }

    /**
     *   Obtiene una entrada limpia de $_COOKIE[$index]
     *
     *  @param string $index Indice a leer ($_COOKIE['index>'])
     *  @param bool $filter Indica si se debe aplicar o no un filtro, por defecto en true
     *  @return string Valor leido o null si el indice no existe
     */
    public static function cookie($index, $filter = true) {
      return self::filter($index, INPUT_COOKIE, $filter);
    }

    /**
     *  Entrada estandar
     */
    public static function standard($index, $filter = true) {
      $input = file_get_contents('php://input');
      parse_str($input, $array);

      if(!isset($array[$index]))
        return null;

      if(!$filter)
        return $array[$index];

      return filter_var($array[$index], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
  }