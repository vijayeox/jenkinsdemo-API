<?php
namespace FileIndexer;

use FileIndexer\Controller\FileIndexerController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\RestClient;
use FileIndexer\Service\FileIndexerService;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Mockery;

class FileIndexerControllerTest extends ControllerTest
{
    private $config;

    public function setUp() : void
    {
        $this->loadConfig();
        $this->config = $this->getApplicationConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(realpath(dirname(__FILE__)."/../Dataset/WorkflowTest.yml"));
        $dataset->addYamlFile(dirname(__FILE__) . "/../../../User/test/Dataset/User.yml");
        return $dataset;
    }

    private function getMockRestClientForFileIndexerService()
    {
        $fileIndexerService = $this->getApplicationServiceLocator()->get(Service\FileIndexerService::class);
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $fileIndexerService->setRestClient($mockRestClient);
        return $mockRestClient;
    }

    public function getMockMessageProducer(){
        $fileIndexerService = $this->getApplicationServiceLocator()->get(Service\FileIndexerService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $fileIndexerService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('FileIndexer');
        $this->assertControllerName(FileIndexerController::class); // as specified in router's controller name alias
        $this->assertControllerClass('FileIndexerController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        //Scenario where both workflow_instance_id and activity_instance_id exists
        $this->initAuthToken($this->adminUser);
        $data = ['id' => 102];
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'/elastic')->once()->andReturn();
        }
        if (enableElastic==0) {
            $mockRestClient = $this->getMockRestClientForFileIndexerService();
            $mockRestClient->expects('get')->with("localhost:".$this->config['elasticsearch']['port']."/sampleapp_index/file/2")->once()->andReturn(array("body" => json_encode(array (
              '_index' => 'sampleapp_index',
              '_type' => 'file',
              '_id' => '2',
              '_version' => 11,
              'found' => true,
              '_source' =>
              array (
                'index' => 'SampleApp_index',
                'body' =>
                array (
                    0 =>
                  array (
                    'workflow_instance_id' => '1',
                    'activity_instance_id' => '1',
                    'form_id' => '2',
                    'form_name' => 'Test Form 2',
                    'org_id' => '1',
                    'app_id' => '99',
                    'app_name' => 'SampleApp',
                    'workflow_name' => 'Test Workflow 1',
                    'worflow_instance_status' => 'In Progress',
                    'workflow_instance_date_created' => '2019-06-26 00:00:00',
                    'activity_name' => 'Task',
                    'activity_instance_status' => 'In Progress',
                    'activity_instance_start_date' => '2019-09-16 13:23:21',
                    'activity_instance_act_by_date' => NULL,
                    'fields' => '{"field3" : "field3text","field4" : "field4text"}',
                    'data' => '{"field3":3,"field4":4}',
                ),
              ),
                'id' => 2,
                'operation' => 'Index',
                'type' => 'file',
            ),
          ))));
        }
        
        $this->dispatch('/fileindexer', 'POST', $data);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('index');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['form_id'], $data['id']);
        $this->assertEquals($content['data'][0]['form_name'], 'Test Form 2');
        $this->assertEquals($content['data'][0]['fields'], '{"field3" : "field3text","field4" : "field4text"}');
    }

    public function testCreateScenario2()
    {
        //Scenario where workflow_instance_id exists and activity_instance_id does not
        $this->initAuthToken($this->adminUser);
        $data = ['id' => 1];
        $this->dispatch('/fileindexer', 'POST', $data);
        if (enableElastic==0) {
            $mockRestClient = $this->getMockRestClientForFileIndexerService();
            $mockRestClient->expects('get')->with("localhost:".$this->config['elasticsearch']['port']."/sampleapp_index/file/1")->once()->andReturn(array("body" => json_encode(array (
              '_index' => 'sampleapp_index',
              '_type' => 'file',
              '_id' => '1',
              '_version' => 1,
              'found' => true,
              '_source' =>
              array (
                'index' => 'SampleApp_index',
                'body' =>
                array (
                  0 =>
                  array (
                    'workflow_instance_id' => '1',
                    'activity_instance_id' => NULL,
                    'form_id' => '1',
                    'form_name' => 'Task',
                    'org_id' => '1',
                    'app_id' => '99',
                    'app_name' => 'SampleApp',
                    'workflow_name' => 'Test Workflow 1',
                    'worflow_instance_status' => 'In Progress',
                    'workflow_instance_date_created' => '2019-06-26 00:00:00',
                    'activity_name' => NULL,
                    'activity_instance_status' => NULL,
                    'activity_instance_start_date' => NULL,
                    'activity_instance_act_by_date' => NULL,
                    'fields' => '{"field1" : "field1text","field2" : "field2text"}',
                    'data' => '{\"field1\":3,\"field2\":4}',
                ),
              ),
                'id' => 1,
                'operation' => 'Index',
                'type' => 'file',
            ),
          ))));
        }
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'/topic/elastic')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('index');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['form_id'], $data['id']);
        $this->assertEquals($content['data'][0]['form_name'], 'Task');
        $this->assertEquals($content['data'][0]['fields'], '{"field1" : "field1text","field2" : "field2text"}');
    }

    public function testCreateScenario3()
    {
        //scenario where both workflow_instance_id and activity_instance_id does not
        $this->initAuthToken($this->adminUser);
        $data = ['id' => 3];
        $this->dispatch('/fileindexer', 'POST', $data);
        if (enableElastic==0) {
            $mockRestClient = $this->getMockRestClientForFileIndexerService();
            $mockRestClient->expects('get')->with("localhost:".$this->config['elasticsearch']['port']."/sampleapp_index/file/3")->once()->andReturn(array("body" => json_encode(array (
              '_index' => 'sampleapp_index',
              '_type' => 'file',
              '_id' => '2',
              '_version' => 11,
              'found' => true,
              '_source' =>
              array (
                'index' => 'SampleApp_index',
                'body' =>
                array (
                    0 =>
                  array (
                    'workflow_instance_id' => '1',
                    'activity_instance_id' => '1',
                    'form_id' => '2',
                    'form_name' => 'Test Form 2',
                    'org_id' => '1',
                    'app_id' => '99',
                    'app_name' => 'SampleApp',
                    'workflow_name' => 'Test Workflow 1',
                    'worflow_instance_status' => 'In Progress',
                    'workflow_instance_date_created' => '2019-06-26 00:00:00',
                    'activity_name' => 'Task',
                    'activity_instance_status' => 'In Progress',
                    'activity_instance_start_date' => '2019-09-16 13:23:21',
                    'activity_instance_act_by_date' => NULL,
                    'fields' => '{"field2" : "field2text","field4" : "field4text"}',
                    'data' => 'new data',
                ),
              ),
                'id' => 2,
                'operation' => 'Index',
                'type' => 'file',
            ),
          ))));
        }
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'/topic/elastic')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('index');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['form_id'], $data['id']);
        $this->assertEquals($content['data'][0]['form_name'], 'Test Form 3');
        $this->assertEquals($content['data'][0]['fields'], '{"field2" : "field2text","field4" : "field4text"}');
    }

    public function testCreateScenario4()
    {
        //Scenario where file id does not exist
        $this->initAuthToken($this->adminUser);
        $data = ['id' => 35];
        $this->dispatch('/fileindexer', 'POST', $data);
        if (enableElastic==0) {
            $mockRestClient = $this->getMockRestClientForFileIndexerService();
            $mockRestClient->expects('get')->with("localhost:".$this->config['elasticsearch']['port']."/sampleapp_index/file/35")->once()->andReturn(array("body" => json_encode(array (
              '_index' => 'sampleapp_index',
              '_type' => 'file',
              '_id' => '35',
              'found' => false
          ))));
        }
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'/topic/elastic')->once()->andReturn();
        }
        $this->assertResponseStatusCode(400);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('index');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Failure to Index File ');
    }

    public function testDelete()
    {
        //Scenario where file id does not exist
        $this->initAuthToken($this->adminUser);
        $data = ['id' => 1];
        $this->dispatch('/fileindexer/remove', 'POST', $data);
        if (enableElastic==0) {
            $mockRestClient = $this->getMockRestClientForFileIndexerService();
            $mockRestClient->expects('get')->with("localhost:".$this->config['elasticsearch']['port']."/sampleapp_index/file/1")->once()->andReturn(array("body" => json_encode(array (
              '_index' => 'sampleapp_index',
              '_type' => 'file',
              '_id' => '1',
              'found' => false,
              ))));
        }
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'/topic/elastic')->once()->andReturn();
        }
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('deleteIndex');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['fileId'], 1);
    }

    public function testDeleteWithWrongId()
    {
        //Scenario where file id does not exist
        $this->initAuthToken($this->adminUser);
        $data = ['id' => 10];
        $this->dispatch('/fileindexer/remove', 'POST', $data);
        if (enableElastic==0) {
            $mockRestClient = $this->getMockRestClientForFileIndexerService();
            $mockRestClient->expects('get')->with("localhost:".$this->config['elasticsearch']['port']."/sampleapp_index/file/10")->once()->andReturn(array("body" => json_encode(array (
              '_index' => 'sampleapp_index',
              '_type' => 'file',
              '_id' => '1',
              'found' => false,
              ))));
        }
        if(enableActiveMQ == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'/topic/elastic')->once()->andReturn();
        }
        $this->assertResponseStatusCode(400);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('deleteIndex');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Failure to Delete File ');
    }
}
