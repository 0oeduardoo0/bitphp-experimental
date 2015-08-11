<?php

  namespace Bitphp\Modules\Http;

  use \Bitphp\Core\Globals;

  /**
   *   Obtiene una entrada limpia de los metodos de entrada
   */
  class Input {

    /**
     *   Para ahorrar codigo tio
     */
    private static function filter($index, $method, $filter) {
      $filter = $filter ? FILTER_SANITIZE_FULL_SPECIAL_CHARS : FILTER_DEFAULT;
      return filter_input($method, $index, $filter);
    }

    /**
     *   Obtiene la entrada limpia de los parametros de la url
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
     */
    public static function post($index, $filter = true) {
      return self::filter($index, INPUT_POST, $filter);
    }

    /**
     *   Obtiene una entrada limpia de $_GET[$index]
     */
    public static function get($index, $filter = true) {
      return self::filter($index, INPUT_GET, $filter);
    }

    /**
     *   Obtiene una entrada limpia de $_COOKIE[$index]
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