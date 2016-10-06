<?php


namespace boFramework\text;
/**
 * boTypeFilters
 *
 * class used to filter datas, it is mostly used to check incoming informations from the user.
 * All functions are statics
 *
 * @version $Id$
 * @copyright 2008
 */


class boTypeFilters{


    /**
     * boTypeFilters::TypeFilters()
     *
     * Constructor, useless
     */
    function boTypeFilters()
    {
    }

    /**
     * boTypeFilters::string()
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
     */
    public static function string($var, $options = null, $default = null)
    {
        $retour = $default;
        if (isset($var) && is_scalar($var) && $var !== '') {
            $retour = $var;
            if (get_magic_quotes_gpc()) {
                $retour = stripslashes($retour);
            }
            if (!is_null($options)) {
                $options = explode('|', $options);
                foreach($options as $value) {
                    switch ($value) {
                        case 'EMAIL':
							$retour = trim($retour);
                            if (!boTypeFilters::email($retour)) {
                                return null;
                            }
                            break;
                        case 'FILENAME':
                            $retour = boTypeFilters::makeGoodDirName($retour);
                            break;
                        case 'HTML':
                            $retour = htmlspecialchars($retour, ENT_NOQUOTES);
                            break;
                        case 'ONELINE':
                            $retour = str_replace("\r", '', $retour);
                            $retour = str_replace("\n", '', $retour);
                            break;
                        case 'UTF8ENCODE':
                            $retour = utf8_encode($retour);
                            break;
                        case 'UTF8DECODE':
                            $retour = utf8_decode($retour);
                            break;
                    }
                }
            }
        }
        return $retour;
    }

    /**
     * boTypeFilters::number()
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
     */
    public static function number($var, $default = 0, $type = 'int', $arr = 2)
    {
        $retour = (is_numeric($default) || is_null($default) || is_bool($default))?$default:0;
        if (isset($var) && is_scalar($var)) {
            switch ($type) {
                case 'bool':
                    $retour = (intval($var) != 0 || $var != false)?true:false;
                    break;
                case 'float':
                    $retour = ereg_replace(',', '.', $var);
                    // echo 'valeur : '.$retour;
                    $retour = floatval($retour);
                    $retour = round($retour, $arr);
                    break;
                case 'int':
                case 'integer':
                default:
                    $retour = intval($var);
                    break;
            }
        }
        return $retour;
    }

    /**
     * boTypeFilters::bool()
     *
     * checks a boolean value
     *
     * @param mixed $var the name of the var transmitted
     * @return boolean
     * @static
     */
    public static function bool($var)
    {
        return boTypeFilters::number($var, false, 'bool');
    }

    /**
     * boTypeFilters::email()
     *
     * used to know if the parameter is a well formed email or a message from hell
     *
     * @copyright je l'ai piqué à quelqu'un, je sais plus vraiment à qui.
     * @link http://atranchant.developpez.com/code/validation/index.php
     * @param string $email email value to test
     * @return boolean
     * @static
     */
    public static function email($email)
    {
		$atom   = '[-a-z0-9!#$%&\'*+\\/=?^_`{|}~]';   // caractères autorisés avant l'arobase
		$domain = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)'; // caractères autorisés après l'arobase (nom de domaine)

		$regex = '/^' . $atom . '+' .   // Une ou plusieurs fois les caractères autorisés avant l'arobase
		'(\.' . $atom . '+)*' .         // Suivis par zéro point ou plus
		                                // séparés par des caractères autorisés avant l'arobase
		'@' .                           // Suivis d'un arobase
		'(' . $domain . '{1,63}\.)+' .  // Suivis par 1 à 63 caractères autorisés pour le nom de domaine
		                                // séparés par des points
		$domain . '{2,63}$/i';          // Suivi de 2 à 63 caractères autorisés pour le nom de domaine

