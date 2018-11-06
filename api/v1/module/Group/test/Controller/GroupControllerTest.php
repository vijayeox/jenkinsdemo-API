<?php
namespace Group;

use Group\Controller\GroupController;
use Group\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class GroupControllerTest extends ControllerTest {

    public function setUp() : void {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Group.yml");
        return $dataset;
    }

    protected function setDefaultAsserts() {
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testgetGroupsforUser() { // Testing to create a new app
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Group', 'parent_id'=> 0, 'org_id'=>1, 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_group'));
        $this->dispatch('/group/user/436', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groupsUser');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['data']['name'], 'Test Group');
        $this->assertEquals($content['data']['parent_id'], 0);
        $this->assertEquals($content['data']['org_id'], 1);
        $this->assertEquals($content['data']['manager_id'], 436);
        $this->assertEquals($content['data']['description'], "Description Test Data");
        $this->assertEquals($content['data']['logo'], "grp1.png");
        $this->assertEquals($content['data']['cover_photo'], "grp1.png");
        $this->assertEquals($content['data']['type'], 1);
        $this->assertEquals($content['data']['status'], "Active");
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Create Group', 'parent_id'=> 0, 'org_id'=>1, 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/group', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('group');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'Test Create Group');
        $this->assertEquals($content['data']['parent_id'], 0);
        $this->assertEquals($content['data']['org_id'], 1);
        $this->assertEquals($content['data']['manager_id'], 436);
        $this->assertEquals($content['data']['description'], "Description Test Data");
        $this->assertEquals($content['data']['logo'], "grp1.png");
        $this->assertEquals($content['data']['cover_photo'], "grp1.png");
        $this->assertEquals($content['data']['type'], 1);
        $this->assertEquals($content['data']['status'], "Active");
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testUpdate(){
        $data = ['name' => 'Test Create Group', 'parent_id'=> 0, 'org_id'=>1, 'manager_id' => 436, 'description
        '=>'Description Test Data', 'logo' => 'grp1.png', 'cover_photo'=>'grp1.png', 'type' => 1, 'status' => 'Active'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/group/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('group');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], 'Test Create Group12');
        $this->assertEquals($content['data']['parent_id'], 0);
        $this->assertEquals($content['data']['org_id'], 1);
        $this->assertEquals($content['data']['manager_id'], 436);
        $this->assertEquals($content['data']['description'], "Description Test Data");
        $this->assertEquals($content['data']['logo'], "grp1.png");
        $this->assertEquals($content['data']['cover_photo'], "grp1.png");
        $this->assertEquals($content['data']['type'], 1);
        $this->assertEquals($content['data']['status'], "Active");
    }

    public function testDelete() {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('group');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

}