<?php

namespace boFramework\io;

/**
 *
 *
 */
class boFSUtils{
	/**
	 * Constructor
	 */
	function __construct(){

	}

	public static function removeDir($dir, $deleteMe = true) {
		if(!$dh = @opendir($dir)) return;
		while (false !== ($obj = readdir($dh))) {
			if($obj=='.' || $obj=='..') continue;
			if (!@unlink($dir.'/'.$obj)) boFSUtils::removeDir($dir.'/'.$obj, true);
		}

		closedir($dh);
		if ($deleteMe){
			@rmdir($dir);
		}
	}

	public static function getFreeFileName($place,$filename){
		$nuname = $filename;
		if(file_exists($place.$filename)){
			$numVar = 1;
			$explodedName = explode('.',$nuname);
			$beforeLastPart = count($explodedName)-2;
			$trouve = false;
			while($trouve===false){
				$tempArray = $explodedName;
				$tempArray[$beforeLastPart] .= "[{$numVar}]";
				$tempName = implode('.',$tempArray);
				if (file_exists($place.$tempName)){
					$numVar++;
				}else{
					$nuname = $tempName;
					$trouve=true;
				}
			}
		}
		return $nuname;
	}

}
