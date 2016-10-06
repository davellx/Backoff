<?php
/**
 * Fichier Index
 * Gets the menu and controls the main action : module request
 *
 * @version $Id$
 * @copyright 2007
 */

use boCore\misc\boSession;
use boCore\modules\boModules;
use boCore\users\boLogin;
use boFramework\io\boRequest;

if(!is_file('../config/config.php')) die('missing config file');
include('../config/config.php');
include('boIncludes/autoload.php');

boSession::start();
boSpecialHeaders::getNoCacheHeaders();


// ok, let's verify if you're welcome !
new boLogin();

// can be usefull : if you have access to the back office just add "?phpinfo=1" to the url in order to see the phpinfo.
$info = boRequest::string('phpinfo');
if(!is_null($info)){
    phpinfo();
    die();
}

$modules = new boModules();
$modules->launch();

// nothing worked, nevertheless let's show womething cool like the default page.
boPage::defaultPage();
