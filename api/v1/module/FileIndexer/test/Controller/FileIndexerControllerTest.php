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
use Mockery;

class FileIndexerControllerTest extends ControllerTest
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

    private function getMockRestClientForTaskService()
    {
        $taskService = $this->getApplicationServiceLocator()->get(Service\TaskService::class);
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $taskService->setRestClient($mockRestClient);
        return $mockRestClient;
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
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'New Project 1'];
        $this->dispatch('/fileindexer', 'POST', $data);
        //$this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('index');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
    }
}
