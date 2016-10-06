<?php
namespace stats;

use boCore\modules\boModule;

if(!defined('BACKOFF')) die();

class statsModule extends boModule{
    
    function __construct(){
        parent::__construct(null,false);
        $this->launchAction();
    }
    
    public function increment(){
        $data  = json_decode(file_get_contents(__DIR__.'/data/data.json'),true);
        $data['value'] = $data['value'] + 1;
        file_put_contents(__DIR__.'/data/data.json',json_encode($data));
        die(json_encode($data));
    }
    
    function __call($method, $arguments){
        //quick and dirty but mainly dirty
        $data  = json_decode(file_get_contents(__DIR__.'/data/data.json'),true);
        $value = $data['value'];
        include $this->path.'views/base.php';
        die();
    }    
    
}