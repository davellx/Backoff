<?php

namespace boCore\dataBase;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Gedmo\DoctrineExtensions;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Tree\TreeListener;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Proxy\Autoloader;

/**
 *
 *
 */
class boDBAccessor{

	private static $instance;
	public $db;
	public $manager;

    public static function getInstance()
    {
        if (!isset(boDBAccessor::$instance)) {
            boDBAccessor::$instance = new boDBAccessor();
        }

        return boDBAccessor::$instance;
    }

    /**
     * Constructor
     */
    private function __construct(){

        $paths = array(MODELS_PATH);
        $isDevMode = DEBUGMODE;

        // the connection configuration
        $dbParams = array(
            'driver'   => 'pdo_'.DBTYPE,
            'user'     => DBUSER,
            'password' => DBPASSWORD,
            'dbname'   => DBNAME,
            'host'     => DBHOST,
            //'url' => "{DBTYPE}://{DBUSER}:{DBPASSWORD}@{DBHOST}/{DBNAME}",
        );
        

        $config = Setup::createConfiguration($isDevMode);
        //AnnotationRegistry::registerFile(basename(EXTERNAL_LIBS_PATH)."/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
        $cache = new ArrayCache();
        //$cache->deleteAll();
        $annotationReader = new AnnotationReader();
        $cachedAnnotationReader = new CachedReader(
            $annotationReader, 
            $cache 
        );
        $driverChain = new MappingDriverChain();
        DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
            $driverChain,
            $cachedAnnotationReader
        );
        $annotationDriver = new AnnotationDriver(
            $cachedAnnotationReader, 
            array(MODELS_PATH) // paths to look in
        );
        $driverChain->addDriver($annotationDriver, 'Entity');
        //$config = new Configuration();
        //$config->setProxyDir(sys_get_temp_dir()); 
        $config->setProxyDir(BASEPATH.'boModels/Proxy');
        $config->setProxyNamespace('Proxy');
        $config->setAutoGenerateProxyClasses($this->verifyProxyFiles()); // this can be based on production config.
        $config->setMetadataDriverImpl($driverChain);
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        
        $evm = new EventManager();

//        $this->getSluggable($evm, $cachedAnnotationReader);
//        $this->getTree($evm, $cachedAnnotationReader);
        $this->getTimestampable($evm, $cachedAnnotationReader);
        
        
        // mysql set names UTF-8 if required
        $evm->addEventSubscriber(new MysqlSessionInit());
        // Finally, create entity manager
        $this->manager = EntityManager::create($dbParams, $config, $evm);            


        //die('ok');
    }

    public function getSluggable($evm,$annotationReader){
        $sluggableListener = new SluggableListener();
        $sluggableListener->setAnnotationReader($annotationReader);
        $evm->addEventSubscriber($sluggableListener);
    }
        
    public function getTree($evm,$annotationReader){
        $treeListener = new TreeListener();
        $treeListener->setAnnotationReader($annotationReader);
        $evm->addEventSubscriber($treeListener);
    }
        
    public function getTimestampable($evm,$annotationReader){
        $timestampableListener = new TimestampableListener();
        $timestampableListener->setAnnotationReader($annotationReader);
        $evm->addEventSubscriber($timestampableListener);
    }
    
    private function verifyProxyFiles(){
        $ent = glob(MODELS_PATH.'Entity/*.php');
        $ent = str_replace([MODELS_PATH,'.php','/'], '', $ent);
        foreach($ent AS $value){
            if(!is_file(MODELS_PATH.'Proxy/__CG__'.$value.'.php')){
                return true;
            }
        }
        return false;
    }
    
        
    // Prévient les utilisateurs sur le clônage de l'instance
    public function __clone()
    {
        trigger_error('Le clônage n\'est pas autorisé.', E_USER_ERROR);
    }
    
}


