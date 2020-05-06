<?php
namespace Analytics;

use Analytics\Controller\WidgetController;
use Analytics\Model;
use MailSo\Sieve\Exceptions\Exception;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use Oxzion\Search\Indexer;
use Oxzion\Auth\AuthContext;
use Mockery;
use Oxzion\Auth\AuthConstants;

class WidgetConWidgetCalcAndFilterTest extends ControllerTest
{
    private $mock;
    private $index_pre;
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
        $config = $this->getApplicationConfig();
        if (isset($config['elasticsearch']['core'])) {
            $this->index_pre = $config['elasticsearch']['core'].'_';
        } else {
            $this->index_pre = '';
        }
    }

    public function tearDown()  : void {
        parent::tearDown();
        Mockery::close();
    }

    public function createIndex($indexer, $body)
    {
        $entity_name = 'test';
        $app_name = $body['app_name'];
        $id = $body['id'];
        $return=$indexer->index($app_name, $id, $entity_name, $body);
    }



    private function setMockData($input,$output)
    {
            $mock =  Mockery::mock('overload:Elasticsearch\ClientBuilder');
            $mock->shouldReceive('create')
            ->once()
            ->andReturn(0);
            $mock->shouldReceive('search')
            ->once()
            ->with($input)
            ->andReturn($output);
    }

    public function setElasticData()
    {
        $parser = new SymfonyYamlParser();
        $eDataset = $parser->parseYaml(dirname(__FILE__)."/../Dataset/ElasticCalcFilter.yml");
        $indexer=  $this->getApplicationServiceLocator()->get(Indexer::class);
 //       $indexer->delete('sampleapp_index', 'all');
 //       $indexer->delete('crm_index', 'all');
 //       $indexer->delete('diveinsurance', 'all');
        $dataset = $eDataset['ox_elastic'];
        foreach ($dataset as $body) {
            $this->createIndex($indexer, $body);
        }
        sleep(2);
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/DataSource.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/QueryCalcFilter.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Visualization.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/WidgetCalcFilter.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/WidgetQueryCalcFilter.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }


    public function testGetWithData() {
        if (enableElastic!=0) {
            $this->setElasticData();
        } else {
            $this->markTestSkipped('Only Integration Test'); //Mock will not work in this case. 
    //       $input =  json_decode('{"index":"'.$this->index_pre.'crm_index","body":{"query":{"bool":{"must":[{"term":{"org_id":1}},{"exists":{"field":"_id"}},{"range":{"createdAt":{"gte":"2018-01-01","lte":"2019-12-12","format":"yyyy-MM-dd"}}}]}},"_source":["*"],"explain":true},"_source":["*"],"from":0,"size":0}',true);
    //       $output = json_decode('{"took":0,"timed_out":false,"_shards":{"total":1,"successful":1,"skipped":0,"failed":0},"hits":{"total":{"value":3,"relation":"eq"},"max_score":null,"hits":[]}}',true);
    //       $this->setMockData($input,$output);
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","industry":"Insurance","budget_amount":1000},{"owner_username":"john","industry":"Software","budget_amount":2000},{"owner_username":"mark","industry":"Insurance","budget_amount":6000},{"owner_username":"jane","industry":"Insurance","budget_amount":5000}]');

    }

    public function testGetWithCombinedData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/0e57b45f-5938-4e26-acd8-d65fb89e8503?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
     //   echo $jsoncontent;
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","industry":"Insurance","actual_amount":500,"budget_amount":1000},{"owner_username":"john","industry":"Software","actual_amount":2300,"budget_amount":2000},{"owner_username":"mark","industry":"Insurance","actual_amount":5600,"budget_amount":6000},{"owner_username":"jane","industry":"Insurance","actual_amount":5000,"budget_amount":5000}]');
        

    }

    public function testGetWithExpressionData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/41e881c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","industry":"Insurance","budget_amount":1000,"actual_amount":500,"calcfield1":14285.71,"calcfield2":14285.714285714286,"calcfield3":0.02857142857142857,"calcfield4":0.029},{"owner_username":"john","industry":"Software","budget_amount":2000,"actual_amount":2300,"calcfield1":-17142.86,"calcfield2":-17142.85714285714,"calcfield3":0.05714285714285714,"calcfield4":0.057},{"owner_username":"mark","industry":"Insurance","budget_amount":6000,"actual_amount":5600,"calcfield1":68571.43,"calcfield2":68571.42857142857,"calcfield3":0.17142857142857143,"calcfield4":0.171},{"owner_username":"jane","industry":"Insurance","budget_amount":5000,"actual_amount":5000,"calcfield1":0,"calcfield2":0,"calcfield3":0.14285714285714285,"calcfield4":0.143}]');

    }


    public function testGetWithCombinedSingleData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/345881c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"q1":14000,"q2":13400}]');
    }


    public function testGetWithSingleExpressionData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/667781c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"q1":14000,"q2":13400,"sum":27400}]');
    }

    public function testGetWithSingleExpressionDataWithOutName() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/667781c3-344d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '27400');
    }

    public function testGetWithFilterParametersData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test');  
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?data=true&filter=%5B%22owner_username%22%2C%22%3D%3D%22%2C%22john%22%5D', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","industry":"Insurance","budget_amount":1000},{"owner_username":"john","industry":"Software","budget_amount":2000}]');
        
    }

    public function testGetWithComplexFilterParametersData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?data=true&filter=%5B%5B%22owner_username%22%2C%22%3D%3D%22%2C%22john%22%5D%2C%22AND%22%2C%5B%22industry%22%2C%22%3D%3D%22%2C%22Software%22%5D%5D', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","industry":"Software","budget_amount":2000}]');
    }

    public function testGetWithNotOverridingFilterParametersData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/123781c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"mark","budget_amount":6000,"actual_amount":5600},{"owner_username":"john","actual_amount":500}]');
    }

    public function testGetWithOverridingFilterParametersData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/123781c3-040d-44d8-9295-f2c3130bafbc?data=true&filter=%5B%5B%22owner_username%22%2C%22%3D%3D%22%2C%22john%22%5D%2C%22AND%22%2C%5B%22industry%22%2C%22%3D%3D%22%2C%22Software%22%5D%5D', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","budget_amount":2000,"actual_amount":2300}]');
    }

    public function testGetWithOverridingAtWidgetQueryData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/987881c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"mark","budget_amount":6000},{"owner_username":"john","budget_amount":1000}]');
    }

    public function testGetWithOverridingAtWidgetData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/987881c3-040d-44d8-9295-f2c3130bafbc?data=true&field=actual_amount', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"mark","actual_amount":5600},{"owner_username":"john","actual_amount":500}]');
    }

    public function testGetWithOverridingAtWidgetAndWidgetQueryData() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/333381c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","budget_amount":6000,"actual_amount":2300}]');
    }

    public function testGetWithOverridingMultiLevel() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/333381c3-040d-44d8-9295-f2c3130bafbc?data=true&filter=%5B%5B%22owner_username%22%2C%22%3D%3D%22%2C%22john%22%5D%2C%22AND%22%2C%5B%22industry%22%2C%22%3D%3D%22%2C%22Insurance%22%5D%5D', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","budget_amount":1000,"actual_amount":500}]');
    }


    public function testSortingWithLists() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/123432c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","industry":"Insurance","budget_amount":1000},{"owner_username":"mark","industry":"Insurance","budget_amount":3000},{"owner_username":"mark","industry":"Insurance","budget_amount":3000},{"owner_username":"jane","industry":"Insurance","budget_amount":5000},{"owner_username":"john","industry":"Software","budget_amount":2000}]');
    }

    public function testSortingWithGroup() {
        if(enableElastic==0){
            $this->markTestSkipped('Only Integration Test'); 
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/123432c3-040d-4444-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($content['status'], 'success');
        $jsoncontent = json_encode($content['data']['widget']['data']);
        $this->assertEquals($jsoncontent, '[{"owner_username":"john","budget_amount":1500},{"owner_username":"mark","budget_amount":3000},{"owner_username":"jane","budget_amount":5000}]');
    }


}