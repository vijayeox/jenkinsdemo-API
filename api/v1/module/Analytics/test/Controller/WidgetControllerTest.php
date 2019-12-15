<?php
namespace Analytics;

use Analytics\Controller\WidgetController;
use Analytics\Model;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\SymfonyYamlParser;
use Oxzion\Search\Indexer;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class WidgetControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();

    }

    public function createIndex($indexer, $body)
    {
        $entity_name = 'test';
        $app_name = $body['app_name'];
        $id = $body['id'];
        AuthContext::put(AuthConstants::ORG_ID, $body['org_id']);
        $return=$indexer->index($app_name, $id, $entity_name, $body);
    }

    public function setElasticData()
    {
        $parser = new SymfonyYamlParser();
        $eDataset = $parser->parseYaml(dirname(__FILE__)."/../Dataset/Elastic.yml");
        $indexer=  $this->getApplicationServiceLocator()->get(Indexer::class);
  //      $indexer->delete('sampleapp_index', 'all');
  //      $indexer->delete('crm_index', 'all');
        sleep(1);
        $dataset = $eDataset['ox_elastic'];
        foreach ($dataset as $body) {
            $this->createIndex($indexer, $body);
        }
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/DataSource.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Query.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Visualization.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Widget.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/WidgetQuery.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Analytics');
        $this->assertControllerName(WidgetController::class); // as specified in router's controller name alias
        $this->assertControllerClass('WidgetController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['query_uuid' => '1a7d9e0d-f6cd-40e2-9154-87de247b9ce1','visualization_uuid' => "44f22a46-26d2-48df-96b9-c58520005817", 'ispublic' => 1 , 'name' => 'widget30' , 'configuration' => 'sample configuration','expression' => '','queries' => array(array('uuid' =>'8f1d2819-c5ff-4426-bc40-f7a20704a738','configuration' => 'sample_conf'),array('uuid' =>'86c0cc5b-2567-4e5f-a741-f34e9f6f1af1','configuration' => 'sample_conf'))];
        $this->assertEquals(8, $this->getConnection()->getRowCount('ox_widget'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['query_uuid'], $data['query_uuid']);
        $this->assertEquals($content['data']['ispublic'], $data['ispublic']);
        $this->assertEquals(9, $this->getConnection()->getRowCount('ox_widget'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['query_uuid' => '1a7d9e0d-f6cd-40e2-9154-87de247b9ce1','visualization_uuid' => "44f22a46-26d2-48df-96b9-c58520005817", 'ispublic' => 1 , 'configuration' => 'sample configuration','expression' => ''];
        $this->assertEquals(8, $this->getConnection()->getRowCount('ox_widget'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }

    //DO NOT ADD THIS AT IS NOT NEEDED. LEAVING THIS HERE IN CASE THE REQUIREMENT CHANGES
    //-----------------------------------------------------------------------------------
    // - BRIAN
    // public function testUpdate()
    // {
    //     $data = ['visualization_id' => 1, 'version' => 1];
    //     $this->initAuthToken($this->adminUser);
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc', 'PUT', null);
    //     $this->assertResponseStatusCode(200);
    //     $this->setDefaultAsserts();
    //     $this->assertMatchedRouteName('analytics_widget');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals($content['data']['visualization_id'], $data['visualization_id']);
    // }

    // public function testUpdateWithWrongVersion()
    // {
    //     $data = ['visualization_id' => 1, 'version' => 3];
    //     $this->initAuthToken($this->adminUser);
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc', 'PUT', null);
    //     $this->assertResponseStatusCode(404);
    //     $this->setDefaultAsserts();
    //     $this->assertMatchedRouteName('analytics_widget');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['message'], 'Version changed');
    // }

    // public function testUpdateNotFound()
    // {
    //     $data = ['visualization_id' => 1, 'version' => 1];
    //     $this->initAuthToken($this->adminUser);
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/analytics/widget/1000', 'PUT', null);
    //     $this->assertResponseStatusCode(404);
    //     $this->setDefaultAsserts();
    //     $this->assertMatchedRouteName('analytics_widget');
    //     $content = (array)json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?version=1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteWithWrongVersion()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?version=3', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Version changed');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/10000?version=1', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGet() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['widget']['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
  //      $this->assertEquals($content['data']['widget']['query_uuid'],"8f1d2819-c5ff-4426-bc40-f7a20704a738");
    }

    public function testGetWithParams() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?config=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['widget']['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['widget']['ispublic'],1);
    }


    public function testGetWithData() {
        if (enableElastic!=0) {
            $this->setElasticData();
            sleep(1) ;
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/0e57b45f-5938-4e26-acd8-d65fb89e8503?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['widget']['data'], 3);
    }

    public function testGetWithCombinedData() {
        if (enableElastic!=0) {
            $this->setElasticData();
            sleep(1) ;
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['widget']['data'][0]['field3'],"cfield3text");
        $this->assertEquals($content['data']['widget']['data'][0]['field5'],35);
        $this->assertEquals($content['data']['widget']['data'][0]['field6'],45);
        $this->assertEquals($content['data']['widget']['data'][1]['field3'],"cfield5text");
        $this->assertEquals($content['data']['widget']['data'][1]['field5'],40);
        $this->assertEquals($content['data']['widget']['data'][1]['field6'],70);
    }

    public function testGetWithExpressionData() {
        if (enableElastic!=0) {
            $this->setElasticData();
            sleep(1) ;
        }
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/41e881c3-040d-44d8-9295-f2c3130bafbc?data=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['widget']['data'][0]['field3'],"cfield3text");
        $this->assertEquals($content['data']['widget']['data'][0]['field5'],35);
        $this->assertEquals($content['data']['widget']['data'][0]['field6'],45);
        $this->assertEquals($content['data']['widget']['data'][0]['calcfield1'],4.5);
        $this->assertEquals($content['data']['widget']['data'][0]['calcfield2'],10);
        $this->assertEquals($content['data']['widget']['data'][1]['field3'],"cfield5text");
        $this->assertEquals($content['data']['widget']['data'][1]['field5'],40);
        $this->assertEquals($content['data']['widget']['data'][1]['field6'],70);
        $this->assertEquals($content['data']['widget']['data'][1]['calcfield1'],21);
        $this->assertEquals($content['data']['widget']['data'][1]['calcfield2'],30);
        
    }

    public function testGetWithConfig() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc?config=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['widget']['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
    }

    public function testGetNotFound() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget/100', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 8);
        $this->assertEquals($content['data']['data'][5]['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['data'][5]['is_owner'], true);
        $this->assertEquals($content['data']['data'][5]['name'], 'widget1');
        $this->assertEquals($content['data']['data'][6]['name'], 'widget2');
        $this->assertEquals($content['data']['data'][5]['is_owner'], true);
        $this->assertEquals($content['data']['data'][6]['uuid'], '0e57b45f-5938-4e26-acd8-d65fb89e8503');
        $this->assertEquals($content['data']['total'],8);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?sort=[{"field":"visualization_id","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 8);
        $this->assertEquals($content['data']['data'][5]['uuid'], '0e57b45f-5938-4e26-acd8-d65fb89e8503');
        $this->assertEquals($content['data']['data'][5]['name'], 'widget2');
        $this->assertEquals($content['data']['data'][6]['name'], 'widget1');
        $this->assertEquals($content['data']['data'][6]['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['total'],8);
    }

    public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?skip=1&limit=10&sort=[{"field":"visualization_id","dir":"asc"}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 7);
        $this->assertEquals($content['data']['data'][5]['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['data'][5]['name'], 'widget1');
        $this->assertEquals($content['data']['data'][5]['is_owner'], true);
        $this->assertEquals($content['data']['total'],8);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?limit=10&sort=[{"field":"id","dir":"desc"}]&filter=[{"logic":"and"},{"filters":[{"field":"visualization_id","operator":"neq","value":"2"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 6);
        $this->assertEquals($content['data']['data'][1]['uuid'], '0e57b45f-5938-4e26-acd8-d65fb89e8503');
        $this->assertEquals($content['data']['data'][1]['name'], 'widget2');
        $this->assertEquals($content['data']['total'],6);
    }
}