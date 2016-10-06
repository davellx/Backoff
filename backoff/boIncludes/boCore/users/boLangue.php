<?php

namespace boCore\users;

use boFramework\io\boUserDetection;

/**
 *
 *
 */
class boLangue{

    var $languages = array('fr', 'en', 'de');

    var $langue;
    /**
     * Constructor
     * @access protected
     */
    function __construct($l = NULL){
        if(is_null($l)){
            if(isset($_SESSION['langue'])){
                $this->langue = $_SESSION['langue'];
            }else{
                $this->langue = $this->getBrowserPreferences();
                $_SESSION['langue'] = $this->langue;
            }
        }else{
            if(in_array($l,$this->languages)){
                $this->langue = $l;
                $_SESSION['langue'] = $l;
            }else{
                $this->langue = boUserDetection::getBrowserLangPreferences($this->languages);
                $_SESSION['langue'] = $this->langue;
            }
        }

    }

    public function __toString() {
        return $this->langue;
    }

}
