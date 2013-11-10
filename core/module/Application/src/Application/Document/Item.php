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

    public function setName($name) 
    {
        $this->name = (string)$name;
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