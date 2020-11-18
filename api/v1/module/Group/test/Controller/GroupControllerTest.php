<?php
namespace Group;

use Group\Controller\GroupController;
use Mockery;
use Oxzion\Service\GroupService;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class GroupControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getMockMessageProducer()
    {
        $groupService = $this->getApplicationServiceLocator()->get(Service\GroupService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $groupService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Group.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGetGroups()
    {
        $this->initAuthToken($this->adminUser);
        $groupId = '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de';
        $this->dispatch("/group/$groupId", 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($groupId, $content['data']['uuid']);
        $this->assertEquals('Test Group', $content['data']['name']);
        $this->assertEquals('Description Test Data', $content['data']['description']);
        $userId = '4fd99e8e-758f-11e9-b2d5-68ecc57cde45';
        $accountId = '53012471-2863-4949-afb1-e69b0891c98a';
        $this->assertEquals($userId, $content['data']['managerId']);
        $this->assertEquals($accountId, $content['data']['accountId']);
    }

    public function testGetGroupsWithAccountId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testGetGroupsWithInValidAccountId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testGetGroupsNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/10000', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetGroupsListWithAccountID()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/group', 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(3, count($content['data']));
        $groups = ['Test Group', 'Test Group 5', 'Test Group Once Again'];
        foreach ($groups as $key => $value) {
            $this->assertEquals($value, $content['data'][$key]['name']);
        }
    }

    public function testGetGroupsforByManager()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group', 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(401);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to get the groups list');
    }

    public function testGetGroupsForManager()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/group', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testGetGroupsForEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/group', 'GET');
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

