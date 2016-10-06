<?php

namespace boCore\modules;

/**
 * Modules
 * Class used to get the whole list of the modules
 *
 * In order to retreive all the modules, this class needs all the modules directory to have an '_info.php' file inside
 *
 * The file structure usually looks like :
 *
 *  <code>
 * 	$moduleInfo = Array(
 *		'id'=> 'idname', // it must be the name of the directory also : avoid specials chars
 *		'name'=>'Module\'s name', // longer name used for the menu
 *		'author'=>'davel', // if you really like to show-off like me
 *		'description'=>'Module\'s description', // longer desc not used at the moment but... who knows
 *		'version'=>'0.1', // module's version, can be useful
 *		'needsInstall'=>true, // to know if the module needs installation or not
 * 		'submenu'=>true // is there a submenu for this module ?
 * 						// see the Module class for more informations
 *	);
 *
 * </code>
 *
 *
 * @package module
 * @author Davel_x
 * @copyright Copyright (c) 2007
 * @version $Id$
 * @access public
 **/
class boModules
{

    /**
    * @var array names of the actions parts in the URL
    **/
    public static $actionNames = array('module','procedure');

    /**
    * @var array action filled through the URL
    **/
    public static $actions = array();
    /**
    * @var string the directory of the Modules
    **/
    public $repertoire = MODULESPATH;
    /**
    * @var array contains the list of Modules
    **/
    public $list = array();

    /**
     * Modules::getList()
     *
     * gets the list of Modules in $list
     */
    function getList()
    {
        $this->list = array();
        $modules = simplexml_load_file($this->repertoire.'modules.xml');
        foreach ($modules->module as $module){
            $moduleName = (string)$module['name'];
            $this->list[$moduleName] = new boModule($moduleName);
        }
    }

    function getActionDatas(){
        // if action is not set, that means we have to use the URL based action
        $path_info = @$_SERVER['ORIG_PATH_INFO'];
        if (!$path_info) $path_info = @$_SERVER['PATH_INFO'];
        if (!$path_info) $path_info = @$_SERVER['REQUEST_URI'];
        $path_info = str_replace([$_SERVER['SCRIPT_NAME'],'b2b','backoff'], '', $path_info);
        $path_info = explode("?",$path_info)[0];
        $navVars = explode("/",$path_info);
        
        // remove empty keys
        $empty_elements = array_keys($navVars,"");
        foreach ($empty_elements as $e)
            unset($navVars[$e]);
        $navVars = array_merge(array(),$navVars);
        $nbActions = count(boModules::$actionNames);
        for($i = 0;$i < $nbActions; $i++){
            boModules::$actions[boModules::$actionNames[$i]] = isset($navVars[$i])?$navVars[$i]:'';
        }
    }

    public function launchAction($force = null){
        $currentAction = boModules::$actions['module'];
        if($force != null) $currentAction = $force;
        if (array_key_exists($currentAction, $this->list))
        {
            $rc = new \ReflectionClass($currentAction.'\\'.$currentAction.'Module');
            $currentModule = $rc->newInstance();
            if($currentModule instanceof iBoModuleComplet){
                $currentModule->action();
                die();
            }
        }
    }
    
    public function launch(){
        $this->getActionDatas();
        $this->getList();
        $this->launchAction();
    }
        
}
