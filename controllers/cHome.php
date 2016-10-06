<?php

use boCore\dataBase\boDBUtils;
use boCore\front\boBaseController;
use boFramework\io\boRequest;
use boFramework\utils\boArrays;
use Entity\Visiteur;

 class cHome extends boBaseController{
	 
 	public static $registeredActions = array('default', 'home', 'site');
        
         /**
 	 * Constructor
 	 */
 	function __construct(){
		
 	}

	public function another(){
		include FRONT_VIEWS.'another.php';
	}
        
	public function home(){
        include FRONT_VIEWS.'base.php';
	}
        
        
	// homepage
	public function __call($name,$arguments){
		$this->home();
	}
	
 }
