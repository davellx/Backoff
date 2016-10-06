<?php

namespace boFramework\io;

use boFramework\text\boTypeFilters;

/**
 * Request
 *
 * class used to get the informations sent by the user by _GET or POST (_REQUEST in fact).
 * All functions are statics.
 *
 * @package base
 * @author Davel_x
 * @copyright Copyright (c) 2007
 * @version $Id$
 * @access public
 **/
class boRequest{

	/**
	 * boRequest::Request()
	 *
	 * Constructor, useless
	 **/
	function __construct(){

	}

	 /**
	 * @var string sets the way the script acts when a file with the same name exists
	 * @static
	 **/
	 public static $fileCopyMethod = 'numero';

	 /**
	 * @var boolean sets the way the script acts when a file with the same name exists
	 * @static
	 **/
	 public static $fileMakeGoodName = true;

	 /**
	 * @var boolean sets the way the script acts when a file with the same name exists
	 * @static
	 **/
	 public static $fileExcludeExtentions = false;


	/**
	 * boRequest::string()
	 *
	 * make sure it's a string
	 *
	 * @param string $var the name of the var transmitted
	 * @param string $options options must be separated by a pipe(|) :
	 * - FILENAME clears the string from the chars that are difficult for file names;
	 * - EMAIL returns the string only if the return value is email formed;
	 * - HTML transforms special chars un HTML entities;
	 * - ONELINE removes the newlines chars;
	 * - UTF8ENCODE encodes the string in UTF8;
	 * - UTF8DECODE decodes the string from UTF8;
	 * @param mixed $default the default value if the var doesn't meet the requirements
	 * @return mixed
	 * @static
	 **/
	public static function string($var,$options=NULL,$default=NULL){
		$retour = $default;
		if(isset($_REQUEST[$var])){
			$retour = boTypeFilters::string($_REQUEST[$var],$options,$default);
		}
		return $retour;
	}



	/**
	 * boRequest::number()
	 *
	 * make sure it's a number, and a good one
	 *
	 * @param mixed $var the name of the var transmitted
	 * @param integer $default the default value if the var doesn't meet the requirements
	 * @param string $type type can be on of thoses :
	 * - bool
	 * - float
	 * - int/integer, the default value
	 * @param integer $arr used for the 'float' type, specifies the number of chars after the points
	 * @return mixed
	 * @todo find a better way to explain the $arr argument
	 * @static
	 **/
	public static function number($var,$default=0,$type='int',$arr=2){
		$retour=(is_numeric($default) || is_null($default) || is_bool($default))?$default:0;
		if(isset($_REQUEST[$var])){
			$_REQUEST[$var] = ($_REQUEST[$var] == 'false')?false:$_REQUEST[$var];
			$retour = boTypeFilters::number($_REQUEST[$var],$default,$type,$arr);
		}
		return $retour;
	}

	/**
	 * boRequest::array()
	 *
	 * checks an array value, for a checkbox for example
	 *
	 * @param mixed $var the name of the var transmitted
	 * @return array
	 * @static
	 **/

	public static function arrayData($var){
		if(isset($_REQUEST[$var]) && is_array($_REQUEST[$var])){
			return $_REQUEST[$var];
		}else{
			return array();
		}
	}

	/**
	 * boRequest::bool()
	 *
	 * checks a boolean value
	 *
	 * @param mixed $var the name of the var transmitted
	 * @return boolean
	 * @static
	 **/
	public static function bool($var){
		return boRequest::number($var,false,'bool');
	}

	/**
	 * boRequest::file()
	 *
	 * Used to get a file uploaded by the user
	 * and put in where we want and with the correct name we want. So be it.
	 *
	 * It's possible to define if the filename must be cleaned of all weird chars with the variable boRequest::$fileMakeGoodName, the default value is true
	 *
	 * The boRequest::$fileCopyMethod is used to choose the way the script acts when a file with the same filename already exists. Its different values can be :
	 * - overwrite, the default value, simply deletes the old file to put the new one in place
	 * - saveoldversion save the old file with the prefix 'old_'
	 * - numero adds a number just before the last dot of the filename, the number is incremented as many times as needed to find a filename that doesn't exists
	 *
	 * @param mixed $var the name of the var transmitted
	 * @param string $place the directory where the file will be stored
	 * @param mixed $newname the new name of the file
	 * @param string $ext the extentions that will be included or exluded (with boRequest::$fileExcludeExtentions) separated by a pipe char '|'
	 * @return mixed the new filename or false if something wrong happened
	 * @static
	 **/
	public static function file($var,$place='./',$newname=NULL,$ext=''){
//		print_r($_FILES);
		if(isset($_FILES[$var]) && is_uploaded_file($_FILES[$var]['tmp_name']) && is_writable($place)){
			// fait il partie des interndits ou des permis ?
			$ext = explode('|',$ext);
                        $validExt = 0;
			foreach($ext as $value){
				// est ce que cette extension se trouve dans le nom original ou bien dans nuname
				if($value != ''){
                                    $longExt = strlen($value)+1;
                                    if(
                                            substr($_FILES[$var]['name'], strlen($_FILES[$var]['name'])-$longExt)== '.'.$value || 
                                            (
                                                substr($newname, strlen($newname)-$longExt)== '.'.$value &&
                                                !is_null($newname)
                                            )
                                        ){
                                            // s'il ne faut pas qu'il y soit, on jarte
                                            if(boRequest::$fileExcludeExtentions){
                                                    return false;
                                            }else{
                                                $validExt++;
                                            }
                                    }else{
                                        // s'il n'y sont pas et qu'il le faut, on jarte
                                        if(boRequest::$fileExcludeExtentions){
                                            $validExt++;
                                        }
                                    }
				}
			}
                        if(
                                (boRequest::$fileExcludeExtentions && $validExt < count($ext)) ||
                                (!boRequest::$fileExcludeExtentions && $validExt < 1)
                            ) return false;
			// on se charge du nouveau nom
			$nuname=is_null($newname)?$_FILES[$var]['name']:$newname;
			if(boRequest::$fileMakeGoodName)
				$nuname = boTypeFilters::makeGoodDirName($nuname);
			// si ça se trouve ça esiste déjà
			if(file_exists($place.$nuname)){
				// et qu'est ce qu'on fait dans ce cas ?
				switch(boRequest::$fileCopyMethod){
				case 'numero':
					// à remplir !!
					$numVar = 1;
					$explodedName = explode('.',$nuname);
					$beforeLastPart = count($explodedName)-2;
					$trouve = false;
					while($trouve===false){
						$tempArray = $explodedName;
						$tempArray[$beforeLastPart] .= "[$numVar]";
						$tempName = implode('.',$tempArray);
					    if (file_exists($place.$tempName)){
					      $numVar++;
						}else{
					      $nuname = $tempName;
					      $trouve=true;
					    }
					}
					break;
				case 'saveoldversion':
					copy($place.$nuname,$place.'old_'.$nuname);
					break;
				case 'overwrite':
				default:
					// bin non, normalement, rien  à faire !
					break;
				}
			}
			// on dirait bien que tout est bon, dans le cochon
			if(move_uploaded_file($_FILES[$var]['tmp_name'],$place.$nuname)){
				chmod ($place.$nuname, 0644);
				return $nuname;
			}else{
				return false;
			}
		}else {
			return false;
		}
	}

}
