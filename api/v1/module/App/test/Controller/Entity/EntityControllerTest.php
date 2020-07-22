<?php
namespace App;

use App\Controller\EntityController;
use Mockery;
use Oxzion\Test\ControllerTest;
use Oxzion\Workflow\WorkflowFactory;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class EntityControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../../Dataset/Workflow.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['id'] > 0, true);
        $this->assertEquals($content['data'][0]['name'], 'entity1');
    }

    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'entity1');
        $this->assertEquals($content['data']['app_id'], 99);
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/122', 'GET');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = json_decode('{"name":"Entity Test1","description":"Entity Description"}');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'] > 2, true);
        $this->assertEquals($content['data']['name'], $data->name);
    }

    public function testCreateFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['app_id' => 99];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    public function testUpdate()
    {
        $this->initAuthToken($this->adminUser);
        $data = json_decode('{"name":"Entity23","description":"Entity Description","content":[{"content":"<div>Entity Content goes here!!!....</div>","type": "Document"},{"form_id":1,"type": "Form"}]}');
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/8ab30b2d-d1da-427a-8e40-bc954b2b0f87/entity/2', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data->name);
    }

    public function testUpdateNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Sample2', 'text' => 'Sample 2 Description'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/122', 'PUT', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(403);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/8ab30b2d-d1da-427a-8e40-bc954b2b0f87/entity/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('App');
        $this->assertControllerName(EntityController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EntityController');
        $this->assertMatchedRouteName('appentity');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity Not Found');
    }

    public function testDeploy()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files' => array(
                'name' => 'ScriptTaskTest.bpmn',
                'tmp_name' => __DIR__ . "/../../Dataset/ScriptTaskTest.bpmn",
                'size' => filesize(__DIR__ . "/../../Dataset/ScriptTaskTest.bpmn"),
                'error' => 0,
            ),
        );
        $config = $this->getApplicationConfig();
        $workflowFactory = WorkflowFactory::getInstance($config);
        $processManager = $workflowFactory->getProcessManager();
        $baseFolder = $config['UPLOAD_FOLDER'];
        if (enableCamunda == 0) {
            $mockProcessManager = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessManagerImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
            $mockProcessManager->expects('deploy')->with('NewWorkflow', array('/app/api/v1/config/autoload/../../data/uploads/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/ScriptTaskTest.bpmn'))->once()->andReturn(array(1));
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn(null);
            $workflowService->setProcessManager($mockProcessManager);
        }
        $data = array('name' => 'NewWorkflow');
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/d23d0c68-98c9-11e9-adc5-308d99c9145b/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        // print_r($content);exit;
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeployWithForm()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files' => array(
                'name' => 'SampleBPMN.bpmn',
                'tmp_name' => __DIR__ . "/../../Dataset/SampleBPMN.bpmn",
                'size' => filesize(__DIR__ . "/../../Dataset/SampleBPMN.bpmn"),
                'error' => 0,
            ),
        );
        $config = $this->getApplicationConfig();
        $workflowFactory = WorkflowFactory::getInstance($config);
        $processManager = $workflowFactory->getProcessManager();
        $parsingResult = $processManager->parseBPMN(__DIR__ . "/../../Dataset/SampleBPMN.bpmn", 99);
        $baseFolder = $config['UPLOAD_FOLDER'];
        if (enableCamunda == 0) {
            $mockProcessManager = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessManagerImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
            $mockProcessManager->expects('deploy')->with('NewWorkflow', array('/app/api/v1/config/autoload/../../data/uploads/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/SampleBPMN.bpmn'))->once()->andReturn(array("process:2232-abc"));
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn($parsingResult);
            $workflowService->setProcessManager($mockProcessManager);
        }
        $data = array('name' => 'NewWorkflow');
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/d23d0c68-98c9-11e9-adc5-308d99c9145b/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeployWithOutName()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files' => array(
                'name' => 'ScriptTaskTest.bpmn',
                'tmp_name' => __DIR__ . "/../../Dataset/ScriptTaskTest.bpmn",
                'size' => filesize(__DIR__ . "/../../Dataset/ScriptTaskTest.bpmn"),
                'error' => 0,
            ),
        );
        $data = array();
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/d23d0c68-98c9-11e9-adc5-308d99c9145b/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeployFailedBpmn()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES = array(
            'files' => array(
                'name' => 'ScriptTaskTestFail.bpmn',
                'tmp_name' => __DIR__ . "/../../Dataset/ScriptTaskTestFail.bpmn",
                'size' => filesize(__DIR__ . "/../../Dataset/ScriptTaskTestFail.bpmn"),
                'error' => 0,
            ),
        );
        $config = $this->getApplicationConfig();
        $baseFolder = $config['UPLOAD_FOLDER'];
        if (enableCamunda == 0) {
            $mockProcessManager = Mockery::mock('\Oxzion\Workflow\Camunda\ProcessManagerImpl');
            $workflowService = $this->getApplicationServiceLocator()->get(\Oxzion\Service\WorkflowService::class);
            $mockProcessManager->expects('deploy')->with('NewWorkflow1', array('/app/api/v1/config/autoload/../../data/uploads/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/ScriptTaskTestFail.bpmn'))->once()->andReturn(0);
            $mockProcessManager->expects('parseBPMN')->withAnyArgs()->once()->andReturn(null);
            $workflowService->setProcessManager($mockProcessManager);
        }
        $data = array('name' => 'NewWorkflow1');
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/d23d0c68-98c9-11e9-adc5-308d99c9145b/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(417);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }

    public function testDeployWithOutFile()
    {
        $_FILES = array();
        $this->initAuthToken($this->adminUser);
        $data = array('name' => 'NewWorkflow');
        $this->dispatch('/app/1c0f0bc6-df6a-11e9-8a34-2a2ae2dbcce4/entity/d23d0c68-98c9-11e9-adc5-308d99c9145b/deployworkflow', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
    }
}
