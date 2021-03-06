<?php

namespace DoctrineMongoODMModule\Proxy\__CG__\Application\Document;

use Doctrine\ODM\MongoDB\Persisters\DocumentPersister;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class User extends \Application\Document\User implements \Doctrine\ODM\MongoDB\Proxy\Proxy
{
    private $__documentPersister__;
    public $__identifier__;
    public $__isInitialized__ = false;
    public function __construct(DocumentPersister $documentPersister, $identifier)
    {
        $this->__documentPersister__ = $documentPersister;
        $this->__identifier__ = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->__documentPersister__) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->__documentPersister__->load($this->__identifier__, $this) === null) {
                throw \Doctrine\ODM\MongoDB\DocumentNotFoundException::documentNotFound(get_class($this), $this->__identifier__);
            }
            unset($this->__documentPersister__, $this->__identifier__);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return $this->__identifier__;
        }
        $this->__load();
        return parent::getId();
    }

    public function getUsername()
    {
        $this->__load();
        return parent::getUsername();
    }

    public function getItem()
    {
        $this->__load();
        return parent::getItem();
    }

    public function getIsOnline()
    {
        $this->__load();
        return parent::getIsOnline();
    }

    public function getLastUpdated()
    {
        $this->__load();
        return parent::getLastUpdated();
    }

    public function getPositionX()
    {
        $this->__load();
        return parent::getPositionX();
    }

    public function getPositionY()
    {
        $this->__load();
        return parent::getPositionY();
    }

    public function getPositionZ()
    {
        $this->__load();
        return parent::getPositionZ();
    }

    public function setUsername($username)
    {
        $this->__load();
        return parent::setUsername($username);
    }

    public function setItem($item)
    {
        $this->__load();
        return parent::setItem($item);
    }

    public function setIsOnline($isOnline)
    {
        $this->__load();
        return parent::setIsOnline($isOnline);
    }

    public function setLastUpdated()
    {
        $this->__load();
        return parent::setLastUpdated();
    }

    public function setPositionX($positionX)
    {
        $this->__load();
        return parent::setPositionX($positionX);
    }

    public function setPositionY($postionY)
    {
        $this->__load();
        return parent::setPositionY($postionY);
    }

    public function setPositionZ($positionZ)
    {
        $this->__load();
        return parent::setPositionZ($positionZ);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'username', 'item', 'isOnline', 'lastUpdated', 'positionX', 'positionY', 'positionZ');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->__documentPersister__) {
            $this->__isInitialized__ = true;
            $class = $this->__documentPersister__->getClassMetadata();
            $original = $this->__documentPersister__->load($this->__identifier__);
            if ($original === null) {
                throw \Doctrine\ODM\MongoDB\MongoDBException::documentNotFound(get_class($this), $this->__identifier__);
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->__documentPersister__, $this->__identifier__);
        }
        
    }
}