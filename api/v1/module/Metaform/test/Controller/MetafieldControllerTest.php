<?php
namespace Metaform;

use Metaform\Controller\MetafieldController;
use Zend\Stdlib\ArrayUtils;
use Metaform\Model\Metafield;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;

class MetafieldControllerTest extends ControllerTest{
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
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metafield = new Metafield();
        $metafield->exchangeArray(['id' => 122, 'name' => 'Sample1', 'sequence' => 1]);
        $metafield1 = new Metafield();
        $metafield1->exchangeArray(['id' => 123, 'name' => 'Sample2', 'sequence' => 2]);
        $resultSet->initialize([$metafield, $metafield1]);
        $metafieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['formId' => 111])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/metaform/111/metafield', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], $metafield->id);
        $this->assertEquals($content['data'][0]['name'], $metafield->name);
        $this->assertEquals($content['data'][0]['sequence'], $metafield->sequence);
        $this->assertEquals($content['data'][1]['id'], $metafield1->id);
        $this->assertEquals($content['data'][1]['name'], $metafield1->name);
        $this->assertEquals($content['data'][1]['sequence'], $metafield1->sequence);
        
    }

    public function testGet(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metafield = new Metafield();
        $metafield->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$metafield]);
        $metafieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/metaform/111/metafield/122', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $metafield->id);
        $this->assertEquals($content['data']['name'], $metafield->name);
        
    }

    public function testGetNotFound(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $resultSet->initialize([]);
        $metafieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue($resultSet));
        $this->dispatch('/metaform/111/metafield/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
    }


    public function testCreate(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Sample1'];
        $obj = new Metafield();
        $obj->exchangeArray($data);
        $obj->formId = 111;
        $resultSet->initialize([$obj->toArray()]);
        $metafieldTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $metafieldTableGateway->expects($this->once())
                ->method('getLastInsertValue')
                ->will($this->returnValue(123));
        $mockData = $this->getMockDbObject();
        $mockDbAdapter = $mockData['mock'];
        $dbAdapter = $mockData['dbAdapter'];
        $fieldData = new ResultSet(ResultSet::TYPE_ARRAY);
        $fieldData->initialize([['id' => 22]]);
        $mockDbAdapter->expects($this->once())
                ->method('query')
                ->with($this->anything(), Adapter::QUERY_MODE_EXECUTE)
                ->will($this->returnValue($fieldData));
        $mockDbAdapter->expects($this->atLeastOnce())
                ->method('getPlatform')
                ->will($this->returnValue($dbAdapter->getPlatform()));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/111/metafield', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 123);
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testCreateWithWrongField(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Sample1'];
        $obj = new Metafield();
        $obj->exchangeArray($data);
        $obj->formId = 111;
        $resultSet->initialize([$obj->toArray()]);
        $fieldData = new ResultSet(ResultSet::TYPE_ARRAY);
        $fieldData->initialize([]);
        $mockData = $this->getMockDbObject();
        $mockDbAdapter = $mockData['mock'];
        $dbAdapter = $mockData['dbAdapter'];
        $mockDbAdapter->expects($this->once())
                ->method('query')
                ->with($this->anything(), Adapter::QUERY_MODE_EXECUTE)
                ->will($this->returnValue($fieldData));
        $mockDbAdapter->expects($this->atLeastOnce())
                ->method('getPlatform')
                ->will($this->returnValue($dbAdapter->getPlatform()));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/111/metafield', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals(strrpos($content['message'], "Field '$obj->name'"), 0);
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testCreateFailure(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $data = ['name' => 'Sample1'];
        $obj = new Metafield();
        $obj->exchangeArray($data);
        $obj->formId = 111;
        $resultSet->initialize([]);
        $metafieldTableGateway->expects($this->once())
                ->method('insert')
                ->with($obj->toArray())
                ->will($this->returnValue($resultSet));
        $mockData = $this->getMockDbObject();
        $mockDbAdapter = $mockData['mock'];
        $dbAdapter = $mockData['dbAdapter'];
        $fieldData = new ResultSet(ResultSet::TYPE_ARRAY);
        $fieldData->initialize([['id' => 22]]);
        $mockDbAdapter->expects($this->once())
                ->method('query')
                ->with($this->anything(), Adapter::QUERY_MODE_EXECUTE)
                ->will($this->returnValue($fieldData));
        $mockDbAdapter->expects($this->atLeastOnce())
                ->method('getPlatform')
                ->will($this->returnValue($dbAdapter->getPlatform()));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/111/metafield', 'POST', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testUpdate(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metafield = new Metafield();
        $metafield->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$metafield]);
        $data = ['name' => 'Sample2', 'sequence' => 1];
        $obj = new Metafield();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $obj->formId = 111;
        $metafieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue($resultSet));
        $metafieldTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(1));
        $mockData = $this->getMockDbObject();
        $mockDbAdapter = $mockData['mock'];
        $dbAdapter = $mockData['dbAdapter'];
        $fieldData = new ResultSet(ResultSet::TYPE_ARRAY);
        $fieldData->initialize([['id' => 22]]);
        $mockDbAdapter->expects($this->once())
                ->method('query')
                ->with($this->anything(), Adapter::QUERY_MODE_EXECUTE)
                ->will($this->returnValue($fieldData));
        $mockDbAdapter->expects($this->atLeastOnce())
                ->method('getPlatform')
                ->will($this->returnValue($dbAdapter->getPlatform()));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/111/metafield/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], $obj->id);
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['sequence'], $data['sequence']);
        
    }

    public function testUpdateInvalidField(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metafield = new Metafield();
        $metafield->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$metafield]);
        $data = ['name' => 'Sample2', 'sequence' => 1];
        $obj = new Metafield();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $obj->formId = 111;
        $metafieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue($resultSet));
        $mockData = $this->getMockDbObject();
        $mockDbAdapter = $mockData['mock'];
        $dbAdapter = $mockData['dbAdapter'];
        $fieldData = new ResultSet(ResultSet::TYPE_ARRAY);
        $fieldData->initialize([]);
        $mockDbAdapter->expects($this->once())
                ->method('query')
                ->with($this->anything(), Adapter::QUERY_MODE_EXECUTE)
                ->will($this->returnValue($fieldData));
        $mockDbAdapter->expects($this->atLeastOnce())
                ->method('getPlatform')
                ->will($this->returnValue($dbAdapter->getPlatform()));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/111/metafield/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals(strrpos($content['message'], "Field '$obj->name'"), 0);
        $this->assertEquals($content['data']['name'], $data['name']);
        
    }

    public function testUpdateNotFound(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metafield = new Metafield();
        $metafield->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([]);
        $data = ['name' => 'Sample2', 'description' => 'Sample 2 Description'];
        $obj = new Metafield();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $obj->formId = 111;
        $metafieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue($resultSet));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/111/metafield/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testUpdateFailure(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $resultSet = $data['resultSet'];
        $metafield = new Metafield();
        $metafield->exchangeArray(['id' => 122, 'name' => 'Sample1']);
        $resultSet->initialize([$metafield]);
        $data = ['name' => 'Sample2', 'description' => 'Sample 2 Description'];
        $obj = new Metafield();
        $obj->exchangeArray($data);
        $obj->id = 122;
        $obj->formId = 111;
        $metafieldTableGateway->expects($this->once())
                ->method('select')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue($resultSet));
        $metafieldTableGateway->expects($this->once())
                ->method('update')
                ->with($obj->toArray(), ['id' => 122])
                ->will($this->returnValue(0));
        $mockData = $this->getMockDbObject();
        $mockDbAdapter = $mockData['mock'];
        $dbAdapter = $mockData['dbAdapter'];
        $fieldData = new ResultSet(ResultSet::TYPE_ARRAY);
        $fieldData->initialize([['id' => 22]]);
        $mockDbAdapter->expects($this->once())
                ->method('query')
                ->with($this->anything(), Adapter::QUERY_MODE_EXECUTE)
                ->will($this->returnValue($fieldData));
        $mockDbAdapter->expects($this->atLeastOnce())
                ->method('getPlatform')
                ->will($this->returnValue($dbAdapter->getPlatform()));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/metaform/111/metafield/122', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
        
    }

    public function testDelete(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $metafieldTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue(1));
        $this->dispatch('/metaform/111/metafield/122', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        
        
    }

    public function testDeleteNotFound(){
        $data = $this->getMockGatewayData(Model\MetafieldTableGateway::class, Model\Metafield::class);
        $metafieldTableGateway = $data['mock'];
        $metafieldTableGateway->expects($this->once())
                ->method('delete')
                ->with(['id' => 122, 'formId' => 111])
                ->will($this->returnValue(0));
        $this->dispatch('/metaform/111/metafield/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('metaform');
        $this->assertControllerName(MetafieldController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MetafieldController');
        $this->assertMatchedRouteName('metafield');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        
        
    }
}
