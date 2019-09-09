<?php
namespace Mlet;

use Mlet\Controller\MletController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Search\Indexer;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class MletControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
        if (enableElastic!=0) {
            $this->setElasticData();
            $config = $this->getApplicationConfig();
            $this->setupData();
            sleep(1) ;
        }
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Mlet.yml");
        return $dataset;
    }

    public function setElasticData()
    {
        $parser = new SymfonyYamlParser();
        $this->dataset = $parser->parseYaml(dirname(__FILE__)."/../Dataset/Elastic.yml");
    }

    public function createIndex($indexer, $body)
    {
        $type = 'type';
        $app_id = $body['app_id'];
        $id = $body['id'];
        AuthContext::put(AuthConstants::ORG_ID, $body['org_id']);
        $return=$indexer->index($app_id, $id, $type, $body);
    }

    public function setupData()
    {
        $indexer=  $this->getApplicationServiceLocator()->get(Indexer::class);
        $dataset = $this->dataset['ox_elastic'];
        foreach ($dataset as $body) {
            $this->createIndex($indexer, $body);
        }
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/mlet', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Mlet');
        $this->assertControllerName(MletController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MletController');
        $this->assertMatchedRouteName('mlet');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 4);
    }


    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/mlet/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Mlet');
        $this->assertControllerName(MletController::class); // as specified in router's controller name alias
        $this->assertControllerClass('MletController');
        $this->assertMatchedRouteName('mlet');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testSumResults()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = [];
        $this->setJsonContent(json_encode($data));
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/mlet/1/result', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['result']['data'], 1450.75);
    }

    public function testCountResults()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = [];
        $this->setJsonContent(json_encode($data));
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/mlet/5/result', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['result']['data'], 4);
    }

    public function testListResults()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = [];
        $this->setJsonContent(json_encode($data));
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/mlet/3/result', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals(count($content['data']['result']['data']), 5);
        $this->assertEquals($content['data']['result']['data'], [['amount' => 50.5,'name' => 'testing document'],['amount' => 600,'name' => 'New document'],
        ['amount' => 500.25,'name' => 'west document'],['amount' => 100,'name' => 'test document'],['amount' => 200,'name' => 'different document']]);
    }

    public function testGroupResults()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = [];
        $this->setJsonContent(json_encode($data));
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/mlet/4/result', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals(count($content['data']['result']['data']), 2);
        $this->assertEquals($content['data']['result']['data'], [['name' => 'John Doe','value' => 1400.25,'grouplist' => 'created_by'],[
            'name' => 'Mike Price','value' => 50.5,'grouplist' => 'created_by']]);
    }

    public function testGroupWithFilterResults()
    {
        if (enableElastic==0) {
            $this->markTestSkipped('Only Integration Test');
        }
        $data = ["Filter_value-created_by"=>"John Doe"];
        $this->setJsonContent(json_encode($data));
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/mlet/4/result', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['data']['result']['data'], [['name' => 'John Doe','value' => 1400.25,'grouplist' => 'created_by']]);
    }
    

    public function tearDown():void
    {
        parent::tearDown();
        if (enableElastic!=0) {
            $indexer=  $this->getApplicationServiceLocator()->get(Indexer::class);
            $return1=$indexer->delete('11_test', 'all');
            $return2=$indexer->delete('12_test', 'all');
        }
    }
}