        // test de l'adresse e-mail
        if (preg_match($regex, $email)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * boTypeFilters::emailCompleteValidation()
     *
     * more complete way to verify if an email address exists. Also checks if the server is an email server.
     *
     * @copyright je l'ai piqué à quelqu'un, je sais plus vraiment à qui.
     * @link http://www.linuxjournal.com/article/9585
     * @param string $email email value to test
     * @return boolean
     * @static
     */
    function emailCompleteValidation($email)
    {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        }else {
            $domain = substr($email, $atIndex + 1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                // local part length exceeded
                $isValid = false;
            }else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                $isValid = false;
            }else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
                // local part starts or ends with '.'
                $isValid = false;
            }else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = false;
            }else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = false;
            }else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = false;
            }else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                    str_replace("\\\\", "", $local))) {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                        str_replace("\\\\", "", $local))) {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
                // domain not found in DNS

                $isValid = false;
            }
        }
        return $isValid;
    }

    /**
     * boTypeFilters::makeGoodDirName()
     *
     * Transforms a word/sentence in a good directory/file name
     * without all theses aliens(french) stupid accentuated letters
     *
     * @param string $name the 'thing' to transform
     * @return string well formed dir/file name
     * @static
     */
    public static function makeGoodDirName($name)
    {
        settype($name, "string");
        $invalide = array('\'', '/', ':', '*', '?', '"', '<', '>', '|', '%');
        $i = 0;
        while (isset($invalide[$i])) {
            $name = str_replace ($invalide[$i], '', $name);
            $i++;
        }
        /*
		$origin = array("\xe1","\xc1","\xe0","\xc0","\xe2","\xc2","\xe4","\xc4","\xe3","\xc3","\xe5","\xc5",
		"\xaa","\xe7","\xc7","\xe9","\xc9","\xe8","\xc8","\xea","\xca","\xeb","\xcb","\xed",
		"\xcd","\xec","\xcc","\xee","\xce","\xef","\xcf","\xf1","\xd1","\xf3","\xd3","\xf2",
		"\xd2","\xf4","\xd4","\xf6","\xd6","\xf5","\xd5","\x8","\xd8","\xba","\xf0","\xfa","\xda",
		"\xf9","\xd9","\xfb","\xdb","\xfc","\xdc","\xfd","\xdd","\xff","\xe6","\xc6","\xdf","\xf8");
		*/
        $origin = array ('á', 'Á', 'à', 'À', 'â', 'Â', 'ä', 'Ä', 'ã', 'Ã', 'å', 'Å', 'ª',
            'ç', 'Ç', 'é', 'É', 'è', 'È', 'ê', 'Ê', 'ë', 'Ë', 'í', 'Í', 'ì', 'Ì', 'î', 'Î', 'ï', 'Ï', 'ñ', 'Ñ',
            'ó', 'Ó', 'ò', 'Ò', 'ô', 'Ô', 'ö', 'Ö', 'õ', 'Õ', '', 'Ø', 'º', 'ð', 'ú', 'Ú', 'ù', 'Ù', 'û', 'Û', 'ü', 'Ü', 'ý', 'Ý', 'ÿ',
            'æ', 'Æ', 'ß', 'ø', '&');
        $replacement = array("a", "A", "a", "A", "a", "A", "a", "A", "a", "A", "a", "A", "a", "c", "C", "e", "E", "e", "E", "e", "E", "e", "E",
            "i", "I", "i", "I", "i", "I", "i", "I", "n", "N", "o", "O", "o", "O", "o", "O", "o", "O", "o", "O", "o", "O", "o", "o",
            "u", "U", "u", "U", "u", "U", "u", "U", "y", "Y", "y", "a", "A", "s", "o", "et");

        $name = str_replace($origin, $replacement, $name);
        $name = preg_replace('/[^abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789\ -._]/', '', $name);
        $name = trim($name);
        $name = str_replace (array('  ', ' '), array(' ', '-'), $name);
        $name = mb_strtolower($name, "UTF-8");
        return $name;
    }

}