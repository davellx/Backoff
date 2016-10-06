<?php
namespace boCore\modules;
/**
 * Module
 *
 * Simplified version of a module class : doesn't implements the iModuleComplet interface
 *
 * It is used alone by the <code>Modules</code> class to get the list
 * and is extended by the modules classes as the constructor is called to get the module info.
 *
 * @package module
 * @author Davel_x
 * @copyright Copyright (c) 2007
 * @version $Id$
 * @access public
 */

class boModule {

    /**
     * @var string the short name of the module, it's also the name of its directory
     */
    public $id;

    /**
     * @var string the long name of the module
     */
    public $name;
    
    /**
     * @var string label shown in the backoff menu
     */
    public $menuname;
 
    /**
     * @var string path to the module directory
     */
    public $path;

    /**
     * @var string method taht will be called at contruct
     */
    public $procedure;

    /**
     * @var string the gender of the name of the module
     */
    public $gender;

    /**
     * @var string the person to shoot at if it doesn't work
     */
    public $author;

    /**
     * @var string a more efficient way than the name to describe the module
     */
    public $description;

    /**
     * @var string the way to know if the module is outdated
     */
    public $version;

    /**
     * @var integer the minimal user level in order to use this module
     */
    public $access;

    /**
     * @var array if you want to add less generic elements
     */
    public $config;

    /**
     * @var array list of the elements of the subMenu for this module,
     * 		it must be an associative Array $key=>$value with $key as the module id and
     * 		$value as the athoer arguments that will be passed to the link in the menu
     */
    public $subMenu;


    /**
     * @var array list of meta
     */
    public $meta;

    /**
     * Constructor
     *
     * @param string $id the identification of the module
     * */
    public function __construct($id = NULL,$autoLaunch = true) {
       
        
        $id = (get_class($this) != __NAMESPACE__.'\\boModule')?str_replace(array('Module',__NAMESPACE__.'\\'),'',get_class($this)):$id;
        $id = explode('\\', $id);
        $id = $id[count($id)-1];
       
        if (!is_null($id) && file_exists(MODULESPATH.$id.DIRECTORY_SEPARATOR.'_info.php')){
            include (MODULESPATH . $id . DIRECTORY_SEPARATOR . '_info.php');
            $this->id = $moduleInfo['id'];
            $this->name = $moduleInfo['name'];
            $this->menuname = $moduleInfo['menuname'];
            $this->author = $moduleInfo['author'];
            $this->description = $moduleInfo['description'];
            $this->visible = $moduleInfo['visible'];
            $this->version = $moduleInfo['version'];
            $this->meta = $moduleInfo['meta'];
            $this->access = isset($moduleInfo['access']) ? $moduleInfo['access'] : 1;
            $this->subMenu = $moduleInfo['submenu'];
            $this->path = MODULESPATH.$this->id.DIRECTORY_SEPARATOR;
            
            if($autoLaunch === true && (get_class($this) != 'boModule')){
                $this->launchAction();
            }
            
         }else {
            return false;
        }
    }
    
    public function launchAction(){
        $this->procedure = (empty($this->procedure))?boModules::$actions['procedure']:$this->procedure;
        $this->{$this->procedure}();
    }
    
    function __call($name, $arguments) {
    }

}
