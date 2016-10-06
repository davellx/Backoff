<?php
use boCore\dataBase\boDBAccessor;
use boCore\front\boControllersManager;
use boCore\misc\boSession;

define('BASEPATH','./backoff/');

if(!is_file('./config/config.php')) die('missing config file');
include('./config/config.php');
include(BASEPATH.'boIncludes/autoload.php');

boSession::start();

boControllersManager::loadControllers(CONTROLLERS);
boControllersManager::launchAction();

