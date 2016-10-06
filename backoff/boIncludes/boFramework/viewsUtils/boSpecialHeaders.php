<?php

/**
 *
 *
 */
class boSpecialHeaders{

	const HTML = "text/html"; // useful ?
	const TEXT = "text/plain";
	const XML = "text/xml";
	const CSV = "text/csv";
	const CSS = "text/css";
	const JAVASCRIPT = "application/javascript";
	const ATOM = "application/atom+xml";
	const ZIP = "multipart/x-zip";
	const GIF = "image/gif";
	const JPG = "image/jpeg";
	const JPEG = "image/jpeg";
	const PNG = "image/png";
	const PDF = "application/pdf";
	const BINARY = "application/octet-stream";
	const EXCEL = "application/vnd.ms-excel";

	/**
	 * Constructor
	 */
	function __construct(){

	}

	public static function redirect($url){
            header("Location: {$url}");
            die();
	}
        
        public static function getNoCacheHeaders(){
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("cache-Control: no-store, no-cache, must-revalidate");
            header("cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");            
        }
        
	public static function getHeaders($type='text/html',$charset='UTF-8'){
            header("Content-type: {$type}; charset={$charset}");
	}

	public static function forceDownload($filename,$filesize=0){
		header("Content-Type: application/force-download; name=\"$filename\"");
		header("Content-Transfer-Encoding: binary");
                if($filesize ===0){
                    header("Content-Length: $filesize");
                }
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Expires: 0");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
 	}

	public static function getXMLDeclaration($charset='UTF-8',$headers=true){
		if($headers)
			boSpecialHeaders::getHeaders(boSpecialHeaders::XML);
		return '<?xml version="1.0" encoding="'.$charset.'" ?>';

	}
        /**
         * getAuth
         * 
         * Asks the visitor for a login/password .htaccess style to get to the content.
         * 
         * @param string $sessionname name of the session used to store the status login of the visitor
         * @param string $login the login that must be used by the visitor
         * @param string $password the password that must be used by the visitor... hey, you know what a auth is, right ?
         */
        public static function getAuth($sessionname,$login,$password){
            if( !isset( $_SESSION[$sessionname] ) )
              {
                if( !isset( $_SERVER['PHP_AUTH_USER'] ) || !isset( $_SERVER['PHP_AUTH_PW'] ) )
                {
                  header("HTTP/1.0 401 Unauthorized");
                  header("WWW-authenticate: Basic realm=\"".SITENAME."\"");
                  header("Content-type: text/html");
                  // Print HTML that a password is required
                  exit;
                }
                else
                {
                  // Validate the $_SERVER['PHP_AUTH_USER'] & $_SERVER['PHP_AUTH_PW']
                  if( $_SERVER['PHP_AUTH_USER']  != $login
                      || $_SERVER['PHP_AUTH_PW'] != $password )
                  {
                    // Invalid: 401 Error & Exit
                    header("HTTP/1.0 401 Unauthorized");
                    header("WWW-authenticate: Basic realm=\"".SITENAME."\"");
                    header("Content-type: text/html");
                    // Print HTML that a username or password is not valid
                    exit;
                  }
                  else
                  {
                    // Valid
                    $_SESSION[$sessionname]=true;
                  }
                }
              }
        }
        
}
