<?php

namespace boCore\front;

 abstract class boBaseController{
 	public static $registeredActions = array('default');

	public function __call($name,$arguments){
            echo "no action exists with the name {$name}";
	}
 }
