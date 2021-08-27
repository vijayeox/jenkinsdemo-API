<?php
namespace User;

    use User\Controller\UserSessionController;
    use Oxzion\Test\MainControllerTest;
    use PHPUnit\DbUnit\TestCaseTrait;
    use PHPUnit\DbUnit\DataSet\YamlDataSet;
    use Zend\Db\Sql\Sql;
    use Zend\Db\Adapter\Adapter;
    use Oxzion\Test\ControllerTest;

    class UserSessionControllerTest extends MainControllerTest
    {
        public function setUp() : void
        {
            $this->loadConfig();
            parent::setUp();
        }

        public function testUpdateSessionWithData()
        {
            $this->initAuthToken($this->adminUser);
            $sessionData='{"0":{"args":{},"name":"Chat","windows":[{"id":"ChatWindow","position":{"top":107,"left":200},"dimension":{"width":400,"height":500}}]}}';
            $data = ['data' => $sessionData];
            $this->setJsonContent(json_encode($data));
            $this->dispatch('/user/me/updatesession', 'POST', $data);
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(UserSessionController::class);
            $this->assertControllerClass('UserSessionController');
            $this->assertMatchedRouteName('updateSession');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array)json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'success');
        }
        public function testGetSessionWithData()
        {
            $this->initAuthToken($this->adminUser);
            $this->dispatch('/user/me/getsession', 'GET');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(UserSessionController::class);
            $this->assertControllerClass('UserSessionController');
            $this->assertMatchedRouteName('getSession');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array)json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'success');
        }
        public function testUpdateSessionWithoutData()
        {
            $this->initAuthToken($this->adminUser);
            $sessionData='';
            $data = ['data' => $sessionData];
            $this->setJsonContent(json_encode($data));
            $this->dispatch('/user/me/updatesession', 'POST', $data);
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(UserSessionController::class);
            $this->assertControllerClass('UserSessionController');
            $this->assertMatchedRouteName('updateSession');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array)json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'success');
        }
        public function testGetSessionWithoutData()
        {
            $this->initAuthToken($this->adminUser);
            $this->dispatch('/user/me/getsession', 'GET');
            $this->assertResponseStatusCode(200);
            $this->assertModuleName('User');
            $this->assertControllerName(UserSessionController::class);
            $this->assertControllerClass('UserSessionController');
            $this->assertMatchedRouteName('getSession');
            $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
            $content = (array)json_decode($this->getResponse()->getContent(), true);
            $this->assertEquals($content['status'], 'success');
        }
    }
