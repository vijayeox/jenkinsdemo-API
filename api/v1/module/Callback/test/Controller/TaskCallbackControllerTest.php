<?php
namespace Callback;

use Callback\Controller\TaskCallbackController;
use Oxzion\Test\ControllerTest;
use Bos\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\RestClient;
use Callback\Service\TaskService;
use Mockery;
    


class TaskCallbackControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    private function getMockRestClientForTaskService(){
        $taskService = $this->getApplicationServiceLocator()->get(Service\TaskService::class);
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $taskService->setRestClient($mockRestClient);
        return $mockRestClient;
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'New Project 1','description' => 'Open project applications','uuid'=>'1'];
        if(enableCamel==0){ 
                     $mockRestClient = $this->getMockRestClientForTaskService();
                     $mockRestClient->expects('postWithHeader')->with("projects",array("name" => "New Project 1","description" => "Open project applications","uuid" => "1"))->once()->andReturn(array("body" => json_encode("{\"success\":true,\"result\":{\"name\":\"New Project 1\",\"description\":\"Open project applications\",\"uuid\":\"1\"},\"context\":{},\"dependent_results\":[],\"errors\":{},\"message\":null}")));  
                    }
        $this->dispatch('/callback/task/addproject', 'POST',array(json_encode($data)=>''));
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('addprojectfromcallback');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
    }

    public function testCreateProjectUuidAlreadyExists()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'New Project 1','description' => 'Open project applications','uuid'=>'1'];
        if(enableCamel==0){ 
                     $mockRestClient = $this->getMockRestClientForTaskService();
                     $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
                     $mockRestClient->expects('postWithHeader')->with("projects",array("name" => "New Project 1","description" => "Open project applications","uuid" => "1"))->once()->andThrow($exception);
                    }
        $this->dispatch('/callback/task/addproject', 'POST',array(json_encode($data)=>''));
        $this->assertResponseStatusCode(400);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('addprojectfromcallback');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }


    public function testCreateProjectInvalidParameters()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['description' => 'Open project applications','uuid'=>'1'];
        if(enableCamel==0){ 
                     $mockRestClient = $this->getMockRestClientForTaskService();
                     $exception = Mockery::Mock('GuzzleHttp\Exception\ClientException');
                     $mockRestClient->expects('postWithHeader')->with("projects",array("name" => NULL,"description" => "Open project applications","uuid" => "1"))->once()->andThrow($exception);
                    }
        $this->dispatch('/callback/task/addproject', 'POST',array(json_encode($data)=>''));
        $this->assertResponseStatusCode(400);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('addprojectfromcallback');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

     protected function setDefaultAsserts()
    {
        $this->assertModuleName('Callback');
        $this->assertControllerName(TaskCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('TaskCallbackController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }


}