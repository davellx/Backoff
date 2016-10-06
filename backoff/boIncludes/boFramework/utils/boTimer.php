<?php

namespace boFramework\utils;

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

 class boTimer{

 	private $timeStart;
	public $timeTriggers = array();

	/**
 	 * Constructor
 	 */
 	function __construct(){
 		$this->init();
 	}

	public function init(){
		$this->timeStart = $this->getMicroTime();
	}

	public function getTime(){
		return ($this->getMicroTime() - $this->timeStart);
	}

	public function trigger($infos=''){
		$this->timeTriggers[] = array('time' => $this->getMicroTime(),'fromStart' => ($this->getMicroTime() - $this->timeStart), 'infos' => $infos);
	}

	private function getMicroTime(){
		list($useg,$seg)=explode(' ',microtime());
		return ((float)$useg+(float)$seg);
	}

 }

$boMainTimer = new boTimer();
