<?php

namespace ApiTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UserControllerTest extends AbstractHttpControllerTestCase
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
        $this->dispatch('/api/user');
        $this->assertResponseStatusCode(405);

        $this->assertModuleName('Api');
        $this->assertControllerName('Api\Controller\User');
        $this->assertControllerClass('UserController');
    }
}
