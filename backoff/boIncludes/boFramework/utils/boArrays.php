<?php

    namespace boFramework\utils;

/**
 *
 *
 * @version $Id$
 * @copyright 2011
 */

 class boArrays{
    /**
     * Constructor
     */
    function __construct(){

    }

    public static function isOneNull($array){
        $nb = count((array)$array);
        for($i = 0; $i < $nb; $i++){
            if(is_null($array[$i])){
                return true;
            }
        }
        return false;
    }

    public static function isOneEmpty($array){
        $nb = count((array)$array);
        for($i = 0; $i < $nb; $i++){
            if(empty($array[$i])){
                return true;
            }
        }
        return false;
    }

 }
