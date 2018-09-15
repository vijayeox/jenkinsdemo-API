<?php
namespace Metaform;

use Metaform\Controller\MetaformController;
use Zend\Stdlib\ArrayUtils;
use Metaform\Model\Metaform;
use Oxzion\Test\ControllerTest;

class MetaformControllerTest extends ControllerTest{
    public function setUp()
    {
        $this->loadConfig();
        parent::setUp();
        $this->initAuthToken('testUser');
    
    }

    public function testGetList(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metaform = new Metaform();
        $metaform->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $metaform1 = new Metaform();
        $metaform1->exchangeArray(['id' => 123, 'name' => 'Sample2']);
        $resultSet->initialize([$metaform, $metaform1]);
        $metaformTableGateway->expects($this->once())
                ->method('select')
                ->will($this->returnValue($resultSet));
        $this->dispatch('/metaform', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], $metaform->id);
        $this->assertEquals($content['data'][0]['name'], $metaform->name);
        $this->assertEquals($content['data'][1]['id'], $metaform1->id);
        $this->assertEquals($content['data'][1]['name'], $metaform1->name);
    }

    public function testGet(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metaform = new Metaform();
        $metaform->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$metaform]);
        $metaformTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/metaform/122', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $metaform->id);
        $this->assertEquals($content['data']['name'], $metaform->name);
        
    }

    public function testGetNotFound(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $resultSet->initialize([]);
        $metaformTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/metaform/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
    }

    public function testCreate(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Sample1'];
        $obj = new Metaform();
        $obj->exchangeArray($data);
        $resultSet->initialize([$obj->toArray()]);
        $metaformTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $metaformTableGateway->expects($this->once())
                ->method('getLastInsertValue')
                ->will($this->returnValue(123));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 123);
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testCreateFailure(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Sample1'];
        $obj = new Metaform();
        $obj->exchangeArray($data);
        $resultSet->initialize([]);
        $metaformTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform', 'POST', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testUpdate(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metaform = new Metaform();
        $metaform->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$metaform]);
        $data = ['name' => 'Sample2', 'description' => 'Sample 2 Description'];
        $obj = new Metaform();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $metaformTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $metaformTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(1));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $obj->id);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
        
    }

    public function testUpdateNotFound(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metaform = new Metaform();
        $metaform->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([]);
        $data = ['name' => 'Sample2', 'description' => 'Sample 2 Description'];
        $obj = new Metaform();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $metaformTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateFailure(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metaform = new Metaform();
        $metaform->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$metaform]);
        $data = ['name' => 'Sample2', 'description' => 'Sample 2 Description'];
        $obj = new Metaform();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $metaformTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $metaformTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(0));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
        
    }

    public function testDelete(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $metaformTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(1));
        $this->dispatch('/metaform/122', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        
        
    }

    public function testDeleteNotFound(){
        $data = $this->getMockGatewayData(Model\MetaformTableGateway::class, Model\Metaform::class);
        $metaformTableGateway = $data['mock'];
        $metaformTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(0));
        $this->dispatch('/metaform/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetaformController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetaformController');
        $this->assertMatchedRouteName('metaform');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
        
    }
}