// Testing to see if the Create Group function is working as intended if all the value passed are correct.
    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Groups 22', 'parentId' => "2db1c5a3-8a82-4d5b-b60a-c648cf1e27de", 'accountId' => '53012471-2863-4949-afb1-e69b0891c98a', 'manager_id' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data', 'logo' => 'grp1.png', 'status' => 'Active'];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupname' => 'Groups 22', 'accountName' => 'Cleveland Black')), 'GROUP_ADDED')->once()->andReturn();
        }
        $this->dispatch('/group', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT g.*, parent.uuid as parentId, man.uuid as managerId, a.uuid as accountId 
                    from ox_group g
                    inner join ox_user man on man.id = g.manager_id
                    inner join ox_group parent on parent.id = g.parent_id
                    inner join ox_account a on a.id = g.account_id
                    where g.uuid = '".$content['data']['uuid']."'";
        $group = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_group where avatar_id =" . $group[0]['manager_id'] . " and group_id =" . $group[0]['id'];
        $oxgroup = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['parentId'], $group[0]['parentId']);
        $this->assertEquals($content['data']['accountId'], $group[0]['accountId']);
        $this->assertEquals($content['data']['manager_id'], $group[0]['managerId']);
        $this->assertEquals($content['data']['description'], $group[0]['description']);
        $this->assertEquals($content['data']['logo'], $group[0]['logo']);
        $this->assertEquals($content['data']['status'], $group[0]['status']);
        $this->assertEquals($group[0]['manager_id'], 1);
        $this->assertEquals($oxgroup[0]['avatar_id'], 1);
        $this->assertEquals(6, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testCreateWithExistingGroup()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Group', 'parentId' => "2db1c5a3-8a82-4d5b-b60a-c648cf1e27de", 'account_id' => 1, 'managerId' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data', 'logo' => 'grp1.png'];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/group', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Group already exists');
    }

    public function testCreateWithExistingGroupInactive()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Group 4', 'parentId' => "2db1c5a3-8a82-4d5b-b60a-c648cf1e27de", 'account_id' => 1, 'managerId' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data', 'logo' => 'grp1.png'];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/group', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Group already exists would you like to reactivate?');
    }

    public function testCreateWithExistingGroupInactiveWithReactivateFlag()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Test Group 4', 'parentId' => "2db1c5a3-8a82-4d5b-b60a-c648cf1e27de", 'accountId' => '53012471-2863-4949-afb1-e69b0891c98a', 'managerId' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data', 'logo' => 'grp1.png', 'reactivate' => 1];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('old_groupName' => 'Test Group 4', 'accountName' => 'Cleveland Black', "new_groupName" => "Test Group 4")), 'GROUP_UPDATED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group 4', 'accountName' => 'Cleveland Black', "username" => "admintest")), 'USERTOGROUP_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group 4', "usernames" => array("admintest"))), 'USERTOGROUP_UPDATED')->once()->andReturn();

        }
        $this->dispatch('/group', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $select = "SELECT status from ox_group where name = 'Test Group 4'";
        $result = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(false, isset($content['data']['reactivate']));
        $this->assertEquals('Active', $result[0]['status']);
    }

    public function testCreateByAdminWithDifferentAccountId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Groups 22', 'parentId' => '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'manager_id' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data', 'logo' => 'grp1.png', 'status' => 'Active'];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupname' => 'Groups 22', 'accountName' => 'Golden State Warriors')), 'GROUP_ADDED')->once()->andReturn();
        }
        $this->setJsonContent(json_encode($data));
        $accountId = 'b0971de7-0387-48ea-8f29-5d3704d96a46';
        $this->dispatch("/account/$accountId/group", 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $select = "SELECT g.*, parent.uuid as parentId, man.uuid as managerId, a.uuid as accountId 
                    from ox_group g
                    inner join ox_user man on man.id = g.manager_id
                    inner join ox_group parent on parent.id = g.parent_id
                    inner join ox_account a on a.id = g.account_id
                    where g.uuid = '".$content['data']['uuid']."'";
        $group = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_group where avatar_id =" . $group[0]['manager_id'] . " and group_id =" . $group[0]['id'];
        $oxgroup = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['parentId'], $group[0]['parentId']);
        $this->assertEquals($accountId, $group[0]['accountId']);
        $this->assertEquals($content['data']['manager_id'], $group[0]['managerId']);
        $this->assertEquals($content['data']['description'], $group[0]['description']);
        $this->assertEquals($content['data']['logo'], $group[0]['logo']);
        $this->assertEquals($content['data']['status'], $group[0]['status']);
        $this->assertEquals($group[0]['manager_id'], 1);
        $this->assertEquals($oxgroup[0]['avatar_id'], 1);
        $this->assertEquals(6, $this->getConnection()->getRowCount('ox_group'));
    }

    public function testCreateNewGroup()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Groups 22', 'parentId' => '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'manager_id' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data', 'logo' => 'grp1.png'];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupname' => 'Groups 22', 'accountName' => 'Cleveland Black')), 'GROUP_ADDED')->once()->andReturn();
        }
        $accountId = '53012471-2863-4949-afb1-e69b0891c98a';
        $this->dispatch("/account/$accountId/group", 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $select = "SELECT g.*, parent.uuid as parentId, man.uuid as managerId, a.uuid as accountId 
                    from ox_group g
                    inner join ox_user man on man.id = g.manager_id
                    inner join ox_group parent on parent.id = g.parent_id
                    inner join ox_account a on a.id = g.account_id
                    where g.uuid = '".$content['data']['uuid']."'";
        $group = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_group where avatar_id =" . $group[0]['manager_id'] . " and group_id =" . $group[0]['id'];
        $oxgroup = $this->executeQueryTest($select);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
        $this->assertEquals($content['data']['parentId'], $group[0]['parentId']);
        $this->assertEquals($accountId, $group[0]['accountId']);
        $this->assertEquals($content['data']['manager_id'], $group[0]['managerId']);
        $this->assertEquals($content['data']['description'], $group[0]['description']);
        $this->assertEquals($content['data']['logo'], $group[0]['logo']);
        $this->assertEquals('Active', $group[0]['status']);
        $this->assertEquals($group[0]['manager_id'], 1);
        $this->assertEquals($oxgroup[0]['avatar_id'], 1);
        $this->assertEquals(6, $this->getConnection()->getRowCount('ox_group'));
    }

