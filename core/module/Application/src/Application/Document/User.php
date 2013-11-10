<?php
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $username;

    /** 
     * @ODM\ReferenceOne(targetDocument="Item") 
    */
    protected $item;

    /** 
     * @ODM\Field(type="boolean") 
    */
    protected $isOnline;

    /** 
     * @ODM\Field(type="date") 
    */
    protected $lastUpdated;

    /**
    * @ODM\Field(type="float")
    */
    protected $postionX;

    /**
    * @ODM\Field(type="float")
    */
    protected $postionY;

    /**
    * @ODM\Field(type="float")
    */
    protected $postionZ;

    public function __construct() {
        $this->setLastUpdated();
        $this->isOnline = true;
        $this->postionX = 0;
        $this->postionY = 0;
        $this->postionZ = 0;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getUsername() 
    {
        return $this->username;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getIsOnline(){
        return $this->isOnline;
    }

    public function getLastUpdated(){
        return $this->lastUpdated;
    }

    public function getPositionX() 
    {
        return $this->postionX;
    }

    public function getPositionY() 
    {
        return $this->postionY;
    }

    public function getPositionZ() 
    {
        return $this->postionZ;
    }

    public function setUsername($username) 
    {
        $this->username = (string)$username;
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function setIsOnline($isOnline){
        $this->isOnline = (boolean) $isOnline;
    }

    public function setLastUpdated(){
        $this->lastUpdated = new \DateTime();
    }

    public function setPositionX($postionX)
    {
        $this->postionX = (float)$postionX;
    }

    public function setPositionY($postionY)
    {
        $this->postionY = (float)$postionY;
    }

    public function setPositionZ($postionZ)
    {
        $this->postionZ = (float)$postionZ;
    }
}