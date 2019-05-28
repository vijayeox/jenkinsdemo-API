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

    public function testCreate()
    {
    	$this->initAuthToken($this->adminUser);
        $data = ['name' => 'New Project 1','description' => 'Open project applications'];
        $this->dispatch('/callback/task/addproject', 'POST',array(json_encode($data)=>''));
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('addprojectfromcallback');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['description'], $data['description']);
    }

     protected function setDefaultAsserts()
    {
        $this->assertModuleName('Callback');
        $this->assertControllerName(TaskCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('TaskCallbackController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }


}
