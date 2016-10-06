<?php

namespace boCore\users;

use boFramework\io\boRequest;


/**
* Login
* Identification Class, this version is really basic
* it uses the globals LOGIN and PASSWORD defined in the config.php file
*
* General usage :
* <code>
* new Login();
* </code>
* see the Constructor doc for more informations.
*
* @package base
* @author Davel_x
* @copyright Copyright (c) 2007
* @version $Id$
* @access public
*/
class boLogin
{
	/**
	* Login::Login()
	*
	* When this class is constructed, it verifies automatically if the user is logged,
	* if (s)he's not it requires the file login.php in the default viewspath and dies(kills?) the script
	*/
	function __construct()
	{
		$testSession = (isset($_SESSION['boLogin']) && $_SESSION['boLogin'] == 'logged');
		$testLogin = boRequest::string('login') == LOGIN;
		$testPass = boRequest::string('pwd') == PASSWORD;
		$logOut = boRequest::bool('logout');
		if (!$testSession)
		{
			if ($testLogin && $testPass)
			{
				$_SESSION['boLogin']='logged';
				$_SESSION['boLevel']=20; // should be enough
			}
			else
			{
				//TODO maybe test something else like users from a database
				require(VIEWSPATH . 'login.php');
				die();
			}
		}else if($logOut){
			unset($_SESSION['boLogin']);
			unset($_SESSION['boLevel']);
			require(VIEWSPATH . 'login.php');
			die();
		}
	}
}
