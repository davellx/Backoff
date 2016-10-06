<?php

namespace boCore\misc;

class boSession{
	/**
	 * Constructor
	 */
	function __construct(){

	}

	public static function setSessionPath($path){
            ini_set('session.save_path', $path);
	}

	public static function start(){
           if (isset($_GET['PHPSESSID'])) {
               $sessid = $_GET['PHPSESSID'];
           } else if (isset($_COOKIE['PHPSESSID'])) {
               $sessid = $_COOKIE['PHPSESSID'];
           } else {
               session_start();
               return false;
           }
           if (!preg_match('/^[a-zA-Z0-9]*$/', $sessid) && strlen($sessid) >= 27 && strlen($sessid) <= 40) {
               return false;
           }
           session_id($sessid);
           session_start();
           return true;
	}


}
