<?php

namespace ApiTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Application\Document\User;
use Application\Document\Item;

class UserControllerTest extends AbstractHttpControllerTestCase
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

    public function testgetListCantBeAccessed()
    {
        $this->dispatch('/api/user');
        $this->assertResponseStatusCode(405);

        $this->assertModuleName('Api');
        $this->assertControllerName('Api\Controller\User');
        $this->assertControllerClass('UserController');
    }

    public function testCreate(){
        $postData = array(
            'username' => 'test_user',
            );
        $this->dispatch('/api/user', "POST", $postData);

        $this->assertResponseStatusCode(200);

        $response = json_decode($this->getResponse()->getContent(), true);
        $user = $this->dm->getRepository('Application\Document\User')->find($response['id']);

        $this->assertEquals($response['id'], $user->getId());
        $this->assertEquals($response['username'], $user->getUsername());
        $this->assertEquals(0, $user->getPositionX());
        $this->assertEquals(0, $user->getPositionY());
        $this->assertEquals(0, $user->getPositionZ());
        $this->assertNotEmpty($user->getLastUpdated());
    }


    public function testGet(){
        $user = new User();
        
        $user->setUsername('user_name');
        $user->setPositionX(5);
        $user->setPositionY(3);
        $user->setPositionZ(10);

        $item = new Item();
        $item->setName('testing');
        $item->setPositionX(4);
        $item->setPositionY(9);
        $item->setPositionZ(6);

        $this->dm->persist($item);

        $user->setItem($item);

        $this->dm->persist($user);
        $this->dm->flush();

        $this->dispatch('/api/user/'.$user->getId());
        $this->assertResponseStatusCode(200);

        $response = json_decode($this->getResponse()->getContent(), true);

        $this->assertEquals($response['id'], $user->getId());
        $this->assertEquals($response['username'], $user->getUsername());
        $this->assertEquals(5, $user->getPositionX());
        $this->assertEquals(3, $user->getPositionY());
        $this->assertEquals(10, $user->getPositionZ());
        $this->assertNotEmpty($user->getLastUpdated());
        
        $this->assertNotEmpty($user->getItem());
        $this->assertEquals("testing", $user->getItem()->getName());
        $this->assertEquals(4, $user->getItem()->getPositionX());
        $this->assertEquals(9, $user->getItem()->getPositionY());
        $this->assertEquals(6, $user->getItem()->getPositionZ());
    }

    public function testPatch(){
        $patchData = array(
            'is_online' => false,
            'position_x' => 2,
            'position_y' => 4,
            'position_z' => 6
            );

        $user = new User();
        
        $user->setUsername('user_name');
        $user->setPositionX(5);
        $user->setPositionY(3);
        $user->setPositionZ(10);

        $this->dm->persist($user);
        $this->dm->flush();

        $data = http_build_query($patchData);

        $this->getRequest()->setMethod('PATCH');
        $this->getRequest()->setContent($data);

        $this->dispatch('/api/user/'.$user->getId());
        $this->assertResponseStatusCode(200);

        $response = json_decode($this->getResponse()->getContent(), true);

        $this->dm->getUnitOfWork()->clear('Application\Document\User');
        $user_new = $this->dm->getRepository('Application\Document\User')->find($user->getId());


        $this->assertNotEquals($user_new->getPositionX(), $user->getPositionX());
        $this->assertNotEquals($user_new->getPositionY(), $user->getPositionY());
        $this->assertNotEquals($user_new->getPositionZ(), $user->getPositionZ());
        $this->assertNotEquals($user_new->getIsOnline(), $user->getIsOnline());
    }

    public function testPatchItem(){
        $userData = array(
            'username' => 'test_user',
            );
        $this->dispatch('/api/user', "POST", $userData);
        $response_create = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);

        $itemData = array(
            'name' => 'testCreate',
            'position_x' => 1,
            'position_y' => 2,
            'position_z' => 3,
            );
        $this->dispatch('/api/item', "POST", $itemData);
        $response_item = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        

        $patchData = array(
            'item' => array('id'=> $response_item['id'])
            );

        $data = http_build_query($patchData);
        $this->getRequest()->setMethod('PATCH');
        $this->getRequest()->setContent($data);

        $this->dispatch('/api/user/'.$response_create['id']);
        $this->assertResponseStatusCode(200);

        $user = $this->dm->getRepository('Application\Document\User')->find($response_create['id']);

        $this->assertNotEmpty($user->getItem());
        $this->assertEquals("testCreate", $user->getItem()->getName());
        $this->assertEquals(1, $user->getItem()->getPositionX());
        $this->assertEquals(2, $user->getItem()->getPositionY());
        $this->assertEquals(3, $user->getItem()->getPositionZ());

        $patchData = array(
            'item' => "null"
            );

        $data = http_build_query($patchData);
        $this->getRequest()->setMethod('PATCH');
        $this->getRequest()->setContent($data);
        $this->dispatch('/api/user/'.$response_create['id']);

        $response_null = json_decode($this->getResponse()->getContent(), true);
        
        #clearing cache
        $this->dm->getUnitOfWork()->clear('Application\Document\User');
        $user = $this->dm->getRepository('Application\Document\User')->find($response_create['id']);

        $this->assertEmpty($user->getItem());
    }

    public function tearDown(){
        $this->dm->getConnection()->dropDatabase($this->config['doctrine']['connection']['odm_default']['dbname']);
        parent::tearDown();
    }
}
