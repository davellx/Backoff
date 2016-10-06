<?php

namespace boCore\dataBase;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class boDBUtils{
    
    /*
     * @return Doctrine\ORM\EntityManager
     */
    public static function getManager(){
        $boDBObject = boDBAccessor::getInstance();
        $GLOBALS['boDBEm'] = $boDBObject->manager;
        return $GLOBALS['boDBEm'];
    }
    
    public static function save($obj){
       $boDBEm = boDBUtils::getManager();
       $boDBEm->persist($obj);
       $boDBEm->flush();
    }
    
    public static function delete($obj){
       $boDBEm = boDBUtils::getManager();
       $boDBEm->remove($obj);
       $boDBEm->flush();
    }
    
    public static function getOneBy($type,$champ,$value){
        $boDBEm = boDBUtils::getManager();
        return $boDBEm->getRepository("Entity\\$type")->findOneBy(array($champ=>$value));
    }
    
    public static function getOneById($type,$id){
        $boDBEm = boDBUtils::getManager();
        return $boDBEm->find("Entity\\$type",$id);
    }
    
    
    
}