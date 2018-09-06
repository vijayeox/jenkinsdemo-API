<?php
namespace Announcement;

use Announcement\Controller\AnnouncementController;
use Zend\Stdlib\ArrayUtils;
use Announcement\Model;
use Oxzion\Test\ControllerTest;

class AnnouncementControllerTest extends ControllerTest{
    public function setUp(){
        $configOverrides = [include __DIR__ . '/../../../../config/autoload/global.php'];
        $this->setApplicationConfig(ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',$configOverrides));
        parent::setUp();
        $this->initAuthToken('testUser');
    }
    public function testGetList(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $announcement = new Model\Announcement();
        $announcement->exchangeArray(['id' => 122, 'name' => 'Test Announcement 1']);
        $announcement1 = new Model\Announcement();
        $announcement1->exchangeArray(['id' => 123, 'name' => 'Test Announcement 2']);
        $resultSet->initialize([$announcement, $announcement1]);
        $announcementTableGateway->expects($this->once())
                ->method('select')
                ->will($this->returnValue($resultSet));
        $this->dispatch('/announcement', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'applicatio
            n/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], $announcement->id);
        $this->assertEquals($content['data'][0]['name'], $announcement->name);
        $this->assertEquals($content['data'][1]['id'], $announcement1->id);
        $this->assertEquals($content['data'][1]['name'], $announcement1->name);
    }
    public function testGet(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $announcement = new Model\Announcement();
        $announcement->exchangeArray(['id' => 1, 'name' => 'Test Announcement']);
        $resultSet->initialize([$announcement]);
        $announcementTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 1])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/announcement/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $announcement->id);
        $this->assertEquals($content['data']['name'], $announcement->name);
    }
    public function testGetNotFound(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $resultSet->initialize([]);
        $announcementTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 1])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/announcement/1', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Test Announcement'];
        $obj = new Model\Announcement();
        $obj->exchangeArray($data);
        $resultSet->initialize([$obj->toArray()]);
        $announcementTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $announcementTableGateway->expects($this->once())
                ->method('getLastInsertValue')
                ->will($this->returnValue(123));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 123);
        $this->assertEquals($content['data']['name'], $data['name']);
    }
    public function testCreateFailure(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Test Announcement'];
        $obj = new Model\Announcement();
        $obj->exchangeArray($data);
        $resultSet->initialize([]);
        $announcementTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement', 'POST', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
    }
    public function testUpdate(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $announcement = new Model\Announcement();
        $announcement->exchangeArray(['id' => 122, 'name' => 'Test Announcement 1']);
        $resultSet->initialize([$announcement]);
        $data = ['name' => 'Test Announcement 2', 'description' => 'Test Announcement Description'];
        $obj = new Model\Announcement();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $announcementTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $announcementTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(1));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $obj->id);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
        
    }

    public function testUpdateNotFound(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $announcement = new Model\Announcement();
        $announcement->exchangeArray(['id' => 122, 'name' => 'Test Announcement 1']);
        $resultSet->initialize([]);
        $data = ['name' => 'Test Announcement 2', 'description' => 'Test Announcement Description'];
        $obj = new Model\Announcement();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $announcementTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateFailure(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $announcement = new Model\Announcement();
        $announcement->exchangeArray(['id' => 122, 'name' => 'Test Announcement 1']);
        $resultSet->initialize([$announcement]);
        $data = ['name' => 'Test Announcement 2', 'description' => 'Test Announcement Description'];
        $obj = new Model\Announcement();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $announcementTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $announcementTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(0));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/announcement/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
    }

    public function testDelete(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $announcementTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(1));
        $this->dispatch('/announcement/122', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $data = $this->getMockGatewayData(Model\AnnouncementTableGateway::class, Model\Announcement::class);
        $announcementTableGateway = $data['mock'];
        $announcementTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(0));
        $this->dispatch('/announcement/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Announcement');
        $this->assertControllerName(AnnouncementController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AnnouncementController');
        $this->assertMatchedRouteName('announcement');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }
}