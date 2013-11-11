<?php
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Item {

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\Field(type="string")
     */
    protected $name;

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
        $this->postionX = 0;
        $this->postionY = 0;
        $this->postionZ = 0;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getName() 
    {
        return $this->name;
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

    public function setName($name) 
    {
        $this->name = (string)$name;
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