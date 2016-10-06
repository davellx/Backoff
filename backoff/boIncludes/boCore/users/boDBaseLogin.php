<?php

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
class boDBaseLogin extends boLogin
{

	/**
	* Login::Login()
	*
	* When this class is constructed, it verifies automatically if the user is logged,
	* if (s)he's not it requires the file login.php in the default viewspath and dies(kills?) the script
	*/
	function __construct()
	{
		global $boDBConnexion;

		$erreur='';
		$testSession = (isset($_SESSION['boLogin']) && $_SESSION['boLogin'] == 'logged');
		$level = 0;

		$testLogin = false;
		$userLogin = boRequest::string('login');
		$userPwd = boRequest::string('pwd');

		$usersTable = Doctrine_Core::getTable(USERCLASS);
		if(!$usersTable || !$usersTable->hasColumn('login') || !$usersTable->hasColumn('password')){
			throw new ErrorException("Your user class must have the login and password columns");
		}

		if(!is_null($userLogin) && !is_null($userPwd)){

			$user = $usersTable->findOneByLogin($userLogin);

			//
			if($user === false){
				$this->showLogin("Login not found");
			}

			if($user->testPass($userPwd) !== false){
				$level = $user->level;
				$testLogin = true;
			}else{
				$this->showLogin("Password Incorrect");
			}
		}

		$logOut = boRequest::bool('logout');
		if (!$testSession)
		{
			if ($testLogin)
			{
				$_SESSION['boLogin'] = 'logged';
				$_SESSION['boLevel'] = $level;
			}
			else
			{
				$erreur = (!is_null($userLogin) || !is_null($userPwd))?'Vérifiez vos identifiants.':'';
				$this->showLogin($erreur);
			}
		}else if($logOut){
			unset($_SESSION['boLogin']);
			unset($_SESSION['boLevel']);
			$this->showLogin();
		}
	}

	private function showLogin($erreur=''){
		if(!is_file(VIEWSPATH . 'login.php'))
			die("Error : login view missing !");
		require(VIEWSPATH . 'login.php');
		die();
	}

}
