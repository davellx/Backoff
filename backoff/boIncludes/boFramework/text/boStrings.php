<?php 
namespace boFramework\text;

class boStrings { 
    
    
    /**
     * 
     * Constructor     
     */

    function __construct() {
        
    }

    public static function contains($haystack, $needle, $ignoreCase = false) {
        if ($ignoreCase) {
            $haystack = strtolower($haystack);
            $needle = strtolower($needle);
        } $needlePos = strpos($haystack, $needle);
        return ($needlePos === false ? false : ($needlePos + 1));
    }
	


    public static function truncateAfterWord($string, $maxChar,$ellipse = "…"){
        $string = mb_substr(strip_tags($string),0,$maxChar);
        if(strlen($string) == $maxChar){
            $pos = strrpos($string, ' ');
            $chaine = substr($string, 0, $pos);
            $chaine .= $ellipse;
        }
        return utf8_encode($chaine);
    }
	

    public static function str2hex($string) {
      $hex = "";
      for ($i = 0; $i < strlen($string); $i++) {
        $hex .= (strlen(dechex(ord($string[$i]))) < 2) ?
        "0" . dechex(ord($string[$i])) : dechex(ord($string[$i]));
      }
      return $hex;
    }

     public static function hex2str($hex) {
        $str = '';
        for($i=0;$i < strlen($hex);$i+=2) {
          $str.=chr(hexdec(substr($hex,$i,2)));
        }
        return $str;
    }        
        
}
