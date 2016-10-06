<?php

if(file_exists(EXTERNAL_LIBS_PATH.'vendor/autoload.php')){
	$GLOBALS['composerLoader'] = include_once EXTERNAL_LIBS_PATH.'vendor/autoload.php';
}
if(!NOBASE){
    Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($GLOBALS['composerLoader'],'loadClass'));
    Doctrine\ORM\Proxy\Autoloader::register(sys_get_temp_dir(), 'Proxy');
}

$includesPaths = array(
    realpath(INCPATH),
    realpath(EXTERNAL_LIBS_PATH),
    realpath(MODELS_PATH),
    realpath(MODULESPATH),
    realpath(INCPATH.DIRECTORY_SEPARATOR.'boFramework'.DIRECTORY_SEPARATOR.'viewsUtils')
);
set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $includesPaths));

function loadClasses($className){
    
    $className = str_replace('\\', DIRECTORY_SEPARATOR , $className).'.php';
    include_once $className;
    
}


spl_autoload_register('loadClasses');