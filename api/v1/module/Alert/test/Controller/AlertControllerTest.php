<?php

namespace Alert;

use Alert\Controller\AlertController;
use Zend\Stdlib\ArrayUtils;
use Alert\Model;
use Oxzion\Test\ControllerTest;

class AlertControllerTest extends ControllerTest {

    public function setUp() {
        $configOverrides = [include __DIR__ . '/../../../../config/autoload/global.php'];
        $this->setApplicationConfig(ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php', $configOverrides));
        parent::setUp();
        $this->initAuthToken('testUser');
    }

    public function testGetList() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $alert = new Model\Alert();
        $alert->exchangeArray(['id' => 122, 'name' => 'Test Alert 1']);
        $alert1 = new Model\Alert();
        $alert1->exchangeArray(['id' => 123, 'name' => 'Test Alert 2']);
        $resultSet->initialize([$alert, $alert1]);
        $alertTableGateway->expects($this->once())
                ->method('select')
                ->will($this->returnValue($resultSet));
        $this->dispatch('/alert', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], $alert->id);
        $this->assertEquals($content['data'][0]['name'], $alert->name);
        $this->assertEquals($content['data'][1]['id'], $alert1->id);
        $this->assertEquals($content['data'][1]['name'], $alert1->name);
    }

    public function testGet() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $alert = new Model\Alert();
        $alert->exchangeArray(['id' => 1, 'name' => 'Test Alert']);
        $resultSet->initialize([$alert]);
        $alertTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 1])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/alert/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $alert->id);
        $this->assertEquals($content['data']['name'], $alert->name);
    }

    public function testGetNotFound() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $resultSet->initialize([]);
        $alertTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 1])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/alert/1', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testCreate() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Test Alert'];
        $obj = new Model\Alert();
        $obj->exchangeArray($data);
        $resultSet->initialize($obj->toArray());
        $alertTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $alertTableGateway->expects($this->once())
                ->method('getLastInsertValue')
                ->will($this->returnValue(123));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/alert', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 123);
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testCreateFailure() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Test Alert'];
        $obj = new Model\Alert();
        $obj->exchangeArray($data);
        $resultSet->initialize([]);
        $alertTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/alert', 'POST', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdate() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $alert = new Model\Alert();
        $alert->exchangeArray(['id' => 122, 'name' => 'Test Alert 1']);
        $resultSet->initialize([$alert]);
        $data = ['name' => 'Test Alert 2', 'text' => 'Test Alert Description'];
        $obj = new Model\Alert();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $alertTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $alertTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(1));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/alert/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $obj->id);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['text'], $data['text']);
    }

    public function testUpdateNotFound() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $alert = new Model\Alert();
        $alert->exchangeArray(['id' => 122, 'name' => 'Test Alert 1']);
        $resultSet->initialize([]);
        $data = ['name' => 'Test Alert 2', 'text' => 'Test Alert Description'];
        $obj = new Model\Alert();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $alertTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/alert/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateFailure() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $alert = new Model\Alert();
        $alert->exchangeArray(['id' => 122, 'name' => 'Test Alert 1']);
        $resultSet->initialize([$alert]);
        $data = ['name' => 'Test Alert 2', 'text' => 'Test Alert Description'];
        $obj = new Model\Alert();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $alertTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $alertTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(0));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/alert/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['text'], $data['text']);
    }

    public function testDelete() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $alertTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(1));
        $this->dispatch('/alert/122', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound() {
        $data = $this->getMockGatewayData(Model\AlertTableGateway::class, Model\Alert::class);
        $alertTableGateway = $data['mock'];
        $alertTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(0));
        $this->dispatch('/alert/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Alert');
        $this->assertControllerName(AlertController::class); // as specified in router's controller name alias
        $this->assertControllerClass('AlertController');
        $this->assertMatchedRouteName('alert');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

}
