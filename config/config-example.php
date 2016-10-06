<?php
/**
 * This file contains all the data used to configurate the BOM
 *
 * @package base
 * @version $Id$
 * @copyright 2007
 */

date_default_timezone_set('Europe/Paris');
setlocale(LC_ALL, "fr_FR.UTF-8","fra.UTF-8","french.UTF-8");
 
// @name LAFILLE Just to be sure
define('BACKOFF','1');

// Just to be sure
define('LOGIN','admin');

// Just to be sure
define('PASSWORD','password');

// Debug or not debug
define('DEBUGMODE',true);

// In case you don't need one
define('NOBASE',true);

// Database type  (mysql, pgsql, sqlite)
define('DBTYPE','mysql');

// Database hostname (usually "localhost")
define('DBHOST','localhost');

// Database user
define('DBUSER','');

// Database password
define('DBPASSWORD','');

// Database name
define('DBNAME','');

define('DBPREFIX','');

// Backoff root
if(!defined('BASEPATH'))
	define('BASEPATH','.'.DIRECTORY_SEPARATOR);

// Models Path
define('MODELS_PATH',BASEPATH.'boModels'.DIRECTORY_SEPARATOR);

// Plugins root
define('MODULESPATH',BASEPATH.'boModules'.DIRECTORY_SEPARATOR);

// FrameWork
define('INCPATH',BASEPATH.'boIncludes'.DIRECTORY_SEPARATOR);

// External Libs
define('EXTERNAL_LIBS_PATH',BASEPATH.'boIncludes'.DIRECTORY_SEPARATOR.'externalLibraries'.DIRECTORY_SEPARATOR);

// views root
define('VIEWSPATH',BASEPATH.'boViews'.DIRECTORY_SEPARATOR);

// User Class Name
define('USERCLASS','Utilisateurs');


// ---------- Less formal
// BO Name/Title
define('SITEBRAND','Backoff');

define('SITENAME',SITEBRAND);


// ---------- Front part

define('FRONTNAME','');

define('CONTROLLERS',BASEPATH.'../controllers/');

define('FRONT_VIEWS',BASEPATH.'../views/');


define('SITEURL','//serverurl');

define('FRONTURL',SITEURL.'?qs=');
define('BACKURL',SITEURL.'backoff/');

define('EMAIL_ADDRESS','email@contact');
define('EMAIL_NAME','Contact ' . SITEBRAND);
