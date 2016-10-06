<?php

namespace boFramework\io;


/**
 *
 *
 */
class boUserDetection{
    /**
     * Constructor
     */
    function __construct(){

    }

    public static function getBrowserLangPreferences($array = array('fr')){
        $bestBrowserLang = '';
        $langs = explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        foreach($langs as $userLangue){
            foreach($array as $langue){
                if(boStrings::contains($userLangue,$langue,true)){
                    $bestBrowserLang = $langue;
                    break;
                }
            }
            if($bestBrowserLang!='')
                break;
        }
        $bestBrowserLang = ($bestBrowserLang != '')?$bestBrowserLang:$array[0];
        return $bestBrowserLang;
    }

}