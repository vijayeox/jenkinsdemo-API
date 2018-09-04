<?php
namespace Field;

use Field\Controller\FieldController;
use Zend\Stdlib\ArrayUtils;
use Field\Model\Field;
use Oxzion\Test\ControllerTest;

class FieldControllerTest extends ControllerTest{
    public function setUp()
    {
        $configOverrides = [include __DIR__ . '/../../../../config/autoload/global.php'];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
        $this->initAuthToken('testUser');
    }

    public function testGetList(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $field = new Field();
        $field->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $field1 = new Field();
        $field1->exchangeArray(['id' => 123, 'name' => 'Sample2']);
        $resultSet->initialize([$field, $field1]);
        $fieldTableGateway->expects($this->once())
                ->method('select')
                ->will($this->returnValue($resultSet));
        $this->dispatch('/field', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], $field->id);
        $this->assertEquals($content['data'][0]['name'], $field->name);
        $this->assertEquals($content['data'][1]['id'], $field1->id);
        $this->assertEquals($content['data'][1]['name'], $field1->name);
    }

    public function testGet(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $field = new Field();
        $field->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$field]);
        $fieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/field/122', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $field->id);
        $this->assertEquals($content['data']['name'], $field->name);
        
    }

    public function testGetNotFound(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $resultSet->initialize([]);
        $fieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/field/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
    }

    public function testCreate(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Sample1'];
        $obj = new Field();
        $obj->exchangeArray($data);
        $resultSet->initialize($obj->toArray());
        $fieldTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $fieldTableGateway->expects($this->once())
                ->method('getLastInsertValue')
                ->will($this->returnValue(123));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/field', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 123);
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testCreateFailure(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Sample1'];
        $obj = new Field();
        $obj->exchangeArray($data);
        $resultSet->initialize([]);
        $fieldTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/field', 'POST', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testUpdate(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $field = new Field();
        $field->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$field]);
        $data = ['name' => 'Sample2', 'text' => 'Sample 2 Description'];
        $obj = new Field();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $fieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $fieldTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(1));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/field/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $obj->id);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['text'], $data['text']);
        
    }

    public function testUpdateNotFound(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $field = new Field();
        $field->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([]);
        $data = ['name' => 'Sample2', 'text' => 'Sample 2 Description'];
        $obj = new Field();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $fieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/field/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateFailure(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $field = new Field();
        $field->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$field]);
        $data = ['name' => 'Sample2', 'text' => 'Sample 2 Description'];
        $obj = new Field();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $fieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122])
                ->will($this->returnValue($resultSet));
        $fieldTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(0));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/field/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['text'], $data['text']);
        
    }

    public function testDelete(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $fieldTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(1));
        $this->dispatch('/field/122', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        
        
    }

    public function testDeleteNotFound(){
        $data = $this->getMockGatewayData(Model\FieldTableGateway::class, Model\Field::class);
        $fieldTableGateway = $data['mock'];
        $fieldTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122])
                ->will($this->returnValue(0));
        $this->dispatch('/field/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('field');
        $this->assertControllerName(FieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FieldController');
        $this->assertMatchedRouteName('field');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
        
    }
}
