<?php

  namespace Bitphp\Modules\Http;

  use \Bitphp\Core\Globals;

  class Response {

    protected static $statusCode;

    protected static function getStatusCode() {
      return empty(self::$statusCode) ? 200 : self::$statusCode;
    }

    protected static function getStatusMessage() {
      $code = empty(self::$statusCode) ? 200 : self::$statusCode;

      $status = [
        200 => 'OK',  
        201 => 'Created',  
        202 => 'Accepted',  
        204 => 'No Content',  
        301 => 'Moved Permanently',  
        302 => 'Found',  
        303 => 'See Other',  
        304 => 'Not Modified',
        400 => 'Bad Request',  
        401 => 'Unauthorized',  
        403 => 'Forbidden',  
        404 => 'Not Found',  
        405 => 'Method Not Allowed',  
        500 => 'Internal Server Error'
      ];

      if ( !isset( $status[ $code ] ) ) {
        trigger_error("Codigo de estado '$code' invalido");
        return;
      }

      return $status[ $code ];
    }

    public static function status( $code ) {
      self::$statusCode = $code;
    }

    public static function xml( $data ) {
      $statusCode = self::$statusCode;
      $statusMessage = self::getStatusMessage();

      header( "HTTP/1.1 $statusCode $statusMessage" );
      header( 'Content-Type: application/xml;charset=utf-8' );
      echo $data;
    }

    public static function json( $data ) {
      $statusCode = self::getStatusCode();
      $statusMessage = self::getStatusMessage();

      header( "HTTP/1.1 $statusCode $statusMessage" );
      header( 'Content-Type: application/json;charset=utf-8' );
      echo $data;
    }

    public static function redir( $url, $delay = 0 ) {
      if(!preg_match('/^(\w+)(\:\/\/)(.*)$/', $url)) {
        $url = Globals::get('base_url') . $url;
      }

      if($delay > 0) {
        require Globals::get('base_path') . '/olimpus/static_pages/redir.php';
        return;
      }

      header("Location: $url");
    }
  }