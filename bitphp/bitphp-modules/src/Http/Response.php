<?php

	namespace Bitphp\Modules\Http;

	class Response {

		protected static $statusCode;

		public static function statusCode( $code ) {
			self::$statusCode = $code;
		}

		public static function getStatusMessage() {
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
        $this->statusCode(500);
    		trigger_error("Codigo de estado '$code' invalido");
    		return;
    	}

    	return $status[ $code ];
		}

		public static function xml( $data ) {
			$statusCode = self::$statusCode;
			$statusMessage = self::getStatusMessage();

			header( "HTTP/1.1 $statusCode $statusMessage" );
      header( 'Content-Type: application/xml;charset=utf-8' );
      echo $data;
		}

		public static function json( $data ) {
			$statusCode = self::$statusCode;
			$statusMessage = self::getStatusMessage();

			header( "HTTP/1.1 $statusCode $statusMessage" );
      header( 'Content-Type: application/json;charset=utf-8' );
      echo $data;
		}

    public static function error() {  }
    public static function redir() {  }
    public static function jsRedir() {  }
	}