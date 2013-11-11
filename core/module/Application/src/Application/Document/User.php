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
    protected $positionX;

    /**
    * @ODM\Field(type="float")
    */
    protected $positionY;

    /**
    * @ODM\Field(type="float")
    */
    protected $positionZ;

    public function __construct() {
        $this->setLastUpdated();
        $this->isOnline = true;
        $this->positionX = 5.878401510630915;
        $this->positionY = 0.5;
        $this->positionZ = -1.0869647027003655;
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
        return $this->positionX;
    }

    public function getPositionY() 
    {
        return $this->positionY;
    }

    public function getPositionZ() 
    {
        return $this->positionZ;
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

    public function setPositionX($positionX)
    {
        $this->positionX = (float)$positionX;
    }

    public function setPositionY($positionY)
    {
        $this->positionY = (float)$positionY;
    }

    public function setPositionZ($positionZ)
    {
        $this->positionZ = (float)$positionZ;
    }
}