//Test Case to check the errors when the required field is not selected. Here I removed the parent_id field from the list.
    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Groups 22', 'description' => 'Description Test Data', 'status' => 'Active'];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group', 'POST', $data);
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation error(s).');
        $this->assertEquals($content['data']['errors']['manager_id'], 'required');
    }

    public function testCreateByEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['name' => 'Groups 22', 'parent_id' => 1, 'account_id' => 'b0971de7-0387-48ea-8f29-5d3704d96a46', 'managerId' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description
        ' => 'Description Test Data', 'logo' => 'grp1.png', 'status' => 'Active'];
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_group'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/group', 'POST', $data);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testUpdate()
    {
        $data = ['name' => 'Test Create Group', 'managerId' => "4fd9ce37-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('old_groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'new_groupName' => 'Test Create Group')), 'GROUP_UPDATED')->once()->andReturn();
        }
        $groupId = '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de';
        $this->dispatch("/group/$groupId", 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $select = "SELECT g.*, parent.uuid as parentId, man.uuid as managerId, a.uuid as accountId 
                    from ox_group g
                    left join ox_user man on man.id = g.manager_id
                    left join ox_group parent on parent.id = g.parent_id
                    inner join ox_account a on a.id = g.account_id
                    where g.uuid = '$groupId'";
        $group = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_group where avatar_id =" . $group[0]['manager_id'] . " and group_id =" . $group[0]['id'];
        $oxgroup = $this->executeQueryTest($select);
        
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($group[0]['name'], $data['name']);
        $this->assertEquals(null, $group[0]['parentId']);
        $this->assertEquals(1, $group[0]['account_id']);
        $this->assertEquals($content['data']['managerId'], $group[0]['managerId']);
        $this->assertEquals($content['data']['description'], $group[0]['description']);
        $this->assertEquals('Active', $group[0]['status']);
        $this->assertEquals($group[0]['manager_id'], 2);
        $this->assertEquals($oxgroup[0]['avatar_id'], 2);

        
    }

    public function testUpdateWithAccountID()
    {
        $data = ['name' => 'Test Create Group', 'managerId' => "4fd9ce37-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('old_groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'new_groupName' => 'Test Create Group')), 'GROUP_UPDATED')->once()->andReturn();
        }
        $groupId = '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de';
        $accountId = '53012471-2863-4949-afb1-e69b0891c98a';
        $this->dispatch("/account/$accountId/group/$groupId", 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $select = "SELECT g.*, parent.uuid as parentId, man.uuid as managerId, a.uuid as accountId 
                    from ox_group g
                    left join ox_user man on man.id = g.manager_id
                    left join ox_group parent on parent.id = g.parent_id
                    inner join ox_account a on a.id = g.account_id
                    where g.uuid = '$groupId'";
        $group = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_group where avatar_id =" . $group[0]['manager_id'] . " and group_id =" . $group[0]['id'];
        $oxgroup = $this->executeQueryTest($select);
        
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($group[0]['name'], $data['name']);
        $this->assertEquals(null, $group[0]['parentId']);
        $this->assertEquals($accountId, $group[0]['accountId']);
        $this->assertEquals(1, $group[0]['account_id']);
        $this->assertEquals($content['data']['managerId'], $group[0]['managerId']);
        $this->assertEquals($content['data']['description'], $group[0]['description']);
        $this->assertEquals('Active', $group[0]['status']);
        $this->assertEquals($group[0]['manager_id'], 2);
        $this->assertEquals($oxgroup[0]['avatar_id'], 2);
    }

    public function testUpdateWithInvalidAccountAccountID()
    {
        $data = ['name' => 'Test Create Group', 'managerId' => "4fd9ce37-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Group does not belong to the account');
    }

    public function testUpdateWithInvalidGroupID()
    {
        $data = ['name' => 'Test Create Group', 'managerId' => "4fd9ce37-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/group/2db82-4d5b-b60a-c648cf1e27de', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Updating non existent Group');
    }

    public function testUpdateByManagerWithDifferentAccountId()
    {
        $data = ['name' => 'Test Create Group', 'managerId' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data'];
        $this->initAuthToken($this->managerUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to edit the group');
    }

    public function testUpdateByManager()
    {
        $data = ['name' => 'Test Create Group', 'managerId' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data'];
        $this->initAuthToken($this->managerUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('old_groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'new_groupName' => 'Test Create Group')), 'GROUP_UPDATED')->once()->andReturn();
        }
        $groupId = '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de';
        $this->dispatch("/group/$groupId", 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(201);
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertMatchedRouteName('groups');
        $select = "SELECT g.*, parent.uuid as parentId, man.uuid as managerId, a.uuid as accountId 
                    from ox_group g
                    left join ox_user man on man.id = g.manager_id
                    left join ox_group parent on parent.id = g.parent_id
                    inner join ox_account a on a.id = g.account_id
                    where g.uuid = '$groupId'";
        $group = $this->executeQueryTest($select);
        $select = "SELECT * from ox_user_group where avatar_id =" . $group[0]['manager_id'] . " and group_id =" . $group[0]['id'];
        $oxgroup = $this->executeQueryTest($select);
        
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($group[0]['name'], $data['name']);
        $this->assertEquals(null, $group[0]['parentId']);
        $this->assertEquals(1, $group[0]['account_id']);
        $this->assertEquals($content['data']['managerId'], $group[0]['managerId']);
        $this->assertEquals($content['data']['description'], $group[0]['description']);
        $this->assertEquals('Active', $group[0]['status']);
        $this->assertEquals($group[0]['manager_id'], 1);
        $this->assertEquals($oxgroup[0]['avatar_id'], 1);
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test', 'managerId' => "4fd99e8e-758f-11e9-b2d5-68ecc57cde45", 'description' => 'Description Test Data'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/10000', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'DELETE');
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Please remove the child groups before deleting the parent group');
    }

    public function testDeleteWithInvalidAccount()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/group/153f3e9e-eb07-4ca4-be78-34f715bd50sd', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Group does not belong to the account');
    }

    public function testDeleteByManager()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'DELETE');
        $this->assertResponseStatusCode(401);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to delete the group');
    }

    public function testDeleteByManagerWithPresentAccount()
    {
        $this->initAuthToken($this->managerUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::Any(), 'GROUP_DELETED')->once()->andReturn();
        }
        $this->dispatch('/group/153f3e9e-eb07-4ca4-be78-34f715bd124', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteByEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', 'DELETE');
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/10000', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('groups');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testSaveUserByAdmin()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->adminUser)), 'USERTOGROUP_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->employeeUser)), 'USERTOGROUP_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'usernames' => array($this->managerUser, $this->employeeUser))), 'USERTOGROUP_UPDATED')->once()->andReturn();
        }
        $groupId = '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de';
        $this->dispatch("/group/$groupId/save", 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $select = "SELECT u.uuid
                    from ox_group g
                    inner join ox_user_group ug on ug.group_id = g.id
                    inner join ox_user u on u.id = ug.avatar_id
                    where g.uuid = '$groupId'";
        $group = $this->executeQueryTest($select);
        $this->assertEquals($group, $data['userIdList']);
    }

    public function testSaveUserWithAccount()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->adminUser)), 'USERTOGROUP_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->employeeUser)), 'USERTOGROUP_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'usernames' => array($this->managerUser, $this->employeeUser))), 'USERTOGROUP_UPDATED')->once()->andReturn();
        }
        $groupId = '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de';
        $this->dispatch("/account/53012471-2863-4949-afb1-e69b0891c98a/group/$groupId/save", 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $select = "SELECT u.uuid
                    from ox_group g
                    inner join ox_user_group ug on ug.group_id = g.id
                    inner join ox_user u on u.id = ug.avatar_id
                    where g.uuid = '$groupId'";
        $group = $this->executeQueryTest($select);
        $this->assertEquals($group, $data['userIdList']);
    }

    public function testSaveUserWithOtherAccount()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->adminUser)), 'USERTOGROUP_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->employeeUser)), 'USERTOGROUP_ADDED')->once()->andReturn();
        }
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/save', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Group does not belong to the account');
    }

    public function testSaveUserWithInvalidGroupId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->adminUser)), 'USERTOGROUP_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->employeeUser)), 'USERTOGROUP_ADDED')->once()->andReturn();
        }
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/group/2da3-8a82-4d5b-b60a-c648cf1e27de/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity not found');
    }
    public function testSaveUserByManagerWithDifferentAccountId()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/save', 'POST', $data);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('saveusers');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to add users to group');
    }

    public function testSaveUserByManager()
    {
        $this->initAuthToken($this->managerUser);
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => 'admintest')), 'USERTOGROUP_DELETED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'accountName' => 'Cleveland Black', 'username' => $this->employeeUser)), 'USERTOGROUP_ADDED')->once()->andReturn();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('groupName' => 'Test Group', 'usernames' => array($this->managerUser, $this->employeeUser))), 'USERTOGROUP_UPDATED')->once()->andReturn();
        }
        $groupId = '2db1c5a3-8a82-4d5b-b60a-c648cf1e27de';
        $this->dispatch("/group/$groupId/save", 'POST', $data);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('saveusers');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testSaveUserByEmployee()
    {
        $this->initAuthToken($this->employeeUser);
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-11e9-b2d5-68ecc57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b2d5-68ecc57cde45'])];
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/save', 'POST', $data);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('Group');
        $this->assertControllerName(GroupController::class); // as specified in router's controller name alias
        $this->assertControllerClass('GroupController');
        $this->assertMatchedRouteName('saveusers');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }

    public function testSaveUserWithoutUser()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/save', 'POST');
        $this->assertResponseStatusCode(406);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals('Select Users', $content['message']);
    }

    public function testSaveUserNotFound()
    {
        $this->initAuthToken($this->adminUser);
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
        }
        $data = ['userIdList' => array(['uuid' => '4fd9ce37-758f-1c57cde45'], ['uuid' => '4fd9f04d-758f-11e9-b'])];
        $this->dispatch('/group/1/save', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testGetUserList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetUserListByManager()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetUserListWithAccountid()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['data'][1]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][1]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetUserListWithInvalidGroupId()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/group/2db1c5a3-8a820a-c648cf1e27de/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetUserListWithInvalidAccountid()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
    }

    public function testGetUserListByManagerWithDifferentAccountId()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/account/b0971de7-0387-48ea-8f29-5d3704d96a46/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users?filter=[{"skip":1,"take":1}]', 'GET');
        $this->assertResponseStatusCode(401);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You do not have permissions to get the user list of group');
    }

    public function testGetUserListWithPagesize()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users?filter=[{"skip":1,"take":1}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 2);
    }

    public function testGetUserListWithPageNo()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users?filter=[{"filter":{"filters":[{"field":"name","operator":"contains","value":"mi"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]
', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Admin Test');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetUserListWithQueryFieldParameter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/2db1c5a3-8a82-4d5b-b60a-c648cf1e27de/users?filter=[{"filter":{"filters":[{"field":"name","operator":"startswith","value":"Ma"}]},"sort":[{"field":"id","dir":"asc"}],"skip":0,"take":1}]', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 1);
        $this->assertEquals($content['data'][0]['uuid'], '4fd9ce37-758f-11e9-b2d5-68ecc57cde45');
        $this->assertEquals($content['data'][0]['name'], 'Manager Test');
        $this->assertEquals($content['total'], 1);
    }

    public function testGetUserListNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/group/64/users', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'], array());
        $this->assertEquals($content['total'], 0);
    }

    public function testGetExcludedGroupsList()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['exclude' => array('2db1c5a3-8a82-4d5b-b60a-c648cf1e27de', '153f3e9e-eb07-4ca4-be78-34f715bd50db'), 'filter' => json_encode(array('0' => array('filter' => array('logic' => 'and', 'filters' => array(['field' => 'name', 'operator' => 'startswith', 'value' => 'Test'])), 'sort' => array(['field' => 'name', 'dir' => 'asc']), 'skip' => 0, 'take' => 2)))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/groups/list', 'POST', $data);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('groupsList');
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, count($content['data']));
        $this->assertEquals($content['data'][0]['name'], 'Test Group 5');
    }

    public function testGetExcludedGroupsListWithExcludedGroupFilter()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['exclude' => array('4fd9f04d-758f-11e9-b2d5-68ecc57cde45', '768d1fb9-de9c-46c3-8d5c-23e0e484ce2e'), 'filter' => json_encode(array('0' => array('sort' => array(['field' => 'name', 'dir' => 'dsc']), 'skip' => 0, 'take' => 2)))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/groups/list', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('groupsList');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['total'], 3);
    }

    public function testGetExcludedGroupsListWithAccountId()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['exclude' => array('2db1c5a3-8a82-4d5b-b60a-c648cf1e27de'), 'filter' => json_encode(array('0' => array('sort' => array(['field' => 'name', 'dir' => 'dsc']), 'skip' => 0, 'take' => 20)))];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/account/53012471-2863-4949-afb1-e69b0891c98a/groups/list', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts('groupsList');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['name'], 'Test Group Once Again');
        $this->assertEquals($content['data'][1]['name'], 'Test Group 5');
    }

}
