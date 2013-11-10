<?php

namespace ApiTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ItemControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include dirname(__DIR__)."/../../config/application.config.php"
        );
        parent::setUp();
    }

    public function testgetListCantBeAccessed()
    {
        $this->dispatch('/api/item');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Api');
        $this->assertControllerName('Api\Controller\Item');
        $this->assertControllerClass('ItemController');
    }
}
