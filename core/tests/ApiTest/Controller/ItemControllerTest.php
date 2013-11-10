<?php

namespace ApiTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Application\Document\Item;

class ItemControllerTest extends AbstractHttpControllerTestCase
{
    protected $dm;
    protected $config;

    public function setUp()
    {
        $this->setApplicationConfig(
            include dirname(__DIR__)."/../../config/application.config.php"
        );

        $serviceManager = $this->getApplicationServiceLocator();
        $this->config = $serviceManager->get('config');
        $this->dm = $serviceManager->get('doctrine.documentmanager.odm_default');
        $this->dm->getConnection()->dropDatabase($this->config['doctrine']['connection']['odm_default']['dbname']);
        parent::setUp();
    }

    public function testGetListActionCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $this->dispatch('/api/item');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Api');
        $this->assertControllerName('Api\Controller\Item');
        $this->assertControllerClass('itemcontroller');
        $response = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($response, array());

    }

    public function testGetListAction(){
        $item = new Item();
        $item->setName('testing');
        $item->setPositionX(4);
        $item->setPositionY(9);
        $item->setPositionZ(6);

        $this->dm->persist($item);

        $item2 = new Item();
        $item2->setName('testing 2');
        $item2->setPositionX(8);
        $item2->setPositionY(18);
        $item2->setPositionZ(12);

        $this->dm->persist($item2);

        $this->dm->flush();

        $this->dispatch('/api/item');
        $response = json_decode($this->getResponse()->getContent(), true);
        
        $this->assertEquals($response[0]['id'], $item->getId());
        $this->assertEquals($response[0]['name'], $item->getName());
        $this->assertEquals($response[0]['position_x'], $item->getPositionX());
        $this->assertEquals($response[0]['position_y'], $item->getPositionY());
        $this->assertEquals($response[0]['position_z'], $item->getPositionZ());

        $this->assertEquals($response[1]['id'], $item2->getId());
        $this->assertEquals($response[1]['name'], $item2->getName());
        $this->assertEquals($response[1]['position_x'], $item2->getPositionX());
        $this->assertEquals($response[1]['position_y'], $item2->getPositionY());
        $this->assertEquals($response[1]['position_z'], $item2->getPositionZ());
    }

    public function testCreate(){
        $postData = array(
            'name' => 'testCreate',
            'position_x' => 1,
            'position_y' => 2,
            'position_z' => 3,
            );
        $this->dispatch('/api/item', "POST", $postData);

        $this->assertResponseStatusCode(200);

        $response = json_decode($this->getResponse()->getContent(), true);
        $item = $this->dm->getRepository('Application\Document\Item')->find($response['id']);

        $this->assertEquals($response['id'], $item->getId());
        $this->assertEquals($response['name'], $item->getName());
        $this->assertEquals($response['position_x'], $item->getPositionX());
        $this->assertEquals($response['position_y'], $item->getPositionY());
        $this->assertEquals($response['position_z'], $item->getPositionZ());
    }

    public function testGet(){

        $item = new Item();
        $item->setName('testing');
        $item->setPositionX(4);
        $item->setPositionY(9);
        $item->setPositionZ(6);

        $this->dm->persist($item);
        $this->dm->flush();

        $this->dispatch('/api/item/'.$item->getId());
        $this->assertResponseStatusCode(200);

        $response = json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($response['id'], $item->getId());
        $this->assertEquals($response['name'], $item->getName());
        $this->assertEquals($response['position_x'], $item->getPositionX());
        $this->assertEquals($response['position_y'], $item->getPositionY());
        $this->assertEquals($response['position_z'], $item->getPositionZ());
   }

    public function tearDown(){
        $this->dm->getConnection()->dropDatabase($this->config['doctrine']['connection']['odm_default']['dbname']);
        parent::tearDown();
    }
}
