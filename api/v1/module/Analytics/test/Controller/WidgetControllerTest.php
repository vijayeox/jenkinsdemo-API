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

class WidgetControllerTest extends ControllerTest
{
    private $mock;
    private $index_pre;
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
        $config = $this->getApplicationConfig();
    }

    public function tearDown()  : void {
        parent::tearDown();
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
        $this->assertEquals(12, $this->getConnection()->getRowCount('ox_widget'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['query_uuid'], $data['query_uuid']);
        $this->assertEquals($content['data']['ispublic'], $data['ispublic']);
        $this->assertEquals(13, $this->getConnection()->getRowCount('ox_widget'));
    }

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['query_uuid' => '1a7d9e0d-f6cd-40e2-9154-87de247b9ce1','visualization_uuid' => "44f22a46-26d2-48df-96b9-c58520005817", 'ispublic' => 1 , 'configuration' => 'sample configuration','expression' => ''];
        $this->assertEquals(12, $this->getConnection()->getRowCount('ox_widget'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('analytics_widget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation error(s).');
        $this->assertEquals($content['data']['errors']['queries'], 'required');
    }

    public function testCopy()
    {
        $this->initAuthToken($this->adminUser);
        $this->assertEquals(12, $this->getConnection()->getRowCount('ox_widget'));
        $data = array('queries' => array(array('uuid' =>'8f1d2819-c5ff-4426-bc40-f7a20704a738','configuration' => 'sample_conf'),array('uuid' =>'86c0cc5b-2567-4e5f-a741-f34e9f6f1af1','configuration' => 'sample_conf')));
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafbc/copy', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('copyWidget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(13, $this->getConnection()->getRowCount('ox_widget'));
    }

    public function testCopyNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('queries' => array(array('uuid' =>'8f1d2819-c5ff-4426-bc40-f7a20704a738','configuration' => 'sample_conf'),array('uuid' =>'86c0cc5b-2567-4e5f-a741-f34e9f6f1af1','configuration' => 'sample_conf')));
        $this->assertEquals(12, $this->getConnection()->getRowCount('ox_widget'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/analytics/widget/51e881c3-040d-44d8-9295-f2c3130bafab/copy', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('copyWidget');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Wiget id 51e881c3-040d-44d8-9295-f2c3130bafab either does not exist OR user has no read permission to the entity.');
//$this->assertEquals($content['data']['message'], 'Given wiget id 51e881c3-040d-44d8-9295-f2c3130bafab either does not exist OR user has no permission to read the widget.');
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

  //   public function testGetHub() {
  //       $this->initAuthToken($this->adminUser);
  //       $this->dispatch('/analytics/widget/41a73da0-bf9b-4069-841b-6f9a93caa691?data=true', 'GET');
  //       $this->assertResponseStatusCode(200);
  //       $this->setDefaultAsserts();
  //       $content = json_decode($this->getResponse()->getContent(), true);
  //       $this->assertEquals($content['status'], 'success');
  //       $this->assertEquals($content['data']['widget']['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
  // //      $this->assertEquals($content['data']['widget']['query_uuid'],"8f1d2819-c5ff-4426-bc40-f7a20704a738");
  //   }

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
        $this->assertEquals(count($content['data']['data']), 12);
        $this->assertEquals($content['data']['data'][5]['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['data'][5]['is_owner'], true);
        $this->assertEquals($content['data']['data'][5]['name'], 'widget1');
        $this->assertEquals($content['data']['data'][6]['name'], 'widget2');
        $this->assertEquals($content['data']['data'][5]['is_owner'], true);
        $this->assertEquals($content['data']['data'][6]['uuid'], '0e57b45f-5938-4e26-acd8-d65fb89e8503');
        $this->assertEquals($content['data']['total'],12);
    }

    public function testGetListWithDeleted()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?show_deleted=true', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 12);
        $this->assertEquals($content['data']['data'][5]['uuid'], '51e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['data'][5]['is_owner'], true);
        $this->assertEquals($content['data']['data'][5]['name'], 'widget1');
        $this->assertEquals($content['data']['data'][5]['isdeleted'], 0);
        $this->assertEquals($content['data']['data'][6]['name'], 'widget2');
        $this->assertEquals($content['data']['data'][5]['is_owner'], true);
        $this->assertEquals($content['data']['data'][6]['uuid'], '0e57b45f-5938-4e26-acd8-d65fb89e8503');
        $this->assertEquals($content['data']['total'],12);
    }

    public function testGetListWithSort()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?filter=[{"sort":[{"field":"visualization_id","dir":"asc"}]}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 12);
        $this->assertEquals($content['data']['data'][5]['uuid'], '0e57b45f-5938-4e26-acd8-d65fb89e8503');
        $this->assertEquals($content['data']['data'][5]['name'], 'widget2');
        $this->assertEquals($content['data']['data'][8]['name'], 'combinedWithDate');
        $this->assertEquals($content['data']['data'][8]['uuid'], '31e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['total'],12);
    }

    public function testGetListSortWithPageSize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?filter=[{"sort":[{"field":"visualization_id","dir":"asc"}],"skip":1,"take":10}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 10);
        $this->assertEquals($content['data']['data'][7]['uuid'], '31e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['data'][7]['name'], 'combinedWithDate');
        $this->assertEquals($content['data']['data'][7]['is_owner'], true);
        $this->assertEquals($content['data']['total'],12);
    }

    public function testGetListwithQueryParameters()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/analytics/widget?filter=[{"filter":{"logic":"and","filters":[{"field":"visualization_id","operator":"neq","value":"2"}]},"sort":[{"field":"id","dir":"desc"}],"skip":0,"take":10}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']['data']), 10);
        $this->assertEquals($content['data']['data'][1]['uuid'], '11e881c3-040d-44d8-9295-f2c3130bafbc');
        $this->assertEquals($content['data']['data'][1]['name'], 'combinedWithSingle');
        $this->assertEquals($content['data']['total'],10);
    }

}
