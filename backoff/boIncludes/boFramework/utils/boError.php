<?php

namespace boFramework\utils;

/**
 * Error
 *
 * @package base
 * @author davel_x
 * @copyright Copyright (c) 2007
 * @version $Id$
 * @access public
 */
class Error{

    /**
     * Constructor
     * @access protected
     */
    function __construct($errorListFile){

    }

    public static function fatalError($errno, $errstr, $errfile, $errline){
        // je crée une phrase de debug pour l'instant elle est ultra simple
        $errMsg = "[".date('d/m/Y H:i:s')."] $errno: $errstr in $errfile on line $errline\r\n";
        //
        if(!DEBUGMODE){
            file_put_contents(dirname(__FILE__).'/errorlog.log', $errMsg,FILE_APPEND);
            $errMsg = 'Il y a eu une erreur dans le traitement des données, nos techniciens ont été prévenus.';
        }
    }

    public static function init(){
        set_error_handler('Error::fatalError');
    }

}
